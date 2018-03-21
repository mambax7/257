<?php

/**
 * Contains the classes responsible for displaying a simple table filled with records of SmartObjects
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectTable
 */

/**
 * SmartObjectColumn class
 *
 * Class representing a single column of a SmartObjectTable
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartObjectColumn
{
    public $_keyname;
    public $_align;
    public $_width;
    public $_customMethodForValue;
    public $_extraParams;
    public $_sortable;
    public $_customCaption;

    /**
     * SmartObjectColumn constructor.
     * @param        $keyname
     * @param string $align
     * @param bool   $width
     * @param bool   $customMethodForValue
     * @param bool   $param
     * @param bool   $customCaption
     * @param bool   $sortable
     */
    public function __construct(
        $keyname,
        $align = 'left',
        $width = false,
        $customMethodForValue = false,
        $param = false,
        $customCaption = false,
        $sortable = true
    ) {
        $this->_keyname              = $keyname;
        $this->_align                = $align;
        $this->_width                = $width;
        $this->_customMethodForValue = $customMethodForValue;
        $this->_sortable             = $sortable;
        $this->_param                = $param;
        $this->_customCaption        = $customCaption;
    }

    public function getKeyName()
    {
        return $this->_keyname;
    }

    /**
     * @return string
     */
    public function getAlign()
    {
        return $this->_align;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->_sortable;
    }

    /**
     * @return bool|string
     */
    public function getWidth()
    {
        if ($this->_width) {
            $ret = $this->_width;
        } else {
            $ret = '';
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function getCustomCaption()
    {
        return $this->_customCaption;
    }
}

/**
 * SmartObjectTable base class
 *
 * Base class representing a table for displaying SmartObjects
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartObjectTable
{
    public $_id;
    public $_objectHandler;
    public $_columns;
    public $_criteria;
    public $_actions;
    public $_objects = false;
    public $_aObjects;
    public $_custom_actions;
    public $_sortsel;
    public $_ordersel;
    public $_limitsel;
    public $_filtersel;
    public $_filterseloptions;
    public $_filtersel2;
    public $_filtersel2options;
    public $_filtersel2optionsDefault;

    public $_tempObject;
    public $_tpl;
    public $_introButtons;
    public $_quickSearch            = false;
    public $_actionButtons          = false;
    public $_head_css_class         = 'bg3';
    public $_hasActions             = false;
    public $_userSide               = false;
    public $_printerFriendlyPage    = false;
    public $_tableHeader            = false;
    public $_tableFooter            = false;
    public $_showActionsColumnTitle = true;
    public $_isTree                 = false;
    public $_showFilterAndLimit     = true;
    public $_enableColumnsSorting   = true;
    public $_customTemplate         = false;
    public $_withSelectedActions    = [];

    /**
     * Constructor
     *
     * @param SmartPersistableObjectHandler $objectHandler {@link SmartPersistableObjectHandler}
     * @param CriteriaElement               $criteria
     * @param array                         $actions       array representing the actions to offer
     *
     * @param bool                          $userSide
     */
    public function __construct(
        SmartPersistableObjectHandler $objectHandler,
        CriteriaElement $criteria = null,
        $actions = ['edit', 'delete'],
        $userSide = false
    ) {
        $this->_id            = $objectHandler->className;
        $this->_objectHandler = $objectHandler;

        if (!$criteria) {
            $criteria = new \CriteriaCompo();
        }
        $this->_criteria       = $criteria;
        $this->_actions        = $actions;
        $this->_custom_actions = [];
        $this->_userSide       = $userSide;
        if ($userSide) {
            $this->_head_css_class = 'head';
        }
    }

    /**
     * @param      $op
     * @param bool $caption
     * @param bool $text
     */
    public function addActionButton($op, $caption = false, $text = false)
    {
        $action                 = [
            'op'      => $op,
            'caption' => $caption,
            'text'    => $text
        ];
        $this->_actionButtons[] = $action;
    }

    /**
     * @param $columnObj
     */
    public function addColumn($columnObj)
    {
        $this->_columns[] = $columnObj;
    }

    /**
     * @param $name
     * @param $location
     * @param $value
     */
    public function addIntroButton($name, $location, $value)
    {
        $introButton             = [];
        $introButton['name']     = $name;
        $introButton['location'] = $location;
        $introButton['value']    = $value;
        $this->_introButtons[]   = $introButton;
        unset($introButton);
    }

    public function addPrinterFriendlyLink()
    {
        $current_urls               = smart_getCurrentUrls();
        $current_url                = $current_urls['full'];
        $this->_printerFriendlyPage = $current_url . '&print';
    }

    /**
     * @param        $fields
     * @param string $caption
     */
    public function addQuickSearch($fields, $caption = _CO_SOBJECT_QUICK_SEARCH)
    {
        $this->_quickSearch = ['fields' => $fields, 'caption' => $caption];
    }

    /**
     * @param $content
     */
    public function addHeader($content)
    {
        $this->_tableHeader = $content;
    }

    /**
     * @param $content
     */
    public function addFooter($content)
    {
        $this->_tableFooter = $content;
    }

    /**
     * @param $caption
     */
    public function addDefaultIntroButton($caption)
    {
        $this->addIntroButton($this->_objectHandler->_itemname, $this->_objectHandler->_page . '?op=mod', $caption);
    }

    /**
     * @param $method
     */
    public function addCustomAction($method)
    {
        $this->_custom_actions[] = $method;
    }

    /**
     * @param $default_sort
     */
    public function setDefaultSort($default_sort)
    {
        $this->_sortsel = $default_sort;
    }

    /**
     * @return string
     */
    public function getDefaultSort()
    {
        if ($this->_sortsel) {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_sortsel', $this->_sortsel);
        } else {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_sortsel', $this->_objectHandler->identifierName);
        }
    }

    /**
     * @param $default_order
     */
    public function setDefaultOrder($default_order)
    {
        $this->_ordersel = $default_order;
    }

    /**
     * @return string
     */
    public function getDefaultOrder()
    {
        if ($this->_ordersel) {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_ordersel', $this->_ordersel);
        } else {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_ordersel', 'ASC');
        }
    }

    /**
     * @param array $actions
     */
    public function addWithSelectedActions($actions = [])
    {
        $this->addColumn(new SmartObjectColumn('checked', 'center', 20, false, false, '&nbsp;'));
        $this->_withSelectedActions = $actions;
    }

    /**
     * Adding a filter in the table
     *
     * @param string $key    key to the field that will be used for sorting
     * @param string $method method of the handler that will be called to populate the options when this filter is selected
     * @param bool   $default
     */
    public function addFilter($key, $method, $default = false)
    {
        $this->_filterseloptions[$key]   = $method;
        $this->_filtersel2optionsDefault = $default;
    }

    /**
     * @param $default_filter
     */
    public function setDefaultFilter($default_filter)
    {
        $this->_filtersel = $default_filter;
    }

    public function isForUserSide()
    {
        $this->_userSide = true;
    }

    /**
     * @param $template
     */
    public function setCustomTemplate($template)
    {
        $this->_customTemplate = $template;
    }

    public function setSortOrder()
    {
        $this->_sortsel = isset($_GET[$this->_objectHandler->_itemname . '_' . 'sortsel']) ? $_GET[$this->_objectHandler->_itemname . '_' . 'sortsel'] : $this->getDefaultSort();
        //$this->_sortsel = isset($_POST['sortsel']) ? $_POST['sortsel']: $this->_sortsel;
        smart_setCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_sortsel', $this->_sortsel);
        $fieldsForSorting = $this->_tempObject->getFieldsForSorting($this->_sortsel);

        if (isset($this->_tempObject->vars[$this->_sortsel]['itemName'])
            && $this->_tempObject->vars[$this->_sortsel]['itemName']) {
            $this->_criteria->setSort($this->_tempObject->vars[$this->_sortsel]['itemName'] . '.' . $this->_sortsel);
        } else {
            $this->_criteria->setSort($this->_objectHandler->_itemname . '.' . $this->_sortsel);
        }

        $this->_ordersel = isset($_GET[$this->_objectHandler->_itemname . '_' . 'ordersel']) ? $_GET[$this->_objectHandler->_itemname . '_' . 'ordersel'] : $this->getDefaultOrder();
        //$this->_ordersel = isset($_POST['ordersel']) ? $_POST['ordersel']:$this->_ordersel;
        smart_setCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_ordersel', $this->_ordersel);
        $ordersArray = $this->getOrdersArray();
        $this->_criteria->setOrder($this->_ordersel);
    }

    /**
     * @param $id
     */
    public function setTableId($id)
    {
        $this->_id = $id;
    }

    /**
     * @param $objects
     */
    public function setObjects($objects)
    {
        $this->_objects = $objects;
    }

    public function createTableRows()
    {
        $this->_aObjects = [];

        $doWeHaveActions = false;

        $objectclass = 'odd';
        if (count($this->_objects) > 0) {
            foreach ($this->_objects as $object) {
                $aObject = [];

                $i = 0;

                $aColumns = [];

                foreach ($this->_columns as $column) {
                    $aColumn = [];

                    if (0 == $i) {
                        $class = 'head';
                    } elseif (0 == $i % 2) {
                        $class = 'even';
                    } else {
                        $class = 'odd';
                    }
                    if (method_exists($object, 'initiateCustomFields')) {
                        //$object->initiateCustomFields();
                    }
                    if ('checked' === $column->_keyname) {
                        $value = '<input type ="checkbox" name="selected_smartobjects[]" value="' . $object->id() . '">';
                    } elseif ($column->_customMethodForValue
                              && method_exists($object, $column->_customMethodForValue)) {
                        $method = $column->_customMethodForValue;
                        if ($column->_param) {
                            $value = $object->$method($column->_param);
                        } else {
                            $value = $object->$method();
                        }
                    } else {
                        /**
                         * If the column is the identifier, then put a link on it
                         */
                        if ($column->getKeyName() == $this->_objectHandler->identifierName) {
                            $value = $object->getItemLink();
                        } else {
                            $value = $object->getVar($column->getKeyName());
                        }
                    }

                    $aColumn['value'] = $value;
                    $aColumn['class'] = $class;
                    $aColumn['width'] = $column->getWidth();
                    $aColumn['align'] = $column->getAlign();

                    $aColumns[] = $aColumn;
                    ++$i;
                }

                $aObject['columns'] = $aColumns;
                $aObject['id']      = $object->id();

                $objectclass = ('even' === $objectclass) ? 'odd' : 'even';

                $aObject['class'] = $objectclass;

                $actions = [];

                // Adding the custom actions if any
                foreach ($this->_custom_actions as $action) {
                    if (method_exists($object, $action)) {
                        $actions[] = $object->$action();
                    }
                }

                require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectcontroller.php';
                $controller = new SmartObjectController($this->_objectHandler);

                if ((!is_array($this->_actions)) || in_array('edit', $this->_actions)) {
                    $actions[] = $controller->getEditItemLink($object, false, true, $this->_userSide);
                }
                if ((!is_array($this->_actions)) || in_array('delete', $this->_actions)) {
                    $actions[] = $controller->getDeleteItemLink($object, false, true, $this->_userSide);
                }
                $aObject['actions'] = $actions;

                $this->_tpl->assign('smartobject_actions_column_width', count($actions) * 30);

                $doWeHaveActions = $doWeHaveActions ? true : count($actions) > 0;

                $this->_aObjects[] = $aObject;
            }
            $this->_tpl->assign('smartobject_objects', $this->_aObjects);
        } else {
            $colspan = count($this->_columns) + 1;
            $this->_tpl->assign('smartobject_colspan', $colspan);
        }
        $this->_hasActions = $doWeHaveActions;
    }

    /**
     * @param  bool $debug
     * @return mixed
     */
    public function fetchObjects($debug = false)
    {
        return $this->_objectHandler->getObjects($this->_criteria, true, true, false, $debug);
    }

    /**
     * @return string
     */
    public function getDefaultFilter()
    {
        if ($this->_filtersel) {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_filtersel', $this->_filtersel);
        } else {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_filtersel', 'default');
        }
    }

    /**
     * @return array|bool
     */
    public function getFiltersArray()
    {
        $ret               = [];
        $field             = [];
        $field['caption']  = _CO_OBJ_NONE;
        $field['selected'] = '';
        $ret['default']    = $field;
        unset($field);

        if ($this->_filterseloptions) {
            foreach ($this->_filterseloptions as $key => $value) {
                $field = [];
                if (is_array($value)) {
                    $field['caption']  = $key;
                    $field['selected'] = $this->_filtersel == $key ? 'selected' : '';
                } else {
                    $field['caption']  = $this->_tempObject->vars[$key]['form_caption'];
                    $field['selected'] = $this->_filtersel == $key ? 'selected' : '';
                }
                $ret[$key] = $field;
                unset($field);
            }
        } else {
            $ret = false;
        }

        return $ret;
    }

    /**
     * @param $default_filter2
     */
    public function setDefaultFilter2($default_filter2)
    {
        $this->_filtersel2 = $default_filter2;
    }

    /**
     * @return string
     */
    public function getDefaultFilter2()
    {
        if ($this->_filtersel2) {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_filtersel2', $this->_filtersel2);
        } else {
            return smart_getCookieVar($_SERVER['PHP_SELF'] . '_filtersel2', 'default');
        }
    }

    /**
     * @return array
     */
    public function getFilters2Array()
    {
        $ret = [];

        foreach ($this->_filtersel2options as $key => $value) {
            $field             = [];
            $field['caption']  = $value;
            $field['selected'] = $this->_filtersel2 == $key ? 'selected' : '';
            $ret[$key]         = $field;
            unset($field);
        }

        return $ret;
    }

    /**
     * @param $limitsArray
     * @param $params_of_the_options_sel
     */
    public function renderOptionSelection($limitsArray, $params_of_the_options_sel)
    {
        // Rendering the form to select options on the table
        $current_urls = smart_getCurrentUrls();
        $current_url  = $current_urls['full'];

        /**
         * What was $params_of_the_options_sel doing again ?
         */
        //$this->_tpl->assign('smartobject_optionssel_action', $_SERVER['PHP_SELF'] . "?" . implode('&', $params_of_the_options_sel));
        $this->_tpl->assign('smartobject_optionssel_action', $current_url);
        $this->_tpl->assign('smartobject_optionssel_limitsArray', $limitsArray);
    }

    /**
     * @return array
     */
    public function getLimitsArray()
    {
        $ret                    = [];
        $ret['all']['caption']  = _CO_SOBJECT_LIMIT_ALL;
        $ret['all']['selected'] = ('all' === $this->_limitsel) ? 'selected' : '';

        $ret['5']['caption']  = '5';
        $ret['5']['selected'] = ('5' == $this->_limitsel) ? 'selected' : '';

        $ret['10']['caption']  = '10';
        $ret['10']['selected'] = ('10' == $this->_limitsel) ? 'selected' : '';

        $ret['15']['caption']  = '15';
        $ret['15']['selected'] = ('15' == $this->_limitsel) ? 'selected' : '';

        $ret['20']['caption']  = '20';
        $ret['20']['selected'] = ('20' == $this->_limitsel) ? 'selected' : '';

        $ret['25']['caption']  = '25';
        $ret['25']['selected'] = ('25' == $this->_limitsel) ? 'selected' : '';

        $ret['30']['caption']  = '30';
        $ret['30']['selected'] = ('30' == $this->_limitsel) ? 'selected' : '';

        $ret['35']['caption']  = '35';
        $ret['35']['selected'] = ('35' == $this->_limitsel) ? 'selected' : '';

        $ret['40']['caption']  = '40';
        $ret['40']['selected'] = ('40' == $this->_limitsel) ? 'selected' : '';

        return $ret;
    }

    /**
     * @return bool
     */
    public function getObjects()
    {
        return $this->_objects;
    }

    public function hideActionColumnTitle()
    {
        $this->_showActionsColumnTitle = false;
    }

    public function hideFilterAndLimit()
    {
        $this->_showFilterAndLimit = false;
    }

    /**
     * @return array
     */
    public function getOrdersArray()
    {
        $ret                    = [];
        $ret['ASC']['caption']  = _CO_SOBJECT_SORT_ASC;
        $ret['ASC']['selected'] = ('ASC' === $this->_ordersel) ? 'selected' : '';

        $ret['DESC']['caption']  = _CO_SOBJECT_SORT_DESC;
        $ret['DESC']['selected'] = ('DESC' === $this->_ordersel) ? 'selected' : '';

        return $ret;
    }

    /**
     * @return mixed|string|void
     */
    public function renderD()
    {
        return $this->render(false, true);
    }

    public function renderForPrint()
    {
    }

    /**
     * @param  bool $fetchOnly
     * @param  bool $debug
     * @return mixed|string|void
     */
    public function render($fetchOnly = false, $debug = false)
    {
        require_once XOOPS_ROOT_PATH . '/class/template.php';

        $this->_tpl = new \XoopsTpl();

        /**
         * We need access to the vars of the SmartObject for a few things in the table creation.
         * Since we may not have a SmartObject to look into now, let's create one for this purpose
         * and we will free it after
         */
        $this->_tempObject = $this->_objectHandler->create();

        $this->_criteria->setStart(isset($_GET['start' . $this->_objectHandler->keyName]) ? (int)$_GET['start' . $this->_objectHandler->keyName] : 0);

        $this->setSortOrder();

        if (!$this->_isTree) {
            $this->_limitsel = isset($_GET['limitsel']) ? $_GET['limitsel'] : smart_getCookieVar($_SERVER['PHP_SELF'] . '_limitsel', '15');
        } else {
            $this->_limitsel = 'all';
        }

        $this->_limitsel = isset($_POST['limitsel']) ? $_POST['limitsel'] : $this->_limitsel;
        smart_setCookieVar($_SERVER['PHP_SELF'] . '_limitsel', $this->_limitsel);
        $limitsArray = $this->getLimitsArray();
        $this->_criteria->setLimit($this->_limitsel);

        $this->_filtersel = isset($_GET['filtersel']) ? $_GET['filtersel'] : $this->getDefaultFilter();
        $this->_filtersel = isset($_POST['filtersel']) ? $_POST['filtersel'] : $this->_filtersel;
        smart_setCookieVar($_SERVER['PHP_SELF'] . '_' . $this->_id . '_filtersel', $this->_filtersel);
        $filtersArray = $this->getFiltersArray();

        if ($filtersArray) {
            $this->_tpl->assign('smartobject_optionssel_filtersArray', $filtersArray);
        }

        // Check if the selected filter is defined and if so, create the selfilter2
        if (isset($this->_filterseloptions[$this->_filtersel])) {
            // check if method associate with this filter exists in the handler
            if (is_array($this->_filterseloptions[$this->_filtersel])) {
                $filter = $this->_filterseloptions[$this->_filtersel];
                $this->_criteria->add($filter['criteria']);
            } else {
                if (method_exists($this->_objectHandler, $this->_filterseloptions[$this->_filtersel])) {

                    // then we will create the selfilter2 options by calling this method
                    $method                   = $this->_filterseloptions[$this->_filtersel];
                    $this->_filtersel2options = $this->_objectHandler->$method();

                    $this->_filtersel2 = isset($_GET['filtersel2']) ? $_GET['filtersel2'] : $this->getDefaultFilter2();
                    $this->_filtersel2 = isset($_POST['filtersel2']) ? $_POST['filtersel2'] : $this->_filtersel2;

                    $filters2Array = $this->getFilters2Array();
                    $this->_tpl->assign('smartobject_optionssel_filters2Array', $filters2Array);

                    smart_setCookieVar($_SERVER['PHP_SELF'] . '_filtersel2', $this->_filtersel2);
                    if ('default' !== $this->_filtersel2) {
                        $this->_criteria->add(new \Criteria($this->_filtersel, $this->_filtersel2));
                    }
                }
            }
        }
        // Check if we have a quicksearch

        if (isset($_POST['quicksearch_' . $this->_id]) && '' != $_POST['quicksearch_' . $this->_id]) {
            $quicksearch_criteria = new \CriteriaCompo();
            if (is_array($this->_quickSearch['fields'])) {
                foreach ($this->_quickSearch['fields'] as $v) {
                    $quicksearch_criteria->add(new \Criteria($v, '%' . $_POST['quicksearch_' . $this->_id] . '%', 'LIKE'), 'OR');
                }
            } else {
                $quicksearch_criteria->add(new \Criteria($this->_quickSearch['fields'], '%' . $_POST['quicksearch_' . $this->_id] . '%', 'LIKE'));
            }
            $this->_criteria->add($quicksearch_criteria);
        }

        $this->_objects = $this->fetchObjects($debug);

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        if ($this->_criteria->getLimit() > 0) {

            /**
             * Geeting rid of the old params
             * $new_get_array is an array containing the new GET parameters
             */
            $new_get_array = [];

            /**
             * $params_of_the_options_sel is an array with all the parameters of the page
             * but without the pagenave parameters. This array will be used in the
             * OptionsSelection
             */
            $params_of_the_options_sel = [];

            $not_needed_params = ['sortsel', 'limitsel', 'ordersel', 'start' . $this->_objectHandler->keyName];
            foreach ($_GET as $k => $v) {
                if (!in_array($k, $not_needed_params)) {
                    $new_get_array[]             = "$k=$v";
                    $params_of_the_options_sel[] = "$k=$v";
                }
            }

            /**
             * Adding the new params of the pagenav
             */
            $new_get_array[] = 'sortsel=' . $this->_sortsel;
            $new_get_array[] = 'ordersel=' . $this->_ordersel;
            $new_get_array[] = 'limitsel=' . $this->_limitsel;
            $otherParams     = implode('&', $new_get_array);

            $pagenav = new \XoopsPageNav($this->_objectHandler->getCount($this->_criteria), $this->_criteria->getLimit(), $this->_criteria->getStart(), 'start' . $this->_objectHandler->keyName, $otherParams);
            $this->_tpl->assign('smartobject_pagenav', $pagenav->renderNav());
        }
        $this->renderOptionSelection($limitsArray, $params_of_the_options_sel);

        // retreive the current url and the query string
        $current_urls = smart_getCurrentUrls();
        $current_url  = $current_urls['full_phpself'];
        $query_string = $current_urls['querystring'];
        if ($query_string) {
            $query_string = str_replace('?', '', $query_string);
        }
        $query_stringArray     = explode('&', $query_string);
        $new_query_stringArray = [];
        foreach ($query_stringArray as $query_string) {
            if (false === strpos($query_string, 'sortsel') && false === strpos($query_string, 'ordersel')) {
                $new_query_stringArray[] = $query_string;
            }
        }
        $new_query_string = implode('&', $new_query_stringArray);

        $orderArray                     = [];
        $orderArray['ASC']['image']     = 'desc.png';
        $orderArray['ASC']['neworder']  = 'DESC';
        $orderArray['DESC']['image']    = 'asc.png';
        $orderArray['DESC']['neworder'] = 'ASC';

        $aColumns = [];

        foreach ($this->_columns as $column) {
            $qs_param         = '';
            $aColumn          = [];
            $aColumn['width'] = $column->getWidth();
            $aColumn['align'] = $column->getAlign();
            $aColumn['key']   = $column->getKeyName();
            if ('checked' === $column->_keyname) {
                $aColumn['caption'] = '<input type ="checkbox" id="checkall_smartobjects" name="checkall_smartobjects"' . ' value="checkall_smartobjects" onclick="smartobject_checkall(window.document.form_' . $this->_id . ', \'selected_smartobjects\');">';
            } elseif ($column->getCustomCaption()) {
                $aColumn['caption'] = $column->getCustomCaption();
            } else {
                $aColumn['caption'] = isset($this->_tempObject->vars[$column->getKeyName()]['form_caption']) ? $this->_tempObject->vars[$column->getKeyName()]['form_caption'] : $column->getKeyName();
            }
            // Are we doing a GET sort on this column ?
            $getSort = (isset($_GET[$this->_objectHandler->_itemname . '_' . 'sortsel'])
                        && $_GET[$this->_objectHandler->_itemname . '_' . 'sortsel'] == $column->getKeyName())
                       || ($this->_sortsel == $column->getKeyName());
            $order   = isset($_GET[$this->_objectHandler->_itemname . '_' . 'ordersel']) ? $_GET[$this->_objectHandler->_itemname . '_' . 'ordersel'] : 'DESC';

            if (isset($_REQUEST['quicksearch_' . $this->_id]) && '' != $_REQUEST['quicksearch_' . $this->_id]) {
                $qs_param = '&quicksearch_' . $this->_id . '=' . $_REQUEST['quicksearch_' . $this->_id];
            }
            if (!$this->_enableColumnsSorting || 'checked' === $column->_keyname || !$column->isSortable()) {
                $aColumn['caption'] = $aColumn['caption'];
            } elseif ($getSort) {
                $aColumn['caption'] = '<a href="'
                                      . $current_url
                                      . '?'
                                      . $this->_objectHandler->_itemname
                                      . '_'
                                      . 'sortsel='
                                      . $column->getKeyName()
                                      . '&'
                                      . $this->_objectHandler->_itemname
                                      . '_'
                                      . 'ordersel='
                                      . $orderArray[$order]['neworder']
                                      . $qs_param
                                      . '&'
                                      . $new_query_string
                                      . '">'
                                      . $aColumn['caption']
                                      . ' <img src="'
                                      . SMARTOBJECT_IMAGES_ACTIONS_URL
                                      . $orderArray[$order]['image']
                                      . '" alt="ASC"></a>';
            } else {
                $aColumn['caption'] = '<a href="' . $current_url . '?' . $this->_objectHandler->_itemname . '_' . 'sortsel=' . $column->getKeyName() . '&' . $this->_objectHandler->_itemname . '_' . 'ordersel=ASC' . $qs_param . '&' . $new_query_string . '">' . $aColumn['caption'] . '</a>';
            }
            $aColumns[] = $aColumn;
        }
        $this->_tpl->assign('smartobject_columns', $aColumns);

        if ($this->_quickSearch) {
            $this->_tpl->assign('smartobject_quicksearch', $this->_quickSearch['caption']);
        }

        $this->createTableRows();

        $this->_tpl->assign('smartobject_showFilterAndLimit', $this->_showFilterAndLimit);
        $this->_tpl->assign('smartobject_isTree', $this->_isTree);
        $this->_tpl->assign('smartobject_show_action_column_title', $this->_showActionsColumnTitle);
        $this->_tpl->assign('smartobject_table_header', $this->_tableHeader);
        $this->_tpl->assign('smartobject_table_footer', $this->_tableFooter);
        $this->_tpl->assign('smartobject_printer_friendly_page', $this->_printerFriendlyPage);
        $this->_tpl->assign('smartobject_user_side', $this->_userSide);
        $this->_tpl->assign('smartobject_has_actions', $this->_hasActions);
        $this->_tpl->assign('smartobject_head_css_class', $this->_head_css_class);
        $this->_tpl->assign('smartobject_actionButtons', $this->_actionButtons);
        $this->_tpl->assign('smartobject_introButtons', $this->_introButtons);
        $this->_tpl->assign('smartobject_id', $this->_id);
        if (!empty($this->_withSelectedActions)) {
            $this->_tpl->assign('smartobject_withSelectedActions', $this->_withSelectedActions);
        }

        $smartobjectTable_template = $this->_customTemplate ?: 'smartobject_smarttable_display.tpl';
        if ($fetchOnly) {
            return $this->_tpl->fetch('db:' . $smartobjectTable_template);
        } else {
            $this->_tpl->display('db:' . $smartobjectTable_template);
        }
    }

    public function disableColumnsSorting()
    {
        $this->_enableColumnsSorting = false;
    }

    /**
     * @param  bool $debug
     * @return mixed|string|void
     */
    public function fetch($debug = false)
    {
        return $this->render(true, $debug);
    }
}
