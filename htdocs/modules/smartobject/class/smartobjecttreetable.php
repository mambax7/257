<?php namespace XoopsModules\Smartobject;

/**
 * Contains the classes responsible for displaying a tree table filled with records of SmartObjects
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectTable
 */

use XoopsModules\Smartobject;

require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';

/**
 * SmartObjectTreeTable class
 *
 * Class representing a tree table for displaying SmartObjects
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartObjectTreeTable extends SmartObjectTable
{
    /**
     * SmartObjectTreeTable constructor.
     * @param SmartPersistableObjectHandler $objectHandler
     * @param bool                          $criteria
     * @param array                         $actions
     * @param bool                          $userSide
     */
    public function __construct(
        SmartPersistableObjectHandler $objectHandler,
        $criteria = false,
        $actions = ['edit', 'delete'],
        $userSide = false
    ) {
        $this->SmartObjectTable($objectHandler, $criteria, $actions, $userSide);
        $this->_isTree = true;
    }

    /**
     * Get children objects given a specific parentid
     *
     * @var    int $parentid id of the parent which children we want to retreive
     * @return array of SmartObject
     */
    public function getChildrenOf($parentid = 0)
    {
        return isset($this->_objects[$parentid]) ? $this->_objects[$parentid] : false;
    }

    /**
     * @param     $object
     * @param int $level
     */
    public function createTableRow($object, $level = 0)
    {
        $aObject = [];

        $i = 0;

        $aColumns        = [];
        $doWeHaveActions = false;

        foreach ($this->_columns as $column) {
            $aColumn = [];

            if (0 == $i) {
                $class = 'head';
            } elseif (0 == $i % 2) {
                $class = 'even';
            } else {
                $class = 'odd';
            }

            if ($column->_customMethodForValue && method_exists($object, $column->_customMethodForValue)) {
                $method = $column->_customMethodForValue;
                $value  = $object->$method();
            } else {
                /**
                 * If the column is the identifier, then put a link on it
                 */
                if ($column->getKeyName() == $this->_objectHandler->identifierName) {
                    $value = $object->getItemLink();
                } else {
                    $value = $object->getVar($column->getKeyName());
                }
            }

            $space = '';
            if ($column->getKeyName() == $this->_objectHandler->identifierName) {
                for ($i = 0; $i < $level; ++$i) {
                    $space .= '--';
                }
            }

            if ('' !== $space) {
                $space .= '&nbsp;';
            }

            $aColumn['value'] = $space . $value;
            $aColumn['class'] = $class;
            $aColumn['width'] = $column->getWidth();
            $aColumn['align'] = $column->getAlign();
            $aColumn['key']   = $column->getKeyName();

            $aColumns[] = $aColumn;
            ++$i;
        }

        $aObject['columns'] = $aColumns;

        $class            = 'even' === $class ? 'odd' : 'even';
        $aObject['class'] = $class;

        $actions = [];

        // Adding the custom actions if any
        foreach ($this->_custom_actions as $action) {
            if (method_exists($object, $action)) {
                $actions[] = $object->$action();
            }
        }

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectcontroller.php';
        $controller = new SmartObjectController($this->_objectHandler);

        if (in_array('edit', $this->_actions)) {
            $actions[] = $controller->getEditItemLink($object, false, true);
        }
        if (in_array('delete', $this->_actions)) {
            $actions[] = $controller->getDeleteItemLink($object, false, true);
        }
        $aObject['actions'] = $actions;

        $this->_tpl->assign('smartobject_actions_column_width', count($actions) * 30);
        $aObject['id']     = $object->id();
        $this->_aObjects[] = $aObject;

        $childrenObjects = $this->getChildrenOf($object->id());

        $this->_hasActions = $this->_hasActions ? true : count($actions) > 0;

        if ($childrenObjects) {
            ++$level;
            foreach ($childrenObjects as $subObject) {
                $this->createTableRow($subObject, $level);
            }
        }
    }

    public function createTableRows()
    {
        $this->_aObjects = [];

        if (count($this->_objects) > 0) {
            foreach ($this->getChildrenOf() as $object) {
                $this->createTableRow($object);
            }

            $this->_tpl->assign('smartobject_objects', $this->_aObjects);
        } else {
            $colspan = count($this->_columns) + 1;
            $this->_tpl->assign('smartobject_colspan', $colspan);
        }
    }

    /**
     * @return mixed
     */
    public function fetchObjects()
    {
        $ret = $this->_objectHandler->getObjects($this->_criteria, 'parentid');

        return $ret;
    }
}
