<?php
/**
 * Private Messages
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.4.0
 * @author          trabis <lusopoemas@gmail.com>
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * rw-banner core preloads
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          mamba
 */
class RwbannerCorePreload extends XoopsPreloadItem
{
    /**
     * @param $args
     */
    public static function eventCoreHeaderEnd($args)
    {
        if (file_exists($filename = __DIR__ . '/../include/maketags.php')) {
            include $filename;
        }

        // to add PSR-4 autoloader
        include __DIR__ . '/autoloader.php';
    }
}
