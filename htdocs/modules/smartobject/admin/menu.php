<?php
/**
* Id: menu.php 2341 2008-05-21 16:34:21Z malanciault
* Module: SmartObject
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;


if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_SOBJECT_INDEX;
//$adminmenu[$i]['link'] = "admin/main.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';

$i++;
$adminmenu[$i]['title'] = _MI_SOBJECT_SENT_LINKS;
$adminmenu[$i]['link'] = "admin/link.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/addlink.png';

$i++;
$adminmenu[$i]['title'] = _MI_SOBJECT_TAGS;
$adminmenu[$i]['link'] = "admin/customtag.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/identity.png';

$i++;
$adminmenu[$i]['title'] = _MI_SOBJECT_ADSENSES;
$adminmenu[$i]['link'] = "admin/adsense.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/alert.png';
$i++;
$adminmenu[$i]['title'] = _MI_SOBJECT_RATINGS;
$adminmenu[$i]['link'] = "admin/rating.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/stats.png';

$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
//---------------------------------


if (!defined('SMARTOBJECT_ROOT_PATH')) {
    include_once XOOPS_ROOT_PATH . '/modules/smartobject/include/functions.php';
}

$smartobject_config = smart_getModuleConfig('smartobject');

if (isset($smartobject_config['enable_currencyman']) && $smartobject_config['enable_currencyman'] == true) {
    $i++;
    $adminmenu[$i]['title'] = _MI_SOBJECT_CURRENCIES;
    $adminmenu[$i]['link'] = "admin/currency.php";
    $adminmenu[$i]["icon"]  = $pathIcon32 . '/cash_stack.png';
}


global $xoopsModule;
if (isset($xoopsModule)) {
//  $i = -1;

// --- for XCL ---
//  $headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');
    $mid = $xoopsModule->getVar('mid') ;
    if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
        $link_pref = XOOPS_URL.'/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id='.$mid;
    } else {
        $link_pref = XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$mid ;
    }
    $headermenu[$i]['link'] = $link_pref ;
// -----

// --- for XCL ---
//  $headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');
    $dirname = $xoopsModule->getVar('dirname') ;
    if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
        $link_module = XOOPS_URL.'/modules/legacy/admin/index.php?action=ModuleUpdate&dirname='.$dirname;
    } else {
        $link_module = XOOPS_URL.'/modules/system/admin.php?fct=modulesadmin&op=update&module='.$dirname;
    }
    $headermenu[$i]['link'] = $link_module ;
// -----

    $i++;


}