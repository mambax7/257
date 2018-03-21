<?php
/*
 ------------------------------------------------------------------------
 XOOPS - PHP Content Management System
 Copyright (c) 2000 XOOPS.org
 <https://xoops.org>
 ------------------------------------------------------------------------
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
 ------------------------------------------------------------------------
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

/**
 * @param         $dirname
 * @param  string $language
 * @return array
 */
function waiting_get_plugin_info($dirname, $language = 'english')
{
    // get $mytrustdirname for D3 modules
    $mytrustdirname = '';
    if (defined('XOOPS_TRUST_PATH') && file_exists(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/mytrustdirname.php')) {
        @include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/mytrustdirname.php';
    }

    $module_plugin_file   = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/waiting.plugin.php';
    $d3module_plugin_file = XOOPS_TRUST_PATH . '/modules/' . $mytrustdirname . '/include/waiting.plugin.php';
    $builtin_plugin_file  = XOOPS_ROOT_PATH . '/modules/waiting/plugins/' . $dirname . '.php';

    if (file_exists($module_plugin_file)) {
        // module side (1st priority)
        $lang_files    = [
            XOOPS_ROOT_PATH . "/modules/$dirname/language/$language/waiting.php",
            XOOPS_ROOT_PATH . "/modules/$dirname/language/english/waiting.php"
        ];
        $langfile_path = '';
        foreach ($lang_files as $lang_file) {
            if (file_exists($lang_file)) {
                $langfile_path = $lang_file;
                break;
            }
        }
        $ret = [
            'plugin_path'   => $module_plugin_file,
            'langfile_path' => $langfile_path,
            'func'          => 'b_waiting_' . $dirname,
            'type'          => 'module'
        ];
    } elseif (!empty($mytrustdirname) && file_exists($d3module_plugin_file)) {
        // D3 module's plugin under xoops_trust_path (2nd priority)
        $lang_files    = [
            XOOPS_TRUST_PATH . "/modules/$mytrustdirname/language/$language/waiting.php",
            XOOPS_TRUST_PATH . "/modules/$mytrustdirname/language/english/waiting.php"
        ];
        $langfile_path = '';
        foreach ($lang_files as $lang_file) {
            if (file_exists($lang_file)) {
                $langfile_path = $lang_file;
                break;
            }
        }
        $ret = [
            'plugin_path'   => $d3module_plugin_file,
            'langfile_path' => $langfile_path,
            'func'          => 'b_waiting_' . $mytrustdirname,
            'type'          => 'module (D3)'
        ];
    } elseif (file_exists($builtin_plugin_file)) {
        // built-in plugin under modules/waiting (3rd priority)
        $ret = [
            'plugin_path'   => $builtin_plugin_file,
            'langfile_path' => '',
            'func'          => 'b_waiting_' . $dirname,
            'type'          => 'Waiting'
        ];
    } else {
        $ret = [];
    }

    return $ret;
}
