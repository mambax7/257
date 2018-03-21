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

class SmartFormSet_passwordElement extends XoopsFormElementTray
{
    /**
     * Size of the field.
     * @var int
     * @access  private
     */
    public $_size;

    /**
     * Maximum length of the text
     * @var int
     * @access  private
     */
    public $_maxlength;

    /**
     * Initial content of the field.
     * @var string
     * @access  private
     */
    public $_value;

    /**
     * Constructor
     *
     * @param string $object
     * @param string $key
     * @internal param string $caption Caption
     * @internal param string $name "name" attribute
     * @internal param int $size Size of the field
     * @internal param int $maxlength Maximum length of the text
     * @internal param int $value Initial value of the field.
     *                          <b>Warning:</b> this is readable in cleartext in the page's source!
     */
    public function __construct($object, $key)
    {
        $var     = $object->vars[$key];
        $control = $object->controls[$key];

        parent::__construct($var['form_caption'] . '<br>' . _US_TYPEPASSTWICE, ' ', $key . '_password_tray');

        $password_box1 = new \XoopsFormPassword('', $key . '1', 10, 32);
        $this->addElement($password_box1);

        $password_box2 = new \XoopsFormPassword('', $key . '2', 10, 32);
        $this->addElement($password_box2);
    }
}
