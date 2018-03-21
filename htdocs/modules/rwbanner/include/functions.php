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

/**
 * @param int    $currentoption
 * @param string $breadcrumb
 */
function rwbanner_adminMenu($currentoption = 0, $breadcrumb = '')
{

    /* Nice buttons styles */
    echo "
        <style type='text/css'>
        #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('../assets/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
        #buttonbar li { display:inline; margin:0; padding:0; }
        #buttonbar a {float:left; background:url('../assets/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
        #buttonbar a span { float:left; display:block; background:url('../assets/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
        /* Commented Backslash Hack hides rule from IE5-Mac \*/
        #buttonbar a span {float:none;}
        /* End IE5-Mac hack */
        #buttonbar a:hover span { color:#333; }
        #buttonbar #current a { background-position:0 -150px; border-width:0; }
        #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
        #buttonbar a:hover { background-position:0% -150px; }
        #buttonbar a:hover span { background-position:100% -150px; }
        </style>
    ";

    // global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    global $xoopsModule, $xoopsConfig;

    $myts = \MyTextSanitizer::getInstance();

    $tblColors                 = [];
    $tblColors[0]              = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
    $tblColors[$currentoption] = 'current';

    echo "<div id='buttontop'>";
    echo '<table style="width: 100%; padding: 0; " cellspacing="0"><tr>';
    //echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _AM_SPARTNER_OPTS . "</a> | <a href=\"../index.php\">" . _AM_SPARTNER_GOMOD . "</a> | <a href=\"import.php\">" . _AM_SPARTNER_IMPORT . "</a> | <a href='" . smartpartner_getHelpPath() ."' target=\"_blank\">" . _AM_SPARTNER_HELP . "</a> | <a href=\"about.php\">" . _AM_SPARTNER_ABOUT . "</a></td>";
    echo '<td style="width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;"><a href="../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod='
         . $xoopsModule->getVar('mid')
         . '">'
         . _AM_RWBANNER_PREF
         . '</a> | <a href="http://rwbanner.brinfo.com.br" target="_blank">'
         . _AM_RWBANNER_DEMO
         . '</a> | <a href="import.php">'
         . _AM_RWBANNER_IMPORT
         . '</a> | <a href="about.php">'
         . _AM_RWBANNER_ABOUT
         . '</a></td>';
    echo '<td style="width: 55%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;"><b>' . $myts->displayTarea($xoopsModule->name()) . ' ' . _AM_RWBANNER_MODADMIN . '</b> ' . $breadcrumb . '</td>';
    echo '</tr></table>';
    echo '</div>';

    echo "<div id='buttonbar'>";
    echo '<ul>';
    echo "<li id='" . $tblColors[0] . "'><a href=\"index.php\"><span>" . _AM_RWBANNER_INDEX . '</span></a></li>';
    echo "<li id='" . $tblColors[1] . "'><a href=\"myblocksadmin.php\"\"><span>" . _MI_RWBANNER_MENU_TITLE2 . '</span></a></li>';
    //  echo "<li id='" . $tblColors[2] . "'><a href=\"javascript:;\" onClick=\"javascript:window.open('inserecateg.php','cadastrar','width=450,height=250,toolbar=no');\"><span>" . _AM_RWBANNER_VALUE_BTN5 . "</span></a></li>";
    echo "<li id='" . $tblColors[2] . "'><a href=\"insercateg.php\"><span>" . _AM_RWBANNER_VALUE_BTN5 . '</span></a></li>';
    //  echo "<li id='" . $tblColors[3] . "'><a href=\"javascript:;\" onClick=\"javascript:window.open('insere.php','cadastrar','width=450,height=310,toolbar=no');\"><span>" . _AM_RWBANNER_VALUE_BTN1 . "</span></a></li>";
    echo "<li id='" . $tblColors[3] . "'><a href=\"inser.php\"><span>" . _AM_RWBANNER_VALUE_BTN1 . '</span></a></li>';
    echo "<li id='" . $tblColors[4] . "'><a href=\"javascript:;\" onClick=\"window.open('" . XOOPS_URL . "/modules/system/admin.php?fct=users','cadastrar');\"><span>" . _AM_RWBANNER_VALUE_BTN8 . '</span></a></li>';
    echo "<li id='" . $tblColors[5] . "'><a href=\"insertag.php\"><span>" . _AM_RWBANNER_VALUE_BTN12 . '</span></a></li>';
    echo '</ul></div>';
}

/**
 * @param string $tablename
 * @param string $iconname
 */
function rwbanner_collapsableBar($tablename = '', $iconname = '')
{
    ?>
    <script type="text/javascript"><!--
        function goto_URL(object) {
            window.location.href = object.options[object.selectedIndex].value;
        }

        function toggle(id) {
            if (document.getElementById) {
                obj = document.getElementById(id);
            }
            if (document.all) {
                obj = document.all[id];
            }
            if (document.layers) {
                obj = document.layers[id];
            }
            if (obj) {
                if (obj.style.display == "none") {
                    obj.style.display = "";
                } else {
                    obj.style.display = "none";
                }
            }

            return false;
        }

        var iconClose = new Image();
        iconClose.src = '../assets/images/icon/close12.gif';
        var iconOpen = new Image();
        iconOpen.src = '../assets/images/icon/open12.gif';

        function toggleIcon(iconName) {
            if (document.images[iconName].src == window.iconOpen.src) {
                document.images[iconName].src = window.iconClose.src;
            }
            elseif(document.images[iconName].src == window.iconClose.src)
            {
                document.images[iconName].src = window.iconOpen.src;
            }

            return;
        }

        //-->
    </script>
    <?php
    echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "')\";>";
}

//Soma X dias em uma data
/**
 * @param $data
 * @param $qDias
 * @return string
 */
function somaData($data, $qDias)
{
    $data = explode(' ', $data);
    $data = $data[0];
    $dt   = explode('-', $data);
    $ano  = $dt[0];
    $mes  = $dt[1];
    $dia  = $dt[2];
    while ($qDias > 0) {
        $tira   = 0;
        $diaAnt = $dia;
        $maxDia = date('t', strtotime($ano . $mes . $dia));
        $dia    += $qDias;
        if ($dia > $maxDia) {
            $dia = 1;
            ++$mes;
            $tira = 1;
            if ($mes > 12) {
                $mes = 1;
                ++$ano;
            }
            $qDias = $qDias - ($maxDia - $diaAnt) - $tira;
        } else {
            $qDias = 0;
        }
        if (strlen($dia) < 2) {
            $dia = '0' . $dia;
        }
        if (strlen($mes) < 2) {
            $mes = '0' . $mes;
        }
    }

    return ($ano . '-' . $mes . '-' . $dia);
}

//Escreve a data por extenso. Parametro data deve ser passado no formato americano 'Y-m-d';
/**
 * @param $data
 * @return string
 */
function escreveData($data)
{
    $vardia = substr($data, 8, 2);
    $varmes = substr($data, 5, 1);
    $varano = substr($data, 0, 4);

    $convertedia = date('w', mktime(0, 0, 0, $varmes, $vardia, $varano));

    $diaSemana = [
        'Domingo',
        'Segunda-feira',
        'Terça-feira',
        'Quarta-feira',
        'Quinta-feira',
        'Sexta-feira',
        'Sábado'
    ];

    $mes = [
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro'
    ];

    return $diaSemana[$convertedia] . ', ' . $vardia . ' de ' . $mes[$varmes] . ' de ' . $varano;
}

//Tipo = BR converte datas no formato americano para o formato brasileiro
//Tipo = EN converte datas no formato brasileiro para o formato americano
/**
 * @param               $data_ori
 * @param  string       $tipo
 * @param  string       $hora
 * @return array|string
 */
function converte($data_ori, $tipo = 'BR', $hora = 'true')
{
    $data = explode(' ', $data_ori);
    if ('BR' === $tipo) {
        $resul = explode('-', $data[0]);
        $resul = $resul[2] . '/' . $resul[1] . '/' . $resul[0];
    } else {
        if ('EN' === $tipo) {
            $resul = explode('/', $data[0]);
            $resul = $resul[2] . '-' . $resul[1] . '-' . $resul[0];
        } else {
            if ('FB' === $tipo) {
                $resul = explode('/', $data[0]);
                $resul = $resul[0] . '.' . $resul[1] . '.' . $resul[2];
            }
        }
    }
    if ($hora) {
        return $resul . ' ' . $data[1];
    } else {
        return $resul;
    }
}

/**
 * @param $bid
 * @return bool
 */
function EmailStats($bid)
{
    global $xoopsDB, $xoopsConfig, $xoopsUser;

    $uid   = $xoopsUser->getVar('uid');
    $email = $xoopsUser->getVar('email');
    $name  = ('' == $xoopsUser->getVar('name')) ? $xoopsUser->getVar('uname') : $xoopsUser->getVar('name');

    $result = $xoopsDB->query('select * from ' . $xoopsDB->prefix('rwbanner_banner') . " where codigo=$bid and idcliente=$uid");
    $row    = $xoopsDB->fetchArray($result);

    if (0 == $row['exibicoes']) {
        $percent = 0;
    } else {
        $percent = substr(100 * $row['clicks'] / $row['exibicoes'], 0, 5);
    }
    if (0 == $row['maxexib']) {
        $left           = _MD_RWBANNER_EXIBREST;
        $row['maxexib'] = _MD_RWBANNER_EXIBREST;
    } else {
        $left = $row['maxexib'] - $row['exibicoes'];
    }
    if (0 == $row['maxclick']) {
        $left_clicks     = _MD_RWBANNER_EXIBREST;
        $row['maxclick'] = _MD_RWBANNER_EXIBREST;
    } else {
        $left_clicks = $row['maxclick'] - $row['clicks'];
    }
    if (0 == $row['periodo']) {
        $left_periodo   = _MD_RWBANNER_EXIBREST;
        $row['periodo'] = _MD_RWBANNER_EXIBREST;
    } else {
        $left_periodo = converte(somaData($row['data'], $row['periodo']), 'BR', 0);
    }
    $row['data'] = converte($row['data'], 'BR', false);
    $fecha       = escreveData(date('Y-n-d'));
    $subject     = _MD_RWBANNER_SUBJECT_EMAILSTATS . ' ' . $xoopsConfig['sitename'];
    $message     = _MD_RWBANNER_BODY1_EMAILSTATS
                   . ' '
                   . $xoopsConfig['sitename']
                   . ":\n\n\n"
                   . _MD_RWBANNER_BODY2_EMAILSTATS
                   . " $name\n"
                   . _MD_RWBANNER_BODY3_EMAILSTATS
                   . " $bid\n"
                   . _MD_RWBANNER_BODY4_EMAILSTATS
                   . ' '
                   . $row['grafico']
                   . "\n"
                   . _MD_RWBANNER_BODY5_EMAILSTATS
                   . ' '
                   . $row['url']
                   . "\n\n"
                   . _MD_RWBANNER_BODY6_EMAILSTATS
                   . ' '
                   . $row['maxexib']
                   . "\n"
                   . _MD_RWBANNER_BODY7_EMAILSTATS
                   . ' '
                   . $row['exibicoes']
                   . "\n"
                   . _MD_RWBANNER_BODY8_EMAILSTATS
                   . " $left\n"
                   . _MD_RWBANNER_BODY11_EMAILSTATS
                   . ' '
                   . $row['maxclick']
                   . "\n"
                   . _MD_RWBANNER_BODY9_EMAILSTATS
                   . ' '
                   . $row['clicks']
                   . "\n"
                   . _MD_RWBANNER_BODY10_EMAILSTATS
                   . " $percent%\n"
                   . _MD_RWBANNER_BODY12_EMAILSTATS
                   . " $left_clicks\n"
                   . _MD_RWBANNER_BODY13_EMAILSTATS
                   . ' '
                   . $row['data']
                   . "\n"
                   . _MD_RWBANNER_BODY14_EMAILSTATS
                   . ' '
                   . $row['periodo']
                   . ' '
                   . _MD_RWBANNER_BODY17_EMAILSTATS
                   . "\n"
                   . _MD_RWBANNER_BODY15_EMAILSTATS
                   . " $left_periodo\n\n\n"
                   . _MD_RWBANNER_BODY16_EMAILSTATS
                   . " $fecha";
    $xoopsMailer = xoops_getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setToEmails($email);
    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
    $xoopsMailer->setFromName($xoopsConfig['sitename']);
    $xoopsMailer->setSubject($subject);
    $xoopsMailer->setBody($message);

    if ($xoopsMailer->send(true)) {
        return true;
    } else {
        return false;
    }
}

/**
 * @return mixed|null|\XoopsModule
 */
function &rwbanner_getModuleInfo()
{
    static $rwModule;
    $dirname = basename(dirname(__DIR__));
    if (!isset($rwModule)) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $dirname) {
            $rwModule =& $xoopsModule;
        } else {
            $hModule  = xoops_getHandler('module');
            $rwModule = $hModule->getByDirname($dirname);
        }
    }

    return $rwModule;
}

/**
 * @return ARRAY|bool|int|mixed
 */
function rwbanner_getModuleConfig()
{
    static $rwConfig;
    if (!$rwConfig) {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule)
            && $xoopsModule->getVar('dirname') == basename(dirname(__DIR__))) {
            global $xoopsModuleConfig;
            $rwConfig =& $xoopsModuleConfig;
        } else {
            $rwModule   =& rwbanner_getModuleInfo();
            $hModConfig = xoops_getHandler('config');
            $rwConfig   = $hModConfig->getConfigsByCat(0, $rwModule->getVar('mid'));
        }
    }

    return $rwConfig;
}

/**
 * Verify that a mysql table exists
 *
 * @package       News
 * @author        Hervé Thouzard (www.herve-thouzard.com)
 * @copyright (c) XOOPS Project (https://xoops.org)
 * @param $tablename
 * @return bool
 */
function rwTableExists($tablename)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Verify that a field exists inside a mysql table
 *
 * @package       News
 * @author        Hervé Thouzard (www.herve-thouzard.com)
 * @copyright (c) XOOPS Project (https://xoops.org)
 * @param $fieldname
 * @param $table
 * @return bool
 */
function rwFieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM   $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Add a field to a mysql table
 *
 * @package       News
 * @author        Hervé Thouzard (www.herve-thouzard.com)
 * @copyright (c) XOOPS Project (https://xoops.org)
 * @param $field
 * @param $table
 * @return bool|\mysqli_result
 */
function rwAddField($field, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

    return $result;
}

/**
 * Remove a field to a mysql table
 *
 * @package RW-Banner
 * @author  Rodrigo Pereira Lima aka RpLima (http://www.brinfo.com.br)
 * @param $field
 * @param $table
 * @return bool|\mysqli_result
 */
function rwRemoveField($field, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " DROP $field;");

    return $result;
}
