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

/**
 * Class SmartobjectCustomtag
 */
class SmartobjectCustomtag extends SmartObject
{
    public $content = false;

    /**
     * SmartobjectCustomtag constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('customtagid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CUSTOMTAG_NAME, _CO_SOBJECT_CUSTOMTAG_NAME_DSC);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false, _CO_SOBJECT_CUSTOMTAG_DESCRIPTION, _CO_SOBJECT_CUSTOMTAG_DESCRIPTION_DSC);
        $this->quickInitVar('content', XOBJ_DTYPE_TXTAREA, true, _CO_SOBJECT_CUSTOMTAG_CONTENT, _CO_SOBJECT_CUSTOMTAG_CONTENT_DSC);
        $this->quickInitVar('language', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CUSTOMTAG_LANGUAGE, _CO_SOBJECT_CUSTOMTAG_LANGUAGE_DSC);

        $this->initNonPersistableVar('dohtml', XOBJ_DTYPE_INT, 'class', 'dohtml', '', true);
        $this->initNonPersistableVar('doimage', XOBJ_DTYPE_INT, 'class', 'doimage', '', true);
        $this->initNonPersistableVar('doxcode', XOBJ_DTYPE_INT, 'class', 'doxcode', '', true);
        $this->initNonPersistableVar('dosmiley', XOBJ_DTYPE_INT, 'class', 'dosmiley', '', true);

        $this->setControl('content', [
            'name'        => 'textarea',
            'form_editor' => 'textarea',
            'form_rows'   => 25
        ]);
        $this->setControl('language', [
            'name' => 'language',
            'all'  => true
        ]);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, [])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return bool|mixed
     */
    public function render()
    {
        if (!$this->content) {
            $ret           = $this->getVar('content');
            $this->content = $ret;
        }

        return $this->content;
    }

    /**
     * @return bool|mixed|string
     */
    public function renderWithPhp()
    {
        if (!$this->content) {
            $ret           = $this->getVar('content');
            $this->content = $ret;
        } else {
            $ret = $this->content;
        }

        // check for PHP if we are not on admin side
        if (!defined('XOOPS_CPFUNC_LOADED') && !(false === strpos($ret, '[php]'))) {
            $ret = str_replace('[php]', '', $ret);
            // we have PHP code, let's evaluate
            eval($ret);

            return '';
        }

        return $this->content;
    }

    /**
     * @return string
     */
    public function getXoopsCode()
    {
        $ret = '[customtag]' . $this->getVar('tag', 'n') . '[/customtag]';

        return $ret;
    }

    /**
     * @return string
     */
    public function getCloneLink()
    {
        $ret = '<a href="' . SMARTOBJECT_URL . 'admin/customtag.php?op=clone&customtagid=' . $this->id() . '"><img src="' . SMARTOBJECT_IMAGES_ACTIONS_URL . 'editcopy.png" style="vertical-align: middle;" alt="' . _CO_SOBJECT_CUSTOMTAG_CLONE . '" title="' . _CO_SOBJECT_CUSTOMTAG_CLONE . '"></a>';

        return $ret;
    }

    /**
     * @param $var
     * @return bool
     */
    public function emptyString($var)
    {
        return (strlen($var) > 0);
    }

    /**
     * @return mixed|string
     */
    public function generateTag()
    {
        $title = rawurlencode(strtolower($this->getVar('description', 'e')));
        $title = xoops_substr($title, 0, 10, '');
        // Transformation des ponctuations
        $pattern = [
            '/%09/', // Tab
            '/%20/', // Space
            '/%21/', // !
            '/%22/', // "
            '/%23/', // #
            '/%25/', // %
            '/%26/', // &
            '/%27/', // '
            '/%28/', // (
            '/%29/', // )
            '/%2C/', // ,
            '/%2F/', // /
            '/%3A/', // :
            '/%3B/', // ;
            '/%3C/', // <
            '/%3D/', // =
            '/%3E/', // >
            '/%3F/', // ?
            '/%40/', // @
            '/%5B/', // [
            '/%5C/', // \
            '/%5D/', // ]
            '/%5E/', // ^
            '/%7B/', // {
            '/%7C/', // |
            '/%7D/', // }
            '/%7E/', // ~
            "/\./" // .
        ];
        $rep_pat = [
            '-',
            '-',
            '-',
            '-',
            '-',
            '-100',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-at-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-'
        ];
        $title   = preg_replace($pattern, $rep_pat, $title);

        // Transformation des caractères accentués
        $pattern = [
            '/%B0/', // °
            '/%E8/', // è
            '/%E9/', // é
            '/%EA/', // ê
            '/%EB/', // ë
            '/%E7/', // ç
            '/%E0/', // à
            '/%E2/', // â
            '/%E4/', // ä
            '/%EE/', // î
            '/%EF/', // ï
            '/%F9/', // ù
            '/%FC/', // ü
            '/%FB/', // û
            '/%F4/', // ô
            '/%F6/', // ö
        ];
        $rep_pat = ['-', 'e', 'e', 'e', 'e', 'c', 'a', 'a', 'a', 'i', 'i', 'u', 'u', 'u', 'o', 'o'];
        $title   = preg_replace($pattern, $rep_pat, $title);

        $tableau = explode('-', $title); // Transforme la chaine de caract�res en tableau
        $tableau = array_filter($tableau, [$this, 'emptyString']); // Supprime les chaines vides du tableau
        $title   = implode('-', $tableau); // Transforme un tableau en chaine de caract�res s�par� par un tiret

        $title .= time();
        $title = md5($title);

        return $title;
    }

    /**
     * @return mixed
     */
    public function getCustomtagName()
    {
        $ret = $this->getVar('name');

        return $ret;
    }
}

/**
 * Class SmartobjectCustomtagHandler
 */
class SmartobjectCustomtagHandler extends SmartPersistableObjectHandler
{
    public $objects = false;

    /**
     * SmartobjectCustomtagHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'customtag', 'customtagid', 'name', 'description', 'smartobject');
        $this->addPermission('view', _CO_SOBJECT_CUSTOMTAG_PERMISSION_VIEW, _CO_SOBJECT_CUSTOMTAG_PERMISSION_VIEW_DSC);
    }

    /**
     * @return array|bool
     */
    public function getCustomtagsByName()
    {
        if (!$this->objects) {
            global $xoopsConfig;

            $ret = [];

            $criteria = new \CriteriaCompo();

            $criteria_language = new \CriteriaCompo();
            $criteria_language->add(new \Criteria('language', $xoopsConfig['language']));
            $criteria_language->add(new \Criteria('language', 'all'), 'OR');
            $criteria->add($criteria_language);

            $smartobjectPermissionsHandler = new SmartObjectPermissionHandler($this);
            $granted_ids                   = $smartobjectPermissionsHandler->getGrantedItems('view');

            if ($granted_ids && count($granted_ids) > 0) {
                $criteria->add(new \Criteria('customtagid', '(' . implode(', ', $granted_ids) . ')', 'IN'));
                $customtagsObj =& $this->getObjects($criteria, true);
                foreach ($customtagsObj as $customtagObj) {
                    $ret[$customtagObj->getVar('name')] = $customtagObj;
                }
            }
            $this->objects = $ret;
        }

        return $this->objects;
    }
}
