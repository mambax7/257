<?php

/**
 * Contains the SmartObjectControl class
 *
 * @license GNU
 * @author marcan <marcan@smartfactory.ca>
 * @version $Id: smartformimageelement.php 799 2008-02-04 22:14:27Z malanciault $
 * @link http://smartfactory.ca The SmartFactory
 * @package SmartObject
 * @subpackage SmartObjectForm
 */
class SmartFormImageElement extends XoopsFormElementTray {
    function SmartFormImageElement($object, $key) {
        $var = $object->vars[$key];
        $object_imageurl = $object->getImageDir();
        $this->XoopsFormElementTray( $var['form_caption'], ' ' );


        $objectArray['image'] = str_replace('{XOOPS_URL}', XOOPS_URL, $objectArray['image']);


        if($object->getVar($key) != '' && (substr($object->getVar($key), 0, 4) == 'http' || substr($object->getVar($key), 0, 11) == '{XOOPS_URL}')){
            $this->addElement( new XoopsFormLabel( '', "<img src='" . str_replace('{XOOPS_URL}', XOOPS_URL, $object->getVar($key)) . "' alt='' /><br/><br/>" ) );
        }elseif($object->getVar($key) != ''){
            $this->addElement( new XoopsFormLabel( '', "<img src='" . $object_imageurl . $object->getVar($key) . "' alt='' /><br/><br/>" ) );
        }

        include_once SMARTOBJECT_ROOT_PATH."class/form/elements/smartformfileuploadelement.php";
        $this->addElement(new SmartFormFileUploadElement($object, $key));

        $this->addElement(new XoopsFormLabel( '<div style="height: 10px; padding-top: 8px; font-size: 80%;">'._CO_SOBJECT_URL_FILE_DSC.'</div>', ''));
        include_once SMARTOBJECT_ROOT_PATH."class/form/elements/smartformtextelement.php";
        include_once SMARTOBJECT_ROOT_PATH."class/form/elements/smartformcheckelement.php";

        $this->addElement(new XoopsFormLabel( '', '<br />' . _CO_SOBJECT_URL_FILE));
        $this->addElement(new SmartFormTextElement($object, 'url_'.$key));
        $this->addElement(new XoopsFormLabel( '', '<br /><br />'));
        $delete_check = new SmartFormCheckElement('', 'delete_'.$key);
        $delete_check->addOption(1, '<span style="color:red;">'._CO_SOBJECT_DELETE.'</span>');
        $this->addElement($delete_check);
    }
}
?>