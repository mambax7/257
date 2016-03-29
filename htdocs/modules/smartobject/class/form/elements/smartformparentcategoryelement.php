<?php

/**
 * Contains the SmartObjectControl class
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: smartformparentcategoryelement.php 159 2007-12-17 16:44:05Z malanciault $
 * @link http://smartfactory.ca The SmartFactory
 * @package SmartObject
 * @subpackage SmartObjectForm
 */
class SmartFormParentcategoryElement extends XoopsFormSelect {
    function SmartFormParentcategoryElement($object, $key) {

        $addNoParent = isset($object->controls[$key]['addNoParent']) ? $object->controls[$key]['addNoParent'] : true;
        $criteria = new CriteriaCompo();
        $criteria->setSort("weight, name");
        $category_handler = xoops_getmodulehandler('category', $object->handler->_moduleName);
        $categories = $category_handler->getObjects($criteria);

        include_once(XOOPS_ROOT_PATH . "/class/tree.php");
        $mytree = new XoopsObjectTree($categories, "categoryid", "parentid");
        $this->XoopsFormSelect( $object->vars[$key]['form_caption'], $key, $object->getVar($key, 'e') );

        $ret = array();
        $options = $this->getOptionArray($mytree, "name", 0, "", $ret);
        if ($addNoParent) {
            $newOptions = array('0'=>'----');
            foreach ($options as $k=>$v) {
                $newOptions[$k] = $v;
            }
            $options = $newOptions;
        }
        $this->addOptionArray($options);
    }

    /**
     * Get options for a category select with hierarchy (recursive)
     *
     * @param XoopsObjectTree $tree
     * @param string $fieldName
     * @param int $key
     * @param string $prefix_curr
     * @param array $ret
     *
     * @return array
     */
    function getOptionArray($tree, $fieldName, $key, $prefix_curr = "", &$ret) {

        if ($key > 0) {
            $value = $tree->_tree[$key]['obj']->getVar($tree->_myId);
            $ret[$key] = $prefix_curr.$tree->_tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= "-";
        }
        if (isset($tree->_tree[$key]['child']) && !empty($tree->_tree[$key]['child'])) {
            foreach ($tree->_tree[$key]['child'] as $childkey) {
                $this->getOptionArray($tree, $fieldName, $childkey, $prefix_curr, $ret);
            }
        }
        return $ret;
    }
}
?>