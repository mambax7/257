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

require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/common.php';

require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectcontroller.php';

if (!defined('XOBJ_DTYPE_SIMPLE_ARRAY')) {
    define('XOBJ_DTYPE_SIMPLE_ARRAY', 101);
}
if (!defined('XOBJ_DTYPE_CURRENCY')) {
    define('XOBJ_DTYPE_CURRENCY', 200);
}
if (!defined('XOBJ_DTYPE_FLOAT')) {
    define('XOBJ_DTYPE_FLOAT', 201);
}
if (!defined('XOBJ_DTYPE_TIME_ONLY')) {
    define('XOBJ_DTYPE_TIME_ONLY', 202);
}
if (!defined('XOBJ_DTYPE_URLLINK')) {
    define('XOBJ_DTYPE_URLLINK', 203);
}
if (!defined('XOBJ_DTYPE_FILE')) {
    define('XOBJ_DTYPE_FILE', 204);
}
if (!defined('XOBJ_DTYPE_IMAGE')) {
    define('XOBJ_DTYPE_IMAGE', 205);
}
if (!defined('XOBJ_DTYPE_FORM_SECTION')) {
    define('XOBJ_DTYPE_FORM_SECTION', 210);
}
if (!defined('XOBJ_DTYPE_FORM_SECTION_CLOSE')) {
    define('XOBJ_DTYPE_FORM_SECTION_CLOSE', 211);
}

/**
 * SmartObject base class
 *
 * Base class representing a single SmartObject
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartObject extends XoopsObject
{
    public $_image_path;
    public $_image_url;

    public $seoEnabled   = false;
    public $titleField;
    public $summaryField = false;

    /**
     * Reference to the handler managing this object
     *
     * @var SmartPersistableObjectHandler reference to {@link SmartPersistableObjectHandler}
     */
    public $handler;

    /**
     * References to control objects, managing the form fields of this object
     */
    public $controls = [];

    /**
     * SmartObject constructor.
     * @param $handler
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Checks if the user has a specific access on this object
     *
     * @param $perm_name
     * @return bool: TRUE if user has access, false if not
     * @internal param string $gperm_name name of the permission to test
     */
    public function accessGranted($perm_name)
    {
        $smartPermissionsHandler = new SmartobjectPermissionHandler($this->handler);

        return $smartPermissionsHandler->accessGranted($perm_name, $this->id());
    }

    /**
     * @param      $section_name
     * @param bool $value
     * @param bool $hide
     */
    public function addFormSection($section_name, $value = false, $hide = false)
    {
        $this->initVar($section_name, XOBJ_DTYPE_FORM_SECTION, $value, false, null, '', false, '', '', false, false, true);
        $this->vars[$section_name]['hide'] = $hide;
    }

    /**
     * @param $section_name
     */
    public function closeSection($section_name)
    {
        $this->initVar('close_section_' . $section_name, XOBJ_DTYPE_FORM_SECTION_CLOSE, '', false, null, '', false, '', '', false, false, true);
    }

    /**
     *
     * @param string $key          key of this field. This needs to be the name of the field in the related database table
     * @param int    $data_type    set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
     * @param mixed  $value        default value of this variable
     * @param bool   $required     set to TRUE if this variable needs to have a value set before storing the object in the table
     * @param int    $maxlength    maximum length of this variable, for XOBJ_DTYPE_TXTBOX type only
     * @param string $options      does this data have any select options?
     * @param bool   $multilingual is this field needs to support multilingual features (NOT YET IMPLEMENTED...)
     * @param string $form_caption caption of this variable in a {@link SmartobjectForm} and title of a column in a  {@link SmartObjectTable}
     * @param string $form_dsc     description of this variable in a {@link SmartobjectForm}
     * @param bool   $sortby       set to TRUE to make this field used to sort objects in SmartObjectTable
     * @param bool   $persistent   set to FALSE if this field is not to be saved in the database
     * @param bool   $displayOnForm
     */
    public function initVar(
        $key,
        $data_type,
        $value = null,
        $required = false,
        $maxlength = null,
        $options = '',
        $multilingual = false,
        $form_caption = '',
        $form_dsc = '',
        $sortby = false,
        $persistent = true,
        $displayOnForm = true
    ) {
        //url_ is reserved for files.
        if (0 === strpos($key, 'url_')) {
            trigger_error("Cannot use variable starting with 'url_'.");
        }
        parent::initVar($key, $data_type, $value, $required, $maxlength, $options);
        if ($this->handler && (!$form_caption || '' === $form_caption)) {
            $dyn_form_caption = strtoupper('_CO_' . $this->handler->_moduleName . '_' . $this->handler->_itemname . '_' . $key);
            if (defined($dyn_form_caption)) {
                $form_caption = constant($dyn_form_caption);
            }
        }
        if ($this->handler && (!$form_dsc || '' === $form_dsc)) {
            $dyn_form_dsc = strtoupper('_CO_' . $this->handler->_moduleName . '_' . $this->handler->_itemname . '_' . $key . '_DSC');
            if (defined($dyn_form_dsc)) {
                $form_dsc = constant($dyn_form_dsc);
            }
        }

        $this->vars[$key] = array_merge($this->vars[$key], [
            'multilingual'        => $multilingual,
            'form_caption'        => $form_caption,
            'form_dsc'            => $form_dsc,
            'sortby'              => $sortby,
            'persistent'          => $persistent,
            'displayOnForm'       => $displayOnForm,
            'displayOnSingleView' => true,
            'readonly'            => false
        ]);
    }

    /**
     * @param        $key
     * @param        $data_type
     * @param bool   $itemName
     * @param string $form_caption
     * @param bool   $sortby
     * @param string $value
     * @param bool   $displayOnForm
     * @param bool   $required
     */
    public function initNonPersistableVar(
        $key,
        $data_type,
        $itemName = false,
        $form_caption = '',
        $sortby = false,
        $value = '',
        $displayOnForm = false,
        $required = false
    ) {
        $this->initVar($key, $data_type, $value, $required, null, '', false, $form_caption, '', $sortby, false, $displayOnForm);
        $this->vars[$key]['itemName'] = $itemName;
    }

    /**
     * Quickly initiate a var
     *
     * Since many vars do have the same config, let's use this method with some of these configuration as a convention ;-)
     *
     * - $maxlength = 0 unless $data_type is a TEXTBOX, then $maxlength will be 255
     * - all other vars are NULL or '' depending of the parameter
     *
     * @param string $key          key of this field. This needs to be the name of the field in the related database table
     * @param int    $data_type    set to one of XOBJ_DTYPE_XXX constants (set to XOBJ_DTYPE_OTHER if no data type ckecking nor text sanitizing is required)
     * @param bool   $required     set to TRUE if this variable needs to have a value set before storing the object in the table
     * @param string $form_caption caption of this variable in a {@link SmartobjectForm} and title of a column in a  {@link SmartObjectTable}
     * @param string $form_dsc     description of this variable in a {@link SmartobjectForm}
     * @param mixed  $value        default value of this variable
     */
    public function quickInitVar(
        $key,
        $data_type,
        $required = false,
        $form_caption = '',
        $form_dsc = '',
        $value = null
    ) {
        $maxlength = 'XOBJ_DTYPE_TXTBOX' === $data_type ? 255 : null;
        $this->initVar($key, $data_type, $value, $required, $maxlength, '', false, $form_caption, $form_dsc, false, true, true);
    }

    /**
     * @param        $varname
     * @param bool   $displayOnForm
     * @param string $default
     */
    public function initCommonVar($varname, $displayOnForm = true, $default = 'notdefined')
    {
        switch ($varname) {
            case 'dohtml':
                $value = 'notdefined' !== $default ? $default : true;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_DOHTML_FORM_CAPTION, '', false, true, $displayOnForm);
                $this->setControl($varname, 'yesno');
                break;

            case 'dobr':
                $value = ('notdefined' === $default) ? true : $default;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_DOBR_FORM_CAPTION, '', false, true, $displayOnForm);
                $this->setControl($varname, 'yesno');
                break;

            case 'doimage':
                $value = 'notdefined' !== $default ? $default : true;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_DOIMAGE_FORM_CAPTION, '', false, true, $displayOnForm);
                $this->setControl($varname, 'yesno');
                break;

            case 'dosmiley':
                $value = 'notdefined' !== $default ? $default : true;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_DOSMILEY_FORM_CAPTION, '', false, true, $displayOnForm);
                $this->setControl($varname, 'yesno');
                break;

            case 'doxcode':
                $value = 'notdefined' !== $default ? $default : true;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_DOXCODE_FORM_CAPTION, '', false, true, $displayOnForm);
                $this->setControl($varname, 'yesno');
                break;

            case 'meta_keywords':
                $value = 'notdefined' !== $default ? $default : '';
                $this->initVar($varname, XOBJ_DTYPE_TXTAREA, $value, false, null, '', false, _CO_SOBJECT_META_KEYWORDS, _CO_SOBJECT_META_KEYWORDS_DSC, false, true, $displayOnForm);
                $this->setControl('meta_keywords', [
                    'name'        => 'textarea',
                    'form_editor' => 'textarea'
                ]);
                break;

            case 'meta_description':
                $value = 'notdefined' !== $default ? $default : '';
                $this->initVar($varname, XOBJ_DTYPE_TXTAREA, $value, false, null, '', false, _CO_SOBJECT_META_DESCRIPTION, _CO_SOBJECT_META_DESCRIPTION_DSC, false, true, $displayOnForm);
                $this->setControl('meta_description', [
                    'name'        => 'textarea',
                    'form_editor' => 'textarea'
                ]);
                break;

            case 'short_url':
                $value = 'notdefined' !== $default ? $default : '';
                $this->initVar($varname, XOBJ_DTYPE_TXTBOX, $value, false, null, '', false, _CO_SOBJECT_SHORT_URL, _CO_SOBJECT_SHORT_URL_DSC, false, true, $displayOnForm);
                break;

            case 'hierarchy_path':
                $value = 'notdefined' !== $default ? $default : '';
                $this->initVar($varname, XOBJ_DTYPE_ARRAY, $value, false, null, '', false, _CO_SOBJECT_HIERARCHY_PATH, _CO_SOBJECT_HIERARCHY_PATH_DSC, false, true, $displayOnForm);
                break;

            case 'counter':
                $value = 'notdefined' !== $default ? $default : 0;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_COUNTER_FORM_CAPTION, '', false, true, $displayOnForm);
                break;

            case 'weight':
                $value = 'notdefined' !== $default ? $default : 0;
                $this->initVar($varname, XOBJ_DTYPE_INT, $value, false, null, '', false, _CO_SOBJECT_WEIGHT_FORM_CAPTION, '', true, true, $displayOnForm);
                break;
            case 'custom_css':
                $value = 'notdefined' !== $default ? $default : '';
                $this->initVar($varname, XOBJ_DTYPE_TXTAREA, $value, false, null, '', false, _CO_SOBJECT_CUSTOM_CSS, _CO_SOBJECT_CUSTOM_CSS_DSC, false, true, $displayOnForm);
                $this->setControl('custom_css', [
                    'name'        => 'textarea',
                    'form_editor' => 'textarea'
                ]);
                break;
        }
        $this->hideFieldFromSingleView($varname);
    }

    /**
     * Set control information for an instance variable
     *
     * The $options parameter can be a string or an array. Using a string
     * is the quickest way:
     *
     * $this->setControl('date', 'date_time');
     *
     * This will create a date and time selectbox for the 'date' var on the
     * form to edit or create this item.
     *
     * Here are the currently supported controls:
     *
     *      - color
     *      - country
     *      - date_time
     *      - date
     *      - email
     *      - group
     *      - group_multi
     *      - image
     *      - imageupload
     *      - label
     *      - language
     *      - parentcategory
     *      - password
     *      - select_multi
     *      - select
     *      - text
     *      - textarea
     *      - theme
     *      - theme_multi
     *      - timezone
     *      - user
     *      - user_multi
     *      - yesno
     *
     * Now, using an array as $options, you can customize what information to
     * use in the control. For example, if one needs to display a select box for
     * the user to choose the status of an item. We only need to tell SmartObject
     * what method to execute within what handler to retreive the options of the
     * selectbox.
     *
     * $this->setControl('status', array('name' => false,
     *                                   'itemHandler' => 'item',
     *                                   'method' => 'getStatus',
     *                                   'module' => 'smartshop'));
     *
     * In this example, the array elements are the following:
     *      - name: false, as we don't need to set a special control here.
     *               we will use the default control related to the object type (defined in initVar)
     *      - itemHandler: name of the object for which we will use the handler
     *      - method: name of the method of this handler that we will execute
     *      - module: name of the module from wich the handler is
     *
     * So in this example, SmartObject will create a selectbox for the variable 'status' and it will
     * populate this selectbox with the result from SmartshopItemHandler::getStatus()
     *
     * Another example of the use of $options as an array is for TextArea:
     *
     * $this->setControl('body', array('name' => 'textarea',
     *                                   'form_editor' => 'default'));
     *
     * In this example, SmartObject will create a TextArea for the variable 'body'. And it will use
     * the 'default' editor, providing it is defined in the module
     * preferences: $xoopsModuleConfig['default_editor']
     *
     * Of course, you can force the use of a specific editor:
     *
     * $this->setControl('body', array('name' => 'textarea',
     *                                   'form_editor' => 'koivi'));
     *
     * Here is a list of supported editor:
     *      - tiny: TinyEditor
     *      - dhtmltextarea: XOOPS DHTML Area
     *      - fckeditor: FCKEditor
     *      - inbetween: InBetween
     *      - koivi: Koivi
     *      - spaw: Spaw WYSIWYG Editor
     *      - htmlarea: HTMLArea
     *      - textarea: basic textarea with no options
     *
     * @param string $var name of the variable for which we want to set a control
     * @param array  $options
     */
    public function setControl($var, $options = [])
    {
        if (isset($this->controls[$var])) {
            unset($this->controls[$var]);
        }
        if (is_string($options)) {
            $options = ['name' => $options];
        }
        $this->controls[$var] = $options;
    }

    /**
     * Get control information for an instance variable
     *
     * @param  string $var
     * @return bool|mixed
     */
    public function getControl($var)
    {
        return isset($this->controls[$var]) ? $this->controls[$var] : false;
    }

    /**
     * Create the form for this object
     *
     * @param         $form_caption
     * @param         $form_name
     * @param  bool   $form_action
     * @param  string $submit_button_caption
     * @param  bool   $cancel_js_action
     * @param  bool   $captcha
     * @return \SmartobjectForm <a href='psi_element://SmartobjectForm'>SmartobjectForm</a> object for this object
     *                                      object for this object
     * @see SmartObjectForm::SmartObjectForm()
     */
    public function getForm(
        $form_caption,
        $form_name,
        $form_action = false,
        $submit_button_caption = _CO_SOBJECT_SUBMIT,
        $cancel_js_action = false,
        $captcha = false
    ) {
        require_once SMARTOBJECT_ROOT_PATH . 'class/form/smartobjectform.php';
        $form = new SmartobjectForm($this, $form_name, $form_caption, $form_action, null, $submit_button_caption, $cancel_js_action, $captcha);

        return $form;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret  = [];
        $vars =& $this->getVars();
        foreach ($vars as $key => $var) {
            $value     = $this->getVar($key);
            $ret[$key] = $value;
        }
        if ('' !== $this->handler->identifierName) {
            $controller = new SmartObjectController($this->handler);
            /**
             * Addition of some automatic value
             */
            $ret['itemLink']         = $controller->getItemLink($this);
            $ret['itemUrl']          = $controller->getItemLink($this, true);
            $ret['editItemLink']     = $controller->getEditItemLink($this, false, true);
            $ret['deleteItemLink']   = $controller->getDeleteItemLink($this, false, true);
            $ret['printAndMailLink'] = $controller->getPrintAndMailLink($this);
        }

        // Hightlighting searched words
        require_once SMARTOBJECT_ROOT_PATH . 'class/smarthighlighter.php';
        $highlight = smart_getConfig('module_search_highlighter', false, true);

        if ($highlight && isset($_GET['keywords'])) {
            $myts     = \MyTextSanitizer::getInstance();
            $keywords = $myts->htmlSpecialChars(trim(urldecode($_GET['keywords'])));
            $h        = new SmartHighlighter($keywords, true, 'smart_highlighter');
            foreach ($this->handler->highlightFields as $field) {
                $ret[$field] = $h->highlight($ret[$field]);
            }
        }

        return $ret;
    }

    /**
     * add an error
     *
     * @param      $err_str
     * @param bool $prefix
     * @internal param string $value error to add
     * @access   public
     */
    public function setErrors($err_str, $prefix = false)
    {
        if (is_array($err_str)) {
            foreach ($err_str as $str) {
                $this->setErrors($str, $prefix);
            }
        } else {
            if ($prefix) {
                $err_str = '[' . $prefix . '] ' . $err_str;
            }
            parent::setErrors($err_str);
        }
    }

    /**
     * @param      $field
     * @param bool $required
     */
    public function setFieldAsRequired($field, $required = true)
    {
        if (is_array($field)) {
            foreach ($field as $v) {
                $this->doSetFieldAsRequired($v, $required);
            }
        } else {
            $this->doSetFieldAsRequired($field, $required);
        }
    }

    /**
     * @param $field
     */
    public function setFieldForSorting($field)
    {
        if (is_array($field)) {
            foreach ($field as $v) {
                $this->doSetFieldForSorting($v);
            }
        } else {
            $this->doSetFieldForSorting($field);
        }
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return count($this->_errors) > 0;
    }

    /**
     * @param $url
     * @param $path
     */
    public function setImageDir($url, $path)
    {
        $this->_image_url  = $url;
        $this->_image_path = $path;
    }

    /**
     * Retreive the group that have been granted access to a specific permission for this object
     *
     * @param $group_perm
     * @return string $group_perm name of the permission
     */
    public function getGroupPerm($group_perm)
    {
        if (!$this->handler->getPermissions()) {
            $this->setError("Trying to access a permission that does not exists for thisobject's handler");

            return false;
        }

        $smartPermissionsHandler = new SmartobjectPermissionHandler($this->handler);
        $ret                     = $smartPermissionsHandler->getGrantedGroups($group_perm, $this->id());

        if (0 == count($ret)) {
            return false;
        } else {
            return $ret;
        }
    }

    /**
     * @param  bool $path
     * @return mixed
     */
    public function getImageDir($path = false)
    {
        if ($path) {
            return $this->_image_path;
        } else {
            return $this->_image_url;
        }
    }

    /**
     * @param  bool $path
     * @return mixed
     */
    public function getUploadDir($path = false)
    {
        if ($path) {
            return $this->_image_path;
        } else {
            return $this->_image_url;
        }
    }

    /**
     * @param  string $key
     * @param  string $info
     * @return array
     */
    public function getVarInfo($key = '', $info = '')
    {
        if (isset($this->vars[$key][$info])) {
            return $this->vars[$key][$info];
        } elseif ('' === $info && isset($this->vars[$key])) {
            return $this->vars[$key];
        } else {
            return $this->vars;
        }
    }

    /**
     * Get the id of the object
     *
     * @return int id of this object
     */
    public function id()
    {
        return $this->getVar($this->handler->keyName, 'e');
    }

    /**
     * Return the value of the title field of this object
     *
     * @param  string $format
     * @return string
     */
    public function title($format = 's')
    {
        return $this->getVar($this->handler->identifierName, $format);
    }

    /**
     * Return the value of the title field of this object
     *
     * @return string
     */
    public function summary()
    {
        if ($this->handler->summaryName) {
            return $this->getVar($this->handler->summaryName);
        } else {
            return false;
        }
    }

    /**
     * Retreive the object admin side link, displayijng a SingleView page
     *
     * @param  bool $onlyUrl wether or not to return a simple URL or a full <a> link
     * @return string user side link to the object
     */
    public function getAdminViewItemLink($onlyUrl = false)
    {
        $controller = new SmartObjectController($this->handler);

        return $controller->getAdminViewItemLink($this, $onlyUrl);
    }

    /**
     * Retreive the object user side link
     *
     * @param  bool $onlyUrl wether or not to return a simple URL or a full <a> link
     * @return string user side link to the object
     */
    public function getItemLink($onlyUrl = false)
    {
        $controller = new SmartObjectController($this->handler);

        return $controller->getItemLink($this, $onlyUrl);
    }

    /**
     * @param  bool $onlyUrl
     * @param  bool $withimage
     * @param  bool $userSide
     * @return string
     */
    public function getEditItemLink($onlyUrl = false, $withimage = true, $userSide = false)
    {
        $controller = new SmartObjectController($this->handler);

        return $controller->getEditItemLink($this, $onlyUrl, $withimage, $userSide);
    }

    /**
     * @param  bool $onlyUrl
     * @param  bool $withimage
     * @param  bool $userSide
     * @return string
     */
    public function getDeleteItemLink($onlyUrl = false, $withimage = false, $userSide = false)
    {
        $controller = new SmartObjectController($this->handler);

        return $controller->getDeleteItemLink($this, $onlyUrl, $withimage, $userSide);
    }

    /**
     * @return string
     */
    public function getPrintAndMailLink()
    {
        $controller = new SmartObjectController($this->handler);

        return $controller->getPrintAndMailLink($this);
    }

    /**
     * @param $sortsel
     * @return array|bool
     */
    public function getFieldsForSorting($sortsel)
    {
        $ret = [];

        foreach ($this->vars as $key => $field_info) {
            if ($field_info['sortby']) {
                $ret[$key]['caption']  = $field_info['form_caption'];
                $ret[$key]['selected'] = $key == $sortsel ? 'selected' : '';
            }
        }

        if (count($ret) > 0) {
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * @param $key
     * @param $newType
     */
    public function setType($key, $newType)
    {
        $this->vars[$key]['data_type'] = $newType;
    }

    /**
     * @param $key
     * @param $info
     * @param $value
     */
    public function setVarInfo($key, $info, $value)
    {
        $this->vars[$key][$info] = $value;
    }

    /**
     * @param         $key
     * @param  bool   $editor
     * @return string
     */
    public function getValueFor($key, $editor = true)
    {
        global $xoopsModuleConfig;

        $ret  = $this->getVar($key, 'n');
        $myts = \MyTextSanitizer::getInstance();

        $control     = isset($this->controls[$key]) ? $this->controls[$key] : false;
        $form_editor = isset($control['form_editor']) ? $control['form_editor'] : 'textarea';

        $html     = isset($this->vars['dohtml']) ? $this->getVar('dohtml') : true;
        $smiley   = true;
        $xcode    = true;
        $image    = true;
        $br       = isset($this->vars['dobr']) ? $this->getVar('dobr') : true;
        $formatML = true;

        if ('default' === $form_editor) {
            global $xoopsModuleConfig;
            $form_editor = isset($xoopsModuleConfig['default_editor']) ? $xoopsModuleConfig['default_editor'] : 'textarea';
        }

        if ($editor) {
            if (defined('XOOPS_EDITOR_IS_HTML')
                && !in_array($form_editor, ['formtextarea', 'textarea', 'dhtmltextarea'])) {
                $br       = false;
                $formatML = !$editor;
            } else {
                return htmlspecialchars($ret, ENT_QUOTES);
            }
        }

        if (method_exists($myts, 'formatForML')) {
            return $myts->displayTarea($ret, $html, $smiley, $xcode, $image, $br, $formatML);
        } else {
            return $myts->displayTarea($ret, $html, $smiley, $xcode, $image, $br);
        }
    }

    /**
     * clean values of all variables of the object for storage.
     * also add slashes whereever needed
     *
     * We had to put this method in the SmartObject because the XOBJ_DTYPE_ARRAY does not work properly
     * at least on PHP 5.1. So we have created a new type XOBJ_DTYPE_SIMPLE_ARRAY to handle 1 level array
     * as a string separated by |
     *
     * @return bool true if successful
     * @access public
     */
    public function cleanVars()
    {
        $ts              = \MyTextSanitizer::getInstance();
        $existing_errors = $this->getErrors();
        $this->_errors   = [];
        foreach ($this->vars as $k => $v) {
            $cleanv = $v['value'];
            if (!$v['changed']) {
            } else {
                $cleanv = is_string($cleanv) ? trim($cleanv) : $cleanv;
                switch ($v['data_type']) {
                    case XOBJ_DTYPE_TXTBOX:
                        if ($v['required'] && '0' != $cleanv && '' == $cleanv) {
                            $this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
                            continue 2;
                        }
                        if (isset($v['maxlength']) && strlen($cleanv) > (int)$v['maxlength']) {
                            $this->setErrors(sprintf(_XOBJ_ERR_SHORTERTHAN, $k, (int)$v['maxlength']));
                            continue 2;
                        }
                        if (!$v['not_gpc']) {
                            $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                        } else {
                            $cleanv = $ts->censorString($cleanv);
                        }
                        break;
                    case XOBJ_DTYPE_TXTAREA:
                        if ($v['required'] && '0' != $cleanv && '' == $cleanv) {
                            $this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
                            continue 2;
                        }
                        if (!$v['not_gpc']) {
                            $cleanv = $ts->stripSlashesGPC($ts->censorString($cleanv));
                        } else {
                            $cleanv = $ts->censorString($cleanv);
                        }
                        break;
                    case XOBJ_DTYPE_SOURCE:
                        if (!$v['not_gpc']) {
                            $cleanv = $ts->stripSlashesGPC($cleanv);
                        } else {
                            $cleanv = $cleanv;
                        }
                        break;
                    case XOBJ_DTYPE_INT:
                    case XOBJ_DTYPE_TIME_ONLY:
                        $cleanv = (int)$cleanv;
                        break;

                    case XOBJ_DTYPE_CURRENCY:
                        $cleanv = smart_currency($cleanv);
                        break;

                    case XOBJ_DTYPE_FLOAT:
                        $cleanv = smart_float($cleanv);
                        break;

                    case XOBJ_DTYPE_EMAIL:
                        if ($v['required'] && '' === $cleanv) {
                            $this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
                            continue 2;
                        }
                        if ('' !== $cleanv
                            && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $cleanv)) {
                            $this->setErrors('Invalid Email');
                            continue 2;
                        }
                        if (!$v['not_gpc']) {
                            $cleanv = $ts->stripSlashesGPC($cleanv);
                        }
                        break;
                    case XOBJ_DTYPE_URL:
                        if ($v['required'] && '' === $cleanv) {
                            $this->setErrors(sprintf(_XOBJ_ERR_REQUIRED, $k));
                            continue 2;
                        }
                        if ('' !== $cleanv && !preg_match("/^http[s]*:\/\//i", $cleanv)) {
                            $cleanv = 'http://' . $cleanv;
                        }
                        if (!$v['not_gpc']) {
                            $cleanv =& $ts->stripSlashesGPC($cleanv);
                        }
                        break;
                    case XOBJ_DTYPE_SIMPLE_ARRAY:
                        $cleanv = implode('|', $cleanv);
                        break;
                    case XOBJ_DTYPE_ARRAY:
                        $cleanv = serialize($cleanv);
                        break;
                    case XOBJ_DTYPE_STIME:
                    case XOBJ_DTYPE_MTIME:
                    case XOBJ_DTYPE_LTIME:
                        $cleanv = !is_string($cleanv) ? (int)$cleanv : strtotime($cleanv);
                        if (!($cleanv > 0)) {
                            $cleanv = strtotime($cleanv);
                        }
                        break;
                    default:
                        break;
                }
            }
            $this->cleanVars[$k] =& $cleanv;
            unset($cleanv);
        }
        if (count($this->_errors) > 0) {
            $this->_errors = array_merge($existing_errors, $this->_errors);

            return false;
        }
        $this->_errors = array_merge($existing_errors, $this->_errors);
        $this->unsetDirty();

        return true;
    }

    /**
     * returns a specific variable for the object in a proper format
     *
     * We had to put this method in the SmartObject because the XOBJ_DTYPE_ARRAY does not work properly
     * at least on PHP 5.1. So we have created a new type XOBJ_DTYPE_SIMPLE_ARRAY to handle 1 level array
     * as a string separated by |
     *
     * @access public
     * @param  string $key    key of the object's variable to be returned
     * @param  string $format format to use for the output
     * @return mixed  formatted value of the variable
     */
    public function getVar($key, $format = 's')
    {
        global $myts;

        $ret = $this->vars[$key]['value'];

        switch ($this->vars[$key]['data_type']) {

            case XOBJ_DTYPE_TXTBOX:
                switch (strtolower($format)) {
                    case 's':
                    case 'show':
                        // ML Hack by marcan
                        $ts  = \MyTextSanitizer::getInstance();
                        $ret = $ts->htmlSpecialChars($ret);

                        if (method_exists($myts, 'formatForML')) {
                            return $ts->formatForML($ret);
                        } else {
                            return $ret;
                        }
                        break 1;
                    // End of ML Hack by marcan

                    case 'clean':
                        $ts = \MyTextSanitizer::getInstance();

                        $ret = smart_html2text($ret);
                        $ret = smart_purifyText($ret);

                        if (method_exists($myts, 'formatForML')) {
                            return $ts->formatForML($ret);
                        } else {
                            return $ret;
                        }
                        break 1;
                    // End of ML Hack by marcan

                    case 'e':
                    case 'edit':
                        $ts = \MyTextSanitizer::getInstance();

                        return $ts->htmlSpecialChars($ret);
                        break 1;
                    case 'p':
                    case 'preview':
                    case 'f':
                    case 'formpreview':
                        $ts = \MyTextSanitizer::getInstance();

                        return $ts->htmlSpecialChars($ts->stripSlashesGPC($ret));
                        break 1;
                    case 'n':
                    case 'none':
                    default:
                        break 1;
                }
                break;
            case XOBJ_DTYPE_LTIME:
                switch (strtolower($format)) {
                    case 's':
                    case 'show':
                    case 'p':
                    case 'preview':
                    case 'f':
                    case 'formpreview':
                        $ret = formatTimestamp($ret, _DATESTRING);

                        return $ret;
                        break 1;
                    case 'n':
                    case 'none':
                    case 'e':
                    case 'edit':
                        break 1;
                    default:
                        break 1;
                }
                break;
            case XOBJ_DTYPE_STIME:
                switch (strtolower($format)) {
                    case 's':
                    case 'show':
                    case 'p':
                    case 'preview':
                    case 'f':
                    case 'formpreview':
                        $ret = formatTimestamp($ret, _SHORTDATESTRING);

                        return $ret;
                        break 1;
                    case 'n':
                    case 'none':
                    case 'e':
                    case 'edit':
                        break 1;
                    default:
                        break 1;
                }
                break;
            case XOBJ_DTYPE_TIME_ONLY:
                switch (strtolower($format)) {
                    case 's':
                    case 'show':
                    case 'p':
                    case 'preview':
                    case 'f':
                    case 'formpreview':
                        $ret = formatTimestamp($ret, 'G:i');

                        return $ret;
                        break 1;
                    case 'n':
                    case 'none':
                    case 'e':
                    case 'edit':
                        break 1;
                    default:
                        break 1;
                }
                break;

            case XOBJ_DTYPE_CURRENCY:
                $decimal_section_original = strstr($ret, '.');
                $decimal_section          = $decimal_section_original;
                if ($decimal_section) {
                    if (1 == strlen($decimal_section)) {
                        $decimal_section = '.00';
                    } elseif (2 == strlen($decimal_section)) {
                        $decimal_section .= '0';
                    }
                    $ret = str_replace($decimal_section_original, $decimal_section, $ret);
                } else {
                    $ret .= '.00';
                }
                break;

            case XOBJ_DTYPE_TXTAREA:
                switch (strtolower($format)) {
                    case 's':
                    case 'show':
                        $ts   = \MyTextSanitizer::getInstance();
                        $html = !empty($this->vars['dohtml']['value']) ? 1 : 0;

                        $xcode = (!isset($this->vars['doxcode']['value'])
                                  || 1 == $this->vars['doxcode']['value']) ? 1 : 0;

                        $smiley = (!isset($this->vars['dosmiley']['value'])
                                   || 1 == $this->vars['dosmiley']['value']) ? 1 : 0;
                        $image  = (!isset($this->vars['doimage']['value'])
                                   || 1 == $this->vars['doimage']['value']) ? 1 : 0;
                        $br     = (!isset($this->vars['dobr']['value']) || 1 == $this->vars['dobr']['value']) ? 1 : 0;

                        /**
                         * Hack by marcan <INBOX> for SCSPRO
                         * Setting mastop as the main editor
                         */
                        if (defined('XOOPS_EDITOR_IS_HTML')) {
                            $br = false;
                        }

                        /**
                         * Hack by marcan <INBOX> for SCSPRO
                         * Setting mastop as the main editor
                         */

                        return $ts->displayTarea($ret, $html, $smiley, $xcode, $image, $br);
                        break 1;
                    case 'e':
                    case 'edit':
                        return htmlspecialchars($ret, ENT_QUOTES);
                        break 1;
                    case 'p':
                    case 'preview':
                        $ts     = \MyTextSanitizer::getInstance();
                        $html   = !empty($this->vars['dohtml']['value']) ? 1 : 0;
                        $xcode  = (!isset($this->vars['doxcode']['value'])
                                   || 1 == $this->vars['doxcode']['value']) ? 1 : 0;
                        $smiley = (!isset($this->vars['dosmiley']['value'])
                                   || 1 == $this->vars['dosmiley']['value']) ? 1 : 0;
                        $image  = (!isset($this->vars['doimage']['value'])
                                   || 1 == $this->vars['doimage']['value']) ? 1 : 0;
                        $br     = (!isset($this->vars['dobr']['value']) || 1 == $this->vars['dobr']['value']) ? 1 : 0;

                        return $ts->previewTarea($ret, $html, $smiley, $xcode, $image, $br);
                        break 1;
                    case 'f':
                    case 'formpreview':
                        $ts = \MyTextSanitizer::getInstance();

                        return htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                        break 1;
                    case 'n':
                    case 'none':
                    default:
                        break 1;
                }
                break;
            case XOBJ_DTYPE_SIMPLE_ARRAY:
                $ret =& explode('|', $ret);
                break;
            case XOBJ_DTYPE_ARRAY:
                $ret =& unserialize($ret);
                break;
            case XOBJ_DTYPE_SOURCE:
                switch (strtolower($format)) {
                    case 's':
                    case 'show':
                        break 1;
                    case 'e':
                    case 'edit':
                        return htmlspecialchars($ret, ENT_QUOTES);
                        break 1;
                    case 'p':
                    case 'preview':
                        $ts = \MyTextSanitizer::getInstance();

                        return $ts->stripSlashesGPC($ret);
                        break 1;
                    case 'f':
                    case 'formpreview':
                        $ts = \MyTextSanitizer::getInstance();

                        return htmlspecialchars($ts->stripSlashesGPC($ret), ENT_QUOTES);
                        break 1;
                    case 'n':
                    case 'none':
                    default:
                        break 1;
                }
                break;
            default:
                if ('' !== $this->vars[$key]['options'] && '' != $ret) {
                    switch (strtolower($format)) {
                        case 's':
                        case 'show':
                            $selected = explode('|', $ret);
                            $options  = explode('|', $this->vars[$key]['options']);
                            $i        = 1;
                            $ret      = [];
                            foreach ($options as $op) {
                                if (in_array($i, $selected)) {
                                    $ret[] = $op;
                                }
                                ++$i;
                            }

                            return implode(', ', $ret);
                        case 'e':
                        case 'edit':
                            $ret = explode('|', $ret);
                            break 1;
                        default:
                            break 1;
                    }
                }
                break;
        }

        return $ret;
    }

    /**
     * @param $key
     */
    public function doMakeFieldreadOnly($key)
    {
        if (isset($this->vars[$key])) {
            $this->vars[$key]['readonly']      = true;
            $this->vars[$key]['displayOnForm'] = true;
        }
    }

    /**
     * @param $key
     */
    public function makeFieldReadOnly($key)
    {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doMakeFieldreadOnly($v);
            }
        } else {
            $this->doMakeFieldreadOnly($key);
        }
    }

    /**
     * @param $key
     */
    public function doHideFieldFromForm($key)
    {
        if (isset($this->vars[$key])) {
            $this->vars[$key]['displayOnForm'] = false;
        }
    }

    /**
     * @param $key
     */
    public function doHideFieldFromSingleView($key)
    {
        if (isset($this->vars[$key])) {
            $this->vars[$key]['displayOnSingleView'] = false;
        }
    }

    /**
     * @param $key
     */
    public function hideFieldFromForm($key)
    {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doHideFieldFromForm($v);
            }
        } else {
            $this->doHideFieldFromForm($key);
        }
    }

    /**
     * @param $key
     */
    public function hideFieldFromSingleView($key)
    {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doHideFieldFromSingleView($v);
            }
        } else {
            $this->doHideFieldFromSingleView($key);
        }
    }

    /**
     * @param $key
     */
    public function doShowFieldOnForm($key)
    {
        if (isset($this->vars[$key])) {
            $this->vars[$key]['displayOnForm'] = true;
        }
    }

    /**
     * Display an automatic SingleView of the object, based on the displayOnSingleView param of each vars
     *
     * @param  bool  $fetchOnly if set to TRUE, then the content will be return, if set to FALSE, the content will be outputed
     * @param  bool  $userSide  for futur use, to do something different on the user side
     * @param  array $actions
     * @param  bool  $headerAsRow
     * @return string content of the template if $fetchOnly or nothing if !$fetchOnly
     */
    public function displaySingleObject(
        $fetchOnly = false,
        $userSide = false,
        $actions = [],
        $headerAsRow = true
    ) {
        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectsingleview.php';
        $singleview = new SmartObjectSingleView($this, $userSide, $actions, $headerAsRow);
        // add all fields mark as displayOnSingleView except the keyid
        foreach ($this->vars as $key => $var) {
            if ($key != $this->handler->keyName && $var['displayOnSingleView']) {
                $is_header = ($key == $this->handler->identifierName);
                $singleview->addRow(new SmartObjectRow($key, false, $is_header));
            }
        }

        if ($fetchOnly) {
            $ret = $singleview->render($fetchOnly);

            return $ret;
        } else {
            $singleview->render($fetchOnly);
        }
    }

    /**
     * @param $key
     */
    public function doDisplayFieldOnSingleView($key)
    {
        if (isset($this->vars[$key])) {
            $this->vars[$key]['displayOnSingleView'] = true;
        }
    }

    /**
     * @param      $field
     * @param bool $required
     */
    public function doSetFieldAsRequired($field, $required = true)
    {
        $this->setVarInfo($field, 'required', $required);
    }

    /**
     * @param $field
     */
    public function doSetFieldForSorting($field)
    {
        $this->setVarInfo($field, 'sortby', true);
    }

    /**
     * @param $key
     */
    public function showFieldOnForm($key)
    {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doShowFieldOnForm($v);
            }
        } else {
            $this->doShowFieldOnForm($key);
        }
    }

    /**
     * @param $key
     */
    public function displayFieldOnSingleView($key)
    {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doDisplayFieldOnSingleView($v);
            }
        } else {
            $this->doDisplayFieldOnSingleView($key);
        }
    }

    /**
     * @param $key
     */
    public function doSetAdvancedFormFields($key)
    {
        if (isset($this->vars[$key])) {
            $this->vars[$key]['advancedform'] = true;
        }
    }

    /**
     * @param $key
     */
    public function setAdvancedFormFields($key)
    {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doSetAdvancedFormFields($v);
            }
        } else {
            $this->doSetAdvancedFormFields($key);
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getUrlLinkObj($key)
    {
        $smartobjectLinkurlHandler = xoops_getModuleHandler('urllink', 'smartobject');
        $urllinkid                 = null !== $this->getVar($key) ? $this->getVar($key) : 0;
        if (0 != $urllinkid) {
            return $smartobjectLinkurlHandler->get($urllinkid);
        } else {
            return $smartobjectLinkurlHandler->create();
        }
    }

    /**
     * @param $urlLinkObj
     * @return mixed
     */
    public function &storeUrlLinkObj($urlLinkObj)
    {
        $smartobjectLinkurlHandler = xoops_getModuleHandler('urllink', 'smartobject');

        return $smartobjectLinkurlHandler->insert($urlLinkObj);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getFileObj($key)
    {
        $smartobjectFileHandler = xoops_getModuleHandler('file', 'smartobject');
        $fileid                 = null !== $this->getVar($key) ? $this->getVar($key) : 0;
        if (0 != $fileid) {
            return $smartobjectFileHandler->get($fileid);
        } else {
            return $smartobjectFileHandler->create();
        }
    }

    /**
     * @param $fileObj
     * @return mixed
     */
    public function &storeFileObj($fileObj)
    {
        $smartobjectFileHandler = xoops_getModuleHandler('file', 'smartobject');

        return $smartobjectFileHandler->insert($fileObj);
    }
}
