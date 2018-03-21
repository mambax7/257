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
 * $Id: myblocksadmin.php,v 1.4 2005/03/15 20:09:34 malanciault Exp $
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

// ------------------------------------------------------------------------- //
//                            myblocksadmin.php                              //
//                - XOOPS block admin for each modules -                     //
//                          GIJOE <http://www.peak.ne.jp>                   //
// ------------------------------------------------------------------------- //

use  XoopsModules\Rwbanner;

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/mygroupperm.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
//require_once "../include/gtickets.php" ;// GIJ
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';

$xoops_system_path = XOOPS_ROOT_PATH . '/modules/system';

// language files
$language = $xoopsConfig['language'];
if (!file_exists("$xoops_system_path/language/$language/admin/blocksadmin.php")) {
    $language = 'english';
}

// to prevent from notice that constants already defined
$error_reporting_level = error_reporting(0);
require_once "$xoops_system_path/constants.php";
require_once "$xoops_system_path/language/$language/admin.php";
require_once "$xoops_system_path/language/$language/admin/blocksadmin.php";
$dirname = basename(dirname(__DIR__));
require_once dirname(__DIR__) . "/language/$language/modinfo.php";

error_reporting($error_reporting_level);

$group_defs = file("$xoops_system_path/language/$language/admin/groups.php");
foreach ($group_defs as $def) {
    if (false !== strpos($def, '_AM_RWBANNER_ACCESSRIGHTS') || false !== strpos($def, '_AM_RWBANNER_ACTIVERIGHTS')) {
        eval($def);
    }
}

// check $xoopsModule
if (!is_object($xoopsModule)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_RWBANNER_NOPERM);
}

// set target_module if specified by $_GET['dirname']
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
if (!empty($_GET['dirname'])) {
    $target_module = $moduleHandler->getByDirname($_GET['dirname']);
}
// check access right (needs system_admin of BLOCK)
$syspermHandler = xoops_getHandler('groupperm');
if (!$syspermHandler->checkRight('system_admin', XOOPS_SYSTEM_BLOCK, $xoopsUser->getGroups())) {
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_RWBANNER_NOPERM);
}

// get blocks owned by the module (Imported from xoopsblock.php then modified)
$block_arr =& XoopsBlock::getByModule($xoopsModule->mid());

function list_blocks()
{
    global $block_arr, $xoopsModule;

    // cachetime options
    $cachetimes = [
        '0'       => _NOCACHE,
        '30'      => sprintf(_SECONDS, 30),
        '60'      => _MINUTE,
        '300'     => sprintf(_MINUTES, 5),
        '1800'    => sprintf(_MINUTES, 30),
        '3600'    => _HOUR,
        '18000'   => sprintf(_HOURS, 5),
        '86400'   => _DAY,
        '259200'  => sprintf(_DAYS, 3),
        '604800'  => _WEEK,
        '2592000' => _MONTH
    ];

    // displaying TH
    rwbanner_collapsableBar('toptable', 'toptableicon');
    echo "<img id='toptableicon' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/close12.gif alt=''></a>&nbsp;" . _AM_RWBANNER_BLOCKS . '</h3>';
    echo "<div id='toptable'>";
    echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . _AM_RWBANNER_BLOCKSINFO . '</span>';
    echo "
    <form action='admin.php' name='blockadmin' method='post'>
        <table width='100%' class='outer' cellpadding='4' cellspacing='1'>
        <tr valign='middle'>
            <th>" . _AM_TITLE . "</th>
            <th align='center' nowrap='nowrap'>" . _AM_SIDE . "</th>
            <th align='center'>" . _AM_WEIGHT . "</th>
            <th align='center'>" . _AM_VISIBLEIN . "</th>
            <th align='center'>" . _AM_BCACHETIME . "</th>
            <th align='right'>" . _AM_RWBANNER_ACTION . "</th>
        </tr>\n";

    // blocks displaying loop
    $class         = 'even';
    $block_configs = get_block_configs();
    foreach (array_keys($block_arr) as $i) {
        $sseln = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = $ssel7 = $ssel8 = $ssel9 = '';
        $scoln = $scol0 = $scol1 = $scol2 = $scol3 = $scol4 = $scol7 = $scol8 = $scol9 = '#FFFFFF';

        $weight     = $block_arr[$i]->getVar('weight');
        $title      = $block_arr[$i]->getVar('title');
        $name       = $block_arr[$i]->getVar('name');
        $bcachetime = $block_arr[$i]->getVar('bcachetime');
        $bid        = $block_arr[$i]->getVar('bid');

        // visible and side
        if (1 != $block_arr[$i]->getVar('visible')) {
            $sseln = ' checked';
            $scoln = '#FF0000';
        } else {
            switch ($block_arr[$i]->getVar('side')) {
                default:
                case XOOPS_SIDEBLOCK_LEFT:
                    $ssel0 = ' checked';
                    $scol0 = '#00FF00';
                    break;
                case XOOPS_SIDEBLOCK_RIGHT:
                    $ssel1 = ' checked';
                    $scol1 = '#00FF00';
                    break;
                case XOOPS_CENTERBLOCK_LEFT:
                    $ssel2 = ' checked';
                    $scol2 = '#00FF00';
                    break;
                case XOOPS_CENTERBLOCK_RIGHT:
                    $ssel4 = ' checked';
                    $scol4 = '#00FF00';
                    break;
                case XOOPS_CENTERBLOCK_CENTER:
                    $ssel3 = ' checked';
                    $scol3 = '#00FF00';
                    break;
                case XOOPS_CENTERBLOCK_BOTTOMLEFT:
                    $ssel7 = ' checked';
                    $scol7 = '#00FF00';
                    break;
                case XOOPS_CENTERBLOCK_BOTTOMRIGHT:
                    $ssel8 = ' checked';
                    $scol8 = '#00FF00';
                    break;
                case XOOPS_CENTERBLOCK_BOTTOM:
                    $ssel9 = ' checked';
                    $scol9 = '#00FF00';
                    break;
            }
        }

        // bcachetime
        $cachetime_options = '';
        foreach ($cachetimes as $cachetime => $cachetime_name) {
            if ($bcachetime == $cachetime) {
                $cachetime_options .= "<option value='$cachetime' selected>$cachetime_name</option>\n";
            } else {
                $cachetime_options .= "<option value='$cachetime'>$cachetime_name</option>\n";
            }
        }

        // target modules
        $db            = \XoopsDatabaseFactory::getDatabaseConnection();
        $result        = $db->query('SELECT module_id FROM ' . $db->prefix('block_module_link') . " WHERE block_id='$bid'");
        $selected_mids = [];
        while (false !== (list($selected_mid) = $db->fetchRow($result))) {
            $selected_mids[] = (int)$selected_mid;
        }
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \CriteriaCompo(new \Criteria('hasmain', 1));
        $criteria->add(new \Criteria('isactive', 1));
        $module_list     = $moduleHandler->getList($criteria);
        $module_list[-1] = _AM_TOPPAGE;
        $module_list[0]  = _AM_RWBANNER_ALLPAGES;
        ksort($module_list);
        $module_options = '';
        foreach ($module_list as $mid => $mname) {
            if (in_array($mid, $selected_mids)) {
                $module_options .= "<option value='$mid' selected>$mname</option>\n";
            } else {
                $module_options .= "<option value='$mid'>$mname</option>\n";
            }
        }

        // delete link if it is cloned block
        if ('D' === $block_arr[$i]->getVar('block_type') || 'C' === $block_arr[$i]->getVar('block_type')) {
            $delete_link = "<br><a href='admin.php?fct=blocksadmin&amp;op=delete&amp;bid=$bid'>" . _DELETE . '</a>';
        } else {
            $delete_link = '';
        }

        // clone link if it is marked as cloneable block
        // $modversion['blocks'][n]['can_clone']
        if ('D' === $block_arr[$i]->getVar('block_type') || 'C' === $block_arr[$i]->getVar('block_type')) {
            $can_clone = true;
        } else {
            $can_clone = false;
            foreach ($block_configs as $bconf) {
                if ($block_arr[$i]->getVar('show_func') == $bconf['show_func']
                    && $block_arr[$i]->getVar('func_file') == $bconf['file']
                    && (empty($bconf['template'])
                        || $block_arr[$i]->getVar('template') == $bconf['template'])) {
                    if (!empty($bconf['can_clone'])) {
                        $can_clone = true;
                    }
                }
            }
        }
        if ($can_clone) {
            $clone_link = "<br><a href='admin.php?fct=blocksadmin&amp;op=clone&amp;bid=$bid'>" . _CLONE . '</a>';
        } else {
            $clone_link = '';
        }

        // displaying part
        echo "
        <tr valign='middle'>
            <td class='$class'>
                $name
                <br>
                <input type='text' name='title[$bid]' value='$title' size='20'>
            </td>
            <td class='$class' align='center' nowrap='nowrap' width='125px'>
<!-- by luciorota - start -->
            <table>
            <tr>
            <td>&nbsp;</td>
            <td>
                <div style='float:left;background-color:$scol2;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_LEFT . "' style='background-color:$scol2;' $ssel2>
                </div>
            </td>
            <td>
                <div style='float:left;background-color:$scol3;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_CENTER . "' style='background-color:$scol3;' $ssel3>
                </div>
            </td>
            <td>
                <div style='float:left;background-color:$scol4;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_RIGHT . "' style='background-color:$scol4;' $ssel4>
                </div>
            </td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>
                <div style='float:left;background-color:$scol0;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_SIDEBLOCK_LEFT . "' style='background-color:$scol0;' $ssel0>
                </div>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
                <div style='float:left;background-color:$scol1;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_SIDEBLOCK_RIGHT . "' style='background-color:$scol1;' $ssel1>
                </div>
            </td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>
                <div style='float:left;background-color:$scol7;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_BOTTOMLEFT . "' style='background-color:$scol7;' $ssel7>
                </div>
            </td>
            <td>
                <div style='float:left;background-color:$scol9;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_BOTTOM . "' style='background-color:$scol9;' $ssel9>
                </div>
            </td>
            <td>
                <div style='float:left;background-color:$scol8;'>
                <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_BOTTOMRIGHT . "' style='background-color:$scol8;' $ssel8>
                </div>
            </td>
            <td>&nbsp;</td>
            </tr>
            </table>
<!--
                <div style='float:left;background-color:$scol0;'>
                    <input type='radio' name='side[$bid]' value='" . XOOPS_SIDEBLOCK_LEFT . "' style='background-color:$scol0;' $ssel0>
                </div>
                <div style='float:left;'>-</div>
                <div style='float:left;background-color:$scol2;'>
                    <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_LEFT . "' style='background-color:$scol2;' $ssel2>
                </div>
                <div style='float:left;background-color:$scol3;'>
                    <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_CENTER . "' style='background-color:$scol3;' $ssel3>
                </div>
                <div style='float:left;background-color:$scol4;'>
                    <input type='radio' name='side[$bid]' value='" . XOOPS_CENTERBLOCK_RIGHT . "' style='background-color:$scol4;' $ssel4>
                </div>
                <div style='float:left;'>-</div>
                <div style='float:left;background-color:$scol1;'>
                    <input type='radio' name='side[$bid]' value='" . XOOPS_SIDEBLOCK_RIGHT . "' style='background-color:$scol1;' $ssel1>
                </div>
-->
<!-- by luciorota - end -->
                <div style='float:left;width:40px;'>&nbsp;</div>
                <div style='float:left;background-color:$scoln;'>
                    <input type='radio' name='side[$bid]' value='-1' style='background-color:$scoln;' $sseln>
                </div>
                <div style='float:left;'>" . _NONE . "</div>
            </td>
            <td class='$class' align='center'>
                <input type='text' name=weight[$bid] value='$weight' size='3' maxlength='5' style='text-align:right;'>
            </td>
            <td class='$class' align='center'>
                <select name='bmodule[$bid][]' size='5' multiple='multiple'>
                    $module_options
                </select>
            </td>
            <td class='$class' align='center'>
                <select name='bcachetime[$bid]' size='1'>
                    $cachetime_options
                </select>
            </td>
            <td class='$class' align='right'>
                <a href='admin.php?fct=blocksadmin&amp;op=edit&amp;bid=$bid'>" . _EDIT . "</a>{$delete_link}{$clone_link}
                <input type='hidden' name='bid[$bid]' value='$bid'>
            </td>
        </tr>\n";

        $class = ('even' === $class) ? 'odd' : 'even';
    }

    echo "
        <tr>
            <td class='foot' align='center' colspan='6'>
                <input type='hidden' name='fct' value='blocksadmin'>
                <input type='hidden' name='op' value='order'>
                  <input type='submit' name='submit' value='" . _SUBMIT . "'>
            </td>
        </tr>
        </table>
    </form>\n";
    echo '</div>';
}

/**
 * @return array
 */
function get_block_configs()
{
    $error_reporting_level = error_reporting(0);
    include __DIR__ . '/../xoops_version.php';
    error_reporting($error_reporting_level);
    if (empty($modversion['blocks'])) {
        return [];
    } else {
        return $modversion['blocks'];
    }
}

function list_groups()
{
    global $target_mid, $target_mname, $block_arr, $xoopsModule;

    $myts = \MyTextSanitizer::getInstance();

    rwbanner_collapsableBar('bottomtable', 'bottomtableicon');

    foreach (array_keys($block_arr) as $i) {
        $item_list[$block_arr[$i]->getVar('bid')] = $block_arr[$i]->getVar('title');
    }

    $form = new Rwbanner\GroupPermForm('', 1, 'block_read', "<img id='bottomtableicon' src="
                                                          . XOOPS_URL
                                                          . '/modules/'
                                                          . $xoopsModule->dirname()
                                                          . "/assets/images/icon/close12.gif alt=''></a>&nbsp;"
                                                          . _AM_RWBANNER_GROUPS
                                                          . "</h3><div id='bottomtable'><span style=\"color: #567; margin: 3px 0 0 0; font-size: small; display: block; \">"
                                                          . _AM_RWBANNER_GROUPSINFO
                                                          . '</span>');
    $form->addAppendix('module_admin', $xoopsModule->mid(), $myts->displayTarea($xoopsModule->name()) . ' ' . _AM_RWBANNER_ACTIVERIGHTS);
    $form->addAppendix('module_read', $xoopsModule->mid(), $myts->displayTarea($xoopsModule->name()) . ' ' . _AM_RWBANNER_ACCESSRIGHTS);
    foreach ($item_list as $item_id => $item_name) {
        $form->addItem($item_id, $myts->displayTarea($item_name));
    }
    echo $form->render();
    echo '</div>';
}

if (!empty($_POST['submit'])) {
    include __DIR__ . '/mygroupperm.php';
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/admin/myblocksadmin.php$query4redirect", 1, _MD_AM_DBUPDATED);
}

xoops_cp_header();
if (file_exists('./mymenu.php')) {
    include __DIR__ . '/mymenu.php';
}

// rwbanner_adminMenu(1, _MI_RWBANNER_MENU_TITLE3);
echo '<br><br><br><br><br><br>';
list_blocks();
list_groups();
xoops_cp_footer();
