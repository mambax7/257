<?php

/**
 * Contains the basic classe for managing a category object based on SmartObject
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectItems
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartseoobject.php';

/**
 * Class SmartobjectCategory
 */
class SmartobjectCategory extends SmartSeoObject
{
    public $_categoryPath;

    /**
     * SmartobjectCategory constructor.
     */
    public function __construct()
    {
        $this->initVar('categoryid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('parentid', XOBJ_DTYPE_INT, '', false, null, '', false, _CO_SOBJECT_CATEGORY_PARENTID, _CO_SOBJECT_CATEGORY_PARENTID_DSC);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', false, null, '', false, _CO_SOBJECT_CATEGORY_NAME, _CO_SOBJECT_CATEGORY_NAME_DSC);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', false, null, '', false, _CO_SOBJECT_CATEGORY_DESCRIPTION, _CO_SOBJECT_CATEGORY_DESCRIPTION_DSC);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, '', false, null, '', false, _CO_SOBJECT_CATEGORY_IMAGE, _CO_SOBJECT_CATEGORY_IMAGE_DSC);

        $this->initCommonVar('doxcode');

        $this->setControl('image', ['name' => 'image']);
        $this->setControl('parentid', ['name' => 'parentcategory']);
        $this->setControl('description', [
            'name'        => 'textarea',
            'itemHandler' => false,
            'method'      => false,
            'module'      => false,
            'form_editor' => 'default'
        ]);

        // call parent constructor to get SEO fields initiated
        parent::__construct();
    }

    /**
     * returns a specific variable for the object in a proper format
     *
     * @access public
     * @param  string $key    key of the object's variable to be returned
     * @param  string $format format to use for the output
     * @return mixed  formatted value of the variable
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, ['description', 'image'])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->getValueFor('description', false);
    }

    /**
     * @return bool|mixed
     */
    public function image()
    {
        $ret = $this->getVar('image', 'e');
        if ('-1' == $ret) {
            return false;
        } else {
            return $ret;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $this->setVar('doxcode', true);
        global $myts;
        $objectArray = parent::toArray();
        if ($objectArray['image']) {
            $objectArray['image'] = $this->getImageDir() . $objectArray['image'];
        }

        return $objectArray;
    }

    /**
     * Create the complete path of a category
     *
     * @todo this could be improved as it uses multiple queries
     * @param  bool $withAllLink make all name clickable
     * @param  bool $currentCategory
     * @return string complete path (breadcrumb)
     */
    public function getCategoryPath($withAllLink = true, $currentCategory = false)
    {
        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectcontroller.php';
        $controller = new SmartObjectController($this->handler);

        if (!$this->_categoryPath) {
            if ($withAllLink && !$currentCategory) {
                $ret = $controller->getItemLink($this);
            } else {
                $currentCategory = false;
                $ret             = $this->getVar('name');
            }
            $parentid = $this->getVar('parentid');
            if (0 != $parentid) {
                $parentObj = $this->handler->get($parentid);
                if ($parentObj->isNew()) {
                    exit;
                }
                $parentid = $parentObj->getVar('parentid');
                $ret      = $parentObj->getCategoryPath($withAllLink, $currentCategory) . ' > ' . $ret;
            }
            $this->_categoryPath = $ret;
        }

        return $this->_categoryPath;
    }
}

/**
 * Class SmartobjectCategoryHandler
 */
class SmartobjectCategoryHandler extends SmartPersistableObjectHandler
{
    public $allCategoriesObj = false;
    public $_allCategoriesId = false;

    /**
     * SmartobjectCategoryHandler constructor.
     * @param XoopsDatabase        $db
     * @param                      $modulename
     */
    public function __construct(\XoopsDatabase $db, $modulename)
    {
        parent::__construct($db, 'category', 'categoryid', 'name', 'description', $modulename);
    }

    /**
     * @param  int    $parentid
     * @param  bool   $perm_name
     * @param  string $sort
     * @param  string $order
     * @return array|bool
     */
    public function getAllCategoriesArray($parentid = 0, $perm_name = false, $sort = 'parentid', $order = 'ASC')
    {
        if (!$this->allCategoriesObj) {
            $criteria = new \CriteriaCompo();
            $criteria->setSort($sort);
            $criteria->setOrder($order);
            global $xoopsUser;
            $userIsAdmin = is_object($xoopsUser) && $xoopsUser->isAdmin();

            if ($perm_name && !$userIsAdmin) {
                if (!$this->setGrantedObjectsCriteria($criteria, $perm_name)) {
                    return false;
                }
            }

            $this->allCategoriesObj =& $this->getObjects($criteria, 'parentid');
        }

        $ret = [];
        if (isset($this->allCategoriesObj[$parentid])) {
            foreach ($this->allCategoriesObj[$parentid] as $categoryid => $categoryObj) {
                $ret[$categoryid]['self'] = $categoryObj->toArray();
                if (isset($this->allCategoriesObj[$categoryid])) {
                    $ret[$categoryid]['sub']          = $this->getAllCategoriesArray($categoryid);
                    $ret[$categoryid]['subcatscount'] = count($ret[$categoryid]['sub']);
                }
            }
        }

        return $ret;
    }

    /**
     * @param               $parentid
     * @param  bool         $asString
     * @return array|string
     */
    public function getParentIds($parentid, $asString = true)
    {
        if (!$this->allCategoriesId) {
            $ret = [];
            $sql = 'SELECT categoryid, parentid FROM ' . $this->table . ' AS ' . $this->_itemname . ' ORDER BY parentid';

            $result = $this->db->query($sql);

            if (!$result) {
                return $ret;
            }

            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $this->allCategoriesId[$myrow['categoryid']] = $myrow['parentid'];
            }
        }

        $retArray = [$parentid];
        while (0 != $parentid) {
            $parentid = $this->allCategoriesId[$parentid];
            if (0 != $parentid) {
                $retArray[] = $parentid;
            }
        }
        if ($asString) {
            return implode(', ', $retArray);
        } else {
            return $retArray;
        }
    }
}
