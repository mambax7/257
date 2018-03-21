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
 * Module: xsitemap
 *
 * @package         module\Xsitemap\admin
 * @author          Urbanspaceman (http://www.takeaweb.it)
 * @copyright       Urbanspaceman (http://www.takeaweb.it)
 * @author          XOOPS Module Development Team
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @link            https://xoops.org XOOPS
 * @since           1.00
 */

use Xmf\Request;
use \XoopsModules\Xsitemap;

include __DIR__ . '/admin_header.php';

xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

$op = Request::getCmd('op', 'show_list_plugin');

switch ($op) {
    case 'add_plugin':

        // Display the form
        /** @var Xsitemap\Plugin $obj */
        $obj = $pluginHandler->create();
        echo $obj->getForm();
        break;

    case 'save_plugin':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/plugin.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $pluginId = Request::getInt('plugin_id', 0, 'POST');

        if (!empty($pluginId)) {
            $obj = $pluginHandler->get($pluginId);
            if (!$obj instanceof Xsitemap\Plugin) { // passed Id for non-existent plugin so create new plugin
                $obj = $pluginHandler->create();
            }
        } else {
            $obj = $pluginHandler->create();
        }
        $timestamp_created = strtotime(Request::getString('plugin_date_created', 0, 'POST'));

        $verif_plugin_online = (1 === Request::getInt('plugin_online', 0, 'POST')) ? 1 : 0;                    //Form plugin_online
        $obj->setVars(
            [
                          'plugin_name'         => Request::getString('plugin_name', '', 'POST'),                   //Form plugin_name
                          'plugin_mod_version'  => Request::getCmd('plugin_mod_version', '', 'POST'),               //Form plugin_mod_version
                          'plugin_mod_table'    => Request::getCmd('plugin_mod_table', '', 'POST'),                 //Form plugin_mod_table
                          'plugin_cat_id'       => Request::getCmd('plugin_cat_id', '', 'POST'),                    //Form plugin_cat_id
                          'plugin_cat_pid'      => Request::getCmd('plugin_cat_pid', '', 'POST'),                   //Form plugin_cat_pid
                          'plugin_cat_name'     => Request::getText('plugin_cat_name', '', 'POST'),                 //Form plugin_cat_name
                          'plugin_weight'       => Request::getCmd('plugin_weight', '', 'POST'),                    //Form plugin_weight
                          'plugin_call'         => Request::getString('plugin_call', '', 'POST'),                   //Form plugin_call
                          'plugin_submitter'    => Request::getInt('plugin_submitter', 0, 'POST'),                  //Form plugin_submitter
                          'plugin_date_created' => strtotime(Request::getString('plugin_date_created', 0, 'POST')), //Form plugin_date_created
                          'plugin_online'       => $verif_plugin_online
            ]                                            //Form plugin_online
        );

        if ($pluginHandler->insert($obj)) {
            $helper->redirect('admin/plugin.php?op=show_list_plugin', 2, _AM_XSITEMAP_FORMOK);
        }
        //include_once("../include/forms.php");
        echo $obj->getHtmlErrors();
        echo $obj->getForm();
        break;

    case 'edit_plugin':
        $obj = $pluginHandler->get(Request::getInt('plugin_id'));
        if ($obj instanceof Xsitemap\Plugin) {
            echo $obj->getForm();
        } else {
            echo _AM_XSITEMAP_ERROR_NO_PLUGIN;
        }
        break;

    case 'delete_plugin':
        $obj = $pluginHandler->get(Request::getInt('plugin_id', 0));
        $ok  = Request::getInt('ok', 0, 'POST');
        if (1 == $ok) {
            //        if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/plugin.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($pluginHandler->delete($obj)) {
                $helper->redirect('admin/plugin.php', 3, _AM_XSITEMAP_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm([
                              'ok'        => 1,
                              'plugin_id' => Request::getInt('plugin_id', 0),
                              'op'        => 'delete_plugin'
                          ], $_SERVER['REQUEST_URI'], sprintf(_AM_XSITEMAP_FORMSUREDEL, $obj->getVar('plugin')));
        }
        break;

    case 'update_online_plugin':

        if (isset($_REQUEST['plugin_id'])) {
            $obj = $pluginHandler->get(Request::getInt('plugin_id'));
        }
        $obj->setVar('plugin_online', Request::getInt('plugin_online', 0));

        if ($pluginHandler->insert($obj)) {
            $helper->redirect('admin/plugin.php', 3, _AM_XSITEMAP_FORMOK);
        }
        echo $obj->getHtmlErrors();

        break;

    case 'default':
    default:
        $adminObject->addItemButton(_AM_XSITEMAP_CREATE_PLUGIN, basename(__FILE__) . '?op=add_plugin', 'add');
        $adminObject->displayButton('left', '');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('plugin_name');
        $criteria->order = 'ASC';
        $numrows         = $pluginHandler->getCount();
        $plugin_arr      = $pluginHandler->getAll($criteria);

        //Display the table
        if ($numrows > 0) {
            echo "<table cellspacing='1' class='outer width100'>\n" . "  <thead>\n" . "  <tr>\n" . "    <th class='txtcenter'>" . _AM_XSITEMAP_PLUGIN_ONLINE . "</th>\n" . "    <th class='txtcenter'>"
                 . _AM_XSITEMAP_PLUGIN_NAME . "</th>\n" . "    <th class='txtcenter'>" . _AM_XSITEMAP_PLUGIN_MOD_VERSION . "</th>\n" . "    <th class='txtcenter'>"
                 . _AM_XSITEMAP_PLUGIN_MOD_TABLE_SHORT . "</th>\n" . "    <th class='txtcenter'>" . _AM_XSITEMAP_PLUGIN_CAT_ID_SHORT . "</th>\n" . "    <th class='txtcenter'>"
                 . _AM_XSITEMAP_PLUGIN_CAT_PID_SHORT . "</th>\n" . "    <th class='txtcenter'>" . _AM_XSITEMAP_PLUGIN_CAT_NAME_SHORT . "</th>\n" . "    <th class='txtcenter'>"
                 . _AM_XSITEMAP_PLUGIN_WEIGHT_SHORT . "</th>\n" . "    <th class='txtcenter'>" . _AM_XSITEMAP_PLUGIN_CALL_SHORT . "</th>\n" . "    <th class='txtcenter'>"
                 . _AM_XSITEMAP_PLUGIN_SUBMITTER . "</th>\n" . "    <th class='txtcenter'>" . _AM_XSITEMAP_PLUGIN_DATE_CREATED . "</th>\n" . "    <th class='txtcenter width10'>"
                 . _AM_XSITEMAP_FORMACTION . "</th>\n" . "  </tr>\n" . "  </thead>\n" . "  <tbody>\n";

            $class = 'odd';
            /** @var \XoopsObject[] $plugin_arr[] */
            foreach (array_keys($plugin_arr) as $i) {
                if (0 == $plugin_arr[$i]->getVar('topic_pid')) {
                    echo "  <tr class='{$class}'>\n";
                    $class  = ('even' === $class) ? 'odd' : 'even';
                    $online = $plugin_arr[$i]->getVar('plugin_online');
                    if (1 == $online) {
                        echo "    <td class='txtcenter width5'><a href='./plugin.php?op=update_online_plugin&plugin_id=" . $plugin_arr[$i]->getVar('plugin_id') . "&plugin_online=0'><img src='"
                             . \Xmf\Module\Admin::iconUrl('on.png', '16') . "' border='0' alt='" . _AM_XSITEMAP_ON . "' title='" . _AM_XSITEMAP_ON . ', ' . sprintf(
                                 _AM_XSITEMAP_CLICK_TO,
                                                                                                                                                                    _AM_XSITEMAP_OFF
                             ) . "'></a></td>\n";
                    } else {
                        echo "    <td class='txtcenter width5'><a href='./plugin.php?op=update_online_plugin&plugin_id=" . $plugin_arr[$i]->getVar('plugin_id') . "&plugin_online=1'><img src='"
                             . \Xmf\Module\Admin::iconUrl('off.png', '16') . "' border='0' alt='" . _AM_XSITEMAP_OFF . "' title='" . _AM_XSITEMAP_OFF . ', ' . sprintf(
                                 _AM_XSITEMAP_CLICK_TO,
                                                                                                                                                                       _AM_XSITEMAP_ON
                             )
                             . "'></a></td>\n";
                    }
                    echo "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_name') . "</td>\n" . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_mod_version') . "</td>\n"
                         . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_mod_table') . "</td>\n" . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_cat_id') . "</td>\n"
                         . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_cat_pid') . "</td>\n" . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_cat_name') . "</td>\n"
                         . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_weight') . "</td>\n" . "    <td class='txtcenter'>" . $plugin_arr[$i]->getVar('plugin_call') . "</td>\n"
                         . "    <td class='txtcenter'>" . XoopsUser::getUnameFromId($plugin_arr[$i]->getVar('plugin_submitter'), 'S') . "</td>\n" . "    <td class='txtcenter'>"
                         . formatTimestamp($plugin_arr[$i]->getVar('plugin_date_created'), 'S') . "</td>\n" . "    <td class='txtcenter width5'>\n"
                         . "      <a href='plugin.php?op=edit_plugin&plugin_id=" . $plugin_arr[$i]->getVar('plugin_id') . "'><img src='" . \Xmf\Module\Admin::iconUrl('edit.png', '16') . "' alt='"
                         . _EDIT . "' title='" . _EDIT . "'></a>\n" . "      <a href='plugin.php?op=delete_plugin&plugin_id=" . $plugin_arr[$i]->getVar('plugin_id') . "'><img src='"
                         . \Xmf\Module\Admin::iconUrl('delete.png', '16') . "' alt='" . _DELETE . "' title='" . _DELETE . "'></a>\n" . "    </td>\n" . "  </tr>\n";
                }
            }
            echo "</tbody>\n" . "</table>\n";
        }
        break;
}

include __DIR__ . '/admin_footer.php';
