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
class SmartFormTimeElement extends XoopsFormSelect
{
    /**
     * SmartFormTimeElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var       = $object->vars[$key];
        $timearray = [];
        for ($i = 0; $i < 24; ++$i) {
            for ($j = 0; $j < 60; $j += 10) {
                $key_t             = ($i * 3600) + ($j * 60);
                $timearray[$key_t] = (0 != $j) ? $i . ':' . $j : $i . ':0' . $j;
            }
        }
        ksort($timearray);
        parent::__construct($var['form_caption'], $key, $object->getVar($key, 'e'));
        $this->addOptionArray($timearray);
    }
}
