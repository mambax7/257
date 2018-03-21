<?php

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * SmartObjects Registry
 *
 * The SmartObjects Registry is an object containing SmartObject objects that will be reused in the same process
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartObjectsRegistry
{
    public $_registryArray;

    /**
     * Access the only instance of this class
     *
     * @return \SmartObjectsRegistry
     *
     * @static
     * @staticvar   object
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Adding objects to the registry
     *
     * @param  SmartPersistableObjectHandler $handler  of the objects to add
     * @param  bool|CriteriaCompo            $criteria to pass to the getObjects method of the handler (with id_as_key)
     * @return FALSE                         if an error occured
     */
    public function addObjectsFromHandler(&$handler, $criteria = false)
    {
        if (method_exists($handler, 'getObjects')) {
            $objects                                                                     = $handler->getObjects($criteria, true);
            $this->_registryArray['objects'][$handler->_moduleName][$handler->_itemname] = $objects;

            return $objects;
        } else {
            return false;
        }
    }

    /**
     * Adding objects to the registry from an item name
     * This method will fetch the handler of the item / module and call the addObjectsFromHandler
     *
     * @param  string             $item       name of the item
     * @param  bool|string        $modulename name of the module
     * @param  bool|CriteriaCompo $criteria   to pass to the getObjects method of the handler (with id_as_key)
     * @return FALSE              if an error occured
     */
    public function addObjectsFromItemName($item, $modulename = false, $criteria = false)
    {
        if (!$modulename) {
            global $xoopsModule;
            if (!is_object($xoopsModule)) {
                return false;
            } else {
                $modulename = $xoopsModule->dirname();
            }
        }
        $objectHandler = xoops_getModuleHandler($item, $modulename);

        if (method_exists($objectHandler, 'getObjects')) {
            $objects                                                                                 = $objectHandler->getObjects($criteria, true);
            $this->_registryArray['objects'][$objectHandler->_moduleName][$objectHandler->_itemname] = $objects;

            return $objects;
        } else {
            return false;
        }
    }

    /**
     * Fetching objects from the registry
     *
     * @param string $itemname
     * @param string $modulename
     *
     * @return false|array the requested objects or FALSE if they don't exists in the registry
     */
    public function getObjects($itemname, $modulename)
    {
        if (!$modulename) {
            global $xoopsModule;
            if (!is_object($xoopsModule)) {
                return false;
            } else {
                $modulename = $xoopsModule->dirname();
            }
        }
        if (isset($this->_registryArray['objects'][$modulename][$itemname])) {
            return $this->_registryArray['objects'][$modulename][$itemname];
        } else {
            // if they were not in registry, let's fetch them and add them to the reigistry
            $moduleHandler = xoops_getModuleHandler($itemname, $modulename);
            if (method_exists($moduleHandler, 'getObjects')) {
                $objects = $moduleHandler->getObjects();
            }
            $this->_registryArray['objects'][$modulename][$itemname] = $objects;

            return $objects;
        }
    }

    /**
     * Fetching objects from the registry, as a list: objectid => identifier
     *
     * @param string $itemname
     * @param string $modulename
     *
     * @return false|array the requested objects or FALSE if they don't exists in the registry
     */
    public function getList($itemname, $modulename)
    {
        if (!$modulename) {
            global $xoopsModule;
            if (!is_object($xoopsModule)) {
                return false;
            } else {
                $modulename = $xoopsModule->dirname();
            }
        }
        if (isset($this->_registryArray['list'][$modulename][$itemname])) {
            return $this->_registryArray['list'][$modulename][$itemname];
        } else {
            // if they were not in registry, let's fetch them and add them to the reigistry
            $moduleHandler = xoops_getModuleHandler($itemname, $modulename);
            if (method_exists($moduleHandler, 'getList')) {
                $objects = $moduleHandler->getList();
            }
            $this->_registryArray['list'][$modulename][$itemname] = $objects;

            return $objects;
        }
    }

    /**
     * Retreive a single object
     *
     * @param string $itemname
     * @param string $key
     *
     * @param  bool  $modulename
     * @return false|\XoopsObject the  requestd object or FALSE if they don't exists in the registry
     */
    public function getSingleObject($itemname, $key, $modulename = false)
    {
        if (!$modulename) {
            global $xoopsModule;
            if (!is_object($xoopsModule)) {
                return false;
            } else {
                $modulename = $xoopsModule->dirname();
            }
        }
        if (isset($this->_registryArray['objects'][$modulename][$itemname][$key])) {
            return $this->_registryArray['objects'][$modulename][$itemname][$key];
        } else {
            $objectHandler = xoops_getModuleHandler($itemname, $modulename);
            $object        = $objectHandler->get($key);
            if (!$object->isNew()) {
                return $object;
            } else {
                return false;
            }
        }
    }
}
