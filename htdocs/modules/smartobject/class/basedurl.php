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
 * Class SmartobjectBasedUrl
 */
class SmartobjectBasedUrl extends SmartObject
{
    /**
     * SmartobjectBasedUrl constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('caption', XOBJ_DTYPE_TXTBOX, false);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTBOX, false);
        $this->quickInitVar('url', XOBJ_DTYPE_TXTBOX, false);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 'e')
    {
        if (0 === strpos($key, 'url_')) {
            return parent::getVar('url', $format);
        } elseif (0 === strpos($key, 'caption_')) {
            return parent::getVar('caption', $format);
        } else {
            return parent::getVar($key, $format);
        }
    }
}
