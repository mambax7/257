<?php
// $Id: smartobjecttag.php 159 2007-12-17 16:44:05Z malanciault $
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
include_once XOOPS_ROOT_PATH."/modules/smartobject/class/smartmlobject.php";
class SmartobjectTag extends SmartMlObject {

    function SmartobjectTag() {
        $this->initVar('tagid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_TAG_TAGID_CAPTION, _CO_SOBJECT_TAG_TAGID_DSC, true);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', true, null, '', false, _CO_SOBJECT_TAG_DESCRIPTION_CAPTION, _CO_SOBJECT_TAG_DESCRIPTION_DSC);
        $this->initVar('value', XOBJ_DTYPE_TXTAREA, '', true, null, '', true, _CO_SOBJECT_TAG_VALUE_CAPTION, _CO_SOBJECT_TAG_VALUE_DSC);

        // call parent constructor to get Multilanguage field initiated
        $this->SmartMlObject();
    }
}
class SmartobjectTagHandler extends SmartPersistableMlObjectHandler {
    function SmartobjectTagHandler($db) {
        $this->SmartPersistableObjectHandler($db, 'tag', 'tagid', 'name', 'description', 'smartobject');
    }

    function getLanguages() {
        include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
        $aLanguages = XoopsLists::getLangList();
        $ret['default'] = _CO_SOBJECT_ALL;
        foreach($aLanguages as $lang) {
            $ret[$lang] = $lang;
        }
        return $ret;
    }
}
?>