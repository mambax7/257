<?php

/**
 * Contains the basis classes for managing any objects derived from SmartObjects
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectCore
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';

/**
 * SmartObject base Multilanguage-enabled class
 *
 * Base class representing a single SmartObject with multilanguages capabilities
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartMlObject extends SmartObject
{
    /**
     * SmartMlObject constructor.
     */
    public function __construct()
    {
        $this->initVar('language', XOBJ_DTYPE_TXTBOX, 'english', false, null, '', true, _CO_SOBJECT_LANGUAGE_CAPTION, _CO_SOBJECT_LANGUAGE_DSC, true, true);
        $this->setControl('language', 'language');
    }

    /**
     * If object is not new, change the control of the not-multilanguage fields
     *
     * We need to intercept this function from SmartObject because if the object is not new...
     */
    // function getForm() {

    //}

    /**
     * Strip Multilanguage Fields
     *
     * Get rid of all the multilanguage fields to have an object with only global fields.
     * This will be usefull when creating the ML object for the first time. Then we will be able
     * to create translations.
     */
    public function stripMultilanguageFields()
    {
        $objectVars    =& $this->getVars();
        $newObjectVars = [];
        foreach ($objectVars as $key => $var) {
            if (!$var['multilingual']) {
                $newObjectVars[$key] = $var;
            }
        }
        $this->vars = $newObjectVars;
    }

    public function stripNonMultilanguageFields()
    {
        $objectVars    =& $this->getVars();
        $newObjectVars = [];
        foreach ($objectVars as $key => $var) {
            if ($var['multilingual'] || $key == $this->handler->keyName) {
                $newObjectVars[$key] = $var;
            }
        }
        $this->vars = $newObjectVars;
    }

    /**
     * Make non multilanguage fields read only
     *
     * This is used when we are creating/editing a translation.
     * We only want to edit the multilanguag fields, not the global one.
     */
    public function makeNonMLFieldReadOnly()
    {
        foreach ($this->getVars() as $key => $var) {
            //if (($key == 'language') || (!$var['multilingual'] && $key <> $this->handler->keyName)) {
            if (!$var['multilingual'] && $key != $this->handler->keyName) {
                $this->setControl($key, 'label');
            }
        }
    }

    /**
     * @param  bool $onlyUrl
     * @param  bool $withimage
     * @return string
     */
    public function getEditLanguageLink($onlyUrl = false, $withimage = true)
    {
        $controller = new SmartObjectController($this->handler);

        return $controller->getEditLanguageLink($this, $onlyUrl, $withimage);
    }
}

/**
 * Class SmartPersistableMlObjectHandler
 */
class SmartPersistableMlObjectHandler extends SmartPersistableObjectHandler
{
    /**
     * @param  null $criteria
     * @param  bool $id_as_key
     * @param  bool $as_object
     * @param  bool $debug
     * @param  bool $language
     * @return array
     */
    public function getObjects(
        $criteria = null,
        $id_as_key = false,
        $as_object = true,
        $debug = false,
        $language = false
    ) {
        // Create the first part of the SQL query to join the "_text" table
        $sql = 'SELECT * FROM ' . $this->table . ' AS ' . $this->_itemname . ' INNER JOIN ' . $this->table . '_text AS ' . $this->_itemname . '_text ON ' . $this->_itemname . '.' . $this->keyName . '=' . $this->_itemname . '_text.' . $this->keyName;

        if ($language) {
            // If a language was specified, then let's create a WHERE clause to only return the objects associated with this language

            // if no criteria was previously created, let's create it
            if (!$criteria) {
                $criteria = new \CriteriaCompo();
            }
            $criteria->add(new \Criteria('language', $language));

            return parent::getObjects($criteria, $id_as_key, $as_object, $debug, $sql);
        }

        return parent::getObjects($criteria, $id_as_key, $as_object, $debug, $sql);
    }

    /**
     * @param  mixed $id
     * @param  bool  $language
     * @param  bool  $as_object
     * @param  bool  $debug
     * @return mixed
     */
    public function &get($id, $language = false, $as_object = true, $debug = false)
    {
        if (!$language) {
            return parent::get($id, $as_object, $debug);
        } else {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('language', $language));

            return parent::get($id, $as_object, $debug, $criteria);
        }
    }

    public function changeTableNameForML()
    {
        $this->table = $this->db->prefix($this->_moduleName . '_' . $this->_itemname . '_text');
    }
}
