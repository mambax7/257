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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */

use XoopsModules\Xsitemap;

$moduleDirName = basename(__DIR__);

require_once __DIR__ . '/../../mainfile.php';
//require_once __DIR__ . '/../../include/cp_header.php';
require_once __DIR__ . '/include/common.php';

$myts = \MyTextSanitizer::getInstance();


$helper       = Xsitemap\Helper::getInstance();
$utility      = new Xsitemap\Utility();
$configurator = new Xsitemap\Common\Configurator();
// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
