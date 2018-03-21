<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * An email text field
 */
class SmartFormSelectElement extends XoopsFormElement
{
    /**
     * @param  bool $withtags
     * @return string
     */
    public function renderValidationJS($withtags = true)
    {
        $js = '';

        return $js;
    }
}
