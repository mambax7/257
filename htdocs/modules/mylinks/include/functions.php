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
 * @copyright     {@link https://xoops.org/ XOOPS Project}
 * @license       {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package       mylinks
 * @since
 * @author        XOOPS Development Team
 */

require_once XOOPS_ROOT_PATH . '/class/tree.php';

/**
 * @param $time
 * @param $status
 * @return string
 */
function newlinkgraphic($time, $status)
{
    $count     = 7;
    $new       = '';
    $startdate = (time() - (86400 * $count));

    if ($startdate < $time) {
        if (1 == $status) {
            $new = "&nbsp;<img src='" . mylinksGetIconURL('newred.gif') . "' alt='" . _MD_MYLINKS_NEWTHISWEEK . "'>";
        } elseif (2 == $status) {
            $new = "&nbsp;<img src='" . mylinksGetIconURL('update.gif') . "' alt='" . _MD_MYLINKS_UPTHISWEEK . "'>";
        }
    }

    return $new;
}

/**
 * @param $hits
 * @return string
 */
function popgraphic($hits)
{
    global $xoopsModuleConfig;
    $retVal = '';

    if (isset($hits) && ($hits >= $xoopsModuleConfig['popular'])) {
        $retVal = "&nbsp;<img src='" . mylinksGetIconURL('pop.gif') . "' alt='" . _MD_MYLINKS_POPULAR . "'>";
    }

    return $retVal;
}

/*
 * Reusable Link Sorting Functions
 *
 * @param string orderby is a shortened string for sorting
 * @return string returns a dB 'ready' ORDER BY string for dB query
 */
/**
 * @param $orderby
 * @return string
 */
function convertorderbyin($orderby)
{
    $orderby = (isset($orderby) && ('' != trim($orderby))) ? trim($orderby) : '';
    switch ($orderby) {
        case 'titleA':
            $orderby = 'title ASC';
            break;
        case 'hitsA':
            $orderby = 'hits ASC';
            break;
        case 'ratingA':
            $orderby = 'rating ASC';
            break;
        case 'dateA':
            $orderby = 'date ASC';
            break;
        case 'titleD':
            $orderby = 'title DESC';
            break;
        case 'hitsD':
            $orderby = 'hits DESC';
            break;
        case 'ratingD':
            $orderby = 'rating DESC';
            break;
        case 'dateD':
        default:
            $orderby = 'date DESC';
            break;
    }

    return $orderby;
}

/**
 * @param $orderby
 * @return string
 */
function convertorderbytrans($orderby)
{
    $orderby = (isset($orderby) && ('' != trim($orderby))) ? trim($orderby) : '';
    switch ($orderby) {
        case 'title ASC':
            $orderbyTrans = '' . _MD_MYLINKS_TITLEATOZ . '';
            break;
        case 'hits ASC':
            $orderbyTrans = '' . _MD_MYLINKS_POPULARITYLTOM . '';
            break;
        case 'rating ASC':
            $orderbyTrans = '' . _MD_MYLINKS_RATINGLTOH . '';
            break;
        case 'date ASC':
            $orderbyTrans = '' . _MD_MYLINKS_DATEOLD . '';
            break;
        case 'title DESC':
            $orderbyTrans = '' . _MD_MYLINKS_TITLEZTOA . '';
            break;
        case 'hits DESC':
            $orderbyTrans = '' . _MD_MYLINKS_POPULARITYMTOL . '';
            break;
        case 'rating DESC':
            $orderbyTrans = '' . _MD_MYLINKS_RATINGHTOL . '';
            break;
        case 'date DESC':
        default:
            $orderbyTrans = '' . _MD_MYLINKS_DATENEW . '';
            break;
    }

    return $orderbyTrans;
}

/**
 * @param $orderby
 * @return string
 */
function convertorderbyout($orderby)
{
    $orderby = (isset($orderby) && ('' != trim($orderby))) ? trim($orderby) : '';
    switch ($orderby) {
        case 'title ASC':
            $orderby = 'titleA';
            break;
        case 'hits ASC':
            $orderby = 'hitsA';
            break;
        case 'rating ASC':
            $orderby = 'ratingA';
            break;
        case 'date ASC':
            $orderby = 'dateA';
            break;
        case 'title DESC':
            $orderby = 'titleD';
            break;
        case 'hits DESC':
            $orderby = 'hitsD';
            break;
        case 'rating DESC':
            $orderby = 'ratingD';
            break;
        case 'date DESC':
        default:
            $orderby = 'dateD';
            break;
    }

    return $orderby;
}

/**
 * Update rating data for a link in dB link table to keep in sync
 * with the vote dB table contents
 * @param int $sel_id Listing ID to update
 */
function updaterating($sel_id)
{
    global $xoopsDB;
    $sel_id     = (int)$sel_id;
    $sql        = 'SELECT COUNT(*), FORMAT(AVG(rating),4) FROM ' . $xoopsDB->prefix('mylinks_votedata') . " WHERE lid={$sel_id}";
    $voteResult = $xoopsDB->query($sql);
    if ($voteResult) {
        list($votesDB, $finalrating) = $xoopsDB->fetchRow($voteResult);
        /*
            $query = "SELECT rating FROM " . $xoopsDB->prefix("mylinks_votedata") . " WHERE lid={$sel_id}";
            $voteresult = $xoopsDB->query($query);
            $votesDB = $xoopsDB->getRowsNum($voteresult);
            $totalrating = 0;
            while (false !== (list($rating)=$xoopsDB->fetchRow($voteresult))) {
                $totalrating += $rating;
            }
            $finalrating = $totalrating/$votesDB;
            $finalrating = number_format($finalrating, 4);
        */
        $query = 'UPDATE ' . $xoopsDB->prefix('mylinks_links') . " SET rating={$finalrating}, votes={$votesDB} WHERE lid = {$sel_id}";
        $xoopsDB->query($query) || exit();
    }
}

//returns the total number of items in items table that are accociated with a given table $table id
/**
 * @param  null   $sel_id
 * @param  string $status
 * @param  string $oper
 * @return mixed
 */
function getTotalItems($sel_id = null, $status = '', $oper = '>')
{
    $sel_id = filter_var($sel_id, FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 0]]);
    $count  = 0;
    $arr    = [];

    // get XoopsObjectTree for categories
    $mylinksCatHandler = xoops_getModuleHandler('category', 'mylinks');
    $catFields         = ['cid', 'pid'];
    $catObjs           = $mylinksCatHandler->getAll(null, $catFields);
    $myCatTree         = new \XoopsObjectTree($catObjs, 'cid', 'pid');

    /* new count routine */
    $childObjArray = $myCatTree->getAllChild($sel_id);
    //    $whereClause = "`cid`=0";
    $whereClause = "`cid`={$sel_id}";
    if (!empty($childObjArray)) {
        $whereClause = "`cid` IN ({$sel_id}";
        foreach ($childObjArray as $childObj) {
            $whereClause .= ',' . $childObj->getVar('cid');
        }
        $whereClause .= ')';
    }
    $query = 'SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix('mylinks_links') . " WHERE {$whereClause}";
    if ('' !== $status) {
        $status = (int)$status;
        if (preg_match('/^[!]*[<=>]{1}[=>]*$/', $oper, $match)) {
            $oper = $match[0];
        } else {
            $oper = '>';
        }
        //        $oper   = (0 == (int)($status)) ? '=': '>';
        $query .= " AND status{$oper}{$status}";
    }
    $result = $GLOBALS['xoopsDB']->query($query);
    list($linkCount) = $GLOBALS['xoopsDB']->fetchRow($result);

    return $linkCount;
}

/*
function getTotalItems($sel_id=NULL, $status='', $oper='>')
{
    global $xoopsDB, $xoopsModule;

    $sel_id = filter_var($sel_id, FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0, 'min_range' => 0)));
    $count = 0;
    $arr = array();

    // get XoopsObjectTree for categories
    $mylinksCatHandler = xoops_getModuleHandler('category', $xoopsModule->getVar('dirname'));
    $catObjs = $mylinksCatHandler->getAll();
    $myCatTree = new \XoopsObjectTree($catObjs, 'cid', 'pid');

    // new count routine
    $childObjArray = $myCatTree->getAllChild($sel_id);
    $catIds = "({$sel_id}";
    foreach ($childObjArray as $childObj) {
        $catIds .= ',' . $childObj->getVar('cid');
    }
    $catIds .= ')';
    $query = "SELECT COUNT(*) FROM " . $xoopsDB->prefix("mylinks_links") . " WHERE `cid` IN {$catIds}";
    if ('' !== $status) {
        $status = (int)($status);
        if ( preg_match($oper, "~^[!]?[<=>]{1}[=>]*$~", $match) ) {
            $oper = $match[0];
        } else {
            $oper = '>';
        }
//        $oper   = (0 == (int)($status)) ? '=': '>';
        $query .= " AND status{$oper}{$status}";
    }
    $result = $xoopsDB->query($query);
    list($linkCount) = $xoopsDB->fetchRow($result);

    return $linkCount;
}
*/
//wanikoo
/**
 * @param $aFile
 * @return string
 */
function mylinksGetStyleURL($aFile)
{
    global $mylinks_theme;
    $StyleURL = XOOPSMYLINKINCURL . "/{$mylinks_theme}/icons/{$aFile}";

    if (file_exists(XOOPSMYLINKINCPATH . "/{$mylinks_theme}/icons/{$aFile}")) {
        return $StyleURL;
    } else {
        return XOOPSMYLINKINCURL . "/icons/{$aFile}";
    }
}

//
/**
 * @param $aFile
 * @return string
 */
function mylinksGetIconURL($aFile)
{
    global $mylinks_theme;

    if (file_exists(XOOPSMYLINKIMGPATH . "/{$mylinks_theme}/icons/{$aFile}")) {
        return XOOPSMYLINKIMGURL . "/{$mylinks_theme}/icons/{$aFile}";
    } else {
        return XOOPSMYLINKIMGURL . "/icons/{$aFile}";
    }
}

//
/**
 * @param         $aFile
 * @param  string $subPath
 * @param  bool   $relPath
 * @return string
 */
function mylinksGetStylePath($aFile, $subPath = '', $relPath = true)
{
    global $mylinks_theme, $xoopsModule;
    //sanitize subPath to make sure it's only contains valid path chars
    $subPath = (!preg_match('/^(\D+)(\d*)$/', $subPath, $regs)) ? '' : $subPath;

    $path = $subPath ? 'modules/' . $xoopsModule->getVar('dirname') : XOOPSMYLINKPATH . '/modules/' . $xoopsModule->getVar('dirname') . '/';

    $subPath   = (!empty($subPath)) ? "/{$subPath}" : '';
    $stylePath = "{$path}{$subPath}/{$mylinks_theme}/{$aFile}";

    return file_exists($stylePath) ? $stylePath : "{$path}{$subPath}/{$aFile}";
}

/**
 * @return string
 */
function ml_wfd_letters()
{
    global $xoopsDB, $xoopsModule;

    xoops_loadLanguage('main', $xoopsModule->getVar('dirname'));
    $alphabet = explode(',', _MD_MYLINKS_LTRCHARS);

    $result      = $xoopsDB->query('SELECT COUNT(*), LEFT(title, 1) AS sletter FROM ' . $xoopsDB->prefix('mylinks_links') . ' WHERE status>0 GROUP BY sletter');
    $letterArray = [];
    while (false !== (list($count, $sletter) = $xoopsDB->fetchRow($result))) {
        $sletter               = mb_strtoupper($sletter);
        $letterArray[$sletter] = $count;
    }

    $letterchoice = "<div class='browsebyletter'>" . _MD_MYLINKS_BROWSETOTOPIC . '</div>';
    $letterchoice .= '[  ';
    $num          = count($alphabet) - 1;
    $halfNum      = round($num / 2);
    $counter      = 0;
    foreach ($alphabet as $key => $ltr) {
        if (array_key_exists($ltr, $letterArray)) {
            $letterchoice .= "<a class='browsebyletter' href='" . XOOPSMYLINKURL . "/viewcat.php?list={$ltr}'>{$ltr}</a>";
        } else {
            $letterchoice .= $ltr;
        }
        if ($counter == $halfNum) {
            $letterchoice .= ' ]<br>[ ';
        } elseif ($counter != $num) {
            $letterchoice .= '&nbsp;|&nbsp;';
        }
        ++$counter;
    }
    $letterchoice .= ' ]';

    return $letterchoice;
}

/**
 * @return string
 */
function ml_wfd_toolbar()
{
    global $xoopsModuleConfig, $xoopsUser;
    $toolbar = "[ <a href='index.php' class='toolbar'>" . _MD_MYLINKS_MAIN . '</a> | ';
    if (is_object($xoopsUser) || (!is_object($xoopsUser) && $xoopsModuleConfig['anonpost'])) {
        $toolbar .= "<a href='submit.php' class='toolbar'>" . _MI_MYLINKS_SMNAME1 . '</a> | ';
    }
    $toolbar .= "<a href='topten.php?sort=2' class='toolbar'>" . _MI_MYLINKS_SMNAME2 . "</a> | <a href='topten.php?sort=1' class='toolbar'>" . _MI_MYLINKS_SMNAME3 . "</a> | <a href='topten.php?sort=3' class='toolbar'>" . _MI_MYLINKS_SMNAME4 . '</a> ]';

    return $toolbar;
}
