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

use  XoopsModules\Rwbanner;

// require_once __DIR__ . '/../class/class.banner.php';
// require_once __DIR__ . '/../class/class.tags.php';
global $xoopsTpl;

$url_arr = explode('/', strstr($_SERVER['PHP_SELF'], '/modules/'));
$mod     = isset($url_arr[2]) ? XoopsModule::getByDirname($url_arr[2]) : false;
if ($mod) {
    $mid = $mod->mid();
} else {
    $mid = 0;
}

$tag        = new Rwbanner\Tag();
$lista_tags = $tag->getTags('ORDER BY id ASC');

for ($i = 0; $i <= count($lista_tags) - 1; ++$i) {
    $mods = unserialize($lista_tags[$i]->modid);
    if (1 == $lista_tags[$i]->getStatus()) {
        if (in_array(0, $mods)) {
            $rwbanner = new Rwbanner\Banner();
            $xoopsTpl->assign($lista_tags[$i]->getName(), $rwbanner->showBanner($lista_tags[$i]->getCateg(), $lista_tags[$i]->getQtde(), $lista_tags[$i]->getCols()));
        } elseif (!in_array(0, $mods) && in_array($mid, $mods)) {
            $rwbanner = new Banner();
            $xoopsTpl->assign($lista_tags[$i]->getName(), $rwbanner->showBanner($lista_tags[$i]->getCateg(), $lista_tags[$i]->getQtde(), $lista_tags[$i]->getCols()));
        } else {
            $xoopsTpl->assign($lista_tags[$i]->getName(), '');
        }
    } else {
        $xoopsTpl->assign($lista_tags[$i]->getName(), '');
    }
}
