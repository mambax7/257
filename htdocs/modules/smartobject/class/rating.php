<?php
//
// ------------------------------------------------------------------------ //
//               XOOPS - PHP Content Management System                      //
//                   Copyright (c) 2000-2016 XOOPS.org                           //
//                      <https://xoops.org>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: https://xoops.org/                                               //
// Project: XOOPS Project                                               //
// -------------------------------------------------------------------------//

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartplugins.php';

/**
 * Class SmartobjectRating
 */
class SmartobjectRating extends SmartObject
{
    public $_modulePlugin = false;

    /**
     * SmartobjectRating constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('ratingid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_RATING_DIRNAME);
        $this->quickInitVar('item', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_RATING_ITEM);
        $this->quickInitVar('itemid', XOBJ_DTYPE_INT, true, _CO_SOBJECT_RATING_ITEMID);
        $this->quickInitVar('uid', XOBJ_DTYPE_INT, true, _CO_SOBJECT_RATING_UID);
        $this->quickInitVar('date', XOBJ_DTYPE_LTIME, true, _CO_SOBJECT_RATING_DATE);
        $this->quickInitVar('rate', XOBJ_DTYPE_INT, true, _CO_SOBJECT_RATING_RATE);

        $this->initNonPersistableVar('name', XOBJ_DTYPE_TXTBOX, 'user', _CO_SOBJECT_RATING_NAME);

        $this->setControl('dirname', [
            'handler'  => 'rating',
            'method'   => 'getModuleList',
            'onSelect' => 'submit'
        ]);

        $this->setControl('item', [
            'object' => &$this,
            'method' => 'getItemList'
        ]);

        $this->setControl('uid', 'user');

        $this->setControl('rate', [
            'handler' => 'rating',
            'method'  => 'getRateList'
        ]);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, ['name', 'dirname'])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return string
     */
    public function name()
    {
        $ret = smart_getLinkedUnameFromId($this->getVar('uid', 'e'), true, []);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function dirname()
    {
        global $smartobjectRatingHandler;
        $moduleArray = $smartobjectRatingHandler->getModuleList();

        return $moduleArray[$this->getVar('dirname', 'n')];
    }

    /**
     * @return mixed
     */
    public function getItemList()
    {
        $plugin = $this->getModulePlugin();

        return $plugin->getItemList();
    }

    /**
     * @return string
     */
    public function getItemValue()
    {
        $moduleUrl      = XOOPS_URL . '/modules/' . $this->getVar('dirname', 'n') . '/';
        $plugin         = $this->getModulePlugin();
        $pluginItemInfo = $plugin->getItemInfo($this->getVar('item'));
        if (!$pluginItemInfo) {
            return '';
        }
        $itemPath = sprintf($pluginItemInfo['url'], $this->getVar('itemid'));
        $ret      = '<a href="' . $moduleUrl . $itemPath . '">' . $pluginItemInfo['caption'] . '</a>';

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getRateValue()
    {
        return $this->getVar('rate');
    }

    /**
     * @return bool
     */
    public function getModulePlugin()
    {
        if (!$this->_modulePlugin) {
            global $smartobjectRatingHandler;
            $this->_modulePlugin = $smartobjectRatingHandler->pluginsObject->getPlugin($this->getVar('dirname', 'n'));
        }

        return $this->_modulePlugin;
    }
}

/**
 * Class SmartobjectRatingHandler
 */
class SmartobjectRatingHandler extends SmartPersistableObjectHandler
{
    public $_rateOptions = [];
    public $_moduleList  = false;
    public $pluginsObject;

    /**
     * SmartobjectRatingHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'rating', 'ratingid', 'rate', '', 'smartobject');
        $this->generalSQL = 'SELECT * FROM ' . $this->table . ' AS ' . $this->_itemname . ' INNER JOIN ' . $this->db->prefix('users') . ' AS user ON ' . $this->_itemname . '.uid=user.uid';

        $this->_rateOptions[1] = 1;
        $this->_rateOptions[2] = 2;
        $this->_rateOptions[3] = 3;
        $this->_rateOptions[4] = 4;
        $this->_rateOptions[5] = 5;

        $this->pluginsObject = new SmartPluginHandler();
    }

    /**
     * @return bool
     */
    public function getModuleList()
    {
        if (!$this->_moduleList) {
            $moduleArray          = $this->pluginsObject->getPluginsArray();
            $this->_moduleList[0] = _CO_SOBJECT_MAKE_SELECTION;
            foreach ($moduleArray as $k => $v) {
                $this->_moduleList[$k] = $v;
            }
        }

        return $this->_moduleList;
    }

    /**
     * @return array
     */
    public function getRateList()
    {
        return $this->_rateOptions;
    }

    /**
     * @param $itemid
     * @param $dirname
     * @param $item
     * @return int
     */
    public function getRatingAverageByItemId($itemid, $dirname, $item)
    {
        $sql    = 'SELECT AVG(rate), COUNT(ratingid) FROM ' . $this->table . " WHERE itemid=$itemid AND dirname='$dirname' AND item='$item' GROUP BY itemid";
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($average, $sum) = $this->db->fetchRow($result);
        $ret['average'] = isset($average) ? $average : 0;
        $ret['sum']     = isset($sum) ? $sum : 0;

        return $ret;
    }

    /**
     * @param $item
     * @param $itemid
     * @param $dirname
     * @param $uid
     * @return bool
     */
    public function already_rated($item, $itemid, $dirname, $uid)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('item', $item));
        $criteria->add(new \Criteria('itemid', $itemid));
        $criteria->add(new \Criteria('dirname', $dirname));
        $criteria->add(new \Criteria('user.uid', $uid));

        $ret =& $this->getObjects($criteria);

        if (!$ret) {
            return false;
        } else {
            return $ret[0];
        }
    }
}
