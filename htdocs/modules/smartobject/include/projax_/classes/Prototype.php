<?php

/**
 * Projax
 *
 * An open source set of php helper classes for prototype and script.aculo.us.
 *
 * @package     Projax
 * @author      Vikas Patial
 * @copyright   Copyright (c) 2006, ngcoders.
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.ngcoders.com
 * @since       Version 0.2
 * @filesource
 */
class Prototype extends JavaScript
{
    public $CALLBACKS = [
        'uninitialized',
        'loading',
        'loaded',
        'interactive',
        'complete',
        'failure',
        'success'
    ];

    public $AJAX_OPTIONS = [
        'before',
        'after',
        'condition',
        'url',
        'asynchronous',
        'method',
        'insertion',
        'position',
        'form',
        'with',
        'update',
        'script',
        'uninitialized',
        'loading',
        'loaded',
        'interactive',
        'complete',
        'failure',
        'success'
    ];

    /**
     * @return string
     */
    public function evaluate_remote_response()
    {
        return 'eval(request.responseText)';
    }

    /**
     * @param $options
     * @return string
     */
    public function form_remote_tag($options)
    {
        $options['form'] = true;

        return '<form action="' . $options['url'] . '" onsubmit="' . $this->remote_function($options) . '; return false;" method="' . (isset($options['method']) ? $options['method'] : 'post') . '"  >';
    }

    /**
     * @param         $name
     * @param  null   $options
     * @param  null   $html_options
     * @return string
     */
    public function link_to_remote($name, $options = null, $html_options = null)
    {
        return $this->link_to_function($name, $this->remote_function($options), $html_options);
    }

    /**
     * @param         $field_id
     * @param  null   $options
     * @return string
     */
    public function observe_field($field_id, $options = null)
    {
        if (isset($options['frequency']) && $options['frequency'] > 0) {
            return $this->_build_observer('Form.Element.Observer', $field_id, $options);
        } else {
            return $this->_build_observer('Form.Element.EventObserver', $field_id, $options);
        }
    }

    /**
     * @param         $form_id
     * @param  null   $options
     * @return string
     */
    public function observe_form($form_id, $options = null)
    {
        if (isset($options['frequency'])) {
            return $this->_build_observer('Form.Observer', $form_id, $options);
        } else {
            return $this->_build_observer('Form.EventObserver', $form_id, $options);
        }
    }

    /**
     * @param  null $options
     * @return string
     */
    public function periodically_call_remote($options = null)
    {
        $frequency = isset($options['frequency']) ? $options['frequency'] : 10;
        $code      = 'new PeriodicalExecuter(function() {' . $this->remote_function($options) . '},' . $frequency . ')';

        return $code;
    }

    /**
     * @param $options
     * @return string
     */
    public function remote_function($options)
    {
        $javascript_options = $this->_options_for_ajax($options);

        $update = '';

        if (isset($options['update']) && is_array($options['update'])) {
            $update = isset($options['update']['success']) ? 'success: ' . $options['update']['success'] : '';
            $update .= empty($update) ? '' : ',';
            $update .= isset($options['update']['failure']) ? 'failure: ' . $options['update']['failure'] : '';
        } else {
            $update .= isset($options['update']) ? $options['update'] : '';
        }

        $ajax_function = empty($update) ? 'new Ajax.Request(' : 'new Ajax.Updater(\'' . $update . '\',';

        $ajax_function .= "'" . $options['url'] . "'";
        $ajax_function .= ',' . $javascript_options . ')';

        $ajax_function = isset($options['before']) ? $options['before'] . ';' . $ajax_function : $ajax_function;
        $ajax_function = isset($options['after']) ? $ajax_function . ';' . $options['after'] : $ajax_function;
        $ajax_function = isset($options['condition']) ? 'if (' . $options['condition'] . ') {' . $ajax_function . '}' : $ajax_function;
        $ajax_function = isset($options['confirm']) ? 'if ( confirm(\'' . $options['confirm'] . '\' ) ) { ' . $ajax_function . ' } ' : $ajax_function;

        return $ajax_function;
    }

    /**
     * @param $name
     * @param $value
     * @param $options
     * @return string
     */
    public function submit_to_remote($name, $value, $options)
    {
        if (isset($options['with'])) {
            $options['with'] = 'Form.serialize(this.form)';
        }

        return '<input type="button" onclick="' . $this->remote_function($options) . '" name="' . $name . '" value ="' . $value . '">';
    }

    /**
     * @param      $element_id
     * @param null $options
     * @param      $block
     */
    public function update_element_function($element_id, $options = null, $block)
    {
        $content = isset($options['content']) ? $options['content'] : '';
        $content = $this->escape($content);
    }

    /**
     * @param $block
     */
    public function update_page($block)
    {
    }

    /**
     * @param $block
     * @return string
     */
    public function update_page_tag(& $block)
    {
        return $this->tag($block);
    }

    /////////////////////////////////////////////////////////////////////////////////////
    //                             Private functions
    /////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param $options
     * @return array
     */
    public function _build_callbacks($options)
    {
        $callbacks = [];
        foreach ($options as $callback => $code) {
            if (in_array($callback, $this->CALLBACKS)) {
                $name             = 'on' . ucfirst($callback);
                $callbacks[$name] = 'function(request){' . $code . '}';
            }
        }

        return $callbacks;
    }

    /**
     * @param         $klass
     * @param         $name
     * @param  null   $options
     * @return string
     */
    public function _build_observer($klass, $name, $options = null)
    {
        if (isset($options['with']) && false === strpos($options['with'], '=')) {
            $options['with'] = '\'' . $options['with'] . '=\' + value';
        } elseif (isset($options['with']) && isset($options['update'])) {
            $options['with'] = 'value';
        }

        $callback = $options['function'] ?: $this->remote_function($options);

        $javascript = "new $klass('$name', ";
        $javascript .= isset($options['frequency']) ? $options['frequency'] . ', ' : '';
        $javascript .= 'function (element,value) { ';
        $javascript .= $callback;
        $javascript .= isset($options['on']) ? ', ' . $options['on'] : '';
        $javascript .= '})';

        return $javascript;
    }

    /**
     * @param $method
     * @return string
     */
    public function _method_option_to_s($method)
    {
        return strstr($method, "'") ? $method : "'$method'";
    }

    /**
     * @param $options
     * @return string
     */
    public function _options_for_ajax($options)
    {
        $js_options = is_array($options) ? $this->_build_callbacks($options) : [];

        if (isset($options['type']) && 'synchronous' === $option['type']) {
            $js_options['asynchronous'] = 'false';
        }

        if (isset($options['method'])) {
            $js_options['method'] = $this->_method_option_to_s($options['method']);
        }

        if (isset($options['position'])) {
            $js_options['insertion'] = 'Insertion.' . ucfirst($options['position']);
        }

        $js_options['evalScripts'] = isset($options['script']) ? $options['script'] : 'true';

        if (isset($options['form'])) {
            $js_options['parameters'] = 'Form.serialize(this)';
        } elseif (isset($options['parameters'])) {
            $js_options['parameters'] = 'Form.serialize(\'' . $options['submit'] . '\')';
        } elseif (isset($options['with'])) {
            $js_options['parameters'] = $options['with'];
        }

        return $this->_options_for_javascript($js_options);
    }

    /////////////////////////////////////////////////////////////////////////////////////
    //                            Mergerd Javascript Generator helpers
    /////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param $javascript
     */
    public function dump($javascript)
    {
        echo $javascript;
    }

    /**
     * @param         $id
     * @param  null   $extend
     * @return string
     */
    public function ID($id, $extend = null)
    {
        return "$('$id')" . (!empty($extend)) ? '.' . $extend . '()' : '';
    }

    /**
     * @param $message
     * @return string
     */
    public function alert($message)
    {
        return $this->call('alert', $message);
    }

    /**
     * @param $variable
     * @param $value
     * @return string
     */
    public function assign($variable, $value)
    {
        return "$variable = $value;";
    }

    /**
     * @param         $function
     * @param  null   $args
     * @return string
     */
    public function call($function, $args = null)
    {
        $arg_str = '';
        if (is_array($args)) {
            foreach ($args as $arg) {
                if (!empty($arg_str)) {
                    $arg_str .= ', ';
                }
                if (is_string($arg)) {
                    $arg_str .= "'$arg'";
                } else {
                    $arg_str .= $arg;
                }
            }
        } else {
            if (is_string($args)) {
                $arg_str .= "'$args'";
            } else {
                $arg_str .= $args;
            }
        }

        return "$function($arg_str)";
    }

    /**
     * @param  int    $seconds
     * @param  string $script
     * @return string
     */
    public function delay($seconds = 1, $script = '')
    {
        return "setTimeout( function() { $script } , " . ($seconds * 1000) . ' )';
    }

    /**
     * @param $id
     * @return string
     */
    public function hide($id)
    {
        return $this->call('Element.hide', $id);
    }

    /**
     * @param         $position
     * @param         $id
     * @param  null   $options_for_render
     * @return string
     */
    public function insert_html($position, $id, $options_for_render = null)
    {
        $args = array_merge([$id], (is_array($options_for_render) ? $options_for_render : [$options_for_render]));

        return $this->call('new Insertion.' . ucfirst($position), $args);
    }

    /**
     * @param $location
     * @return string
     */
    public function redirect_to($location)
    {
        return $this->assign('window.location.href', $location);
    }

    /**
     * @param $id
     * @return string
     */
    public function remove($id)
    {
        if (is_array($id)) {
            $arr_str = '';
            foreach ($id as $obj) {
                if (!empty($arg_str)) {
                    $arg_str .= ', ';
                }
                $arg_str .= "'$arg'";
            }

            return "$A[$arg_str].each(Element.remove)";
        } else {
            return "Element.remove('$id')";
        }
    }

    /**
     * @param $id
     * @param $options_for_render
     * @return string
     */
    public function replace($id, $options_for_render)
    {
        $args = array_merge([$id], (is_array($options_for_render) ? $options_for_render : [$options_for_render]));

        return $this->call('Element.replace', $args);
    }

    /**
     * @param $id
     * @param $options_for_render
     * @return string
     */
    public function replace_html($id, $options_for_render)
    {
        $args = array_merge([$id], (is_array($options_for_render) ? $options_for_render : [$options_for_render]));

        return $this->call('Element.update', $args);
    }

    /**
     * @param         $pattern
     * @param  null   $extend
     * @return string
     */
    public function select($pattern, $extend = null)
    {
        return "$$('$pattern')" . (!empty($extend)) ? '.' . $extend : '';
    }

    /**
     * @param $id
     * @return string
     */
    public function show($id)
    {
        return $this->call('Element.show', $id);
    }

    /**
     * @param $id
     * @return string
     */
    public function toggle($id)
    {
        return $this->call('Element.toggle', $id);
    }
}
