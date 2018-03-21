<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Waiting
 *
 * @category        Module
 * @package         waiting
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            https://xoops.org XOOPS
 */

if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser) || !$GLOBALS['xoopsUser']->IsAdmin()) {
    exit('Restricted Access - ' . basename($_SERVER['PHP_SELF']) . PHP_EOL);
}
/**
 *
 * Waiting Block installation update
 * @param XoopsModule $module  {@see XoopsModule}
 * @param integer     $version currently installed module version
 *
 * @return bool success
 */
function xoops_module_update_waiting($module, $version)
{
    return true;  // force return - following code is really only needed for XOOPS < 2.3

    // Keep Block option values when update (by nobunobu)
    $mid = $module->mid();
    if ($mid) {
        $configHandler       = xoops_getHandler('config');
        $waitingModuleConfig = $configHandler->getConfigsByCat(0, $mid);

        $count = count($waitingModuleConfig['blocks']);

        $blockHandler = xoops_getHandler('block');
        $criteria     = new \CriteriaCompo();
        $criteria->add(new \Criteria('mid', $mid));
        $criteria->add(new \Criteria('block_type', 'D'));
        $criteria->add(new \Criteria('func_num', $count, '>'));
        $blockObjs = $blockHandler->getAll($criteria);

        foreach ($blockObjs as $blockObj) {
            $local_msgs[] = "Non Defined Block <b>{$fblock['name']}</b> will be deleted";
            $success      = $blockHandler->delete($blockObj); // remove the invalid block
        }

        $fieldsArray = ['func_num', 'name', 'options'];
        $criteria    = new \CriteriaCompo();
        $criteria->add(new \Criteria('mid', $mid));
        //        $criteria->add(new \Criteria('func_num', $i));
        $criteria->add(new \Criteria('show_func'), addslashes($waitingModuleConfig['blocks'][$i]['show_func']));
        $criteria->add(new \Criteria('func_file', addslashes($waitingModuleConfig['blocks'][$i]['file'])));
        $fblockObjs = $blockHandler->getObjects($criteria);
        foreach ($fblockObjs as $fblockObj) {
            if (!empty($fblockObj->options())) {
                $old_vals = explode('|', $fblockObj->getVar('options'));
                $def_vals = explode('|', $modversion['blocks'][$fblockObj->getVar('func_num')]['options']);
                if (count($old_vals) == count($def_vals)) {
                    $modversion['blocks'][$fblock->getVar('func_num')]['options'] = $fblockObj->getVar('options');
                    $local_msgs[]                                                 = "Option's values of the block <b>" . $fblockObj->getVar('name') . '</b> will be kept. (value = <b>' . $fblockObj->getVar('options') . '</b>)';
                } elseif (count($old_vals) < count($def_vals)) {
                    $def_vals                                                        = array_merge($old_vals, $def_vals); //merges prev. values with new - older are preserved
                    $modversion['blocks'][$fblockObj->getVar('func_num')]['options'] = implode('|', $def_vals);
                    $local_msgs[]                                                    = "Option's values of the block "
                                                                                       . '<b>'
                                                                                       . $fblockObj->getVar('name')
                                                                                       . '</b> '
                                                                                       . 'will be kept and new option(s) are added. (value = '
                                                                                       . '<b>'
                                                                                       . $waitingModuleConfig['blocks'][$fblockObj->getVar('options')]
                                                                                       . '</b>)';
                } else {
                    $local_msgs[] = "Option's values of the block " . '<b>' . $fblockObj->getVar('name') . '</b> ' . 'will be reset to the default, because of some decrease of options. (value = ' . '<b>' . $waitingModuleConfig['blocks'][$fblockObj->getVar('func_num')]['options'] . '</b>)';
                }
            }
        }
    }

    global $msgs, $myblocksadmin_parsed_updateblock;
    if (!empty($msgs) && empty($myblocksadmin_parsed_updateblock)) {
        $msgs                             = array_merge($msgs, $local_msgs);
        $myblocksadmin_parsed_updateblock = true;
    }

    return true;  //@todo: for now force success - eventually send 'actual' results
}
