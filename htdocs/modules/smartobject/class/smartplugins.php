<?php

/**
 * Class SmartPlugin
 */
class SmartPlugin
{
    public $_infoArray;

    /**
     * SmartPlugin constructor.
     * @param $array
     */
    public function __construct($array)
    {
        $this->_infoArray = $array;
    }

    /**
     * @param $item
     * @return bool
     */
    public function getItemInfo($item)
    {
        if (isset($this->_infoArray['items'][$item])) {
            return $this->_infoArray['items'][$item];
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getItemList()
    {
        $itemsArray = $this->_infoArray['items'];
        foreach ($itemsArray as $k => $v) {
            $ret[$k] = $v['caption'];
        }

        return $ret;
    }

    /**
     * @return bool|int|string
     */
    public function getItem()
    {
        $ret = false;
        foreach ($this->_infoArray['items'] as $k => $v) {
            $search_str = str_replace('%u', '', $v['url']);
            if (strpos($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], $search_str) > 0) {
                $ret = $k;
                break;
            }
        }

        return $ret;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getItemIdForItem($item)
    {
        return $_REQUEST[$this->_infoArray['items'][$item]['request']];
    }
}

/**
 * Class SmartPluginHandler
 */
class SmartPluginHandler
{
    public $pluginPatterns = false;

    /**
     * @param $dirname
     * @return bool|SmartPlugin
     */
    public function getPlugin($dirname)
    {
        $pluginName = SMARTOBJECT_ROOT_PATH . 'plugins/' . $dirname . '.php';
        if (file_exists($pluginName)) {
            require_once $pluginName;
            $function = 'smartobject_plugin_' . $dirname;
            if (function_exists($function)) {
                $array = $function();
                $ret   = new SmartPlugin($array);

                return $ret;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getPluginsArray()
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \CriteriaCompo();
        $criteria->add(new \Criteria('isactive', 1));
        $tempModulesObj = $moduleHandler->getObjects($criteria);
        $modulesObj     = [];
        foreach ($tempModulesObj as $moduleObj) {
            $modulesObj[$moduleObj->getVar('dirname')] = $moduleObj;
        }

        $aFiles = XoopsLists::getFileListAsArray(SMARTOBJECT_ROOT_PATH . 'plugins/');
        $ret    = [];
        foreach ($aFiles as $file) {
            if ('.php' === substr($file, strlen($file) - 4, 4)) {
                $pluginName                = str_replace('.php', '', $file);
                $module_xoops_version_file = XOOPS_ROOT_PATH . "/modules/$pluginName/xoops_version.php";
                if (file_exists($module_xoops_version_file) && isset($modulesObj[$pluginName])) {
                    $ret[$pluginName] = $modulesObj[$pluginName]->getVar('name');
                }
            }
        }

        return $ret;
    }
}
