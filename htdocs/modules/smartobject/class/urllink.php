<?php
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/basedurl.php';

/**
 * Class SmartobjectUrlLink
 */
class SmartobjectUrlLink extends SmartobjectBasedUrl
{
    /**
     * SmartobjectUrlLink constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->quickInitVar('urllinkid', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('target', XOBJ_DTYPE_TXTBOX, true);

        $this->setControl('target', [
            'options' => [
                '_self'  => _CO_SOBJECT_URLLINK_SELF,
                '_blank' => _CO_SOBJECT_URLLINK_BLANK
            ]
        ]);
    }
}

/**
 * Class SmartobjectUrlLinkHandler
 */
class SmartobjectUrlLinkHandler extends SmartPersistableObjectHandler
{
    /**
     * SmartobjectUrlLinkHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'urllink', 'urllinkid', 'caption', 'desc', 'smartobject');
    }
}
