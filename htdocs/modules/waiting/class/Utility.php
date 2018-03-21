<?php namespace XoopsModules\Waiting;

use Xmf\Request;
use XoopsModules\Waiting;
use XoopsModules\Waiting\Common;

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
