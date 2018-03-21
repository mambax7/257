<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/common.php';
require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartdbupdater.php';

/**
 * @param  XoopsModule $module
 * @return bool
 */
function xoops_module_update_smartobject(\XoopsModule $module)
{
    ob_start();

    $dbVersion = smart_GetMeta('version', 'smartobject');
    if (!$dbVersion) {
        $dbVersion = 0;
    }

    $dbupdater = new SmartobjectDbupdater();

    echo '<code>' . _SDU_UPDATE_UPDATING_DATABASE . '<br>';

    // db migrate version = 1
    $newDbVersion = 1;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';

        // Create table smartobject_link
        $table = new SmartDbTable('smartobject_link');
        if (!$table->exists()) {
            $table->setStructure("CREATE TABLE %s (
              `linkid` int(11) NOT NULL auto_increment,
              `from_uid` int(11) NOT NULL default '0',
              `from_email` varchar(255) NOT NULL default '',
              `from_name` varchar(255) NOT NULL default '',
              `to_uid` int(11) NOT NULL default '0',
              `to_email` varchar(255) NOT NULL default '',
              `to_name` varchar(255) NOT NULL default '',
              `link` varchar(255) NOT NULL default '',
              `subject` varchar(255) NOT NULL default '',
              `body` TEXT NOT NULL,
              `mid` int(11) NOT NULL default '0',
              `mid_name` varchar(255) NOT NULL default '',

              PRIMARY KEY  (`linkid`)
            ) ENGINE=MyISAM COMMENT='SmartObject by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
        unset($table);
        // Create table smartobject_link
        $table = new SmartDbTable('smartobject_link');
        if (!$table->fieldExists('date')) {
            $table->addNewField('date', "int(11) NOT NULL default '0'");
            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
        unset($table);

        // Create table smartobject_tag
        $table = new SmartDbTable('smartobject_tag');
        if (!$table->exists()) {
            $table->setStructure("CREATE TABLE %s (
              `tagid` int(11) NOT NULL auto_increment,
              `name` varchar(255) NOT NULL default '',
              `description` TEXT NOT NULL,
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM COMMENT='SmartObject by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }

        // Create table smartobject_tag_text
        $table = new SmartDbTable('smartobject_tag_text');
        if (!$table->exists()) {
            $table->setStructure("CREATE TABLE %s (
              `tagid` int(11) NOT NULL default 0,
              `language` varchar(255) NOT NULL default '',
              `value` TEXT NOT NULL,
              PRIMARY KEY  (`id`, `language`)
            ) ENGINE=MyISAM COMMENT='SmartObject by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }

        // Create table smartobject_adsense
        $table = new SmartDbTable('smartobject_adsense');
        if (!$table->exists()) {
            $table->setStructure("
  `adsenseid` int(11) NOT NULL auto_increment,
  `format` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `style` TEXT NOT NULL,
  `border_color` varchar(6) NOT NULL default '',
  `background_color` varchar(6) NOT NULL default '',
  `link_color` varchar(6) NOT NULL default '',
  `url_color` varchar(6) NOT NULL default '',
  `text_color` varchar(6) NOT NULL default '',
  `client_id` varchar(100) NOT NULL default '',
  `tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`adsenseid`)
            ");
        }

        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }
    }
    // db migrate version = 2
    $newDbVersion = 2;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';

        // Create table smartobject_rating
        $table = new SmartDbTable('smartobject_rating');
        if (!$table->exists()) {
            $table->setStructure('
  `ratingid` int(11) NOT NULL auto_increment,
  `dirname` VARCHAR(255) NOT NULL,
  `item` VARCHAR(255) NOT NULL,
  `itemid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rate` int(1) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY  (`ratingid`),
  UNIQUE (`dirname`, `item`, `itemid`, `uid`)
            ');
        }

        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }

        // Create table smartobject_currency
        $table = new SmartDbTable('smartobject_currency');
        $table->setData("2, 'EUR', 'Euro', '�', 0.65, 0");
        $table->setData("3, 'USD', 'American dollar', '$', 0.9, 0");
        $table->setData("1, 'CAD', 'Canadian dollar', '$', 1, 1");

        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }
    }

    // db migrate version = 3
    $newDbVersion = 3;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';

        // Create table smartobject_customtag
        $table = new SmartDbTable('smartobject_customtag');
        if (!$table->exists()) {
            $table->setStructure('
              `customtagid` int(11) NOT NULL auto_increment,
              `name` VARCHAR(255) NOT NULL,
              `description` TEXT NOT NULL,
              `content` TEXT NOT NULL,
              `language` TEXT NOT NULL,
              PRIMARY KEY  (`customtagid`)
            ');
        }

        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }
    }

    // db migrate version = 4
    $newDbVersion = 4;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';

        // Create table smartobject_currency
        $table = new SmartDbTable('smartobject_currency');
        if (!$table->exists()) {
            $table->setStructure('
              `currencyid` int(11) NOT NULL auto_increment,
              `iso4217` VARCHAR(5) NOT NULL,
              `name` VARCHAR(255) NOT NULL,
              `symbol`  VARCHAR(1) NOT NULL,
              `rate` float NOT NULL,
              `default_currency` int(1) NOT NULL,
              PRIMARY KEY  (`currencyid`)
            ');
        }

        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }
    }

    // db migrate version = 6
    $newDbVersion = 6;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';
    }

    $newDbVersion = 7;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';

        // Create table smartobject_file
        $table = new SmartDbTable('smartobject_file');
        if (!$table->exists()) {
            $table->setStructure('
              `fileid` int(11) NOT NULL auto_increment,
              `caption` varchar(255) collate latin1_general_ci NOT NULL,
              `url` varchar(255) collate latin1_general_ci NOT NULL,
              `description` text collate latin1_general_ci NOT NULL,
               PRIMARY KEY  (`fileid`)
            ');
            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
        unset($table);
        // Create table smartobject_urllink
        $table = new SmartDbTable('smartobject_urllink');
        if (!$table->exists()) {
            $table->setStructure('
              `urllinkid` int(11) NOT NULL auto_increment,
              `caption` varchar(255) collate latin1_general_ci NOT NULL,
              `url` varchar(255) collate latin1_general_ci NOT NULL,
              `description` text collate latin1_general_ci NOT NULL,
              `target` varchar(10) collate latin1_general_ci NOT NULL,
                PRIMARY KEY  (`urllinkid`)
            ');
            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
        unset($table);
    }
    echo '</code>';

    $feedback = ob_get_clean();
    if (method_exists($module, 'setMessage')) {
        $module->setMessage($feedback);
    } else {
        echo $feedback;
    }
    smart_SetMeta('version', $newDbVersion, 'smartobject'); //Set meta version to current

    return true;
}

/**
 * @param  XoopsModule $module
 * @return string
 */
function xoops_module_install_smartobject(\XoopsModule $module)
{
    ob_start();

    echo 'Using the ImpressCMS onInstall event';
    $feedback = ob_get_clean();

    return $feedback;
}
