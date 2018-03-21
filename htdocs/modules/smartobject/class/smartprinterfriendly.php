<?php

/**
 *
 * Module: SmartObject
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class to manage a printer friendly page
 * @author The SmartFactory <www.smartfactory.ca>
 */
class SmartPrinterFriendly
{
    public $_title;
    public $_dsc;
    public $_content;
    public $_tpl;
    public $_pageTitle = false;
    public $_width     = 680;

    /**
     * SmartPrinterFriendly constructor.
     * @param      $content
     * @param bool $title
     * @param bool $dsc
     */
    public function __construct($content, $title = false, $dsc = false)
    {
        $this->_title   = $title;
        $this->_dsc     = $dsc;
        $this->_content = $content;
    }

    public function render()
    {
        /**
         * @todo move the output to a template
         * @todo make the output XHTML compliant
         */

        require_once XOOPS_ROOT_PATH . '/class/template.php';

        $this->_tpl = new \XoopsTpl();

        $this->_tpl->assign('smartobject_print_pageTitle', $this->_pageTitle ?: $this->_title);
        $this->_tpl->assign('smartobject_print_title', $this->_title);
        $this->_tpl->assign('smartobject_print_dsc', $this->_dsc);
        $this->_tpl->assign('smartobject_print_content', $this->_content);
        $this->_tpl->assign('smartobject_print_width', $this->_width);

        $current_urls = smart_getCurrentUrls();
        $current_url  = $current_urls['full'];

        $this->_tpl->assign('smartobject_print_currenturl', $current_url);
        $this->_tpl->assign('smartobject_print_url', $this->url);

        $this->_tpl->display('db:smartobject_print.tpl');
    }

    /**
     * @param $text
     */
    public function setPageTitle($text)
    {
        $this->_pageTitle = $text;
    }

    /**
     * @param $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }
}
