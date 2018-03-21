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
 * @author          Ryuji AMANO <info@ryus.co.jp>
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            http://ryus.co.jp Ryu's Planning
 * @link            https://xoops.org XOOPS
 */

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/../include/functions.php';
require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

$plugins_path = XOOPS_ROOT_PATH . '/modules/waiting/plugins';
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$block         = [];

$mod_lists = $moduleHandler->getList(new \Criteria(1, 1), true);
$mod_objs  = $moduleHandler->getObjects(); // get all module objects

echo '<h4>'
     . _AM_WAITING_PLUGINLIST
     . "</h4>\n"
     . "<table class='outer'>\n"
     . "  <tr>\n"
     . '    <th>'
     . _AM_WAITING_MODACTIVE
     . "</th>\n"
     . '    <th>'
     . _AM_WAITING_MODNAME
     . "</th>\n"
     . '    <th>'
     . _AM_WAITING_DIRNAME
     . "</th>\n"
     . '    <th>'
     . _AM_WAITING_STATUS
     . "</th>\n"
     . "  </tr>\n";  // dirname added by cesag
$class = 'even';
foreach ($mod_objs as $this_obj) {
    $icon        = $this_obj->isactive() ? 'green.gif' : 'red.gif';
    $class       = ('odd' === $class) ? 'even' : 'odd';
    $plugin_info = waiting_get_plugin_info($this_obj->dirname());
    $plugin_type = isset($plugin_info['type']) ? $plugin_info['type'] : '';
    echo "  <tr class='{$class}'>"
         . "    <td class='txtcenter width10'><img src='{$pathIcon16}/{$icon}'>"
         . '    <td>'
         . htmlspecialchars($this_obj->getVar('name'))
         . "</td>\n"
         . "    <td class='txtcenter'>"
         . htmlspecialchars($this_obj->dirname())
         . "</td>\n"
         . "    <td class='txtcenter'>{$plugin_type}</td>\n"
         . "  </tr>\n";
}
echo "</table>\n";
echo _AM_WAITING_PLUGINLIST_DESC;

include __DIR__ . '/admin_footer.php';
