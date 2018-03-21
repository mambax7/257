<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Waiting
 *
 * @category        Module
 * @package         waiting
 * @author          Kazumi Ono (AKA onokazu)
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            http://www.myweb.ne.jp/
 * @link            https://xoops.org XOOPS
 * @since           2.00
 */

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName                     = basename(__DIR__);
$modversion['version']             = 1.06;
$modversion['module_status']       = 'RC1';
$modversion['release_date']        = '2017/05/30';
$modversion['name']                = _MI_WAITING_NAME;
$modversion['description']         = _MI_WAITING_DESC;
$modversion['author']              = 'Ryuji (http://ryus.co.jp/)';
$modversion['author_website_url']  = 'http://ryus.co.jp';
$modversion['credits']             = 'Ryus';
$modversion['help']                = 'page=help';
$modversion['license']             = 'GNU GPL 2.0';
$modversion['license_url']         = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']            = 0;
$modversion['image']               = 'assets/images/logoModule.png';
$modversion['dirname']             = $moduleDirName;
$modversion['modicons16']          = 'assets/images/icons/16';
$modversion['modicons32']          = 'assets/images/icons/32';
$modversion['module_website_url']  = 'www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminmenu']   = 'admin/menu.php';
$modversion['adminindex']  = 'admin/index.php';

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_WAITING_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_WAITING_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_WAITING_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_WAITING_SUPPORT, 'link' => 'page=support'],
];

// Templates

// Blocks
$modversion['blocks'][1]['file']        = 'waiting_waiting.php';
$modversion['blocks'][1]['name']        = _MI_WAITING_BNAME1;
$modversion['blocks'][1]['description'] = _MI_WAITING_BNAME1_DESC;
$modversion['blocks'][1]['show_func']   = 'b_waiting_waiting_show';
$modversion['blocks'][1]['edit_func']   = 'b_waiting_waiting_edit';
$modversion['blocks'][1]['template']    = 'waiting_block_waiting.html';
$modversion['blocks'][1]['options']     = '1|5';

// On Update
if (!empty($_POST['fct']) && !empty($_POST['op']) && 'modulesadmin' === $_POST['fct'] && 'update_ok' === $_POST['op'] && $_POST['module'] == $moduleDirName) {
    include __DIR__ . '/include/updateblock.inc.php';
}
