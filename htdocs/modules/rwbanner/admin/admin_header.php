<?php
/**
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @author       XOOPS Development Team
 **/

use XoopsModules\Rwbanner;

require_once __DIR__ . '/../../../include/cp_header.php';

$path = dirname(dirname(dirname(__DIR__)));
//require_once $path . '/mainfile.php';
//
//require_once $path . '/header.php';
//require_once $path . '/include/cp_functions.php';
//require_once $path . '/include/cp_header.php';
//
//require_once $path . '/kernel/module.php';
//require_once $path . '/class/xoopstree.php';
//require_once $path . '/class/xoopslists.php';
//require_once $path . '/class/xoopsformloader.php';
require_once $path . '/class/pagenav.php';

// require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));
$helper = Rwbanner\Helper::getInstance();
$adminObject = \Xmf\Module\Admin::getInstance();

if (is_object($xoopsUser)) {
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 1, _MD_RWBANNER_NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 1, _MD_RWBANNER_NOPERM);
}

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

//$myts = \MyTextSanitizer::getInstance();
//
//if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
//    require_once $GLOBALS['xoops']->path('class/template.php');
//    $xoopsTpl = new \XoopsTpl();
//}

//xoops_cp_header();
