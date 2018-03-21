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
 * XOOPS rwbanner Upgrade
 *
 * @copyright::  {@link www.brinfo.com.br BrInfo - Soluções Web}
 * @license  ::    {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author   ::     Rodrigo Pereira Lima aka RpLima (http://www.brinfo.com.br)
 * @package  ::    rwbanner
 * @since    ::      1.0
 *
 */

use  XoopsModules\Rwbanner;

require_once __DIR__ . '/../../../include/cp_header.php';
xoops_cp_header();
require_once __DIR__ . '/../include/functions.php';

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;

    //Checa se a tabela ds banners existe, caso n?o exista cria.
    if (!rwTableExists($xoopsDB->prefix('rwbanner_banner'))) {
        $sql = '
    CREATE TABLE ' . $xoopsDB->prefix('rwbanner_banner') . " (
      codigo INT(11) NOT NULL AUTO_INCREMENT,
      categoria INT(11) DEFAULT NULL,
      titulo VARCHAR(255) DEFAULT NULL,
      texto TEXT,
      url VARCHAR(255) DEFAULT NULL,
      grafico VARCHAR(255) DEFAULT NULL,
      usarhtml INT(1) DEFAULT NULL,
      htmlcode TEXT,
      showimg INT(1) NOT NULL DEFAULT '1',
      exibicoes INT(11) DEFAULT NULL,
      maxexib INT(11) NOT NULL DEFAULT '0',
      clicks INT(11) DEFAULT NULL,
      maxclick INT(11) NOT NULL DEFAULT '0',
      data DATETIME DEFAULT NULL,
      periodo INT(5) NOT NULL DEFAULT '0',
      status INT(1) UNSIGNED NOT NULL DEFAULT '1',
      target VARCHAR(50) DEFAULT '_blank',
      idcliente INT(11) DEFAULT NULL,
      obs TEXT,
      PRIMARY KEY  (`codigo`)
    ) ENGINE=MyISAM;
    ";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_RWBANNER_UPGRADEFAILED1;
            ++$errors;
        }
    } else {
        if (!rwFieldExists('titulo', $xoopsDB->prefix('rwbanner_banner'))) {
            rwAddField('titulo varchar(255) default NULL AFTER categoria', $xoopsDB->prefix('rwbanner_banner'));
        }
        if (!rwFieldExists('texto', $xoopsDB->prefix('rwbanner_banner'))) {
            rwAddField('texto text AFTER titulo', $xoopsDB->prefix('rwbanner_banner'));
        }
        if (!rwFieldExists('showimg', $xoopsDB->prefix('rwbanner_banner'))) {
            rwAddField("showimg int(1) NOT NULL default '1' AFTER htmlcode", $xoopsDB->prefix('rwbanner_banner'));
        }
        if (!rwFieldExists('periodo', $xoopsDB->prefix('rwbanner_banner'))) {
            rwAddField("periodo int(5) NOT NULL default '0' AFTER data", $xoopsDB->prefix('rwbanner_banner'));
        }
        if (!rwFieldExists('obs', $xoopsDB->prefix('rwbanner_banner'))) {
            rwAddField('obs text AFTER idcliente', $xoopsDB->prefix('rwbanner_banner'));
        }
        if (!rwFieldExists('maxclick', $xoopsDB->prefix('rwbanner_banner'))) {
            rwAddField("maxclick int(11) NOT NULL default '0' AFTER clicks", $xoopsDB->prefix('rwbanner_banner'));
        }
    }

    if (!rwTableExists($xoopsDB->prefix('rwbanner_categorias'))) {
        $sql = '
    CREATE TABLE ' . $xoopsDB->prefix('rwbanner_categorias') . " (
      cod INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      titulo VARCHAR(50) DEFAULT NULL,
      larg INT(11) UNSIGNED NOT NULL DEFAULT '0',
      alt INT(11) UNSIGNED NOT NULL DEFAULT '0',
      PRIMARY KEY  (cod)
    ) ENGINE=MyISAM;
    ";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_RWBANNER_UPGRADEFAILED2;
            ++$errors;
        }
    } else {
        if (rwFieldExists('modid', $xoopsDB->prefix('rwbanner_categorias'))) {
            rwRemoveField('modid', $xoopsDB->prefix('rwbanner_categorias'));
        }
    }

    if (!rwTableExists($xoopsDB->prefix('rwbanner_tags'))) {
        $sql = '
    CREATE TABLE ' . $xoopsDB->prefix('rwbanner_tags') . " (
      id INT(11) NOT NULL AUTO_INCREMENT,
      title VARCHAR(255) DEFAULT NULL,
      name VARCHAR(255) NOT NULL DEFAULT 'rwbanner',
      codbanner INT(5) DEFAULT NULL,
      categ INT(5) NOT NULL DEFAULT '1',
      qtde INT(5) NOT NULL DEFAULT '1',
      cols INT(5) NOT NULL DEFAULT '1',
      modid TEXT,
      obs TEXT,
      status INT(1) NOT NULL DEFAULT '1',
      PRIMARY KEY  (id)
    ) ENGINE=MyISAM;
    ";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_RWBANNER_UPGRADEFAILED3;
            ++$errors;
        }
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('rwbanner_tags') . ' (id,title,name,codbanner,categ,qtde,cols,modid,obs,status) VALUES (1,"RW-BANNER Default TAG","rwbanner","",1,1,1,"a:1:{i:0;s:1:\"0\";}","",1)';
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_RWBANNER_UPGRADEFAILED3;
            ++$errors;
        }
    }

    if ($errors) {
        echo '<h1>' . _AM_RWBANNER_UPGRADEFAILED . "</h1>\n";
    } else {
        echo _AM_RWBANNER_UPGRADECOMPLETE . " - <a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module='. basename(dirname(__DIR__)) .'>" . _AM_RWBANNER_UPDATEMODULE . '</a>';
        echo '<p style="text-align:justify;">' . _AM_RWBANNER_UPGRADECOMPLETE1 . '</p>';
        echo '<pre>\n';
        print('
      #######################################################################################
      #### Hack by rw-banner
      #### Cria variáveis smarty para exibir banner no tema do site e nos templates
      #######################################################################################
      $mod = XoopsModule::getByDirname(basename(dirname(__DIR__)));
      if ($mod) {
        require_once (dirname(__DIR__) .\'/include/maketags.php\');
      }
      #######################################################################################
      #### Fim do Hack by rw-banner
      #######################################################################################
    ');
        echo "</pre>\n";
        echo "<p style='text-align: justify;'>" . _AM_RWBANNER_UPGRADECOMPLETE2 . "</p>\n";
        echo "<pre>\n";
        print('
      #######################################################################################
      #### Hack by rw-banner
      #### Permite a exibição de banners randomicos em qualquer módulo que aceite bbcodes
      #######################################################################################
      $mod = XoopsModule::getByDirname(basename(dirname(__DIR__)));
      if ($mod) {
        include(dirname(__DIR__) .\'/include/bbcode.php\');
      }
      #######################################################################################
      #### Fim do Hack by rw-banner
      #######################################################################################
    ');
        echo "</pre>\n";
    }
} else {
    printf("<h2>%s</h2>\n", _MD_RWBANNER_NOPERM);
}
