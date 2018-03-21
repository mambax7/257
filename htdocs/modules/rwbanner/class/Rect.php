<?php namespace XoopsModules\Rwbanner;

//  ------------------------------------------------------------------------ //
//                                  RW-Banner                                //
//                    Copyright (c) 2006 Web Applications                    //
//                     <http://www.bcsg.com.br/rwbanner>                    //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author: Rodrigo Pereira Lima (Web Applications)                           //
// Site: http://www.bcsg.com.br/rwbanner                                     //
// Project: RW-Banner                                                        //
// Descrição: Sistema de gerenciamento de mídias publicitárias               //
// ------------------------------------------------------------------------- //


use XoopsModules\Rwbanner;

/**
 * class Rect
 * store the size values into an associative array
 */
class Rect
{
    /**
     * Rect constructor.
     * @param $x2
     * @param $y2
     */
    public function __construct($x2, $y2)
    {
        $this->xmax  = $x2;
        $this->ymax  = $y2;
        $this->value = $this->__str__();
    }

    /**
     * @return array
     */
    public function __str__()
    {
        $ret           = [$this->xmax, $this->ymax];
        $ret['width']  = $this->xmax;
        $ret['height'] = $this->ymax;

        return $ret;
    }
}
/* end */
