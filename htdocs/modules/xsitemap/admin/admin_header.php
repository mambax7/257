<?php
/*
 * xsitemap module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package    module\Xsitemap\admin
 * @author     Urbanspaceman (http://www.takeaweb.it)
 * @copyright  Urbanspaceman (http://www.takeaweb.it)
 * @author     XOOPS Module Dev Team
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @link       https://xoops.org XOOPS
 * @since      1.00
 **/
use \XoopsModules\Xsitemap;

$moduleDirName = basename(dirname(__DIR__));
require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));
/** @var Xsitemap\Helper $helper */
$helper = Xsitemap\Helper::getInstance();

/** @var Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

//$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
//$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
//$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

//if functions.php file exist
//require_once __DIR__ . '/../include/functions.php';

// require_once __DIR__ . '/../class/plugin.php';
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
//
///** @var PluginHandler $pluginHandler */
//$pluginHandler = $helper->getHandler('plugin');

//xoops_cp_header();
