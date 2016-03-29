<?php
// $Id: customtag.php 159 2007-12-17 16:44:05Z malanciault $
// ------------------------------------------------------------------------ //
//               XOOPS - PHP Content Management System                      //
//                   Copyright (c) 2000 XOOPS.org                           //
//                      <http://www.xoops.org/>                             //
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
// URL: http://www.xoops.org/                                               //
// Project: The XOOPS Project                                               //
// -------------------------------------------------------------------------//

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartobject.php";

class SmartobjectCustomtag extends SmartObject {

    var $content = false;

    function SmartobjectCustomtag() {
        $this->quickInitVar('customtagid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CUSTOMTAG_NAME, _CO_SOBJECT_CUSTOMTAG_NAME_DSC);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false, _CO_SOBJECT_CUSTOMTAG_DESCRIPTION, _CO_SOBJECT_CUSTOMTAG_DESCRIPTION_DSC);
        $this->quickInitVar('content', XOBJ_DTYPE_TXTAREA, true, _CO_SOBJECT_CUSTOMTAG_CONTENT, _CO_SOBJECT_CUSTOMTAG_CONTENT_DSC);
        $this->quickInitVar('language', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CUSTOMTAG_LANGUAGE, _CO_SOBJECT_CUSTOMTAG_LANGUAGE_DSC);

        $this->initNonPersistableVar('dohtml', XOBJ_DTYPE_INT, 'class', 'dohtml', '', true);
        $this->initNonPersistableVar('doimage', XOBJ_DTYPE_INT, 'class', 'doimage', '', true);
        $this->initNonPersistableVar('doxcode', XOBJ_DTYPE_INT, 'class', 'doxcode', '', true);
        $this->initNonPersistableVar('dosmiley', XOBJ_DTYPE_INT, 'class', 'dosmiley', '', true);

        $this->setControl('content', array('name' => 'textarea',
                                        'form_editor' => 'textarea',
                                        'form_rows' => 25));
        $this->setControl('language', array('name' => 'language',
                                        'all' => true));

    }

    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array())) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function render() {
        if (!$this->content) {
            $ret = $this->getVar('content');
            $this->content = $ret;
        }
        return $this->content;
    }

    function renderWithPhp() {
        if (!$this->content) {
            $ret = $this->getVar('content');
            $this->content = $ret;
        } else {
            $ret = $this->content;
        }

        // check for PHP if we are not on admin side
        if (!defined('XOOPS_CPFUNC_LOADED') && !(strpos($ret, '[php]') === false)) {
            $ret = str_replace('[php]', '', $ret);
            // we have PHP code, let's evaluate
            eval($ret);
            return '';
        }
        return $this->content;
    }

    function getXoopsCode() {
        $ret = '[customtag]' . $this->getVar('tag', 'n'). '[/customtag]';
        return $ret;
    }

    function getCloneLink() {
        $ret = '<a href="' . SMARTOBJECT_URL . 'admin/customtag.php?op=clone&customtagid=' . $this->id() . '"><img src="' . SMARTOBJECT_IMAGES_ACTIONS_URL . 'editcopy.png" style="vertical-align: middle;" alt="' . _CO_SOBJECT_CUSTOMTAG_CLONE . '" title="' . _CO_SOBJECT_CUSTOMTAG_CLONE . '" /></a>';
        return $ret;
    }

    function emptyString($var)
    {
        return (strlen($var) > 0);
    }

    function generateTag() {
        $title = rawurlencode(strtolower($this->getVar('description', 'e')));
        $title = xoops_substr($title, 0, 10, '');
        // Transformation des ponctuations
        //                 Tab     Space      !        "        #        %        &        '        (        )        ,        /        :        ;        <        =        >        ?        @        [        \        ]        ^        {        |        }        ~       .
        $pattern = array("/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/", "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/", "/%7C/", "/%7D/", "/%7E/", "/\./");
        $rep_pat = array(  "-"  ,   "-"  ,   "-"  ,   "-"  ,   "-"  , "-100" ,   "-"  ,   "-"  ,   "-"  ,   "-"  ,   "-"  ,   "-"  ,  "-"   ,   "-"  ,   "-"  ,   "-"  ,  "-"   ,   "-"  , "-at-" ,   "-"  ,   "-"   ,  "-"  ,   "-"  ,   "-"  ,   "-"  ,   "-"  ,   "-"  ,   "-" );
        $title   = preg_replace($pattern, $rep_pat, $title);

        // Transformation des caractères accentués
        //                  °        è        é        ê        ë        ç        à        â        ä        î        ï        ù        ü        û        ô        ö
        $pattern = array("/%B0/", "/%E8/", "/%E9/", "/%EA/", "/%EB/", "/%E7/", "/%E0/", "/%E2/", "/%E4/", "/%EE/", "/%EF/", "/%F9/", "/%FC/", "/%FB/", "/%F4/", "/%F6/");
        $rep_pat = array(  "-"  ,   "e"  ,   "e"  ,   "e"  ,   "e"  ,   "c"  ,   "a"  ,   "a"  ,   "a"  ,   "i"  ,   "i"  ,   "u"  ,   "u"  ,   "u"  ,   "o"  ,   "o"  );
        $title   = preg_replace($pattern, $rep_pat, $title);

        $tableau = explode("-", $title); // Transforme la chaine de caractères en tableau
        $tableau = array_filter($tableau, array($this, "emptyString")); // Supprime les chaines vides du tableau
        $title   = implode("-", $tableau); // Transforme un tableau en chaine de caractères séparé par un tiret

        $title = $title . time();
        $title = md5($title);
        return $title;
    }

    function getCustomtagName() {
        $ret = $this->getVar('name');
        return $ret;
    }
}
class SmartobjectCustomtagHandler extends SmartPersistableObjectHandler {

    var $objects=false;

    function SmartobjectCustomtagHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'customtag', 'customtagid', 'name', 'description', 'smartobject');
        $this->addPermission('view', _CO_SOBJECT_CUSTOMTAG_PERMISSION_VIEW, _CO_SOBJECT_CUSTOMTAG_PERMISSION_VIEW_DSC);
    }

    function getCustomtagsByName() {
        if (!$this->objects) {
            global $xoopsConfig;

            $ret = array();

            $criteria = new CriteriaCompo();

            $criteria_language = new CriteriaCompo();
            $criteria_language->add(new Criteria('language', $xoopsConfig['language']));
            $criteria_language->add(new Criteria('language', 'all'), 'OR');
            $criteria->add($criteria_language);

            $smartobject_permissions_handler = new SmartObjectPermissionHandler($this);
            $granted_ids = $smartobject_permissions_handler->getGrantedItems('view');

            if ($granted_ids && count($granted_ids) > 0) {
                $criteria->add(new Criteria('customtagid', '(' . implode(', ', $granted_ids) . ')', 'IN'));
                $customtagsObj = $this->getObjects($criteria, true);
                foreach($customtagsObj as $customtagObj) {
                    $ret[$customtagObj->getVar('name')] = $customtagObj;
                }
            }
            $this->objects = $ret;
        }
        return $this->objects;
    }
}

?>