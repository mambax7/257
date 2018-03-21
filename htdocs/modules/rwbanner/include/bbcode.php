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
// require_once __DIR__ . '/../class/class.tags.php';

use XoopsModules\Rwbanner;

global $xoopsConfig;

$valid_aligns = ['left', 'center', 'right'];
$tag          = new Tag();
$lista_tags   = $tag->getTags('ORDER BY id ASC');
$valid_tags   = $tag->getTags('ORDER BY id ASC', true);
if (preg_match_all('/\[RW align=(.*)\](.*)\[\/RW\]/sU', $text, $texto)) {
    if (3 == count($texto)) {
        for ($i = 0; $i <= count($texto[0]) - 1; ++$i) {
            $arr[$i]['tag']   = $texto[2][$i];
            $arr[$i]['algin'] = $texto[1][$i];
        }

        for ($i = 0; $i <= count($arr) - 1; ++$i) {
            if (in_array($arr[$i]['tag'], $valid_tags)) {
                foreach ($lista_tags as $tag) {
                    if ($arr[$i]['tag'] == $tag->getName() && 1 == $tag->getStatus()) {
                        if (in_array($arr[$i]['algin'], $valid_aligns)) {
                            $banner     = new Banner();
                            $patterns[] = "/\[RW align=" . $arr[$i]['algin'] . ']' . $tag->getName() . "\[\/RW\]/sU";
                            if ($tag->isRandom) {
                                $replacements[] = $banner->showBanner($tag->getCateg(), $tag->getQtde(), $tag->getCols(), $arr[$i]['algin']);
                            } else {
                                $replacements[] = $banner->show1Banner($tag->getCodbanner(), $texto[1]);
                            }
                            unset($banner);
                        } else {
                            $patterns[]     = "/\[RW align=" . $arr[$i]['algin'] . ']' . $tag->getName() . "\[\/RW\]/sU";
                            $replacements[] = sprintf(_MD_RWBANNER_TAG_ERROR, $arr[$i]['tag']);
                        }
                    }
                }
            } else {
                $patterns[]     = "/\[RW align=" . $arr[$i]['algin'] . ']' . $arr[$i]['tag'] . "\[\/RW\]/sU";
                $replacements[] = sprintf(_MD_RWBANNER_TAG_ERROR1, $arr[$i]['tag']);
            }
        }
    }
}
unset($texto, $valid_aligns, $lista_tags, $valid_tags, $arr, $banner);
