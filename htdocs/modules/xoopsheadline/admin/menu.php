<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

use XoopsModules\Xoopsheadline;

// require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Xoopsheadline\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


$adminmenu[] = [
    'title' => _MI_HEADLINES_MENU_ADMININDEX,
    'link'  => 'admin/index.php',
    'desc'  => _MI_HEADLINES_MENU_ADMININDEX_DESC,
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_HEADLINES_MENU_ADMINHL,
    'link'  => 'admin/main.php',
    'desc'  => _MI_HEADLINES_MENU_ADMINHL_DESC,
    'icon'  => $pathIcon32 . '/content.png',
];

$adminmenu[] = [
    'title' => _MI_HEADLINES_MENU_ADMINABOUT,
    'link'  => 'admin/about.php',
    'desc'  => _MI_HEADLINES_MENU_ADMINABOUT_DESC,
    'icon'  => $pathIcon32 . '/about.png',
];
