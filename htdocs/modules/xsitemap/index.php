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
 */
/**
 * @package    module\Xsitemap\frontside
 * @author     Urbanspaceman (http://www.takeaweb.it)
 * @copyright  Urbanspaceman (http://www.takeaweb.it)
 * @copyright  XOOPS Project
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @link       https://xoops.org XOOPS
 * @since      1.00
 **/


require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
require_once __DIR__ . '/../../mainfile.php';

//template assign
$GLOBALS['xoopsOption']['template_main'] = 'xsitemap_index.tpl';

require_once $GLOBALS['xoops']->path('header.php');
require_once $GLOBALS['xoops']->path('class/tree.php');
//require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/plugin.php');
//require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/Utility.php');
//require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/DummyObject.php');

$xsitemap_configs = $GLOBALS['xoopsModuleConfig'];

$xsitemap_show = $utility::generateSitemap();

$GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url('browse.php?modules/' . $moduleDirName . '/assets/css/style.css'));
$GLOBALS['xoopsTpl']->assign([
                                 'xsitemap'           => $xsitemap_show,
                                 'num_col'            => $xsitemap_configs['columns_number'],
                                 'show_sublink'       => $xsitemap_configs['show_sublink'],
                                 'show_subcategories' => $xsitemap_configs['show_subcategories']
                             ]);

require_once $GLOBALS['xoops']->path('footer.php');
