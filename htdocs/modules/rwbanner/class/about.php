<?php namespace XoopsModules\Rwbanner;

/*
                                  RW-Banner
                          Copyright (c) 2006 BrInfo
                          <http://www.brinfo.com.br>
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  You may not change or alter any portion of this comment or credits
  of supporting developers from this source code or any supporting
  source code which is considered copyrighted (c) material of the
  original comment or credit authors.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*/
/**
 * Class About is a simple class that lets you build an about page
 *
 * @copyright::  {@link www.brinfo.com.br BrInfo - Soluções Web}
 * @license  ::    {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author   ::     The SmartFactory <www.smartfactory.ca>
 * @package  ::    rwbanner
 *
 */

use XoopsModules\Rwbanner;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class About
 * @package XoopsModules\Rwbanner
 */
class About
{
    public $lang_aboutTitle;
    public $lang_author_info;
    public $lang_developer_lead;
    public $lang_developer_contributor;
    public $lang_developer_website;
    public $lang_developer_email;
    public $lang_developer_credits;
    public $lang_module_info;
    public $lang_module_status;
    public $lang_module_release_date;
    public $lang_module_demo;
    public $lang_module_support;
    public $lang_module_bug;
    public $lang_module_submit_bug;
    public $lang_module_feature;
    public $lang_module_submit_feature;
    public $lang_module_disclaimer;
    public $lang_author_word;
    public $lang_version_history;
    public $lang_by;

    /**
     * RwbannerAbout constructor.
     * @param string $aboutTitle
     */
    public function __construct($aboutTitle = 'About')
    {
        global $xoopsModule, $xoopsConfig;

        /** @var Rwbanner\Helper $helper */
        $helper = Rwbanner\Helper::getInstance();
        $helper->loadLanguage('modinfo');

        $this->_aboutTitle                = $aboutTitle;
        $this->lang_author_info           = _MI_RWBANNER_AUTHOR_INFO;
        $this->lang_developer_lead        = _MI_RWBANNER_DEVELOPER_LEAD;
        $this->lang_developer_contributor = _MI_RWBANNER_DEVELOPER_CONTRIBUTOR;
        $this->lang_developer_website     = _MI_RWBANNER_DEVELOPER_WEBSITE;
        $this->lang_developer_email       = _MI_RWBANNER_DEVELOPER_EMAIL;
        $this->lang_developer_credits     = _MI_RWBANNER_DEVELOPER_CREDITS;
        $this->lang_module_info           = _MI_RWBANNER_MODULE_INFO;
        $this->lang_module_status         = _MI_RWBANNER_MODULE_STATUS;
        $this->lang_module_release_date   = _MI_RWBANNER_MODULE_RELEASE_DATE;
        $this->lang_module_demo           = _MI_RWBANNER_MODULE_DEMO;
        $this->lang_module_support        = _MI_RWBANNER_MODULE_SUPPORT;
        $this->lang_module_bug            = _MI_RWBANNER_MODULE_BUG;
        $this->lang_module_submit_bug     = _MI_RWBANNER_MODULE_SUBMIT_BUG;
        $this->lang_module_feature        = _MI_RWBANNER_MODULE_FEATURE;
        $this->lang_module_submit_feature = _MI_RWBANNER_MODULE_SUBMIT_FEATURE;
        $this->lang_module_disclaimer     = _MI_RWBANNER_MODULE_DISCLAIMER;
        $this->lang_author_word           = _MI_RWBANNER_AUTHOR_WORD;
        $this->lang_version_history       = _MI_RWBANNER_VERSION_HISTORY;
        $this->lang_by                    = _MI_RWBANNER_BY;
    }

    public function render()
    {
        $myts = \MyTextSanitizer::getInstance();

        global $xoopsModule;

        xoops_cp_header();
        // rwbanner_adminMenu('',_AM_RWBANNER_ABOUT);
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $versioninfo   = $moduleHandler->get($GLOBALS['xoopsModule']->getVar('mid'));

        $adminObject = $versioninfo->getInfo('adminMenu');

        if (false != $adminObject && '' != trim($adminObject)) {
            if (function_exists($adminObject)) {
                $func = $adminObject;
                if (!$func(-1, $this->_aboutTitle . ' ' . $versioninfo->getInfo('name'))) {
                }
            }
        }

        // Left headings...
        echo "<img src='" . XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/' . $versioninfo->getInfo('image') . "' alt='' hspace='0' vspace='0' align='left' style='margin-right: 10px;'></a>";
        echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo->getInfo('name') . ' version ' . $versioninfo->getInfo('version') . ' (' . $versioninfo->getInfo('status_version') . ')</div>';
        if ('' != $versioninfo->getInfo('author_realname')) {
            $author_name = $versioninfo->getInfo('author') . ' (' . $versioninfo->getInfo('author_realname') . ')';
        } else {
            $author_name = $versioninfo->getInfo('author');
        }

        echo "<div style = 'line-height: 16px; font-weight: bold; display: block;'>" . $this->lang_by . ' ' . $author_name;
        echo '</div>';
        echo "<div style = 'line-height: 16px; display: block;'>" . $versioninfo->getInfo('license') . "</div>\n";

        // Developers Information
        echo "<br><table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<td colspan='2' class='bg3' align='left'><b>" . $this->lang_author_info . '</b></td>';
        echo '</tr>';

        if ('' != $versioninfo->getInfo('developer_lead')) {
            echo '<tr>';
            echo "<td class='head' width = '150px' align='left'>" . $this->lang_developer_lead . '</td>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('developer_lead') . '</td>';
            echo '</tr>';
        }
        if ('' != $versioninfo->getInfo('developer_contributor')) {
            echo '<tr>';
            echo "<td class='head' width = '150px' align='left'>" . $this->lang_developer_contributor . '</td>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('developer_contributor') . '</td>';
            echo '</tr>';
        }
        if ('' != $versioninfo->getInfo('developer_website_url')) {
            echo '<tr>';
            echo "<td class='head' width = '150px' align='left'>" . $this->lang_developer_website . '</td>';
            echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('developer_website_url') . "' target='blank'>" . $versioninfo->getInfo('developer_website_name') . '</a></td>';
            echo '</tr>';
        }
        if ('' != $versioninfo->getInfo('developer_email')) {
            echo '<tr>';
            echo "<td class='head' width = '150px' align='left'>" . $this->lang_developer_email . '</td>';
            echo "<td class='even' align='left'><a href='mailto:" . $versioninfo->getInfo('developer_email') . "'>" . $versioninfo->getInfo('developer_email') . '</a></td>';
            echo '</tr>';
        }

        echo '</table>';
        echo "<br>\n";
        // Module Developpment information
        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<td colspan='2' class='bg3' align='left'><b>" . $this->lang_module_info . '</b></td>';
        echo '</tr>';

        if ('' != $versioninfo->getInfo('date')) {
            echo '<tr>';
            echo "<td class='head' width = '200' align='left'>" . $this->lang_module_release_date . '</td>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('date') . '</td>';
            echo '</tr>';
        }

        if ('' != $versioninfo->getInfo('status')) {
            echo '<tr>';
            echo "<td class='head' width = '200' align='left'>" . $this->lang_module_status . '</td>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('status') . '</td>';
            echo '</tr>';
        }

        if ('' != $versioninfo->getInfo('demo_site_url')) {
            echo '<tr>';
            echo "<td class='head' align='left'>" . $this->lang_module_demo . '</td>';
            echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('demo_site_url') . "' target='blank'>" . $versioninfo->getInfo('demo_site_name') . '</a></td>';
            echo '</tr>';
        }

        if ('' != $versioninfo->getInfo('support_site_url')) {
            echo '<tr>';
            echo "<td class='head' align='left'>" . $this->lang_module_support . '</td>';
            echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('support_site_url') . "' target='blank'>" . $versioninfo->getInfo('support_site_name') . '</a></td>';
            echo '</tr>';
        }

        if ('' != $versioninfo->getInfo('submit_bug')) {
            echo '<tr>';
            echo "<td class='head' align='left'>" . $this->lang_module_bug . '</td>';
            echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('submit_bug') . "' target='blank'>" . $this->lang_module_submit_bug . '</a></td>';
            echo '</tr>';
        }
        if ('' != $versioninfo->getInfo('submit_feature')) {
            echo '<tr>';
            echo "<td class='head' align='left'>" . $this->lang_module_feature . '</td>';
            echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('submit_feature') . "' target='blank'>" . $this->lang_module_submit_feature . '</a></td>';
            echo '</tr>';
        }

        echo '</table>';
        // Warning
        if ('' != $versioninfo->getInfo('warning')) {
            echo "<br>\n";
            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo '<tr>';
            echo "<td class='bg3' align='left'><b>" . $this->lang_module_disclaimer . '</b></td>';
            echo '</tr>';

            echo '<tr>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('warning') . '</td>';
            echo '</tr>';

            echo '</table>';
        }
        // Author's note
        if ('' != $versioninfo->getInfo('author_word')) {
            echo "<br>\n";
            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo '<tr>';
            echo "<td class='bg3' align='left'><b>" . $this->lang_author_word . '</b></td>';
            echo '</tr>';

            echo '<tr>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('author_word') . '</td>';
            echo '</tr>';

            echo '</table>';
        }

        // Version History
        if ('' != $versioninfo->getInfo('version_history')) {
            echo "<br>\n";
            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo '<tr>';
            echo "<td class='bg3' align='left'><b>" . $this->lang_version_history . '</b></td>';
            echo '</tr>';

            echo '<tr>';
            echo "<td class='even' align='left'>" . $versioninfo->getInfo('version_history') . '</td>';
            echo '</tr>';

            echo '</table>';
        }

        echo '<br>';

        $modFooter = $versioninfo->getInfo('modFooter');

        if (false != $adminObject && '' != trim($modFooter)) {
            if (function_exists($modFooter)) {
                $func = $modFooter;
                echo "<div align='center'>" . $func() . '</div>';
            }
        }

        xoops_cp_footer();
    }
}
