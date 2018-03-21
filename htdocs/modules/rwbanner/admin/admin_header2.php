<?php
//  ------------------------------------------------------------------------ //
//                                  RW-Banner                                //
//                    Copyright (c) 2006 BrInfo                              //
//                     <http://www.brinfo.com.br>                            //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author: Rodrigo Pereira Lima (BrInfo - Soluções Web)                      //
// Site: http://www.brinfo.com.br                                            //
// Project: RW-Banner                                                        //
// Descrição: Sistema de gerenciamento de mídias publicitárias               //
// ------------------------------------------------------------------------- //

use XoopsModules\Rwbanner;

$path = dirname(dirname(dirname(__DIR__)));
require_once $path . '/mainfile.php';
require_once $path . '/include/cp_header.php';
require_once $path . '/kernel/module.php';
require_once $path . '/class/xoopstree.php';
require_once $path . '/class/xoopslists.php';
require_once $path . '/class/xoopsformloader.php';
require_once $path . '/class/pagenav.php';

if (is_object($xoopsUser)) {
    $dirname = basename(dirname(__DIR__));
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($dirname);
    if (!$xoopsUser->isAdmin($module->mid())) {
        redirect_header(XOOPS_URL . '/', 1, _MD_RWBANNER_NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 1, _MD_RWBANNER_NOPERM);
}

require_once XOOPS_ROOT_PATH . '/modules/' . $module->dirname() . '/include/functions.php';

/** @var Rwbanner\Helper $helper */
$helper = Rwbanner\Helper::getInstance();
$helper->loadLanguage('modinfo');

$myts = \MyTextSanitizer::getInstance();
