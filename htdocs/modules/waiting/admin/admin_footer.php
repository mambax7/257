<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Waiting
 *
 * @category        Module
 * @package         waiting
 * @author          Ryuji AMANO <info@ryus.co.jp>
 * @author          XOOPS Module Development Team
 * @copyright       {@link https://xoops.org 2001-2016 XOOPS Project}
 * @license         {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @link            http://ryus.co.jp Ryu's Planning
 * @link            https://xoops.org XOOPS
 */

$pathIcon32 = \Xmf\Module\Admin::iconUrl('', 32);
echo "<div class='adminfooter'>\n" . "  <div style='text-align: center;'>\n" . "    <a href='https://xoops.org' rel='external'><img src='{$pathIcon32}/xoopsmicrobutton.gif' alt='XOOPS' title='XOOPS'></a>\n" . "  </div>\n" . '  ' . _AM_WAITING_FOOTER . "\n" . '</div>';

xoops_cp_footer();
