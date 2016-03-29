<?php
// $Id: smartobjectabout.php,v 1.3 2012/03/31 11:04:54 ohwada Exp $

// 2012-01-01 K.OHWADA
// PHP 5.3 : Assigning the return value of new by reference is now deprecated.

/**
* Id: smartobjectabout.php 159 2007-12-17 16:44:05Z malanciault
* Module: SmartObject
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

if (!defined("XOOPS_ROOT_PATH")) {
die("XOOPS root path not defined");
}

/**
* Class About is a simple class that lets you build an about page
* @author The SmartFactory <www.smartfactory.ca>
*/

class SmartobjectAbout
{
    var $_lang_aboutTitle;
    var $_lang_author_info;
    var $_lang_developer_lead;
    var $_lang_developer_contributor;
    var $_lang_developer_website;
    var $_lang_developer_email;
    var $_lang_developer_credits;
    var $_lang_module_info;
    var $_lang_module_status;
    var $_lang_module_release_date;
    var $_lang_module_demo;
    var $_lang_module_support;
    var $_lang_module_bug;
    var $_lang_module_submit_bug;
    var $_lang_module_feature;
    var $_lang_module_submit_feature;
    var $_lang_module_disclaimer;
    var $_lang_author_word;
    var $_lang_version_history;
    var $_lang_by;
    var $_tpl;

    function SmartobjectAbout($aboutTitle='About')
    {
        global $xoopsModule, $xoopsConfig;

        $fileName = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
        if (file_exists($fileName)) {
            include_once $fileName;
        } else {
            include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . '/language/english/modinfo.php';
        }
        $this->_aboutTitle = $aboutTitle;

        $this->_lang_developer_contributor = _CO_SOBJECT_DEVELOPER_CONTRIBUTOR;
        $this->_lang_developer_website = _CO_SOBJECT_DEVELOPER_WEBSITE;
        $this->_lang_developer_email = _CO_SOBJECT_DEVELOPER_EMAIL;
        $this->_lang_developer_credits = _CO_SOBJECT_DEVELOPER_CREDITS;
        $this->_lang_module_info = _CO_SOBJECT_MODULE_INFO;
        $this->_lang_module_status = _CO_SOBJECT_MODULE_STATUS;
        $this->_lang_module_release_date =_CO_SOBJECT_MODULE_RELEASE_DATE ;
        $this->_lang_module_demo = _CO_SOBJECT_MODULE_DEMO;
        $this->_lang_module_support = _CO_SOBJECT_MODULE_SUPPORT;
        $this->_lang_module_bug = _CO_SOBJECT_MODULE_BUG;
        $this->_lang_module_submit_bug = _CO_SOBJECT_MODULE_SUBMIT_BUG;
        $this->_lang_module_feature = _CO_SOBJECT_MODULE_FEATURE;
        $this->_lang_module_submit_feature = _CO_SOBJECT_MODULE_SUBMIT_FEATURE;
        $this->_lang_module_disclaimer = _CO_SOBJECT_MODULE_DISCLAIMER;
        $this->_lang_author_word = _CO_SOBJECT_AUTHOR_WORD;
        $this->_lang_version_history = _CO_SOBJECT_VERSION_HISTORY;

    }

    function sanitize($value) {
        $myts = MyTextSanitizer::getInstance();
        return $myts->displayTarea($value, 1);
    }

    function render()
    {
        /**
         * @todo move the output to a template
         * @todo make the output XHTML compliant
         */

        $myts = &MyTextSanitizer::getInstance();

        Global $xoopsModule;

        smart_xoops_cp_header();

        $module_handler = &xoops_gethandler('module');
        $versioninfo = &$module_handler->get($xoopsModule->getVar('mid'));

        //smart_adminMenu(-1, $this->_aboutTitle . " " . $versioninfo->getInfo('name'));

        include_once XOOPS_ROOT_PATH . '/class/template.php';

// ---
// 2012-01-01 PHP 5.3 : Assigning the return value of new by reference is now deprecated.
//      $this->_tpl =& new XoopsTpl();
        $this->_tpl =  new XoopsTpl();
// ---

        $this->_tpl->assign('module_url', XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/");
        $this->_tpl->assign('module_image', $versioninfo->getInfo('image'));
        $this->_tpl->assign('module_name', $versioninfo->getInfo('name'));
        $this->_tpl->assign('module_version', $versioninfo->getInfo('version'));
        $this->_tpl->assign('module_status_version', $versioninfo->getInfo('status_version'));

        // Left headings...
        if ($versioninfo->getInfo('author_realname') != '') {
            $author_name = $versioninfo->getInfo('author') . " (" . $versioninfo->getInfo('author_realname') . ")";
        } else {
            $author_name = $versioninfo->getInfo('author');
        }
        $this->_tpl->assign('module_author_name', $author_name);

        $this->_tpl->assign('module_license', $versioninfo->getInfo('license'));

        $this->_tpl->assign('module_credits', $versioninfo->getInfo('credits'));

        // Developers Information
        $this->_tpl->assign('module_developer_lead', $versioninfo->getInfo('developer_lead'));
        $this->_tpl->assign('module_developer_contributor', $versioninfo->getInfo('developer_contributor'));
        $this->_tpl->assign('module_developer_website_url', $versioninfo->getInfo('developer_website_url'));
        $this->_tpl->assign('module_developer_website_name', $versioninfo->getInfo('developer_website_name'));
        $this->_tpl->assign('module_developer_email', $versioninfo->getInfo('developer_email'));

        $people = $versioninfo->getInfo('people');
        if ($people) {

            $this->_tpl->assign('module_people_developers', isset($people['developers']) ? array_map(array($this, 'sanitize'), $people['developers']) : false);
            $this->_tpl->assign('module_people_testers', isset($people['testers']) ? array_map(array($this, 'sanitize'), $people['testers']) : false);
            $this->_tpl->assign('module_people_translators', isset($people['translators']) ? array_map(array($this, 'sanitize'), $people['translators']) : false);
            $this->_tpl->assign('module_people_documenters', isset($people['documenters']) ? array_map(array($this, 'sanitize'), $people['documenters']) : false);
            $this->_tpl->assign('module_people_other', isset($people['other']) ? array_map(array($this, 'sanitize'), $people['other']) : false);
        }
        //$this->_tpl->assign('module_developers', $versioninfo->getInfo('developer_email'));

        // Module Development information
        $this->_tpl->assign('module_date', $versioninfo->getInfo('date'));
        $this->_tpl->assign('module_status', $versioninfo->getInfo('status'));
        $this->_tpl->assign('module_demo_site_url', $versioninfo->getInfo('demo_site_url'));
        $this->_tpl->assign('module_demo_site_name', $versioninfo->getInfo('demo_site_name'));
        $this->_tpl->assign('module_support_site_url', $versioninfo->getInfo('support_site_url'));
        $this->_tpl->assign('module_support_site_name', $versioninfo->getInfo('support_site_name'));
        $this->_tpl->assign('module_submit_bug', $versioninfo->getInfo('submit_bug'));
        $this->_tpl->assign('module_submit_feature', $versioninfo->getInfo('submit_feature'));

        // Warning
        $this->_tpl->assign('module_warning', $this->sanitize($versioninfo->getInfo('warning')));

        // Author's note
        $this->_tpl->assign('module_author_word', $versioninfo->getInfo('author_word'));

        // For changelog thanks to 3Dev
        global $xoopsModule;
        $filename = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/changelog.txt';
        if(is_file($filename)){

            $filesize = filesize($filename);
            $handle = fopen($filename, 'r');
            $this->_tpl->assign('module_version_history', $myts->displayTarea(fread($handle, $filesize), true));
            fclose($handle);
        }

        $this->_tpl->display( 'db:smartobject_about.html' );

        smart_modFooter();

        xoops_cp_footer();
    }
}

?>
