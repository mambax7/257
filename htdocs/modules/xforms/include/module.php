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
 * xForms module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xforms
 * @since           1.30
 * @author          Xoops Development Team
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("Xoops root path not defined");
}
//include_once(XOOPS_ROOT_PATH . "/modules/xforms/class/dbupdater.php");
//include_once(XOOPS_ROOT_PATH . "/modules/xforms/include/common.php");
include_once(XOOPS_ROOT_PATH . "/modules/xforms/include/migrate.php");
//@include_once(XOOPS_ROOT_PATH . "/modules/xforms/language/" . $xoopsConfig['language'] . "/admin.php");

/**
 * @param $xoopsModule
 *
 * @return bool
 */
function xoops_module_pre_install_xforms(&$xoopsModule)
{
    // NOP
    return true;
}

/**
 * @param $module
 *
 * @return bool
 */
function xoops_module_install_xforms(&$module)
{
    global $moduleperm_handler;

    for ($i = 1; $i < 4; ++$i) {
        $perm = $moduleperm_handler->create();
        $perm->setVar('gperm_name', 'xforms_form_access');
        $perm->setVar('gperm_itemid', 1);
        $perm->setVar('gperm_groupid', $i);
        $perm->setVar('gperm_modid', $module->getVar('mid'));
        $moduleperm_handler->insert($perm);
    }

    return true;
}

/**
 * @param $xoopsModule
 * @param $prev_version
 *
 * @return bool
 */
function xoops_module_update_xforms(&$xoopsModule, $prev_version)
{
    ob_start();
//    invert_nohtm_dohtml_values();

//    if ($prev_version <= 121)
    update_tables_to_130($xoopsModule);
    $feedback = ob_get_clean();
    if (method_exists($xoopsModule, "setMessage")) {
        $xoopsModule->setMessage($feedback);
    } else {
        echo $feedback;
    }

    return true;
}

/**
 * @param $xoopsModule
 *
 * @return bool
 */
function xoops_module_pre_uninstall_xforms(&$xoopsModule)
{
    // NOP
    return true;
}

/**
 * @param $xoopsModule
 */
function xoops_module_uninstall_xforms(&$xoopsModule)
{
    // NOP
}

// =========================================================================================
// This function updates any existing table of a 1.2x version to the format used
// in the release of xForms 1.30
// =========================================================================================
/**
 * @param $module
 */
function update_tables_to_130($module)
{
    $migrate = new Migrate;
//    $migrate->copyTable('demomvc_log', 'demomvc_log2', true);
//    $migrate->addColumn('demomvc_log2', 'log_xint', 'log_start_time','int(10) not null default \'0\'' );
//    $migrate->update('demomvc_log2', array('log_xint' => time()), '');
//    $migrate->queueExecute(true);

    $migrate->addTable('zzztest');
    $migrate->addColumn('zzztest', 'log_xint', 'int(10) not null default \'0\'');
//    $migrate->update('zzztest', array('log_xint' => time()), '');
//    $migrate->addColumn('zzztest', 'log_xint2', '','int(10) not null default \'0\'' );
//    $migrate->update('zzztest', array('log_xint2' => time()), '');
    $migrate->queueExecute(true);
}

/*

    $dbupdater = new XformsDbupdater();

zz
    if (!xforms_tableExists('xforms_meta')) {
        // Create table xforms_meta
        $table = new xformsTable('xforms_meta');
        $table->setStructure("CREATE TABLE %s (
            metakey varchar(50) NOT NULL default '',
            metavalue varchar(255) NOT NULL default '',
            PRIMARY KEY (metakey))
            ENGINE=MyISAM;");
        $table->setData(sprintf("'version', %s", round($GLOBALS['xoopsModule']->getVar('version') / 100, 2)));
        if ($dbupdater->updateTable($table)) {
            echo "xforms_meta table created<br />";
        }
    }

    if (!xforms_tableExists('xforms_mirrors')) {
        // Create table xforms_mirror
        $table = new xformsTable('xforms_mirrors');
        $table->setStructure("CREATE TABLE %s (
            mirror_id int(11) unsigned NOT NULL auto_increment,
            lid int(11) NOT NULL default '0',
            title varchar(255) NOT NULL default '',
            homeurl varchar(100) NOT NULL default '',
            location varchar(255) NOT NULL default '',
            continent varchar(255) NOT NULL default '',
            downurl varchar(255) NOT NULL default '',
            submit int(11) NOT NULL default '0',
            date int(11) NOT NULL default '0',
            uid int(10) NOT NULL default '0',
            PRIMARY KEY  (mirror_id),
            KEY categoryid (lid))
            ENGINE=MyISAM;");
        if ($dbupdater->updateTable($table)) {
            echo "xforms_mirrors table created<br />";
        }
    }

    if (!xforms_tableExists('xforms_ip_log')) {
        // Create table xforms_ip_log
        $table = new xformsTable('xforms_ip_log');
        $table->setStructure("CREATE TABLE %s (
            ip_logid int(11) NOT NULL auto_increment,
            lid int(11) NOT NULL default '0',
            uid int(11) NOT NULL default '0',
            date int(11) NOT NULL default '0',
            ip_address varchar(20) NOT NULL default '',
            PRIMARY KEY  (ip_logid)
            ENGINE=MyISAM;");
        if ($dbupdater->updateTable($table)) {
            echo "xforms_mirrors table created<br />";
        }
    }

    $download_fields = array(
        "lid" =>            array("Type" => "int(11) unsigned NOT NULL auto_increment", "Default" => false),
        "cid" =>            array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title" =>          array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "url" =>            array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "filename" =>       array("Type" => "varchar(150) NOT NULL default ''", "Default" => true),
        "filetype" =>       array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "homepage" =>       array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "version" =>        array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "size" =>           array("Type" => "int(8) NOT NULL default '0'", "Default" => true),
        "platform" =>       array("Type" => "varchar(50) NOT NULL default ''", "Default" => true),
        "screenshot" =>     array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot2" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot3" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot4" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "submitter" =>      array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "publisher" =>      array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "status" =>         array("Type" => "tinyint(2) NOT NULL default '0'", "Default" => true),
        "date" =>           array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "hits" =>           array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "rating" =>         array("Type" => "double(6,4) NOT NULL default '0.0000'", "Default" => true),
        "votes" =>          array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "comments" =>       array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "license" =>        array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "mirror" =>         array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "price" =>          array("Type" => "varchar(10) NOT NULL default 'Free'", "Default" => true),
        "paypalemail" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "features" =>       array("Type" => "text NOT NULL", "Default" => false),
        "requirements" =>   array("Type" => "text NOT NULL", "Default" => false),
        "homepagetitle" =>  array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "forumid" =>        array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "limitations" =>    array("Type" => "varchar(255) NOT NULL default '30 day trial'", "Default" => true),
        "versiontypes" =>   array("Type" => "varchar(255) NOT NULL default 'None'", "Default" => true),
        "dhistory" =>       array("Type" => "text NOT NULL", "Default" => false),
        'published' =>      array("Type" => "int(11) NOT NULL default '1089662528'", "Default" => true),
        "expired" =>        array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "updated" =>        array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "offline" =>        array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "description" =>    array("Type" => "text NOT NULL", "Default" => false),
        "ipaddress" =>      array("Type" => "varchar(120) NOT NULL default '0'", "Default" => true),
        "notifypub" =>      array("Type" => "int(1) NOT NULL default '0'", "Default" => true),
        "summary" =>        array("Type" => "text NOT NULL", "Default" => false),
        "formulize_idreq" =>    array("Type" => "int(5) NOT NULL default '0'", "Default" => true)
    );
    $renamed_fields = array(
        "logourl" => "screenshot"
    );
    echo "<br /><B>Checking Download table</B><br />";
    $download_handler = xoops_getmodulehandler('download', 'xforms');
    $download_table = new xformsTable("xforms_downloads");
    $fields = get_table_info($download_handler->table, $download_fields);
    // Check for renamed fields
    rename_fields($download_table, $renamed_fields, $fields, $download_fields);
    update_table($download_fields, $fields, $download_table);
    if ($dbupdater->updateTable($download_table)) {
        echo "Downloads table updated<br />";
    }
    unset($fields);

    $mod_fields = array(
        "requestid" =>      array("Type" => "int(11) NOT NULL auto_increment", "Default" => false),
        "lid" =>            array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "cid" =>            array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title" =>          array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "url" =>            array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "filename" =>       array("Type" => "varchar(150) NOT NULL default ''", "Default" => true),
        "filetype" =>       array("Type" => "varchar(100) NOT NULL default ''", "Default" => true),
        "homepage" =>       array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "version" =>        array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "size" =>           array("Type" => "int(8) NOT NULL default '0'", "Default" => true),
        "platform" =>       array("Type" => "varchar(50) NOT NULL default ''", "Default" => true),
        "screenshot" =>     array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot2" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot3" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "screenshot4" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "submitter" =>      array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "publisher" =>      array("Type" => "text NOT NULL", "Default" => false),
        "status" =>         array("Type" => "tinyint(2) NOT NULL default '0'", "Default" => true),
        "date" =>           array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "hits" =>           array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "rating" =>         array("Type" => "double(6,4) NOT NULL default '0.0000'", "Default" => true),
        "votes" =>          array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "comments" =>       array("Type" => "int(11) unsigned NOT NULL default '0'", "Default" => true),
        "license" =>        array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "mirror" =>         array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "price" =>          array("Type" => "varchar(10) NOT NULL default 'Free'", "Default" => true),
        "paypalemail" =>    array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "features" =>       array("Type" => "text NOT NULL", "Default" => false),
        "requirements" =>   array("Type" => "text NOT NULL", "Default" => false),
        "homepagetitle" =>  array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "forumid" =>        array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "limitations" =>    array("Type" => "varchar(255) NOT NULL default '30 day trial'", "Default" => true),
        "versiontypes" =>   array("Type" => "varchar(255) NOT NULL default 'None'", "Default" => true),
        "dhistory" =>       array("Type" => "text NOT NULL", "Default" => false),
        'published' =>      array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "expired" =>        array("Type" => "int(10) NOT NULL default '0'", "Default" => true),
        "updated" =>        array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "offline" =>        array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "summary" =>        array("Type" => "text NOT NULL", "Default" => false),
        "description" =>    array("Type" => "text NOT NULL", "Default" => false),
        "modifysubmitter" =>    array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "requestdate" =>        array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "formulize_idreq" =>    array("Type" => "int(5) NOT NULL default '0'", "Default" => true)
    );
    $renamed_fields = array(
        "logourl" => "screenshot"
    );
    echo "<br /><B>Checking Modified Downloads table</B><br />";
    $mod_handler = xoops_getmodulehandler('modification', 'xforms');
    $mod_table = new xformsTable("xforms_mod");
    $fields = get_table_info($mod_handler->table, $mod_fields);
    rename_fields($mod_table, $renamed_fields, $fields, $mod_fields);
    update_table($mod_fields, $fields, $mod_table);
    if ($dbupdater->updateTable($mod_table)) {
        echo "Modified Downloads table updated <br />";
    }
    unset($fields);

    $cat_fields = array(
        "cid" =>            array("Type" => "int(5) unsigned NOT NULL auto_increment", "Default" => false),
        "pid" =>            array("Type" => "int(5) unsigned NOT NULL default '0'", "Default" => true),
        "title" =>          array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "imgurl" =>         array("Type" => "varchar(255) NOT NULL default ''", "Default" => true),
        "description" =>    array("Type" => "text NOT NULL default ''", "Default" => true),
        "total" =>          array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "summary" =>        array("Type" => "text NOT NULL", "Default" => false),
        "spotlighttop" =>   array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "spotlighthis" =>   array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "dohtml" =>         array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "dosmiley" =>       array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doxcode" =>        array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "doimage" =>        array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "dobr" =>           array("Type" => "tinyint(1) NOT NULL default '1'", "Default" => true),
        "weight" =>         array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "formulize_fid" =>  array("Type" => "int(5) NOT NULL default '0'", "Default" => true)
    );
    echo "<br /><B>Checking Category table</B><br />";
    $cat_handler = xoops_getmodulehandler('category', 'xforms');
    $cat_table = new xformsTable("xforms_cat");
    $fields = get_table_info($cat_handler->table, $cat_fields);
    update_table($cat_fields, $fields, $cat_table);
    if ($dbupdater->updateTable($cat_table)) {
        echo "Category table updated<br />";
    }
    unset($fields);

    $broken_fields = array(
        "reportid" =>       array("Type" => "int(5) NOT NULL auto_increment", "Default" => false),
        "lid" =>            array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "sender" =>         array("Type" => "int(11) NOT NULL default '0'", "Default" => true),
        "ip" =>             array("Type" => "varchar(20) NOT NULL default ''", "Default" => true),
        "date" =>           array("Type" => "varchar(11) NOT NULL default '0'", "Default" => true),
        "confirmed" =>      array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true),
        "acknowledged" =>   array("Type" => "tinyint(1) NOT NULL default '0'", "Default" => true)
    );
    echo "<br /><B>Checking Broken Report table</B><br />";
    $broken_handler = xoops_getmodulehandler('report', 'xforms');
    $broken_table = new xformsTable("xforms_broken");
    $fields = get_table_info($broken_handler->table, $broken_fields);
    update_table($broken_fields, $fields, $broken_table);
    if ($dbupdater->updateTable($broken_table)) {
        echo "Broken Reports table updated<br />";
    }
    unset($fields);

    */
/*

// =========================================================================================
// we are going to change the names for the fields like nohtml, nosmilies, noxcode, noimage, nobreak in
// the xforms_cat table into dohtml, dosmilies and so on.  Therefore the logic will change
// 0=yes  1=no and the currently stored value need to be changed accordingly
// =========================================================================================
function invert_nohtm_dohtml_values()
{
    $ret = array();
    global $xoopsDB;
    $cat_handler = xoops_getmodulehandler('category', 'xforms');
    $result = $xoopsDB->query("SHOW COLUMNS FROM ".$cat_handler->table);
    while ($existing_field = $xoopsDB->fetchArray($result)) {
         $fields[$existing_field['Field']] = $existing_field['Type'];
    }
    if (in_array("nohtml", array_keys($fields))) {
        $dbupdater = new xformsDbupdater();
        //Invert column values
        // alter options in xforms_cat
        $table = new xformsTable('xforms_cat');
        $table->addAlteredField('nohtml', "dohtml tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('nosmiley', "dosmiley tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('noxcodes', "doxcode tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('noimages', "doimage tinyint(1) NOT NULL DEFAULT '1'");
        $table->addAlteredField('nobreak', "dobr tinyint(1) NOT NULL DEFAULT '1'");

        //inverting values no=1 <=> do=0
        // have to store teporarly as value = 2 to
        // avoid putting everithing to same value
        // if you change 1 to 0, then 0 to one,
        // every value will be 1, follow me?
        $table->addUpdatedWhere('dohtml', 2,'=1');
        $table->addUpdatedWhere('dohtml', 1,'=0');
        $table->addUpdatedWhere('dohtml', 0,'=2');

        $table->addUpdatedWhere('dosmiley', 2,'=1');
        $table->addUpdatedWhere('dosmiley', 1,'=0');
        $table->addUpdatedWhere('dosmiley', 0,'=2');

        $table->addUpdatedWhere('doxcode', 2,'=1');
        $table->addUpdatedWhere('doxcode', 1,'=0');
        $table->addUpdatedWhere('doxcode', 0,'=2');

        $table->addUpdatedWhere('doimage', 2,'=1');
        $table->addUpdatedWhere('doimage', 1,'=0');
        $table->addUpdatedWhere('doimage', 0,'=2');
        $ret = $dbupdater->updateTable($table);
    }

    return $ret;
}
*/

/**
 * Updates a table by comparing correct fields with existing ones
 *
 * @param array       $new_fields
 * @param array       $existing_fields
 * @param xformsTable $table
 *
 * @return void
 */
/*
function update_table($new_fields, $existing_fields, &$table)
{
foreach ($new_fields as $field => $fieldinfo) {
    $type = $fieldinfo["Type"];
    if (!in_array($field, array_keys($existing_fields) ) ) {
       //Add field as it is missing
       $table->addNewField($field, $type);
       //$xoopsDB->query("ALTER TABLE " . $table . " ADD " . $field . " " . $type);
       //echo $field . "(" . $type . ") <FONT COLOR='##22DD51'>Added</FONT><br />";
    } elseif ($existing_fields[$field] != $type) {
        $table->addAlteredField($field, $field . " " . $type);
        // check $fields[$field]['type'] for things like "int(10) unsigned"
        //$xoopsDB->query("ALTER TABLE " . $table . " CHANGE " . $field . " " . $field . " " . $type);
        //echo $field . " <FONT COLOR='#FF6600'>Changed to</FONT> " . $type . "<br />";
    } else {
        //echo $field . " <FONT COLOR='#0033FF'>Uptodate</FONT><br />";
    }
}
}

/*
* Get column information for a table - we'll need to send along an array of fields to determine
* whether the "Default" index value should be appended
*
* @param string $table
* @param array $default_fields
* @return array
*/
/*

function get_table_info($table, $default_fields)
{
global $xoopsDB;
$result = $xoopsDB->query("SHOW COLUMNS FROM " . $table);
while ($existing_field = $xoopsDB->fetchArray($result)) {
    $fields[$existing_field['Field']] = $existing_field['Type'];
    if ($existing_field['Null'] != "YES") {
        $fields[$existing_field['Field']] .= " NOT NULL";
    }
    if ($existing_field['Extra']) {
        $fields[$existing_field['Field']] .= " " . $existing_field['Extra'];
    }
    if ($default_fields[$existing_field['Field']]['Default']) {
        $fields[$existing_field['Field']] .= " default '" . $existing_field['Default'] . "'";
    }
}

return $fields;
}
*/

/**
 * Renames fields in a table and updates the existing fields array to reflect it.
 *
 * @param xformsTable $table
 * @param array       $renamed_fields
 * @param array       $fields
 * @param array       $new_fields
 *
 * @return array
 */

/*
function rename_fields(&$table, $renamed_fields, &$fields, $new_fields)
{
foreach (array_keys($fields) as $field) {
    if (in_array($field, array_keys($renamed_fields))) {
        $new_field_name = $renamed_fields[$field];
        $new_field_type = $new_fields[$new_field_name]["Type"];
        $table->addAltered($field, $new_field_name . " " . $new_field_type);
        //$xoopsDB->query("ALTER TABLE " . $table . " CHANGE " . $field . " " . $new_field_name . " " . $new_field_type);
        //echo $field." Renamed to ". $new_field_name . "<br />";
        $fields[$new_field_name] = $new_field_type;
    }
}
//return $fields;
}

*/
