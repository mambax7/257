<?php
/*
 * ****************************************************************************
 * xsitemap - MODULE FOR XOOPS CMS
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * ****************************************************************************
 */
/**
 * @package    module\Xsitemap\frontside
 * @author     Urbanspaceman (http://www.takeaweb.it)
 * @copyright  Urbanspaceman (http://www.takeaweb.it)
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @link       https://xoops.org XOOPS
 */

include __DIR__ . '/preloads/autoloader.php';

$moduleDirName = basename(__DIR__);
$modversion    = [
            'version'             => 1.54,
            'module_status'       => 'RC-2',
            'release_date'        => '2017/11/15',
            'name'                => _MI_XSITEMAP_NAME,
            'description'         => _MI_XSITEMAP_DESC,
            'author'              => 'Urbanspaceman',
            'author_website_url'  => 'http://www.takeaweb.it',
            'author_website_name' => 'TAKEAWEB',
            'credits'             => 'astueo.com (CSS Stylesheet), Mage, Mamba, Zyspec, Aerograf',
            'license'             => 'GNU GPL 2.0',
            'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html',
            'help'                => 'page=help',
            'release_info'        => 'This is a SITEMAP module written for XOOPS 2.5.9',
            'manual'              => '',
            'manual_file'         => '',
            'image'               => 'assets/images/logoModule.png',
            'dirname'             => $moduleDirName,
            'modicons16'          => 'assets/images/icons/16',
            'modicons32'          => 'assets/images/icons/32',
            'min_php'             => '5.5',
            'min_xoops'           => '2.5.9',
            'min_admin'           => '1.2',
            'min_db'              => ['mysql' => '5.5'],
            // About
            'module_website_name' => 'XOOPS',
            'module_website_url'  => 'www.xoops.org',
            'release_file'        => $GLOBALS['xoops']->url("www/modules/{$moduleDirName}/docs/changelog.txt"),
            // Admin things
            'hasAdmin'            => 1,
            'adminindex'          => 'admin/index.php',
            'adminmenu'           => 'admin/menu.php',
            // Mysql file
            'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
            // Tables
            'tables'              =>  [
                  $moduleDirName . '_' . 'plugin'
            ],
            // Scripts to run upon installation or update
            'onInstall'           => 'include/oninstall.php',
            'onUpdate'            => 'include/onupdate.php',
            'onUninstall'         => 'include/onuninstall.php',
            // Menu
            'hasMain'             => 1,
            'system_menu'         => 1,
            // ------------------- Help files ------------------- //
            'helpsection'         => [
                ['name' => _MI_XSITEMAP_OVERVIEW, 'link'   => 'page=help'],
                ['name' => _MI_XSITEMAP_DISCLAIMER, 'link' => 'page=disclaimer'],
                ['name' => _MI_XSITEMAP_LICENSE, 'link'    => 'page=license'],
                ['name' => _MI_XSITEMAP_SUPPORT, 'link'    => 'page=support']
            ],
            //Templates
            'templates'           => [
            [
                'file'        => 'xsitemap_index.tpl',
                'description' => ''
            ],
            [
                'file'        => 'xsitemap_slickmap.tpl',
                'description' => ''
            ],
            /*
            [
                file' => 'xsitemap_style.tpl',
                description' => ''
            ],
            */
            [
                'file'        => 'xsitemap_xml.tpl',
                'description' => ''
            ],
            [
                'file'        => 'admin/xsitemap_index.tpl',
                'description' => ''
            ]
        ],
];
// Preferences
$modversion['config'] = [
    [
        'name'        => 'show_subcategories',
        'title'       => '_MI_XSITEMAP_SHOW_PARENT',
        'description' => '_MI_XSITEMAP_SHOW_PARENT_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1
    ],
    [
        'name'        => 'show_sublink',
        'title'       => '_MI_XSITEMAP_SHOW_ACTION',
        'description' => '_MI_XSITEMAP_SHOW_ACTION_DESC',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 1
    ],
    [
        'name'        => 'invisible_dirnames',
        'title'       => '_MI_XSITEMAP_DIRNAMES',
        'description' => '_MI_XSITEMAP_DIRNAMES_DESC',
        'formtype'    => 'textbox',
        'valuetype'   => 'text',
        'default'     => ''
    ],
    [
        'name'        => 'columns_number',
        'title'       => '_MI_XSITEMAP_COLS',
        'description' => '_MI_XSITEMAP_COLS_DESC',
        'formtype'    => 'select',
        'valuetype'   => 'int',
        'default'     => 4,
        'options'     => [
            '1'  => 1,
            '2'  => 2,
            '3'  => 3,
            '4'  => 4,
            '5'  => 5,
            '6'  => 6,
            '7'  => 7,
            '8'  => 8,
            '9'  => 9,
            '10' => 10
        ]
    ]
];
