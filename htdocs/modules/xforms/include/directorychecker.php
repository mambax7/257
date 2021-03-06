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

//defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
xoops_loadLanguage('directorychecker', 'xforms');

/**
 * Class DirectoryChecker
 * check status of a directory
 */
class directorychecker
{
    /**
     * @param     $path
     * @param int $mode
     * @param     $redirectFile
     *
     * @return bool|string
     */
    public static function getDirectoryStatus($path, $mode = 0777, $redirectFile = null)
    {
        global $pathIcon16;

        if (empty($path)) {
            return false;
        }
        if (is_null($redirectFile)) {
            $redirectFile = $_SERVER['PHP_SELF'];
        }
        if (!@is_dir($path)) {
            $path_status = "<img src='$pathIcon16/0.png' >";
            $path_status .= "$path (" . _DC_XFORMS_NOTAVAILABLE . ") ";
            $path_status .= "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
            $path_status .= "<input type='hidden' name='op' value='createdir'>";
            $path_status .= "<input type='hidden' name='path' value='$path'>";
            $path_status .= "<input type='hidden' name='redirect' value='$redirectFile'>";
            $path_status .= "<button class='submit' onClick='this.form.submit();'>" . _DC_XFORMS_CREATETHEDIR . "</button>";
            $path_status .= "</form>";
        } elseif (@is_writable($path)) {
            $path_status = "<img src='$pathIcon16/1.png' >";
            $path_status .= "$path (" . _DC_XFORMS_AVAILABLE . ") ";
            $currentMode = (substr(decoct(fileperms($path)), 2));
            if ($currentMode != decoct($mode)) {
                $path_status = "<img src='$pathIcon16/0.png' >";
                $path_status .= $path . sprintf(_DC_XFORMS_NOTWRITABLE, decoct($mode), $currentMode);
                $path_status .= "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                $path_status .= "<input type='hidden' name='op' value='setdirperm'>";
                $path_status .= "<input type='hidden' name='mode' value='$mode'>";
                $path_status .= "<input type='hidden' name='path' value='$path'>";
                $path_status .= "<input type='hidden' name='redirect' value='$redirectFile'>";
                $path_status .= "<button class='submit' onClick='this.form.submit();'>" . _DC_XFORMS_SETMPERM . "</button>";
                $path_status .= "</form>";
            }
        } else {
            $currentMode = (substr(decoct(fileperms($path)), 2));
            $path_status = "<img src='$pathIcon16/0.png' >";
            $path_status .= $path . sprintf(_DC_XFORMS_NOTWRITABLE, decoct($mode), $currentMode);
            $path_status .= "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
            $path_status .= "<input type='hidden' name='op' value='setdirperm'>";
            $path_status .= "<input type='hidden' name='mode' value='$mode'>";
            $path_status .= "<input type='hidden' name='path' value='$path'>";
            $path_status .= "<input type='hidden' name='redirect' value='$redirectFile'>";
            $path_status .= "<button class='submit' onClick='this.form.submit();'>" . _DC_XFORMS_SETMPERM . "</button>";
            $path_status .= "</form>";
        }

        return $path_status;
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function createDirectory($target, $mode = 0777)
    {
        $target = str_replace("..", "", $target);
        // http://www.php.net/manual/en/function.mkdir.php
        return is_dir($target) || (self::createDirectory(dirname($target), $mode) && mkdir($target, $mode));
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function setDirectoryPermissions($target, $mode = 0777)
    {
        $target = str_replace("..", "", $target);

        return @chmod($target, (int)$mode);
    }

    /**
     * @param   $dir_path
     *
     * @return bool
     */
    public static function dirExists($dir_path)
    {
        return is_dir($dir_path);
    }
}

$op = (isset($_POST['op'])) ? $_POST['op'] : "";
switch ($op) {
    case "createdir":
        if (isset($_POST['path'])) {
            $path = $_POST['path'];
        }
        if (isset($_POST['redirect'])) {
            $redirect = $_POST['redirect'];
        }
        $msg = (DirectoryChecker::createDirectory($path)) ? _DC_XFORMS_DIRCREATED : _DC_XFORMS_DIRNOTCREATED;
        redirect_header($redirect, 2, $msg . ': ' . $path);
        exit();
        break;
    case "setdirperm":
        if (isset($_POST['path'])) {
            $path = $_POST['path'];
        }
        if (isset($_POST['redirect'])) {
            $redirect = $_POST['redirect'];
        }
        if (isset($_POST['mode'])) {
            $mode = $_POST['mode'];
        }
        $msg = (DirectoryChecker::setDirectoryPermissions($path, $mode)) ? _DC_XFORMS_PERMSET : _DC_XFORMS_PERMNOTSET;
        redirect_header($redirect, 2, $msg . ': ' . $path);
        exit();
        break;
}
