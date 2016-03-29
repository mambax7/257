<?php
/**
 * $Id: functions.php 3442 2008-07-05 11:45:59Z malanciault $
 * Module: SmartRental
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

function smart_get_css_link($cssfile) {
    $ret = '<link rel="stylesheet" type="text/css" href="' . $cssfile . '" />';
    return $ret;
}
function smart_get_page_before_form() {
    global $smart_previous_page;
    return isset ($_POST['smart_page_before_form']) ? $_POST['smart_page_before_form'] : $smart_previous_page;
}
/**
 * Checks if a user is admin of $module
 *
 * @return boolean : true if user is admin
 */
function smart_userIsAdmin($module = false) {
    global $xoopsUser;
    static $smart_isAdmin;
    if (!$module) {
        global $xoopsModule;
        $module = $xoopsModule->getVar('dirname');
    }
    if (isset ($smart_isAdmin[$module])) {
        return $smart_isAdmin[$module];
    }
    if (!$xoopsUser) {
        $smart_isAdmin[$module] = false;
        return $smart_isAdmin[$module];
    }
    $smart_isAdmin[$module] = false;
    $smartModule = smart_getModuleInfo($module);
    if (!is_object($smartModule)) {
        return false;
    }
    $module_id = $smartModule->getVar('mid');
    $smart_isAdmin[$module] = $xoopsUser->isAdmin($module_id);
    return $smart_isAdmin[$module];
}
function smart_isXoops22() {
    $xoops22 = false;
    $xv = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) == '2') {
        $xoops22 = true;
    }
    return $xoops22;
}
function smart_getModuleName($withLink = true, $forBreadCrumb = false, $moduleName = false) {
    if (!$moduleName) {
        global $xoopsModule;
        $moduleName = $xoopsModule->getVar('dirname');
    }
    $smartModule = & smart_getModuleInfo($moduleName);
    $smartModuleConfig = & smart_getModuleConfig($moduleName);
    if (!isset ($smartModule)) {
        return '';
    }

    if ($forBreadCrumb && (isset ($smartModuleConfig['show_mod_name_breadcrumb']) && !$smartModuleConfig['show_mod_name_breadcrumb'])) {
        return '';
    }
    if (!$withLink) {
        return $smartModule->getVar('name');
    } else {
        $seoMode = smart_getModuleModeSEO($moduleName);
        if ($seoMode == 'rewrite') {
            $seoModuleName = smart_getModuleNameForSEO($moduleName);
            $ret = XOOPS_URL . '/' . $seoModuleName . '/';
        } elseif ($seoMode == 'pathinfo') {
            $ret = XOOPS_URL . '/modules/' . $moduleName . '/seo.php/' . $seoModuleName . '/';
        } else {
            $ret = XOOPS_URL . '/modules/' . $moduleName . '/';
        }

        return '<a href="' . $ret . '">' . $smartModule->getVar('name') . '</a>';
    }
}
function smart_getModuleNameForSEO($moduleName = false) {
    $smartModule = & smart_getModuleInfo($moduleName);
    $smartModuleConfig = & smart_getModuleConfig($moduleName);
    if (isset ($smartModuleConfig['seo_module_name'])) {
        return $smartModuleConfig['seo_module_name'];
    }
    $ret = smart_getModuleName(false, false, $moduleName);
    return (strtolower($ret));
}
function smart_getModuleModeSEO($moduleName = false) {
    $smartModule = & smart_getModuleInfo($moduleName);
    $smartModuleConfig = & smart_getModuleConfig($moduleName);
    return isset ($smartModuleConfig['seo_mode']) ? $smartModuleConfig['seo_mode'] : false;
}
function smart_getModuleIncludeIdSEO($moduleName = false) {
    $smartModule = & smart_getModuleInfo($moduleName);
    $smartModuleConfig = & smart_getModuleConfig($moduleName);
    return !empty ($smartModuleConfig['seo_inc_id']);
}
function smart_getenv($key) {
    $ret = '';
    $ret = isset ($_SERVER[$key]) ? $_SERVER[$key] : (isset ($_ENV[$key]) ? $_ENV[$key] : '');
    return $ret;
}
function smart_xoops_cp_header() {
    xoops_cp_header();
    global $xoopsModule, $xoopsConfig;
    /**
     * include SmartObject admin language file
     */
    $fileName = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/admin.php';
    if (file_exists($fileName)) {
        include_once $fileName;
    } else {
        include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . '/language/english/admin.php';
    }
    ?>
<script type='text/javascript'>
    <!--
    var smart_url='<?php echo SMARTOBJECT_URL ?>';
    var smart_modulename='<?php echo $xoopsModule->getVar('dirname') ?>';
    // -->
    </script>

<script
    type='text/javascript'
    src='<?php echo SMARTOBJECT_URL ?>include/smart.js'></script>
    <?php

    /**
     * Include the admin language constants for the SmartObject Framework
     */
    $admin_file = SMARTOBJECT_ROOT_PATH . "language/" . $xoopsConfig['language'] . "/admin.php";
    if (!file_exists($admin_file)) {
        $admin_file = SMARTOBJECT_ROOT_PATH . "language/english/admin.php";
    }
    include_once ($admin_file);
}
/**
 * Detemines if a table exists in the current db
 *
 * @param string $table the table name (without XOOPS prefix)
 * @return bool True if table exists, false if not
 *
 * @access public
 * @author xhelp development team
 */
function smart_TableExists($table) {
    $bRetVal = false;
    //Verifies that a MySQL table exists
    $xoopsDB = & XoopsDatabaseFactory::getDatabaseConnection();
    $realname = $xoopsDB->prefix($table);
    $sql = "SHOW TABLES FROM ".XOOPS_DB_NAME;
    $ret = $xoopsDB->queryF($sql);
    while (list ($m_table) = $xoopsDB->fetchRow($ret)) {
        if ($m_table == $realname) {
            $bRetVal = true;
            break;
        }
    }
    $xoopsDB->freeRecordSet($ret);
    return ($bRetVal);
}
/**
 * Gets a value from a key in the xhelp_meta table
 *
 * @param string $key
 * @return string $value
 *
 * @access public
 * @author xhelp development team
 */
function smart_GetMeta($key, $moduleName = false) {
    if (!$moduleName) {
        $moduleName = smart_getCurrentModuleName();
    }
    $xoopsDB = & XoopsDatabaseFactory::getDatabaseConnection();
    $sql = sprintf("SELECT metavalue FROM %s WHERE metakey=%s", $xoopsDB->prefix($moduleName . '_meta'), $xoopsDB->quoteString($key));
    $ret = $xoopsDB->query($sql);
    if (!$ret) {
        $value = false;
    } else {
        list ($value) = $xoopsDB->fetchRow($ret);
    }
    return $value;
}
function smart_getCurrentModuleName() {
    global $xoopsModule;
    if (is_object($xoopsModule)) {
        return $xoopsModule->getVar('dirname');
    } else {
        return false;
    }
}
/**
 * Sets a value for a key in the xhelp_meta table
 *
 * @param string $key
 * @param string $value
 * @return bool TRUE if success, FALSE if failure
 *
 * @access public
 * @author xhelp development team
 */
function smart_SetMeta($key, $value, $moduleName = false) {
    if (!$moduleName) {
        $moduleName = smart_getCurrentModuleName();
    }
    $xoopsDB = & XoopsDatabaseFactory::getDatabaseConnection();
    $ret = smart_GetMeta($key, $moduleName);
    if ($ret === '0' || $ret > 0) {
        $sql = sprintf("UPDATE %s SET metavalue = %s WHERE metakey = %s", $xoopsDB->prefix($moduleName . '_meta'), $xoopsDB->quoteString($value), $xoopsDB->quoteString($key));
    } else {
        $sql = sprintf("INSERT INTO %s (metakey, metavalue) VALUES (%s, %s)", $xoopsDB->prefix($moduleName . '_meta'), $xoopsDB->quoteString($key), $xoopsDB->quoteString($value));
    }
    $ret = $xoopsDB->queryF($sql);
    if (!$ret) {
        return false;
    }
    return true;
}
// Thanks to Mithrandir :-)
function smart_substr($str, $start, $length, $trimmarker = '...') {
    // if the string is empty, let's get out ;-)
    if ($str == '') {
        return $str;
    }
    // reverse a string that is shortened with '' as trimmarker
    $reversed_string = strrev(xoops_substr($str, $start, $length, ''));
    // find first space in reversed string
    $position_of_space = strpos($reversed_string, " ", 0);
    // truncate the original string to a length of $length
    // minus the position of the last space
    // plus the length of the $trimmarker
    $truncated_string = xoops_substr($str, $start, $length - $position_of_space +strlen($trimmarker), $trimmarker);
    return $truncated_string;
}
function smart_getConfig($key, $moduleName = false, $default = 'default_is_undefined') {
    if (!$moduleName) {
        $moduleName = smart_getCurrentModuleName();
    }
    $configs = smart_getModuleConfig($moduleName);
    if (isset ($configs[$key])) {
        return $configs[$key];
    } else {
        if ($default === 'default_is_undefined') {
            return null;
        } else {
            return $default;
        }
    }
}
/**
 * Copy a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.0
 * @param       string   $source    The source
 * @param       string   $dest      The destination
 * @return      bool     Returns true on success, false on failure
 */
function smart_copyr($source, $dest) {
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }
    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        // Deep copy directories
        if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) {
            copyr("$source/$entry", "$dest/$entry");
        } else {
            copy("$source/$entry", "$dest/$entry");
        }
    }
    // Clean up
    $dir->close();
    return true;
}
/**
 * Thanks to the NewBB2 Development Team
 */
function smart_admin_mkdir($target) {
    // http://www.php.net/manual/en/function.mkdir.php
    // saint at corenova.com
    // bart at cdasites dot com
    if (is_dir($target) || empty ($target)) {
        return true; // best case check first
    }
    if (file_exists($target) && !is_dir($target)) {
        return false;
    }
    if (smart_admin_mkdir(substr($target, 0, strrpos($target, '/')))) {
        if (!file_exists($target)) {
            $res = mkdir($target, 0777); // crawl back up & create dir tree
            smart_admin_chmod($target);
            return $res;
        }
    }
    $res = is_dir($target);
    return $res;
}
/**
 * Thanks to the NewBB2 Development Team
 */
function smart_admin_chmod($target, $mode = 0777) {
    return @ chmod($target, $mode);
}
function smart_imageResize($src, $maxWidth, $maxHeight) {
    $width = '';
    $height = '';
    $type = '';
    $attr = '';
    if (file_exists($src)) {
        list ($width, $height, $type, $attr) = getimagesize($src);
        if ($width > $maxWidth) {
            $originalWidth = $width;
            $width = $maxWidth;
            $height = $width * $height / $originalWidth;
        }
        if ($height > $maxHeight) {
            $originalHeight = $height;
            $height = $maxHeight;
            $width = $height * $width / $originalHeight;
        }
        $attr = " width='$width' height='$height'";
    }
    return array (
    $width,
    $height,
    $type,
    $attr
    );
}
function &smart_getModuleInfo($moduleName = false) {
    static $smartModules;
    if (isset ($smartModules[$moduleName])) {
        $ret =& $smartModules[$moduleName];
        return $ret;
    }
    global $xoopsModule;
    if (!$moduleName) {
        if (isset ($xoopsModule) && is_object($xoopsModule)) {
            $smartModules[$xoopsModule->getVar('dirname')] = & $xoopsModule;
            return $smartModules[$xoopsModule->getVar('dirname')];
        }
    }
    if (!isset ($smartModules[$moduleName])) {
        if (isset ($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $moduleName) {
            $smartModules[$moduleName] = & $xoopsModule;
        } else {
            $hModule = & xoops_gethandler('module');
            if ($moduleName != 'xoops') {
                $smartModules[$moduleName] = & $hModule->getByDirname($moduleName);

            } else {
                $smartModules[$moduleName] = & $hModule->getByDirname('system');
            }
        }
    }
    return $smartModules[$moduleName];
}
function &smart_getModuleConfig($moduleName = false) {
    static $smartConfigs;
    if (isset ($smartConfigs[$moduleName])) {
        $ret = & $smartConfigs[$moduleName];
        return $ret;
    }
    global $xoopsModule, $xoopsModuleConfig;
    if (!$moduleName) {
        if (isset ($xoopsModule) && is_object($xoopsModule)) {
            $smartConfigs[$xoopsModule->getVar('dirname')] = & $xoopsModuleConfig;
            return $smartConfigs[$xoopsModule->getVar('dirname')];
        }
    }
    // if we still did not found the xoopsModule, this is because there is none
    if (!$moduleName) {
        $ret = false;
        return $ret;
    }
    if (isset ($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $moduleName) {
        $smartConfigs[$moduleName] = & $xoopsModuleConfig;
    } else {
        $module = & smart_getModuleInfo($moduleName);
        if (!is_object($module)) {
            $ret = false;
            return $ret;
        }
        $hModConfig = & xoops_gethandler('config');
        $smartConfigs[$moduleName] = & $hModConfig->getConfigsByCat(0, $module->getVar('mid'));
    }
    return $smartConfigs[$moduleName];
}
function smart_deleteFile($dirname) {
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
}
function smart_formatErrors($errors = array ()) {
    $ret = '';
    foreach ($errors as $key => $value) {
        $ret .= "<br /> - " . $value;
    }
    return $ret;
}
/**
 * smart_getLinkedUnameFromId()
 *
 * @param integer $userid Userid of poster etc
 * @param integer $name :  0 Use Usenamer 1 Use realname
 * @return
 */
function smart_getLinkedUnameFromId($userid = 0, $name = 0, $users = array (), $withContact = false) {
    if (!is_numeric($userid)) {
        return $userid;
    }
    $userid = intval($userid);
    if ($userid > 0) {
        if ($users == array ()) {
            //fetching users
            $member_handler = & xoops_gethandler('member');
            $user = & $member_handler->getUser($userid);
        } else {
            if (!isset ($users[$userid])) {
                return $GLOBALS['xoopsConfig']['anonymous'];
            }
            $user = & $users[$userid];
        }
        if (is_object($user)) {
            $ts = & MyTextSanitizer :: getInstance();
            $username = $user->getVar('uname');
            $fullname = '';
            $fullname2 = $user->getVar('name');
            if (($name) && !empty ($fullname2)) {
                $fullname = $user->getVar('name');
            }
            if (!empty ($fullname)) {
                $linkeduser = "$fullname [<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $userid . "'>" . $ts->htmlSpecialChars($username) . "</a>]";
            } else {
                $linkeduser = "<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $userid . "'>" . ucwords($ts->htmlSpecialChars($username)) . "</a>";
            }
            // add contact info : email + PM
            if ($withContact) {
                $linkeduser .= ' <a href="mailto:' . $user->getVar('email') . '"><img style="vertical-align: middle;" src="' . XOOPS_URL . '/images/icons/email.gif' . '" alt="' . _CO_SOBJECT_SEND_EMAIL . '" title="' . _CO_SOBJECT_SEND_EMAIL . '"/></a>';
                $js = "javascript:openWithSelfMain('" . XOOPS_URL . '/pmlite.php?send2=1&to_userid=' . $userid . "', 'pmlite',450,370);";
                $linkeduser .= ' <a href="' . $js . '"><img style="vertical-align: middle;" src="' . XOOPS_URL . '/images/icons/pm.gif' . '" alt="' . _CO_SOBJECT_SEND_PM . '" title="' . _CO_SOBJECT_SEND_PM . '"/></a>';
            }
            return $linkeduser;
        }
    }
    return $GLOBALS['xoopsConfig']['anonymous'];
}
function smart_adminMenu($currentoption = 0, $breadcrumb = '', $submenus = false, $currentsub = -1) {
    global $xoopsModule, $xoopsConfig;
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/modinfo.php';
    }
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/admin.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/admin.php';
    }
    $headermenu = array();
    $adminmenu = array();
    include XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/menu.php';
    $tpl = new XoopsTpl();
    $tpl->assign(array (
        'headermenu' => $headermenu,
        'adminmenu' => $adminmenu,
        'current' => $currentoption,
        'breadcrumb' => $breadcrumb,
        'headermenucount' => count($headermenu
    ), 'submenus' => $submenus, 'currentsub' => $currentsub, 'submenuscount' => count($submenus)));
    $tpl->display('db:smartobject_admin_menu.html');
}
function smart_collapsableBar($id = '', $title = '', $dsc = '') {
    global $xoopsModule;
    echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"togglecollapse('" . $id . "'); toggleIcon('" . $id . "_icon')\";>";
    echo "<img id='" . $id . "_icon' src=" . SMARTOBJECT_URL . "images/close12.gif alt='' /></a>&nbsp;" . $title . "</h3>";
    echo "<div id='" . $id . "'>";
    if ($dsc != '') {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $dsc . "</span>";
    }
}
function smart_ajaxCollapsableBar($id = '', $title = '', $dsc = '') {
    global $xoopsModule;
    $onClick = "ajaxtogglecollapse('$id')";
    //$onClick = "togglecollapse('$id'); toggleIcon('" . $id . "_icon')";
    echo '<h3 style="border: 1px solid; color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; " onclick="' . $onClick . '">';
    echo "<img id='" . $id . "_icon' src=" . SMARTOBJECT_URL . "images/close12.gif alt='' /></a>&nbsp;" . $title . "</h3>";
    echo "<div id='" . $id . "'>";
    if ($dsc != '') {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $dsc . "</span>";
    }
}
/**
 * Ajax testing......
 */
/*
 function smart_collapsableBar($id = '', $title = '', $dsc='')
 {

 global $xoopsModule;
 //echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $id . "'); toggleIcon('" . $id . "_icon')\";>";

 ?>
 <h3 class="smart_collapsable_title"><a href="javascript:Effect.Combo('<? echo $id ?>');"><? echo $title ?></a></h3>
 <?

 echo "<img id='" . $id . "_icon' src=" . SMARTOBJECT_URL . "images/close12.gif alt='' /></a>&nbsp;" . $title . "</h3>";
 echo "<div id='" . $id . "'>";
 if ($dsc != '') {
 echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $dsc . "</span>";
 }
 }
 */
function smart_openclose_collapsable($name) {
    $urls = smart_getCurrentUrls();
    $path = $urls['phpself'];
    $cookie_name = $path . '_smart_collaps_' . $name;
    $cookie_name = str_replace('.', '_', $cookie_name);
    $cookie = smart_getCookieVar($cookie_name, '');
    if ($cookie == 'none') {
        echo '
                <script type="text/javascript"><!--
                togglecollapse("' . $name . '"); toggleIcon("' . $name . '_icon");
                    //-->
                </script>
                ';
    }
    /*  if ($cookie == 'none') {
     echo '
     <script type="text/javascript"><!--
     hideElement("' . $name . '");
     //-->
     </script>
     ';
     }
     */
}
function smart_close_collapsable($name) {
    echo "</div>";
    smart_openclose_collapsable($name);
    echo "<br />";
}
function smart_setCookieVar($name, $value, $time = 0) {
    if ($time == 0) {
        $time = time() + 3600 * 24 * 365;
        //$time = '';
    }
    setcookie($name, $value, $time, '/');
}
function smart_getCookieVar($name, $default = '') {
    $name = str_replace('.', '_', $name);
    if ((isset ($_COOKIE[$name])) && ($_COOKIE[$name] > '')) {
        return $_COOKIE[$name];
    } else {
        return $default;
    }
}

function smart_getCurrentUrls() {
    $urls = array();
    $http = ((strpos(XOOPS_URL, "https://")) === false) ? ("http://") : ("https://");
    $phpself = $_SERVER['PHP_SELF'];
    $httphost = $_SERVER['HTTP_HOST'];
    $querystring = $_SERVER['QUERY_STRING'];
    if ($querystring != '') {
        $querystring = '?' . $querystring;
    }
    $currenturl = $http . $httphost . $phpself . $querystring;
    $urls = array ();
    $urls['http'] = $http;
    $urls['httphost'] = $httphost;
    $urls['phpself'] = $phpself;
    $urls['querystring'] = $querystring;
    $urls['full_phpself'] = $http . $httphost . $phpself;
    $urls['full'] = $currenturl;
    $urls['isHomePage'] = (XOOPS_URL . "/index.php") == ($http . $httphost . $phpself);
    return $urls;
}

function smart_getCurrentPage() {
    $urls = smart_getCurrentUrls();
    return $urls['full'];
}
/**
 * Create a title for the short_url field of an article
 *
 * @credit psylove
 *
 * @var string $title title of the article
 * @var string $withExt do we add an html extension or not
 * @return string sort_url for the article
 */
/**
 * Moved in SmartMetaGenClass
 */
/*
 function smart_seo_title($title='', $withExt=true)
 {
 // Transformation de la chaine en minuscule
 // Codage de la chaine afin d'�viter les erreurs 500 en cas de caract�res impr�vus
 $title   = rawurlencode(strtolower($title));

 // Transformation des ponctuations
 //                 Tab     Space      !        "        #        %        &        '        (        )        ,        /        :        ;        <        =        >        ?        @        [        \        ]        ^        {        |        }        ~       .
 $pattern = array("/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/", "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/", "/%7C/", "/%7D/", "/%7E/", "/\./");
 $rep_pat = array(  "-"  ,   "-"  ,   ""   ,   ""   ,   ""   , "-100" ,   ""   ,   "-"  ,   ""   ,   ""   ,   ""   ,   "-"  ,   ""   ,   ""   ,   ""   ,   "-"  ,   ""   ,   ""   , "-at-" ,   ""   ,   "-"   ,  ""   ,   "-"  ,   ""   ,   "-"  ,   ""   ,   "-"  ,  ""  );
 $title   = preg_replace($pattern, $rep_pat, $title);

 // Transformation des caract�res accentu�s
 //                  �        �        �        �        �        �        �        �        �        �        �        �        �        �        �
 $pattern = array("/%E8/", "/%E9/", "/%EA/", "/%EB/", "/%E7/", "/%E0/", "/%E2/", "/%E4/", "/%EE/", "/%EF/", "/%F9/", "/%FC/", "/%FB/", "/%F4/", "/%F6/");
 $rep_pat = array(  "e"  ,   "e"  ,   "e"  ,   "e"  ,   "c"  ,   "a"  ,   "a"  ,   "a"  ,   "i"  ,   "i"  ,   "u"  ,   "u"  ,   "u"  ,   "o"  ,   "o"  );
 $title   = preg_replace($pattern, $rep_pat, $title);

 if (sizeof($title) > 0)
 {
 if ($withExt) {
 $title .= '.html';
 }
 return $title;
 }
 else
 return '';
 }
 */
function smart_modFooter() {
    global $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $tpl = new XoopsTpl();

    $hModule = & xoops_gethandler('module');
    $versioninfo = & $hModule->get($xoopsModule->getVar('mid'));
    $modfootertxt = "Module " . $versioninfo->getInfo('name') . " - Version " . $versioninfo->getInfo('version') . "";
    $modfooter = "<a href='" . $versioninfo->getInfo('support_site_url') . "' target='_blank'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/images/cssbutton.gif' title='" . $modfootertxt . "' alt='" . $modfootertxt . "'/></a>";
    $tpl->assign('modfooter', $modfooter);

    if (!defined('_AM_SOBJECT_XOOPS_PRO')) {
        define("_AM_SOBJECT_XOOPS_PRO", "Do you need help with this module ?<br />Do you need new features not yet available ?");
    }
    $smartobject_config = smart_getModuleConfig('smartobject');
    $tpl->assign('smartobject_enable_admin_footer', $smartobject_config['enable_admin_footer']);
    $tpl->display(SMARTOBJECT_ROOT_PATH . 'templates/smartobject_admin_footer.html');
}
function smart_xoops_cp_footer() {
    smart_modFooter();
    xoops_cp_footer();
}
function smart_sanitizeForCommonTags($text) {
    global $xoopsConfig;
    $text = str_replace('{X_SITENAME}', $xoopsConfig['sitename'], $text);
    $text = str_replace('{X_ADMINMAIL}', $xoopsConfig['adminmail'], $text);
    return $text;
}
function smart_addScript($src) {
    echo '<script src="' . $src . '" type="text/javascript"></script>';
}
function smart_addStyle($src) {
    if ($src == 'smartobject') {
        $src = SMARTOBJECT_URL . 'module.css';
    }
    echo smart_get_css_link($src);
}
function smart_addAdminAjaxSupport() {
    smart_addScript(SMARTOBJECT_URL . 'include/scriptaculous/lib/prototype.js');
    smart_addScript(SMARTOBJECT_URL . 'include/scriptaculous/src/scriptaculous.js');
    smart_addScript(SMARTOBJECT_URL . 'include/scriptaculous/src/smart.js');
}
function smart_sanitizeForSmartpopupLink($text) {
    $patterns[] = "/\[smartpopup=(['\"]?)([^\"'<>]*)\\1](.*)\[\/smartpopup\]/sU";
    $replacements[] = "<a href=\"javascript:openWithSelfMain('\\2', 'smartpopup', 700, 519);\">\\3</a>";
    $ret = preg_replace($patterns, $replacements, $text);
    return $ret;
}
/**
 * Finds the width and height of an image (can also be a flash file)
 *
 * @credit phppp
 *
 * @var string $url path of the image file
 * @var string $width reference to the width
 * @var string $height reference to the height
 * @return bool false if impossible to find dimension
 */
function smart_getImageSize($url, & $width, & $height) {
    if (empty ($width) || empty ($height)) {
        if (!$dimension = @ getimagesize($url)) {
            return false;
        }
        if (!empty ($width)) {
            $height = $dimension[1] * $width / $dimension[0];
        }
        elseif (!empty ($height)) {
            $width = $dimension[0] * $height / $dimension[1];
        } else {
            list ($width, $height) = array (
            $dimension[0],
            $dimension[1]
            );
        }
        return true;
    } else {
        return true;
    }
}
/**
 * Convert characters to decimal values
 *
 * @author eric.wallet at yahoo.fr
 * @link http://ca.php.net/manual/en/function.htmlentities.php#69913
 */
function smart_htmlnumericentities($str) {
    return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
}
function & smart_getcorehandler($name, $optional = false) {
    static $handlers;
    $name = strtolower(trim($name));
    if (!isset ($handlers[$name])) {
        if (file_exists($hnd_file = XOOPS_ROOT_PATH . '/kernel/' . $name . '.php')) {
            require_once $hnd_file;
        }
        $class = 'Xoops' . ucfirst($name) . 'Handler';
        if (class_exists($class)) {
            $handlers[$name] = new $class ($GLOBALS['xoopsDB'], 'xoops');
        }
    }
    if (!isset ($handlers[$name]) && !$optional) {
        trigger_error('Class <b>' . $class . '</b> does not exist<br />Handler Name: ' . $name, E_USER_ERROR);
    }
    if (isset ($handlers[$name])) {
        return $handlers[$name];
    }
    $inst = false;
}
function smart_sanitizeAdsenses_callback($matches) {
    global $smartobject_adsense_handler;
    if (isset($smartobject_adsense_handler->objects[$matches[1]])){
        $adsenseObj = $smartobject_adsense_handler->objects[$matches[1]];
        $ret = $adsenseObj->render();
        return $ret;
    } else {
        return '';
    }
}
function smart_sanitizeAdsenses($text) {

    $patterns = array ();
    $replacements = array ();

    $patterns[] = "/\[adsense](.*)\[\/adsense\]/sU";
    $text = preg_replace_callback($patterns, 'smart_sanitizeAdsenses_callback', $text);
    return $text;
}
function smart_sanitizeCustomtags_callback($matches) {
    global $smartobject_customtag_handler;
    if (isset($smartobject_customtag_handler->objects[$matches[1]])){
        $customObj = $smartobject_customtag_handler->objects[$matches[1]];
        $ret = $customObj->renderWithPhp();
        return $ret;
    } else {
        return '';
    }
}
function smart_sanitizeCustomtags($text) {
    $patterns = array ();
    $replacements = array ();

    $patterns[] = "/\[customtag](.*)\[\/customtag\]/sU";
    $text = preg_replace_callback($patterns, 'smart_sanitizeCustomtags_callback', $text);
    return $text;
}

function smart_loadLanguageFile($module, $file) {
    global $xoopsConfig;

    $filename = XOOPS_ROOT_PATH . '/modules/' . $module . '/language/' . $xoopsConfig['language'] . '/' . $file . '.php';
    if (!file_exists($filename)) {
        $filename = XOOPS_ROOT_PATH . '/modules/' . $module . '/language/english/' . $file . '.php';
    }
    if (file_exists($filename)) {
        include_once($filename);
    }
}

function smart_loadCommonLanguageFile() {
    smart_loadLanguageFile('smartobject', 'common');
}

function smart_purifyText($text, $keyword = false)
{
    global $myts;
    $text = str_replace('&nbsp;', ' ', $text);
    $text = str_replace('<br />', ' ', $text);
    $text = str_replace('<br/>', ' ', $text);
    $text = str_replace('<br', ' ', $text);
    $text = strip_tags($text);
    $text = html_entity_decode($text);
    $text = $myts->undoHtmlSpecialChars($text);
    $text = str_replace(')', ' ', $text);
    $text = str_replace('(', ' ', $text);
    $text = str_replace(':', ' ', $text);
    $text = str_replace('&euro', ' euro ', $text);
    $text = str_replace('&hellip', '...', $text);
    $text = str_replace('&rsquo', ' ', $text);
    $text = str_replace('!', ' ', $text);
    $text = str_replace('?', ' ', $text);
    $text = str_replace('"', ' ', $text);
    $text = str_replace('-', ' ', $text);
    $text = str_replace('\n', ' ', $text);
    $text = str_replace('&#8213;', ' ', $text);

    if ($keyword){
        $text = str_replace('.', ' ', $text);
        $text = str_replace(',', ' ', $text);
        $text = str_replace('\'', ' ', $text);
    }
    $text = str_replace(';', ' ', $text);

    return $text;
}

function smart_html2text($document)
{
    // PHP Manual:: function preg_replace
    // $document should contain an HTML document.
    // This will remove HTML tags, javascript sections
    // and white space. It will also convert some
    // common HTML entities to their text equivalent.
    // Credits : newbb2
    $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
    "'<img.*?/>'si",       // Strip out img tags
    "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
    "'([\r\n])[\s]+'",                // Strip out white space
    "'&(quot|#34);'i",                // Replace HTML entities
    "'&(amp|#38);'i",
    "'&(lt|#60);'i",
    "'&(gt|#62);'i",
    "'&(nbsp|#160);'i",
    "'&(iexcl|#161);'i",
    "'&(cent|#162);'i",
    "'&(pound|#163);'i",
    "'&(copy|#169);'i",
    "'&#(\d+);'e");                    // evaluate as php

    $replace = array ("",
    "",
    "",
    "\\1",
    "\"",
    "&",
    "<",
    ">",
    " ",
    chr(161),
    chr(162),
    chr(163),
    chr(169),
    "chr(\\1)");

    $text = preg_replace($search, $replace, $document);
    return $text;
}

/**
 * @author pillepop2003 at yahoo dot de
 *
 * Use this snippet to extract any float out of a string. You can choose how a single dot is treated with the (bool) 'single_dot_as_decimal' directive.
 * This function should be able to cover almost all floats that appear in an european environment.
 */
function smart_getfloat($str, $set=FALSE){
    if(preg_match("/([0-9\.,-]+)/", $str, $match)) {
        // Found number in $str, so set $str that number
        $str = $match[0];
        if(strstr($str, ',')){
            // A comma exists, that makes it easy, cos we assume it separates the decimal part.
            $str = str_replace('.', '', $str);    // Erase thousand seps
            $str = str_replace(',', '.', $str);    // Convert , to . for floatval command
            return floatval($str);
        }else{
            // No comma exists, so we have to decide, how a single dot shall be treated
            if(preg_match("/^[0-9\-]*[\.]{1}[0-9-]+$/", $str) == TRUE && $set['single_dot_as_decimal'] == TRUE){
                // Treat single dot as decimal separator

                return floatval($str);
            } else {//echo "str: ".$str; echo "ret: ".str_replace('.', '', $str); echo "<br/><br/> ";
                // Else, treat all dots as thousand seps
                $str = str_replace('.', '', $str);    // Erase thousand seps
                return floatval($str);
            }
        }
    }else{
        // No number found, return zero
        return 0;
    }
}

function smart_currency($var, $currencyObj=false) {
    $ret = smart_getfloat($var,  array('single_dot_as_decimal'=> TRUE));
    $ret = round($ret, 2);
    // make sur we have at least .00 in the $var
    $decimal_section_original = strstr($ret, '.');
    $decimal_section = $decimal_section_original;
    if ($decimal_section) {
        if (strlen($decimal_section) == 1) {
            $decimal_section = '.00';
        } elseif(strlen($decimal_section) == 2) {
            $decimal_section = $decimal_section . '0';
        }
        $ret = str_replace($decimal_section_original, $decimal_section, $ret);
    } else {
        $ret = $ret . '.00';
    }
    if ($currencyObj) {
        $ret = $ret . ' ' . $currencyObj->getCode();
    }
    return $ret;
}

function smart_float($var) {
    return smart_currency($var);
}

function smart_getModuleAdminLink($moduleName=false) {
    global $xoopsModule;
    if (!$moduleName && (isset ($xoopsModule) && is_object($xoopsModule))) {
        $moduleName = $xoopsModule->getVar('dirname');
    }
    $ret = '';
    if ($moduleName) {
        $ret = "<a href='" . XOOPS_URL . "/modules/$moduleName/admin/index.php'>" ._CO_SOBJECT_ADMIN_PAGE . "</a>";
    }
    return $ret;
}

function smart_getEditors() {
    $filename = XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php';
    if (!file_exists($filename)) {
        return false;
    }
    include_once $filename;
    $xoopseditor_handler = XoopsEditorHandler::getInstance();
    $aList = $xoopseditor_handler->getList();
    $ret = array();
    foreach($aList as $k=>$v) {
        $ret[$v] = $k;
    }
    return $ret;
}

function smart_getTablesArray($moduleName, $items) {
    $ret = array();
    foreach ($items as $item) {
        $ret[] = $moduleName . '_' . $item;
    }
    $ret[] = $moduleName . '_meta';
    return $ret;
}
?>