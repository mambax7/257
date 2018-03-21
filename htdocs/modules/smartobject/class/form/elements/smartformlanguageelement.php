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
class SmartFormLanguageElement extends XoopsFormSelectLang
{
    /**
     * SmartFormLanguageElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var     = $object->vars[$key];
        $control = $object->controls[$key];
        $all     = isset($control['all']) ? true : false;

        parent::__construct($var['form_caption'], $key, $object->getVar($key, 'e'));
        if ($all) {
            $this->addOption('all', _ALL);
        }
    }
}
