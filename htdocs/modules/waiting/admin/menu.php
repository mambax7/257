<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Waiting
 *
 * @category        Module
 * @package         waiting
 * @subpackage      administration
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            https://xoops.org XOOPS
 */

use XoopsModules\Waiting;

// require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Waiting\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$adminmenu = [
    [
        'title' => _MI_WAITING_MENU_HOME,
        'link'  => 'admin/index.php',
        'icon'  => "{$pathIcon32}/home.png"
    ],

    [
        'title' => _MI_WAITING_MENU_PLUGINS,
        'link'  => 'admin/main.php',
        'icon'  => "{$pathIcon32}/search.png"
    ],

    [
        'title' => _MI_WAITING_MENU_ABOUT,
        'link'  => 'admin/about.php',
        'icon'  => "{$pathIcon32}/about.png"
    ]
];
