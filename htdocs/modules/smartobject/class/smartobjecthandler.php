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

/**
 * Persistable SmartObject Handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of derived class objects as well as some basic operations inherant to objects manipulation
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @credit  Jan Keller Pedersen <mithrandir@xoops.org> - IDG Danmark A/S <www.idg.dk>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartPersistableObjectHandler extends XoopsObjectHandler
{
    public $_itemname;

    /**
     * Name of the table use to store this {@link SmartObject}
     *
     * Note that the name of the table needs to be free of the database prefix.
     * For example "smartsection_categories"
     * @var string
     */
    public $table;

    /**
     * Name of the table key that uniquely identify each {@link SmartObject}
     *
     * For example: "categoryid"
     * @var string
     */
    public $keyName;

    /**
     * Name of the class derived from {@link SmartObject} and which this handler is handling
     *
     * Note that this string needs to be lowercase
     *
     * For example: "smartsectioncategory"
     * @var string
     */
    public $className;

    /**
     * Name of the field which properly identify the {@link SmartObject}
     *
     * For example: "name" (this will be the category's name)
     * @var string
     */
    public $identifierName;

    /**
     * Name of the field which will be use as a summary for the object
     *
     * For example: "summary"
     * @var string
     */
    public $summaryName;

    /**
     * Page name use to basically manage and display the {@link SmartObject}
     *
     * This page needs to be the same in user side and admin side
     *
     * For example category.php - we will deduct smartsection/category.php as well as smartsection/admin/category.php
     * @todo this could probably be automatically deducted from the class name - for example, the class SmartsectionCategory will have "category.php" as it's managing page
     * @var string
     */
    public $_page;

    /**
     * Full path of the module using this {@link SmartObject}
     *
     * <code>XOOPS_URL . "/modules/smartsection/"</code>
     * @todo this could probably be automatically deducted from the class name as it is always prefixed with the module name
     * @var string
     */
    public $_modulePath;

    public $_moduleUrl;

    public $_moduleName;

    public $_uploadUrl;

    public $_uploadPath;

    public $_allowedMimeTypes = 0;

    public $_maxFileSize = 1000000;

    public $_maxWidth = 500;

    public $_maxHeight = 500;

    public $highlightFields = [];

    /**
     * Array containing the events name and functions
     *
     * @var array
     */
    public $eventArray = [];

    /**
     * Array containing the permissions that this handler will manage on the objects
     *
     * @var array
     */
    public $permissionsArray = false;

    public $generalSQL = false;

    public $_eventHooks     = [];
    public $_disabledEvents = [];

    /**
     * Constructor - called from child classes
     *
     * @param XoopsDatabase $db           {@link XoopsDatabase}
     * @param string        $itemname     Name of the class derived from <a href='psi_element://SmartObject'>SmartObject</a> and which this handler is handling and which this handler is handling
     * @param string        $keyname      Name of the table key that uniquely identify each {@link SmartObject}
     * @param string        $idenfierName Name of the field which properly identify the {@link SmartObject}
     * @param string        $summaryName
     * @param string        $modulename
     * @internal param string $tablename Name of the table use to store this <a href='psi_element://SmartObject'>SmartObject</a>
     * @internal param string $page Page name use to basically manage and display the <a href='psi_element://SmartObject'>SmartObject</a>
     * @internal param string $moduleName name of the module
     */
    public function __construct(\XoopsDatabase $db, $itemname, $keyname, $idenfierName, $summaryName, $modulename)
    {
        parent::__construct($db);

        $this->_itemname      = $itemname;
        $this->_moduleName    = $modulename;
        $this->table          = $db->prefix($modulename . '_' . $itemname);
        $this->keyName        = $keyname;
        $this->className      = ucfirst($modulename) . ucfirst($itemname);
        $this->identifierName = $idenfierName;
        $this->summaryName    = $summaryName;
        $this->_page          = $itemname . '.php';
        $this->_modulePath    = XOOPS_ROOT_PATH . '/modules/' . $this->_moduleName . '/';
        $this->_moduleUrl     = XOOPS_URL . '/modules/' . $this->_moduleName . '/';
        $this->_uploadPath    = XOOPS_UPLOAD_PATH . '/' . $this->_moduleName . '/';
        $this->_uploadUrl     = XOOPS_UPLOAD_URL . '/' . $this->_moduleName . '/';
    }

    /**
     * @param $event
     * @param $method
     */
    public function addEventHook($event, $method)
    {
        $this->_eventHooks[$event] = $method;
    }

    /**
     * Add a permission that this handler will manage for its objects
     *
     * Example: $this->addPermission('view', _AM_SSHOP_CAT_PERM_READ, _AM_SSHOP_CAT_PERM_READ_DSC);
     *
     * @param string      $perm_name   name of the permission
     * @param string      $caption     caption of the control that will be displayed in the form
     * @param bool|string $description description of the control that will be displayed in the form
     */
    public function addPermission($perm_name, $caption, $description = false)
    {
        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectpermission.php';

        $this->permissionsArray[] = [
            'perm_name'   => $perm_name,
            'caption'     => $caption,
            'description' => $description
        ];
    }

    /**
     * @param $criteria
     * @param $perm_name
     * @return bool
     */
    public function setGrantedObjectsCriteria(&$criteria, $perm_name)
    {
        $smartPermissionsHandler = new SmartobjectPermissionHandler($this);
        $grantedItems            = $smartPermissionsHandler->getGrantedItems($perm_name);
        if (count($grantedItems) > 0) {
            $criteria->add(new \Criteria($this->keyName, '(' . implode(', ', $grantedItems) . ')', 'IN'));

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $_uploadPath
     * @param bool $_allowedMimeTypes
     * @param bool $_maxFileSize
     * @param bool $_maxWidth
     * @param bool $_maxHeight
     */
    public function setUploaderConfig(
        $_uploadPath = false,
        $_allowedMimeTypes = false,
        $_maxFileSize = false,
        $_maxWidth = false,
        $_maxHeight = false
    ) {
        $this->_uploadPath       = $_uploadPath ?: $this->_uploadPath;
        $this->_allowedMimeTypes = $_allowedMimeTypes ?: $this->_allowedMimeTypes;
        $this->_maxFileSize      = $_maxFileSize ?: $this->_maxFileSize;
        $this->_maxWidth         = $_maxWidth ?: $this->_maxWidth;
        $this->_maxHeight        = $_maxHeight ?: $this->_maxHeight;
    }

    /**
     * create a new {@link SmartObject}
     *
     * @param bool $isNew Flag the new objects as "new"?
     *
     * @return SmartObject {@link SmartObject}
     */
    public function create($isNew = true)
    {
        $obj = new $this->className($this);
        $obj->setImageDir($this->getImageUrl(), $this->getImagePath());
        if (!$obj->handler) {
            $obj->Handler = $this;
        }

        if (true === $isNew) {
            $obj->setNew();
        }

        return $obj;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->_uploadUrl . $this->_itemname . '/';
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        $dir = $this->_uploadPath . $this->_itemname;
        if (!file_exists($dir)) {
            smart_admin_mkdir($dir);
        }

        return $dir . '/';
    }

    /**
     * retrieve a {@link SmartObject}
     *
     * @param  mixed $id        ID of the object - or array of ids for joint keys. Joint keys MUST be given in the same order as in the constructor
     * @param  bool  $as_object whether to return an object or an array
     * @param  bool  $debug
     * @param  bool  $criteria
     * @return mixed reference to the <a href='psi_element://SmartObject'>SmartObject</a>, FALSE if failed
     *                          FALSE if failed
     */
    public function get($id, $as_object = true, $debug = false, $criteria = false)
    {
        if (!$criteria) {
            $criteria = new \CriteriaCompo();
        }
        if (is_array($this->keyName)) {
            for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                /**
                 * In some situations, the $id is not an INTEGER. SmartObjectTag is an example.
                 * Is the fact that we removed the (int)() represents a security risk ?
                 */
                //$criteria->add(new \Criteria($this->keyName[$i], ($id[$i]), '=', $this->_itemname));
                $criteria->add(new \Criteria($this->keyName[$i], $id[$i], '=', $this->_itemname));
            }
        } else {
            //$criteria = new \Criteria($this->keyName, (int)($id), '=', $this->_itemname);
            /**
             * In some situations, the $id is not an INTEGER. SmartObjectTag is an example.
             * Is the fact that we removed the (int)() represents a security risk ?
             */
            $criteria->add(new \Criteria($this->keyName, $id, '=', $this->_itemname));
        }
        $criteria->setLimit(1);
        if ($debug) {
            $obj_array = $this->getObjectsD($criteria, false, $as_object);
        } else {
            $obj_array =& $this->getObjects($criteria, false, $as_object);
            //patch: weird bug of indexing by id even if id_as_key = false;
            if (!isset($obj_array[0]) && is_object($obj_array[$id])) {
                $obj_array[0] = $obj_array[$id];
                unset($obj_array[$id]);
                $obj_array[0]->unsetNew();
            }
        }

        if (1 != count($obj_array)) {
            $obj = $this->create();

            return $obj;
        }

        return $obj_array[0];
    }

    /**
     * retrieve a {@link SmartObject}
     *
     * @param  mixed $id        ID of the object - or array of ids for joint keys. Joint keys MUST be given in the same order as in the constructor
     * @param  bool  $as_object whether to return an object or an array
     * @return mixed reference to the {@link SmartObject}, FALSE if failed
     */
    public function &getD($id, $as_object = true)
    {
        return $this->get($id, $as_object, true);
    }

    /**
     * retrieve objects from the database
     *
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the ID as key for the array?
     * @param bool            $as_object return an array of objects?
     *
     * @param  bool           $sql
     * @param  bool           $debug
     * @return array
     */
    public function getObjects(
        CriteriaElement $criteria = null,
        $id_as_key = false,
        $as_object = true,
        $sql = false,
        $debug = false
    ) {
        $ret   = [];
        $limit = $start = 0;

        if ($this->generalSQL) {
            $sql = $this->generalSQL;
        } elseif (!$sql) {
            $sql = 'SELECT * FROM ' . $this->table . ' AS ' . $this->_itemname;
        }

        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' !== $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        if ($debug) {
            xoops_debug($sql);
        }

        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        return $this->convertResultSet($result, $id_as_key, $as_object);
    }

    /**
     * @param        $sql
     * @param        $criteria
     * @param  bool  $force
     * @param  bool  $debug
     * @return array
     */
    public function query($sql, $criteria, $force = false, $debug = false)
    {
        $ret = [];

        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->groupby) {
                $sql .= $criteria->getGroupby();
            }
            if ('' !== $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
        }
        if ($debug) {
            xoops_debug($sql);
        }

        if ($force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            return $ret;
        }

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $ret[] = $myrow;
        }

        return $ret;
    }

    /**
     * retrieve objects with debug mode - so will show the query
     *
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the ID as key for the array?
     * @param bool            $as_object return an array of objects?
     *
     * @param  bool           $sql
     * @return array
     */
    public function getObjectsD(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true, $sql = false)
    {
        return $this->getObjects($criteria, $id_as_key, $as_object, $sql, true);
    }

    /**
     * @param $arrayObjects
     * @return array|bool
     */
    public function getObjectsAsArray($arrayObjects)
    {
        $ret = [];
        foreach ($arrayObjects as $key => $object) {
            $ret[$key] = $object->toArray();
        }
        if (count($ret > 0)) {
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * Convert a database resultset to a returnable array
     *
     * @param object $result    database resultset
     * @param bool   $id_as_key - should NOT be used with joint keys
     * @param bool   $as_object
     *
     * @return array
     */
    public function convertResultSet($result, $id_as_key = false, $as_object = true)
    {
        $ret = [];
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $obj = $this->create(false);
            $obj->assignVars($myrow);
            if (!$id_as_key) {
                if ($as_object) {
                    $ret[] =& $obj;
                } else {
                    $ret[] = $obj->toArray();
                }
            } else {
                if ($as_object) {
                    $value =& $obj;
                } else {
                    $value = $obj->toArray();
                }
                if ('parentid' === $id_as_key) {
                    $ret[$obj->getVar('parentid', 'e')][$obj->getVar($this->keyName)] =& $value;
                } else {
                    $ret[$obj->getVar($this->keyName)] = $value;
                }
            }
            unset($obj);
        }

        return $ret;
    }

    /**
     * @param  null $criteria
     * @param  int  $limit
     * @param  int  $start
     * @return array
     */
    public function getListD($criteria = null, $limit = 0, $start = 0)
    {
        return $this->getList($criteria, $limit, $start, true);
    }

    /**
     * Retrieve a list of objects as arrays - DON'T USE WITH JOINT KEYS
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} conditions to be met
     * @param int             $limit    Max number of objects to fetch
     * @param int             $start    Which record to start at
     *
     * @param  bool           $debug
     * @return array
     */
    public function getList(CriteriaElement $criteria = null, $limit = 0, $start = 0, $debug = false)
    {
        $ret = [];
        if (null === $criteria) {
            $criteria = new \CriteriaCompo();
        }

        if ('' === $criteria->getSort()) {
            $criteria->setSort($this->getIdentifierName());
        }

        $sql = 'SELECT ' . (is_array($this->keyName) ? implode(', ', $this->keyName) : $this->keyName);
        if (!empty($this->identifierName)) {
            $sql .= ', ' . $this->getIdentifierName();
        }
        $sql .= ' FROM ' . $this->table . ' AS ' . $this->_itemname;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' !== $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }

        if ($debug) {
            xoops_debug($sql);
        }

        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        $myts = \MyTextSanitizer::getInstance();
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            //identifiers should be textboxes, so sanitize them like that
            $ret[$myrow[$this->keyName]] = empty($this->identifierName) ? 1 : $myts->displayTarea($myrow[$this->identifierName]);
        }

        return $ret;
    }

    /**
     * count objects matching a condition
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement} to match
     * @return int             count of objects
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $field   = '';
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if ('' !== $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        /**
         * if we have a generalSQL, lets used this one.
         * This needs to be improved...
         */
        if ($this->generalSQL) {
            $sql = $this->generalSQL;
            $sql = str_replace('SELECT *', 'SELECT COUNT(*)', $sql);
        } else {
            $sql = 'SELECT ' . $field . 'COUNT(*) FROM ' . $this->table . ' AS ' . $this->_itemname;
        }
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' !== $criteria->groupby) {
                $sql .= $criteria->getGroupby();
            }
        }

        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if (false === $groupby) {
            list($count) = $this->db->fetchRow($result);

            return $count;
        } else {
            $ret = [];
            while (false !== (list($id, $count) = $this->db->fetchRow($result))) {
                $ret[$id] = $count;
            }

            return $ret;
        }
    }

    /**
     * delete an object from the database
     *
     * @param  XoopsObject $obj reference to the object to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete(\XoopsObject $obj, $force = false)
    {
        $eventResult = $this->executeEvent('beforeDelete', $obj);
        if (!$eventResult) {
            $obj->setErrors('An error occured during the BeforeDelete event');

            return false;
        }

        if (is_array($this->keyName)) {
            $clause = [];
            for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                $clause[] = $this->keyName[$i] . ' = ' . $obj->getVar($this->keyName[$i]);
            }
            $whereclause = implode(' AND ', $clause);
        } else {
            $whereclause = $this->keyName . ' = ' . $obj->getVar($this->keyName);
        }
        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $whereclause;
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        $eventResult = $this->executeEvent('afterDelete', $obj);
        if (!$eventResult) {
            $obj->setErrors('An error occured during the AfterDelete event');

            return false;
        }

        return true;
    }

    /**
     * @param $event
     */
    public function disableEvent($event)
    {
        if (is_array($event)) {
            foreach ($event as $v) {
                $this->_disabledEvents[] = $v;
            }
        } else {
            $this->_disabledEvents[] = $event;
        }
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissionsArray;
    }

    /**
     * insert a new object in the database
     *
     * @param  XoopsObject $obj         reference to the object
     * @param  bool        $force       whether to force the query execution despite security settings
     * @param  bool        $checkObject check if the object is dirty and clean the attributes
     * @param  bool        $debug
     * @return bool        FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $obj, $force = false, $checkObject = true, $debug = false)
    {
        if (false !== $checkObject) {
            if (!is_object($obj)) {
                return false;
            }
            /**
             * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
             */
            if (!is_a($obj, $this->className)) {
                $obj->setError(get_class($obj) . ' Differs from ' . $this->className);

                return false;
            }
            if (!$obj->isDirty()) {
                $obj->setErrors('Not dirty'); //will usually not be outputted as errors are not displayed when the method returns true, but it can be helpful when troubleshooting code - Mith

                return true;
            }
        }

        if ($obj->seoEnabled) {
            // Auto create meta tags if empty
            $smartobjectMetagen = new SmartMetagen($obj->title(), $obj->getVar('meta_keywords'), $obj->summary());

            if (!$obj->getVar('meta_keywords') || !$obj->getVar('meta_description')) {
                if (!$obj->meta_keywords()) {
                    $obj->setVar('meta_keywords', $smartobjectMetagen->_keywords);
                }

                if (!$obj->meta_description()) {
                    $obj->setVar('meta_description', $smartobjectMetagen->_meta_description);
                }
            }

            // Auto create short_url if empty
            if (!$obj->short_url()) {
                $obj->setVar('short_url', $smartobjectMetagen->generateSeoTitle($obj->title('n'), false));
            }
        }

        $eventResult = $this->executeEvent('beforeSave', $obj);
        if (!$eventResult) {
            $obj->setErrors('An error occured during the BeforeSave event');

            return false;
        }

        if ($obj->isNew()) {
            $eventResult = $this->executeEvent('beforeInsert', $obj);
            if (!$eventResult) {
                $obj->setErrors('An error occured during the BeforeInsert event');

                return false;
            }
        } else {
            $eventResult = $this->executeEvent('beforeUpdate', $obj);
            if (!$eventResult) {
                $obj->setErrors('An error occured during the BeforeUpdate event');

                return false;
            }
        }
        if (!$obj->cleanVars()) {
            $obj->setErrors('Variables were not cleaned properly.');

            return false;
        }
        $fieldsToStoreInDB = [];
        foreach ($obj->cleanVars as $k => $v) {
            if (XOBJ_DTYPE_INT == $obj->vars[$k]['data_type']) {
                $cleanvars[$k] = (int)$v;
            } elseif (is_array($v)) {
                $cleanvars[$k] = $this->db->quoteString(implode(',', $v));
            } else {
                $cleanvars[$k] = $this->db->quoteString($v);
            }
            if ($obj->vars[$k]['persistent']) {
                $fieldsToStoreInDB[$k] = $cleanvars[$k];
            }
        }
        if ($obj->isNew()) {
            if (!is_array($this->keyName)) {
                if ($cleanvars[$this->keyName] < 1) {
                    $cleanvars[$this->keyName] = $this->db->genId($this->table . '_' . $this->keyName . '_seq');
                }
            }

            $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', array_keys($fieldsToStoreInDB)) . ') VALUES (' . implode(',', array_values($fieldsToStoreInDB)) . ')';
        } else {
            $sql = 'UPDATE ' . $this->table . ' SET';
            foreach ($fieldsToStoreInDB as $key => $value) {
                if ((!is_array($this->keyName) && $key == $this->keyName)
                    || (is_array($this->keyName)
                        && in_array($key, $this->keyName))) {
                    continue;
                }
                if (isset($notfirst)) {
                    $sql .= ',';
                }
                $sql      .= ' ' . $key . ' = ' . $value;
                $notfirst = true;
            }
            if (is_array($this->keyName)) {
                $whereclause = '';
                for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                    if ($i > 0) {
                        $whereclause .= ' AND ';
                    }
                    $whereclause .= $this->keyName[$i] . ' = ' . $obj->getVar($this->keyName[$i]);
                }
            } else {
                $whereclause = $this->keyName . ' = ' . $obj->getVar($this->keyName);
            }
            $sql .= ' WHERE ' . $whereclause;
        }

        if ($debug) {
            xoops_debug($sql);
        }

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            $obj->setErrors($this->db->error());

            return false;
        }

        if ($obj->isNew() && !is_array($this->keyName)) {
            $obj->assignVar($this->keyName, $this->db->getInsertId());
        }
        $eventResult = $this->executeEvent('afterSave', $obj);
        if (!$eventResult) {
            $obj->setErrors('An error occured during the AfterSave event');

            return false;
        }

        if ($obj->isNew()) {
            $obj->unsetNew();
            $eventResult = $this->executeEvent('afterInsert', $obj);
            if (!$eventResult) {
                $obj->setErrors('An error occured during the AfterInsert event');

                return false;
            }
        } else {
            $eventResult = $this->executeEvent('afterUpdate', $obj);
            if (!$eventResult) {
                $obj->setErrors('An error occured during the AfterUpdate event');

                return false;
            }
        }

        return true;
    }

    /**
     * @param       $obj
     * @param  bool $force
     * @param  bool $checkObject
     * @param  bool $debug
     * @return bool
     */
    public function insertD($obj, $force = false, $checkObject = true, $debug = false)
    {
        return $this->insert($obj, $force, $checkObject, true);
    }

    /**
     * Change a value for objects with a certain criteria
     *
     * @param string          $fieldname  Name of the field
     * @param string          $fieldvalue Value to write
     * @param CriteriaElement $criteria   {@link CriteriaElement}
     *
     * @param  bool           $force
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, CriteriaElement $criteria = null, $force = false)
    {
        $set_clause = $fieldname . ' = ';
        if (is_numeric($fieldvalue)) {
            $set_clause .= $fieldvalue;
        } elseif (is_array($fieldvalue)) {
            $set_clause .= $this->db->quoteString(implode(',', $fieldvalue));
        } else {
            $set_clause .= $this->db->quoteString($fieldvalue);
        }
        $sql = 'UPDATE ' . $this->table . ' SET ' . $set_clause;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * delete all objects meeting the conditions
     *
     * @param  CriteriaElement $criteria {@link CriteriaElement} with conditions to meet
     * @return bool
     */

    public function deleteAll(CriteriaElement $criteria = null)
    {
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql = 'DELETE FROM ' . $this->table;
            $sql .= ' ' . $criteria->renderWhere();
            if (!$this->db->query($sql)) {
                return false;
            }
            $rows = $this->db->getAffectedRows();

            return $rows > 0 ? $rows : true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getModuleInfo()
    {
        return smart_getModuleInfo($this->_moduleName);
    }

    /**
     * @return bool
     */
    public function getModuleConfig()
    {
        return smart_getModuleConfig($this->_moduleName);
    }

    /**
     * @return string
     */
    public function getModuleItemString()
    {
        $ret = $this->_moduleName . '_' . $this->_itemname;

        return $ret;
    }

    /**
     * @param $object
     */
    public function updateCounter($object)
    {
        if (isset($object->vars['counter'])) {
            $new_counter = $object->getVar('counter') + 1;
            $sql         = 'UPDATE ' . $this->table . ' SET counter=' . $new_counter . ' WHERE ' . $this->keyName . '=' . $object->id();
            $this->query($sql, null, true);
        }
    }

    /**
     * Execute the function associated with an event
     * This method will check if the function is available
     *
     * @param  string $event name of the event
     * @param         $executeEventObj
     * @return mixed  result of the execution of the function or FALSE if the function was not executed
     * @internal param object $obj $object on which is performed the event
     */
    public function executeEvent($event, &$executeEventObj)
    {
        if (!in_array($event, $this->_disabledEvents)) {
            if (method_exists($this, $event)) {
                $ret = $this->$event($executeEventObj);
            } else {
                // check to see if there is a hook for this event
                if (isset($this->_eventHooks[$event])) {
                    $method = $this->_eventHooks[$event];
                    // check to see if the method specified by this hook exists
                    if (method_exists($this, $method)) {
                        $ret = $this->$method($executeEventObj);
                    }
                }
                $ret = true;
            }

            return $ret;
        }

        return true;
    }

    /**
     * @param  bool $withprefix
     * @return string
     */
    public function getIdentifierName($withprefix = true)
    {
        if ($withprefix) {
            return $this->_itemname . '.' . $this->identifierName;
        } else {
            return $this->identifierName;
        }
    }
}
