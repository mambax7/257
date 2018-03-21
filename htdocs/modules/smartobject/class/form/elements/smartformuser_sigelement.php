<?php
/**
 * Contains the set password tray class
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectForm
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

class SmartFormUser_sigElement extends XoopsFormElementTray
{
    /**
     * SmartFormUser_sigElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var     = $object->vars[$key];
        $control = $object->controls[$key];

        parent::__construct($var['form_caption'], '<br><br>', $key . '_signature_tray');

        $signature_textarea = new \XoopsFormDhtmlTextArea('', $key, $object->getVar($key, 'e'));
        $this->addElement($signature_textarea);

        $attach_checkbox = new \XoopsFormCheckBox('', 'attachsig', $object->getVar('attachsig', 'e'));
        $attach_checkbox->addOption(1, _US_SHOWSIG);
        $this->addElement($attach_checkbox);
    }
}
