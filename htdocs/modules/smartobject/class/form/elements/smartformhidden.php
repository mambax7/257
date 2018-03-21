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
 * Class SmartFormHidden
 */
class SmartFormHidden extends XoopsFormHidden
{
    /**
     * @return string
     */
    public function render()
    {
        if (is_array($this->getValue())) {
            $ret = '';
            foreach ($this->getValue() as $value) {
                $ret .= "<input type='hidden' name='" . $this->getName() . "[]' id='" . $this->getName() . "' value='" . $value . "'>\n";
            }
        } else {
            $ret = "<input type='hidden' name='" . $this->getName() . "' id='" . $this->getName() . "' value='" . $this->getValue() . "'>";
        }

        return $ret;
    }
}
