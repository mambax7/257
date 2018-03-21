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

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class XoopsheadlineHeadline
 */
class XoopsheadlineHeadline extends XoopsObject
{

    /**
     * XoopsheadlineHeadline constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('headline_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('headline_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_url', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_rssurl', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_cachetime', XOBJ_DTYPE_INT, 600, false);
        $this->initVar('headline_asblock', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_display', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_encoding', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('headline_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_mainimg', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('headline_mainfull', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('headline_mainmax', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('headline_blockimg', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_blockmax', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('headline_xml', XOBJ_DTYPE_SOURCE, null, false);
        $this->initVar('headline_updated', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @return bool
     */
    public function cacheExpired()
    {
        if (time() - $this->getVar('headline_updated') > $this->getVar('headline_cachetime')) {
            return true;
        }

        return false;
    }
}

/**
 * Class xoopsheadlineHeadlineHandler
 */
class xoopsheadlineHeadlineHandler extends XoopsPersistableObjectHandler
{

    /**
     * xoopsheadlineHeadlineHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'xoopsheadline', 'xoopsheadline' . 'Headline', 'headline_id');
    }
}
