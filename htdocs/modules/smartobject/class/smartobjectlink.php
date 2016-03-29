<?php
// $Id: smartobjectlink.php 159 2007-12-17 16:44:05Z malanciault $
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
class SmartobjectLink extends SmartObject {

    function SmartobjectLink() {
        $this->initVar('linkid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('date', XOBJ_DTYPE_INT, 0, false, null,'', false, _CO_SOBJECT_LINK_DATE, '', true, true, false);
        $this->initVar('from_uid', XOBJ_DTYPE_INT, '', false, null, '', false, _CO_SOBJECT_LINK_FROM_UID, _CO_SOBJECT_LINK_FROM_UID_DSC);
        $this->initVar('from_email', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_LINK_FROM_EMAIL, _CO_SOBJECT_LINK_FROM_EMAIL_DSC, true);
        $this->initVar('from_name', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_LINK_FROM_NAME, _CO_SOBJECT_LINK_FROM_NAME_DSC, true);
        $this->initVar('to_uid', XOBJ_DTYPE_INT, '', false, null, '', false, _CO_SOBJECT_LINK_TO_UID, _CO_SOBJECT_LINK_TO_UID_DSC);
        $this->initVar('to_email', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_LINK_TO_EMAIL, _CO_SOBJECT_LINK_TO_EMAIL_DSC, true);
        $this->initVar('to_name', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_LINK_TO_NAME, _CO_SOBJECT_LINK_TO_NAME_DSC, true);
        $this->initVar('link', XOBJ_DTYPE_TXTBOX, '', false, 255, '', false, _CO_SOBJECT_LINK_LINK, _CO_SOBJECT_LINK_LINK_DSC, true);
        $this->initVar('subject', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_LINK_SUBJECT, _CO_SOBJECT_LINK_SUBJECT_DSC, true);
        $this->initVar('body', XOBJ_DTYPE_TXTAREA, '', true, null, '', false, _CO_SOBJECT_LINK_BODY, _CO_SOBJECT_LINK_BODY_DSC);
        $this->initVar('mid', XOBJ_DTYPE_INT, '', false, null, '', false, _CO_SOBJECT_LINK_MID, _CO_SOBJECT_LINK_MID_DSC);
        $this->initVar('mid_name', XOBJ_DTYPE_TXTBOX, '', false, 255, '', false, _CO_SOBJECT_LINK_MID_NAME, _CO_SOBJECT_LINK_MID_NAME_DSC, true);
    }


    /**
    * returns a specific variable for the object in a proper format
    *
    * @access public
    * @param string $key key of the object's variable to be returned
    * @param string $format format to use for the output
    * @return mixed formatted value of the variable
    */
    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('from_uid', 'to_uid', 'date', 'link'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }

    function from_uid() {
        $ret = smart_getLinkedUnameFromId($this->getVar('from_uid', 'e'), 1, null, true);
        return $ret;
    }

    function to_uid($withContact=false) {
        $ret = smart_getLinkedUnameFromId($this->getVar('to_uid', 'e'), 1, null, true);
        return $ret;
    }

    function date() {
        $ret = formatTimestamp($this->getVar('date','e'));
        return $ret;
    }

    function link($full=false) {
        $ret = $this->getVar('link','e');
        if ($full) {
            $myts = MyTextSanitizer::getInstance();
            $ret = $myts->displayTarea($ret);
            return $ret;
        } else {
            $ret = '<a href="' . $ret . '" alt="' . $this->getVar('link','e') . '" title="' . $this->getVar('link','e') . '">' .  _AM_SOBJECT_SENT_LINKS_GOTO . '</a>';
            return $ret;
        }
    }

    function getViewItemLink() {
        $ret = '<a href="' . SMARTOBJECT_URL . 'admin/link.php?op=view&linkid=' . $this->getVar('linkid') . '"><img src="' . SMARTOBJECT_IMAGES_ACTIONS_URL . 'mail_find.png" alt="' . _AM_SOBJECT_SENT_LINK_VIEW . '" title="' . _AM_SOBJECT_SENT_LINK_VIEW . '" /></a>';
        return $ret;
    }

    function getFromInfo() {
        // check if from_uid represent a user

        if ($this->getVar('from_uid')) {
            $user = smart_getLinkedUnameFromId($this->getVar('from_uid'));
            if ($user == $GLOBALS['xoopsConfig']['anonymous']) {
                $user = '<a href="mailto:' . $this->getVar('from_email') . '">' . $this->getVar('from_email') . '</a>';
            }
        } else {
            $user = '<a href="mailto:' . $this->getVar('from_email') . '">' . $this->getVar('from_email') . '</a>';
        }

        return $user;

    }

    function toArray() {
        $ret = parent::toArray();
        $ret['fromInfo'] = $this->getFromInfo();
        $ret['toInfo'] = $this->getToInfo();
        $ret['fullLink'] = $this->link(true);
        return $ret;
    }

    function getToInfo() {
        // check if from_uid represent a user

        if ($this->getVar('to_uid')) {
            $user = smart_getLinkedUnameFromId($this->getVar('to_uid'));
            if ($user == $GLOBALS['xoopsConfig']['anonymous']) {
                $user = '<a href="mailto:' . $this->getVar('to_email') . '">' . $this->getVar('to_email') . '</a>';
            }
        } else {
            $user = '<a href="mailto:' . $this->getVar('to_email') . '">' . $this->getVar('to_email') . '</a>';
        }

        return $user;

    }
}
class SmartobjectLinkHandler extends SmartPersistableObjectHandler {
    function SmartobjectLinkHandler($db) {

        $this->SmartPersistableObjectHandler($db, 'link', 'linkid', 'subject', 'body', 'smartobject');
    }

}
?>