<?php
class SmartPlugin {

    var $_infoArray;

    function SmartPlugin($array) {
        $this->_infoArray = $array;
    }

    function getItemInfo($item) {
        if (isset($this->_infoArray['items'][$item])) {
            return $this->_infoArray['items'][$item];
        } else {
            return false;
        }
    }

    function getItemList() {
        $itemsArray = $this->_infoArray['items'];
        foreach ($itemsArray as $k=>$v) {
            $ret[$k] = $v['caption'];
        }
        return $ret;
    }

    function getItem() {
        $ret = false;
        foreach($this->_infoArray['items'] as $k => $v) {
            $search_str = str_replace('%u', '', $v['url']);
            if (strpos($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], $search_str) > 0) {
                $ret = $k;
                break;
            }
        }
        return $ret;
    }

    function getItemIdForItem($item) {
        return $_REQUEST[$this->_infoArray['items'][$item]['request']];
    }
}

class SmartPluginHandler {

    var $pluginPatterns = false;

    function getPlugin($dirname) {
        $pluginName = SMARTOBJECT_ROOT_PATH . 'plugins/' . $dirname . '.php';
        if (file_exists($pluginName)) {
            include_once($pluginName);
            $function = 'smartobject_plugin_' . $dirname;
            if (function_exists($function)) {
                $array = $function();
                $ret = new SmartPlugin($array);
                return $ret;
            }
        }
        return false;
    }

    function getPluginsArray() {
        include_once(XOOPS_ROOT_PATH . "/class/xoopslists.php");

        $module_handler = xoops_gethandler('module');
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('isactive', 1));
        $tempModulesObj = $module_handler->getObjects($criteria);
        $modulesObj = array();
        foreach ($tempModulesObj as $moduleObj) {
            $modulesObj[$moduleObj->getVar('dirname')] = $moduleObj;
        }

        $aFiles = XoopsLists::getFileListAsArray(SMARTOBJECT_ROOT_PATH . 'plugins/');
        $ret = array();
        foreach($aFiles as $file) {
            if (substr($file, strlen($file) - 4, 4) == '.php') {
                $pluginName = str_replace('.php', '', $file);
                $module_xoops_version_file = XOOPS_ROOT_PATH . "/modules/$pluginName/xoops_version.php";
                if (file_exists($module_xoops_version_file) && isset($modulesObj[$pluginName])) {
                    $ret[$pluginName] = $modulesObj[$pluginName]->getVar('name');
                }
            }
        }
        return $ret;
    }
}
?>