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
class SmartFormUserElement extends XoopsFormSelect
{
    public $multiple = false;

    /**
     * SmartFormUserElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var  = $object->vars[$key];
        $size = isset($var['size']) ? $var['size'] : ($this->multiple ? 5 : 1);

        parent::__construct($var['form_caption'], $key, $object->getVar($key, 'e'), $size, $this->multiple);

        // Adding the options inside this SelectBox
        // If the custom method is not from a module, than it's from the core
        $control = $object->getControl($key);

        global $xoopsDB;
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT uid, uname FROM ' . $xoopsDB->prefix('users');
        $sql   .= ' ORDER BY uname ASC';

        $result = $xoopsDB->query($sql);
        if ($result) {
            while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
                $uArray[$myrow['uid']] = $myrow['uname'];
            }
        }
        $this->addOptionArray($uArray);
    }
}
