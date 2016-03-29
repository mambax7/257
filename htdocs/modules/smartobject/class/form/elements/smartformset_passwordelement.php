<?php
/**
 * Contains the set password tray class
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: smartformset_passwordelement.php 159 2007-12-17 16:44:05Z malanciault $
 * @link http://smartfactory.ca The SmartFactory
 * @package SmartObject
 * @subpackage SmartObjectForm
 */
if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

class SmartFormSet_passwordElement extends XoopsFormElementTray {

    /**
     * Size of the field.
     * @var int
     * @access  private
     */
    var $_size;

    /**
     * Maximum length of the text
     * @var int
     * @access  private
     */
    var $_maxlength;

    /**
     * Initial content of the field.
     * @var string
     * @access  private
     */
    var $_value;

    /**
     * Constructor
     *
     * @param   string  $caption    Caption
     * @param   string  $name       "name" attribute
     * @param   int     $size       Size of the field
     * @param   int     $maxlength  Maximum length of the text
     * @param   int     $value      Initial value of the field.
     *                              <b>Warning:</b> this is readable in cleartext in the page's source!
     */
    function SmartFormSet_passwordElement($object, $key){

        $var = $object->vars[$key];
        $control = $object->controls[$key];

        $this->XoopsFormElementTray($var['form_caption'] . '<br />' . _US_TYPEPASSTWICE, ' ', $key . '_password_tray');

        $password_box1 = new XoopsFormPassword('', $key . '1', 10, 32);
        $this->addElement($password_box1);

        $password_box2 = new XoopsFormPassword('', $key . '2', 10, 32);
        $this->addElement($password_box2);
    }
}
?>