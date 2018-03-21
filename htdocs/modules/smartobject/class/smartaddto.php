<?php

/**
 * SmartAddTo class to easily add content to social networking/bookmarking site
 *
 * @credit http://addtobookmarks.com/, James Morris and the XoopsInfo team
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

class SmartAddTo
{
    public $_layout;
    public $_method;

    /**
     * Constructor of SmartAddTo
     *
     * @param int $layout 0=Horizontal 1 row, 1=Horizontal 2 rows, 2=Vertical with icons, 3=Vertical no icons
     * @param int $method 0=directpage, 1=popup
     */
    public function __construct($layout = 0, $method = 1)
    {
        $layout = (int)$layout;
        if ($layout < 0 || $layout > 3) {
            $layout = 0;
        }
        $this->_layout = $layout;

        $method = (int)$method;
        if ($method < 0 || $method > 1) {
            $method = 1;
        }
        $this->_method = $method;
    }

    /**
     * @param  bool $fetchOnly
     * @return mixed|string|void
     */
    public function render($fetchOnly = false)
    {
        global $xoTheme, $xoopsTpl;

        $xoTheme->addStylesheet(SMARTOBJECT_URL . 'include/addto/addto.css');

        $xoopsTpl->assign('smartobject_addto_method', $this->_method);
        $xoopsTpl->assign('smartobject_addto_layout', $this->_layout);

        $xoopsTpl->assign('smartobject_addto_url', SMARTOBJECT_URL . 'include/addto/');

        if ($fetchOnly) {
            return $xoopsTpl->fetch('db:smartobject_addto.tpl');
        } else {
            $xoopsTpl->display('db:smartobject_addto.tpl');
        }
    }

    /**
     * @return array
     */
    public function renderForBlock()
    {
        global $xoTheme;

        $xoTheme->addStylesheet(SMARTOBJECT_URL . 'include/addto/addto.css');

        $block                             = [];
        $block['smartobject_addto_method'] = $this->_method;
        $block['smartobject_addto_layout'] = $this->_layout;
        $block['smartobject_addto_url']    = SMARTOBJECT_URL . 'include/addto/';

        return $block;
    }
}
