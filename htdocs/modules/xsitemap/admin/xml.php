<?php
/*
 * ****************************************************************************
 * xsitemap - MODULE FOR XOOPS CMS
 * Copyright (c) Urbanspaceman (http://www.takeaweb.it)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */
/**
 * Module: xsitemap
 *
 * @package         module\Xsitemap\admin
 * @author          XOOPS Module Development Team
 * @author          Urbanspaceman (http://www.takeaweb.it)
 * @copyright       Urbanspaceman (http://www.takeaweb.it)
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @link            https://xoops.org XOOPS
 * @since           1.00
 */

use XoopsModules\Xsitemap;

include __DIR__ . '/admin_header.php';

$moduleDirName = basename(dirname(__DIR__));

xoops_cp_header();

require_once $GLOBALS['xoops']->path('class/tree.php');
require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/plugin.php');
//require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/Utility.php');
require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/DummyObject.php');

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

$xmlfile     = $GLOBALS['xoops']->path('xsitemap.xml');
$xmlfile_loc = $GLOBALS['xoops']->url('xsitemap.xml');

if (isset($_POST['update'])) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $helper->redirect('admin/xml.php', 3, $GLOBALS['xoopsSecurity']->getErrors(true));
    }

    echo "<div class='pad7 width80'>\n";
    $utility = new Xsitemap\Utility();
    $xsitemap_show = $utility::generateSitemap();
    $update = _AM_XSITEMAP_XML_ERROR_UPDATE;
    if (!empty($xsitemap_show)) {
        $retVal = $utility::saveSitemap($xsitemap_show);
        if (false !== $retVal) {
            $update = sprintf(_AM_XSITEMAP_BYTES_WRITTEN, $retVal) . "\n";
        }
    }
    echo "<p style='margin-bottom: 2em;'>{$update}</p>\n" . "</div>\n" . "<div class='clear'></div>\n";
}

if (file_exists($xmlfile)) {
    $stat     = stat($xmlfile);
    $last_mod = date(_DATESTRING, $stat['mtime']);

    echo "<div class='pad7 width80'>\n" . "<div class='bold floatleft width15'>\n" . "<p style='margin-bottom: 2em;'>" . _AM_XSITEMAP_XML_LOCATION . ":</p>\n" . "<p style='margin-bottom: 2em;'>"
         . _AM_XSITEMAP_XML_LASTUPD . ":</p>\n" . "<p style='margin-bottom: 2em;'>" . _AM_XSITEMAP_XML_FILE_SIZE . ":</p>\n" . "</div>\n" . "<div class='pad7 floatleft width20'>\n"
         . "<p style='margin-bottom: 2em;'>" . htmlentities($xmlfile_loc) . "</p>\n" . "<p style='margin-bottom: 2em;'>{$last_mod}</p>\n" . "<p style='margin-bottom: 2em;'>{$stat['size']}</p>\n"
         . "</div></div>\n" . "<div class='clear'></div>" . "<div class='pad7 width80'>\n" . "<br><br>\n" . "<form action=xml.php method=post>\n"
         . "  <input type='hidden' name='XOOPS_TOKEN_REQUEST' value='" . $GLOBALS['xoopsSecurity']->createToken() . "'>\n" . "  <input id='viewbtn' type='button' value='" . _AM_XSITEMAP_XML_VIEW_XML
         . "' onclick='window.location.href =\"" . $GLOBALS['xoops']->url('www/xsitemap.xml') . "\"'>\n" . "  <input style='margin-left: 3em;' type='submit' name='update' value='"
         . _AM_XSITEMAP_MANAGER_UPDATE . "'>\n" . "</form>\n" . "<br>\n";
} else {
    echo "<div class='pad7'>\n" . "Create XML file.\n" . "<br>\n" . "<br>\n" . "<form action='xml.php' method='post'>\n" . "  <input type='hidden' name='XOOPS_TOKEN_REQUEST' value='"
         . $GLOBALS['xoopsSecurity']->createToken() . "'>\n" . "  <input type='submit' name='update' value='" . _AM_XSITEMAP_CREATE . "'>\n" . "</form>\n" . "<br>\n";
}
echo "</div>\n" . "<br class='clear'>\n";
include __DIR__ . '/admin_footer.php';
