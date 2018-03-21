<?php namespace XoopsModules\Xoopsheadline;

use Xmf\Request;
use XoopsModules\Xoopsheadline;
use XoopsModules\Xoopsheadline\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------
}
