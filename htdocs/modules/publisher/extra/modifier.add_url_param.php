<?php
/**
 * Smarty plugin
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty add_url_param modifier plugin.
 * From: https://www.smarty.net/forums/viewtopic.php?t=4356
 *
 * Will add the parameters in the query string given to the URL.
 * If the URL already has these parameters, then they will be replaced.
 *
 * Type:     modifier<br>
 * Name:     add_url_param<br>
 * Purpose:  Add a parameter to the end of a URL.
 * @author   Douglass Davis
 * @param \A   $url
 * @param \The $add_query_string
 * @return \The new url
 *                         Usage: <a href="<{$url|add_url_param:'page=1&ajax=' }>"
 * @internal param \A $url url
 * @internal param \The $add_query_string parameter to add in the form of param=value
 */

function smarty_modifier_add_url_param($url, $add_query_string)
{

    // parse original URL
    $parsed_url = parse_url($url);

    $old_qs_parsed = [];

    // parse query strings
    parse_str($parsed_url['query'], $old_qs_parsed);

    parse_str($add_query_string, $add_qs_parsed);

    // replace old query string params with new
    $new_qs_parsed = smarty_array_merge_recursive_simple($old_qs_parsed, $add_qs_parsed);

    // remove empty params
    foreach ($new_qs_parsed as $key => $value) {
        if (null === $value || '' === $value) {
            unset($new_qs_parsed[$key]);
        }
    }

    $parsed_url['query'] = http_build_query($new_qs_parsed);

    // rebuild URL from parts
    $new_url = '';

    if ($parsed_url['scheme']) {
        $new_url .= $parsed_url['scheme'] . '://';
    }

    if ($parsed_url['user']) {
        $new_url .= $parsed_url['user'];

        if ($parsed_url['pass']) {
            $new_url .= ':' . $parsed_url['pass'];
        }

        $new_url .= '@';
    }

    if ($parsed_url['host']) {
        $new_url .= $parsed_url['host'];
    }

    if ($parsed_url['path']) {
        $new_url .= $parsed_url['path'];
    }

    if ($parsed_url['query'] || $parsed_url['fragment']) {
        $new_url .= '?';
    }

    if ($parsed_url['query']) {
        $new_url .= $parsed_url['query'];
    }

    if ($parsed_url['fragment']) {
        $new_url .= '#' . $parsed_url['fragment'];
    }

    return $new_url;
}

// array_merge handles numeric keys differently than string keys.
// For numeric keys it combines the values in the arrays.
// For string keys it replaces them.
//
// However, array_merge_recursive doesn't have that expected behavior.
//
// So, the following is a replacement for array_merge_recursive that
// acts more like array_merge.
//
// From comments in http://www.php.net/manual/en/function.array-merge-recursive.php
//

/**
 * @return array|void
 */
function smarty_array_merge_recursive_simple()
{
    if (func_num_args() < 2) {
        trigger_error(__FUNCTION__ . ' needs two or more array arguments', E_USER_WARNING);

        return;
    }
    $arrays = func_get_args();
    $merged = [];
    while ($arrays) {
        $array = array_shift($arrays);
        if (!is_array($array)) {
            trigger_error(__FUNCTION__ . ' encountered a non array argument', E_USER_WARNING);

            return;
        }
        if (!$array) {
            continue;
        }
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                    $merged[$key] = call_user_func(__FUNCTION__, $merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            } else {
                $merged[] = $value;
            }
        }
    }

    return $merged;
}
