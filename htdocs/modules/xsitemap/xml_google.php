<?php
/**
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
 */
/**
 * @package    module\Xsitemap\frontside
 * @author     Urbanspaceman (http://www.takeaweb.it)
 * @copyright  Urbanspaceman (http://www.takeaweb.it)
 * @copyright  XOOPS Project
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @link       https://xoops.org XOOPS
 * @since      ::    1.00
 **/

use XoopsModules\Xsitemap;

$moduleDirName = basename(__DIR__);
require_once __DIR__ . '/../../mainfile.php';
//template assign
$GLOBALS['xoopsOption']['template_main'] = 'xsitemap_xml.tpl';

require_once $GLOBALS['xoops']->path('header.php');
require_once $GLOBALS['xoops']->path('class/tree.php');
require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/plugin.php');
require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/Utility.php');
require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/DummyObject.php');

$xmlfile = $GLOBALS['xoops']->path('xsitemap.xml');

$xsitemap_show = Xsitemap\Utility::generateSitemap();
if (!empty($xsitemap_show)) {
    $retVal = Xsitemap\Utility::saveSitemap($xsitemap_show);
    if (false !== $retVal) {
        $stat   = stat($xmlfile);
        $status = formatTimestamp($stat['mtime'], _DATESTRING);
    } else {
        $status = _AM_XSITEMAP_XML_ERROR_UPDATE;
    }
} else {
    $status = _AM_XSITEMAP_XML_ERROR_UPDATE;
}

$xoopsTpl->assign('lastmod', $status);
require_once $GLOBALS['xoops']->path('footer.php');
