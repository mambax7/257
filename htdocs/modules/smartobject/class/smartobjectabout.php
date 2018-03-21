<?php
//

// 2012-01-01 K.OHWADA
// PHP 5.3: Assigning the return value of new by reference is now deprecated.

/**
 * Id: smartobjectabout.php 159 2007-12-17 16:44:05Z malanciault
 * Module: SmartObject
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class About is a simple class that lets you build an about page
 * @author The SmartFactory <www.smartfactory.ca>
 */
class SmartobjectAbout
{
    public $_lang_aboutTitle;
    public $_lang_author_info;
    public $_lang_developer_lead;
    public $_lang_developer_contributor;
    public $_lang_developer_website;
    public $_lang_developer_email;
    public $_lang_developer_credits;
    public $_lang_module_info;
    public $_lang_module_status;
    public $_lang_module_release_date;
    public $_lang_module_demo;
    public $_lang_module_support;
    public $_lang_module_bug;
    public $_lang_module_submit_bug;
    public $_lang_module_feature;
    public $_lang_module_submit_feature;
    public $_lang_module_disclaimer;
    public $_lang_author_word;
    public $_lang_version_history;
    public $_lang_by;
    public $_tpl;

    /**
     * SmartobjectAbout constructor.
     * @param string $aboutTitle
     */
    public function __construct($aboutTitle = 'About')
    {
        global $xoopsModule, $xoopsConfig;

        /** @var Smartobject\Helper $helper */
        $helper = Smartobject\Helper::getInstance();
        $helper->loadLanguage('modinfo');
        
        $this->_aboutTitle = $aboutTitle;

        $this->_lang_developer_contributor = _CO_SOBJECT_DEVELOPER_CONTRIBUTOR;
        $this->_lang_developer_website     = _CO_SOBJECT_DEVELOPER_WEBSITE;
        $this->_lang_developer_email       = _CO_SOBJECT_DEVELOPER_EMAIL;
        $this->_lang_developer_credits     = _CO_SOBJECT_DEVELOPER_CREDITS;
        $this->_lang_module_info           = _CO_SOBJECT_MODULE_INFO;
        $this->_lang_module_status         = _CO_SOBJECT_MODULE_STATUS;
        $this->_lang_module_release_date   = _CO_SOBJECT_MODULE_RELEASE_DATE;
        $this->_lang_module_demo           = _CO_SOBJECT_MODULE_DEMO;
        $this->_lang_module_support        = _CO_SOBJECT_MODULE_SUPPORT;
        $this->_lang_module_bug            = _CO_SOBJECT_MODULE_BUG;
        $this->_lang_module_submit_bug     = _CO_SOBJECT_MODULE_SUBMIT_BUG;
        $this->_lang_module_feature        = _CO_SOBJECT_MODULE_FEATURE;
        $this->_lang_module_submit_feature = _CO_SOBJECT_MODULE_SUBMIT_FEATURE;
        $this->_lang_module_disclaimer     = _CO_SOBJECT_MODULE_DISCLAIMER;
        $this->_lang_author_word           = _CO_SOBJECT_AUTHOR_WORD;
        $this->_lang_version_history       = _CO_SOBJECT_VERSION_HISTORY;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function sanitize($value)
    {
        $myts = \MyTextSanitizer::getInstance();

        return $myts->displayTarea($value, 1);
    }

    public function render()
    {
        /**
         * @todo move the output to a template
         * @todo make the output XHTML compliant
         */

        $myts = \MyTextSanitizer::getInstance();

        global $xoopsModule;

        smart_xoops_cp_header();

        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $versioninfo   = $moduleHandler->get($xoopsModule->getVar('mid'));

        //smart_adminMenu(-1, $this->_aboutTitle . " " . $versioninfo->getInfo('name'));

        require_once XOOPS_ROOT_PATH . '/class/template.php';

        // ---
        // 2012-01-01 PHP 5.3: Assigning the return value of new by reference is now deprecated.
        //      $this->_tpl = new \XoopsTpl();
        $this->_tpl = new \XoopsTpl();
        // ---

        $this->_tpl->assign('module_url', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/');
        $this->_tpl->assign('module_image', $versioninfo->getInfo('image'));
        $this->_tpl->assign('module_name', $versioninfo->getInfo('name'));
        $this->_tpl->assign('module_version', $versioninfo->getInfo('version'));
        $this->_tpl->assign('module_status_version', $versioninfo->getInfo('status_version'));

        // Left headings...
        if ('' !== $versioninfo->getInfo('author_realname')) {
            $author_name = $versioninfo->getInfo('author') . ' (' . $versioninfo->getInfo('author_realname') . ')';
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
            $this->_tpl->assign('module_people_developers', isset($people['developers']) ? array_map([$this, 'sanitize'], $people['developers']) : false);
            $this->_tpl->assign('module_people_testers', isset($people['testers']) ? array_map([$this, 'sanitize'], $people['testers']) : false);
            $this->_tpl->assign('module_people_translators', isset($people['translators']) ? array_map([$this, 'sanitize'], $people['translators']) : false);
            $this->_tpl->assign('module_people_documenters', isset($people['documenters']) ? array_map([$this, 'sanitize'], $people['documenters']) : false);
            $this->_tpl->assign('module_people_other', isset($people['other']) ? array_map([$this, 'sanitize'], $people['other']) : false);
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
        if (is_file($filename)) {
            $filesize = filesize($filename);
            $handle   = fopen($filename, 'r');
            $this->_tpl->assign('module_version_history', $myts->displayTarea(fread($handle, $filesize), true));
            fclose($handle);
        }

        $this->_tpl->display('db:smartobject_about.tpl');

        smart_modFooter();

        xoops_cp_footer();
    }
}
