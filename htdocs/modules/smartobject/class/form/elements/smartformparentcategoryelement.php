<?php

/**
 * Contains the SmartObjectControl class
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectForm
 */
class SmartFormParentCategoryElement extends XoopsFormSelect
{
    /**
     * SmartFormParentcategoryElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $addNoParent = isset($object->controls[$key]['addNoParent']) ? $object->controls[$key]['addNoParent'] : true;
        $criteria    = new \CriteriaCompo();
        $criteria->setSort('weight, name');
        $categoryHandler = xoops_getModuleHandler('category', $object->handler->_moduleName);
        $categories      = $categoryHandler->getObjects($criteria);

        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        $mytree = new \XoopsObjectTree($categories, 'categoryid', 'parentid');
        parent::__construct($object->vars[$key]['form_caption'], $key, $object->getVar($key, 'e'));

        $ret     = [];
        $options = $this->getOptionArray($mytree, 'name', 0, '', $ret);
        if ($addNoParent) {
            $newOptions = ['0' => '----'];
            foreach ($options as $k => $v) {
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
     * @param string          $fieldName
     * @param int             $key
     * @param string          $prefix_curr
     * @param array           $ret
     *
     * @return array
     */
    public function getOptionArray($tree, $fieldName, $key, $prefix_curr = '', &$ret)
    {
        if ($key > 0) {
            $value       = $tree->tree[$key]['obj']->getVar($tree->_myId);
            $ret[$key]   = $prefix_curr . $tree->tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= '-';
        }
        if (isset($tree->tree[$key]['child']) && !empty($tree->tree[$key]['child'])) {
            foreach ($tree->tree[$key]['child'] as $childkey) {
                $this->getOptionArray($tree, $fieldName, $childkey, $prefix_curr, $ret);
            }
        }

        return $ret;
    }
}
