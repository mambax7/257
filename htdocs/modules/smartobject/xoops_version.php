<?php
// $Id: xoops_version.php,v 1.3 2012/03/31 10:41:39 ohwada Exp $

/**
* Id: xoops_version.php 3439 2008-07-05 11:40:55Z malanciault

* Module: SmartContent
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

$modversion['name'] = "SmartObject Framework";
$modversion['version'] = '1.11';

$modversion['description'] = "Framework providing extra functionality to SmartModules";
$modversion['author'] = "The SmartFactory";
$modversion['credits'] = "INBOX International, Mithrandir, Sudhaker, Ampersand Design, Technigrafa";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['official'] = 0;
$modversion['image'] = "images/module_logo.png";
$modversion['dirname'] = "smartobject";

// Added by marcan for the About page in admin section
$modversion['developer_website_url'] = "http://xoops.org";
$modversion['developer_website_name'] = "XOOPS";
$modversion['developer_email'] = "";
//$modversion['status_version'] = "Final";
//$modversion['status'] = "Final";

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2013/04/30';
$modversion["module_website_url"] = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Beta 2";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.6";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

// ---
// version 1.10
// $modversion['date'] = "2008-07-05";
$modversion['date'] = "2012-01-01";
// ---

$modversion['people']['developers'][] = "marcan (Marc-Andre Lanciault)";
$modversion['people']['developers'][] = "Mithrandir (Jan Keller Pedersen)";
$modversion['people']['developers'][] = "Sudhaker (Sudhaker Raj)";
$modversion['people']['developers'][] = "stranger";

$modversion['people']['testers'][] = "Andy Cleff";
$modversion['people']['testers'][] = "Felix Tousignant";
$modversion['people']['testers'][] = "Frederic Tousignant";
$modversion['people']['testers'][] = "Pier-Andre Roy";

//$modversion['people']['translators'][] = "translator 1";

//$modversion['people']['documenters'][] = "documenter 1";

//$modversion['people']['other'][] = "other 1";

// ----- BUG: not read other language file -----
//include_once(XOOPS_ROOT_PATH.'/modules/smartobject/language/english/common.php');
global $xoopsConfig;
$common_file = XOOPS_ROOT_PATH.'/modules/smartobject/language/'.$xoopsConfig['language'].'/common.php';
if ( file_exists($common_file) ) {
    $flag_common = true;
    include_once $common_file;
} else {
    include_once(XOOPS_ROOT_PATH.'/modules/smartobject/language/english/common.php');
}
// -----

$modversion['warning'] = _CO_SOBJECT_WARNING_BETA;

$modversion['demo_site_url'] = "";
$modversion['demo_site_name'] = "";
$modversion['support_site_url'] = "http://xoops.org";
$modversion['support_site_name'] = "XOOPS";
$modversion['submit_bug'] = "";
$modversion['submit_feature'] = "";


$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['onInstall'] = "include/update.php";
$modversion['onUpdate'] = "include/update.php";

$modversion['tables'][0] = "smartobject_meta";
$modversion['tables'][1] = "smartobject_link";
$modversion['tables'][2] = "smartobject_tag";
$modversion['tables'][3] = "smartobject_tag_text";
$modversion['tables'][4] = "smartobject_rating";
$modversion['tables'][5] = "smartobject_adsense";
$modversion['tables'][6] = "smartobject_currency";
$modversion['tables'][7] = "smartobject_customtag";
$modversion['tables'][8] = "smartobject_file";
$modversion['tables'][9] = "smartobject_urllink";

// Blocks
$i = 0;

$i++;
$modversion['blocks'][$i]['file'] = "addto.php";
$modversion['blocks'][$i]['name'] = _MI_SOBJECT_ADDTO_TITLE;
$modversion['blocks'][$i]['description']    = _MI_SOBJECT_ADDTO_DESC;
$modversion['blocks'][$i]['show_func'] = "smartobject_addto_show";
$modversion['blocks'][$i]['edit_func'] = "smartobject_addto_edit";
//$modversion['blocks'][$i]['options']  = "0";
$modversion['blocks'][$i]['template'] = 'smartobject_block_addto.html';

// Search
$modversion['hasSearch'] = 0;
/*$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "smartcontent_search";*/
// Menu
$modversion['hasMain'] = 1;
/*
$modversion['blocks'][1]['file'] = "items_new.php";
$modversion['blocks'][1]['name'] = _MI_SOBJECT_ITEMSNEW;
$modversion['blocks'][1]['description'] = "Shows new items";
$modversion['blocks'][1]['show_func'] = "smartcontent_items_new_show";
$modversion['blocks'][1]['edit_func'] = "smartcontent_items_new_edit";
$modversion['blocks'][1]['options'] = "0|datesub|5|65";
$modversion['blocks'][1]['template'] = "smartcontent_items_new.html";
*/
global $xoopsModule;

// Templates
$i = 0;

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_admin_menu.html';
$modversion['templates'][$i]['description'] = '(Admin) Tabs bar for administration pages';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_smarttable_display.html';
$modversion['templates'][$i]['description'] = 'Displays a table of SmartObjects';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_about.html';
$modversion['templates'][$i]['description'] = 'Displays the about page on admin side';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_sendlink.html';
$modversion['templates'][$i]['description'] = 'Displays a Send Link popup';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_sentlink_display.html';
$modversion['templates'][$i]['description'] = 'Displays info about a single link';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_form.html';
$modversion['templates'][$i]['description'] = 'Displays a SmartObject Form';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_rating_form.html';
$modversion['templates'][$i]['description'] = 'Displays the rating form';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_print.html';
$modversion['templates'][$i]['description'] = 'Displays a printer friendly page';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_singleview_display.html';
$modversion['templates'][$i]['description'] = 'Displays a single item view for an object';

$i++;
$modversion['templates'][$i]['file'] = 'smartobject_Addto.html';
$modversion['templates'][$i]['description'] = 'Displays an AddTo bar';

// Config Settings (only for modules that need config settings generated automatically)
$i = 0;

$i++;
$modversion['config'][$i]['name'] = 'enable_currencyman';
$modversion['config'][$i]['title'] = '_MI_SOBJECT_CURRMAN';
$modversion['config'][$i]['description'] = '_MI_SOBJECT_CURRMANDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'enable_admin_footer';
$modversion['config'][$i]['title'] = '_MI_SOBJECT_ADMFOOTER';
$modversion['config'][$i]['description'] = '_MI_SOBJECT_ADMFOOTERDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

/*
$i++;
$modversion['config'][$i]['name'] = 'show_subcats';
$modversion['config'][$i]['title'] = '_MI_SOBJECT_SHOW_SUBCATS';
$modversion['config'][$i]['description'] = '_MI_SOBJECT_SHOW_SUBCATS_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'all';
$modversion['config'][$i]['options'] = array(_MI_SOBJECT_SHOW_SUBCATS_NO  => 'no',
                                        _MI_SOBJECT_SHOW_SUBCATS_NOTEMPTY   => 'nonempty',
                                         _MI_SOBJECT_SHOW_SUBCATS_ALL => 'all');


$modversion['config'][$i]['name'] = 'items_per_page';
$modversion['config'][$i]['title'] = '_MI_SOBJECT_ITEMSPERPAGE';
$modversion['config'][$i]['description'] = '_MI_SOBJECT_ITEMSPERPAGE_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['options'] = array('5'  => 5,
                                            '10'  => 10,
                                            '15'  => 15,
                                            '20'  => 20,
                                            '25'  => 25,
                                            '30'  => 30,
                                         );
$modversion['config'][$i]['default'] = '10';
$i++;

$modversion['config'][$i]['name'] = 'module_meta_description';
$modversion['config'][$i]['title'] = '_MI_SOBJECT_MODMETADESC';
$modversion['config'][$i]['description'] = '_MI_SOBJECT_MODMETADESC_DSC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';
$i++;

$modversion['config'][$i]['name'] = 'default_editor';
$modversion['config'][$i]['title'] = '_CO_SOBJECT_DEFEDITOR';
$modversion['config'][$i]['description'] = '_CO_SOBJECT_DEFEDITOR_DSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['options'] = array('TextArea'  => 'textarea',
                                             'DHTML Text Area' => 'dhtmltextarea',
                                             'TinyEditor' => 'tiny',
                                             'FCKEditor' => 'fckeditor',
                                             'InBetween' => 'inbetween',
                                             'Koivi' => 'koivi',
                                             'Spaw' => 'spaw',
                                             'HTMLArea' => 'htmlarea'
                                         );
$modversion['config'][$i]['default'] = 'fckeditor';
$i++;
*/
?>