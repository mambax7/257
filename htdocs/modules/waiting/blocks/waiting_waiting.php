<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Module: Waiting
 *
 * @category        Module
 * @package         waiting
 * @author          Kazumi Ono (AKA onokazu)
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            http://www.myweb.ne.jp/
 * @link            https://xoops.org XOOPS
 * @since           2.00
 */

// EXTENSIBLE "waiting block" by plugins in both waiting and modules

/**
 * @param $options
 * @return array
 */
function b_waiting_waiting_show($options)
{
    $userLang = $GLOBALS['xoopsConfig']['language'];

    $sql_cache_min  = empty($options[1]) ? 0 : (int)$options[1];
    $sql_cache_file = XOOPS_CACHE_PATH . '/waiting_touch';

    // SQL cache check (you have to use this cache with block's cache by system)
    if (file_exists($sql_cache_file)) {
        $sql_cache_mtime = filemtime($sql_cache_file);
        if (time() < $sql_cache_mtime + $sql_cache_min * 60) {
            return [];
        } else {
            unlink($sql_cache_file);
        }
    }

    require_once __DIR__ . '/../include/functions.php';

    // read language files for plugins
    $lang_dir = XOOPS_ROOT_PATH . '/modules/waiting/language';
    if (file_exists("{$lang_dir}/{$userLang}/plugins.php")) {
        require_once "{$lang_dir}/{$userLang}/plugins.php";
    } elseif (file_exists("{$lang_dir}/english/plugins.php")) {
        require_once "{$lang_dir}/english/plugins.php";
    }

    $plugins_path = XOOPS_ROOT_PATH . '/modules/waiting/plugins';
    $xoopsDB      = \XoopsDatabaseFactory::getDatabaseConnection();
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $block         = [];

    // get module's list installed
    $mod_lists = $moduleHandler->getList(new \Criteria(1, 1), true);
    foreach ($mod_lists as $dirname => $name) {
        $plugin_info = waiting_get_plugin_info($dirname, $userLang);
        if (empty($plugin_info) || empty($plugin_info['plugin_path'])) {
            continue;
        }

        if (!empty($plugin_info['langfile_path'])) {
            require_once $plugin_info['langfile_path'];
        }
        require_once $plugin_info['plugin_path'];

        // call the plugin
        if (function_exists(@$plugin_info['func'])) {
            // get the list of waitings
            $_tmp = call_user_func($plugin_info['func'], $dirname);
            if (isset($_tmp['lang_linkname'])) {
                if (@$_tmp['pendingnum'] > 0 || $options[0] > 0) {
                    $block['modules'][$dirname]['pending'][] = $_tmp;
                }
                unset($_tmp);
            } else {
                // Judging the plugin returns multiple items
                // if lang_linkname does not exist
                foreach ($_tmp as $_one) {
                    if (@$_one['pendingnum'] > 0 || $options[0] > 0) {
                        $block['modules'][$dirname]['pending'][] = $_one;
                    }
                }
            }
        }

        // for older compatibilities
        // Hacked by GIJOE
        $i = 0;
        while (1) {
            $function_name = "b_waiting_{$dirname}_$i";
            if (function_exists($function_name)) {
                $_tmp = call_user_func($function_name);
                ++$i;
                if ($_tmp['pendingnum'] > 0 || $options[0] > 0) {
                    $block['modules'][$dirname]['pending'][] = $_tmp;
                }
                unset($_tmp);
            } else {
                break;
            }
        }
        // End of Hack

        // if(count($block["modules"][$dirname]) > 0){
        if (!empty($block['modules'][$dirname])) {
            $block['modules'][$dirname]['name'] = $name;
        }
    }
    //print_r($block);

    // SQL cache touch (you have to use this cache with block's cache by system)
    if (empty($block) && $sql_cache_min > 0) {
        $fp = fopen($sql_cache_file, 'w');
        fclose($fp);
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_waiting_waiting_edit($options)
{
    $mod_url = XOOPS_URL . '/modules/waiting';

    $sql_cache_min = empty($options[1]) ? 0 : (int)$options[1];

    $form = _MB_WAITING_NOWAITING_DISPLAY . ":&nbsp;<input type='radio' name='options[0]' value='1'";
    if (1 == $options[0]) {
        $form .= ' checked';
    }
    $form .= '>&nbsp;' . _YES . "<input type='radio' name='options[0]' value='0'";
    if (0 == $options[0]) {
        $form .= ' checked';
    }
    $form .= '>&nbsp;' . _NO . "<br>\n";
    $form .= sprintf(_MINUTES, _MB_WAITING_SQL_CACHE . ":&nbsp;<input type='text' name='options[1]' value='{$sql_cache_min}' size='2'>");
    $form .= "<br>\n<br>\n<a href='{$mod_url}/admin/index.php'><img src='{$mod_url}/assets/images/folder16.gif'>" . _MB_WAITING_LINKTOPLUGINCHECK . '</a>';

    return $form;
}
