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
class SmartFormRichFileElement extends XoopsFormElementTray
{
    /**
     * SmartFormRichFileElement constructor.
     * @param string $form_caption
     * @param string $key
     * @param string $object
     */
    public function __construct($form_caption, $key, $object)
    {
        parent::__construct($form_caption, '&nbsp;');
        if ('' !== $object->getVar('url')) {
            $caption = '' !== $object->getVar('caption') ? $object->getVar('caption') : $object->getVar('url');
            $this->addElement(new \XoopsFormLabel('', _CO_SOBJECT_CURRENT_FILE . "<a href='" . str_replace('{XOOPS_URL}', XOOPS_URL, $object->getVar('url')) . "' target='_blank' >" . $caption . '</a><br><br>'));
            //$this->addElement( new \XoopsFormLabel( '', "<br><a href = '".SMARTOBJECT_URL."admin/file.php?op=del&fileid=".$object->id()."'>"._CO_SOBJECT_DELETE_FILE."</a>"));
        }

        require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformfileuploadelement.php';
        if ($object->isNew()) {
            $this->addElement(new SmartFormFileUploadElement($object, $key));
            $this->addElement(new \XoopsFormLabel('', '<br><br><small>' . _CO_SOBJECT_URL_FILE_DSC . '</small>'));
            $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_URL_FILE));
            $this->addElement(new SmartFormTextElement($object, 'url_' . $key));
        }
        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_CAPTION));
        $this->addElement(new SmartFormTextElement($object, 'caption_' . $key));
        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_DESC . '<br>'));
        $this->addElement(new \XoopsFormTextArea('', 'desc_' . $key, $object->getVar('description')));

        if (!$object->isNew()) {
            $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_CHANGE_FILE));
            $this->addElement(new SmartFormFileUploadElement($object, $key));
            $this->addElement(new \XoopsFormLabel('', '<br><br><small>' . _CO_SOBJECT_URL_FILE_DSC . '</small>'));
            $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_URL_FILE));
            $this->addElement(new SmartFormTextElement($object, 'url_' . $key));
        }
    }
}
