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
class SmartFormUrlLinkElement extends XoopsFormElementTray
{
    /**
     * SmartFormUrlLinkElement constructor.
     * @param string $form_caption
     * @param string $key
     * @param string $object
     */
    public function __construct($form_caption, $key, $object)
    {
        parent::__construct($form_caption, '&nbsp;');

        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_URLLINK_URL));
        $this->addElement(new SmartFormTextElement($object, 'url_' . $key));

        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_CAPTION));
        $this->addElement(new SmartFormTextElement($object, 'caption_' . $key));

        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_DESC . '<br>'));
        $this->addElement(new \XoopsFormTextArea('', 'desc_' . $key, $object->getVar('description')));

        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_URLLINK_TARGET));
        $targ_val    = $object->getVar('target');
        $targetRadio = new \XoopsFormRadio('', 'target_' . $key, '' !== $targ_val ? $targ_val : '_blank');
        $control     = $object->getControl('target');
        $targetRadio->addOptionArray($control['options']);

        $this->addElement($targetRadio);
    }
}
