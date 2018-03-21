<?php
/*
                                  RW-Banner
                          Copyright (c) 2006 BrInfo
                          <http://www.brinfo.com.br>
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  You may not change or alter any portion of this comment or credits
  of supporting developers from this source code or any supporting
  source code which is considered copyrighted (c) material of the
  original comment or credit authors.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*/
/**
 * XOOPS rwbanner Ajax Banner Block
 *
 * @copyright::  {@link www.brinfo.com.br BrInfo - Soluções Web}
 * @license  ::    {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author   ::     Rodrigo Pereira Lima aka RpLima (http://www.brinfo.com.br)
 * @package  ::    rwbanner
 * @param $options
 * @return array
 */

use XoopsModules\Rwbanner;

function exibe_ajaxbanner($options)
{
    // require_once __DIR__ . '/../class/class.categoria.php';
    // require_once __DIR__ . '/../class/class.banner.php';

    global $xoopsTpl;
    $dirname = basename(dirname(__DIR__));
    $xoopsTpl->assign('module_dir', $dirname);

    //recebendo parâmetros de configuração
    $block                = [];
    $block['title']       = _MI_RWBANNER_BLOCK4_NAME;
    $block['categ']       = $options[0];
    $block['qtde']        = $options[1];
    $block['cols']        = $options[2];
    $block['tempo']       = $options[3];
    $block['id_div']      = mt_rand(1, 2000);
    $block['lang_carreg'] = _MB_RWBANNER_OPTION15;

    $categ                    = new Rwbanner\Categoria(null, $options[0]);
    $banner                   = new Rwbanner\Banner();
    $block['qtde_encontrado'] = count($banner->getBanners(false, 'ORDER BY RAND()', $options[0], $options[1]));
    $block['alt']             = ($block['qtde'] > 1 && 1 == $block['cols']) ? (($categ->getAlt() * $block['qtde_encontrado']) + (15 * $block['qtde_encontrado'])) : ($categ->getAlt() + 20);

    return $block;
}

/**
 * @param $options
 * @return string
 */
function edita_ajaxbanner($options)
{
    global $xoopsDB;
    $query    = 'SELECT cod,titulo FROM ' . $xoopsDB->prefix('rwbanner_categorias');
    $consulta = $xoopsDB->queryF($query);
    $categ    = _MB_RWBANNER_OPTION1 . "&nbsp;<select options[0] name=\"options[0]\" onchange='javascript:options0.value = this.value;'>";
    while (false !== (list($cod, $titulo) = $xoopsDB->fetchRow($consulta))) {
        $sel   = ($options[0] == $cod) ? " 'selected'" : '';
        $categ .= '<option value="' . $cod . '" ' . $sel . '>' . $titulo . '</option>';
    }
    $categ .= '</select>';
    $form  = $categ;
    //Quantidade de banners à exibir no bloco
    $qtde = _MB_RWBANNER_OPTION2 . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "' onchange='javascript:options1.value = this.value;'>";
    $form .= '<br>' . $qtde;
    //Quantidade de colunas em que os banners serão exibidos
    $qtde = _MB_RWBANNER_OPTION3 . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "' onchange='javascript:options2.value = this.value;'>";
    $form .= '<br>' . $qtde;
    //Tempo de atualização dos banners
    $qtde = _MB_RWBANNER_OPTION13 . "&nbsp;<input type='text' name='options[]' value='" . $options[3] . "' onchange='javascript:options3.value = this.value;'>";
    $form .= '<br>' . $qtde;

    return $form;
}
