<?php
/*
 * xSiteMap module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * @package    module\Xsitemap\admin
 * @copyright  https://xoops.org 2001-2017 XOOPS Project
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author     ZySpec <owners@zyspec.com>
 * @link       https://xoops.org XOOPS
 * @since      1.54
 **/

/**
 * Xoopspoll Core Preload Class
 *
 * class used to check status of mailing polls that have ended.
 *
 * @package    xoopspoll
 * @subpackage class
 */
use XoopsModules\Xsitemap;

/**
 * Class XsitemapCorePreload
 */
class XsitemapCorePreload extends XoopsPreloadItem
{
    /**
     * plugin class for Xoops preload for index page start
     * @param $args
     * @return bool
     */
    public static function eventCoreIndexStart($args)
    {
        include __DIR__ . '/autoloader.php';
        // check once per user session if xsitemap exists
        $sessionVar = 'xsitemapChecked';
        $retVal     = true;
        if (empty($_SESSION[$sessionVar])) {
            if (!file_exists(dirname(__DIR__) . '/xsitemap.xml')) {
//                require_once dirname(__DIR__) . '/include/common.php';
                //Create the xsitemap.xml file in the site root
                $utility = new Xsitemap\Utility();
                $xsitemap_show = $utility::generateSitemap();
                $retVal        = $utility::saveSitemap($xsitemap_show) ? true : false;
            }
            $_SESSION[$sessionVar] = 1;
        }
        return $retVal;
    }

    /**
     * run autoloader
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        include __DIR__ . '/autoloader.php';
    }
}
