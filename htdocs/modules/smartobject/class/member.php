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
require_once XOOPS_ROOT_PATH . '/kernel/user.php';
require_once XOOPS_ROOT_PATH . '/kernel/group.php';
require_once XOOPS_ROOT_PATH . '/kernel/member.php';

/**
 * XOOPS member handler class.
 * This class provides simple interface (a facade class) for handling groups/users/
 * membership data.
 *
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package   kernel
 */
class SmartobjectMemberHandler extends XoopsMemberHandler
{
    /**
     * constructor
     * @param XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
        $this->_uHandler = xoops_getModuleHandler('user', 'smartobject');
    }

    /**
     * @param       $userObj
     * @param  bool $groups
     * @param  bool $notifyUser
     * @param  bool $password
     * @return bool
     */
    public function addAndActivateUser($userObj, $groups = false, $notifyUser = true, &$password = false)
    {
        $email = $userObj->getVar('email');
        if (!$userObj->getVar('email') || '' === $email) {
            $userObj->setErrors(_CO_SOBJECT_USER_NEED_EMAIL);

            return false;
        }

        $password = $userObj->getVar('pass');
        // randomly generating the password if not already set
        if ('' === $password) {
            $password = substr(md5(uniqid(mt_rand(), 1)), 0, 6);
        }
        $userObj->setVar('pass', md5($password));

        // if no username is set, let's generate one
        $unamecount = 20;
        $uname      = $userObj->getVar('uname');
        if (!$uname || '' === $uname) {
            $usernames = $this->genUserNames($email, $unamecount);
            $newuser   = false;
            $i         = 0;
            while (false === $newuser) {
                $crit  = new \Criteria('uname', $usernames[$i]);
                $count = $this->getUserCount($crit);
                if (0 == $count) {
                    $newuser = true;
                } else {
                    //Move to next username
                    ++$i;
                    if ($i == $unamecount) {
                        //Get next batch of usernames to try, reset counter
                        $usernames = $this->genUserNames($email, $unamecount);
                        $i         = 0;
                    }
                }
            }
        }

        global $xoopsConfig;

        $configHandler   = xoops_getHandler('config');
        $xoopsConfigUser = $configHandler->getConfigsByCat(XOOPS_CONF_USER);
        switch ($xoopsConfigUser['activation_type']) {
            case 0:
                $level           = 0;
                $mailtemplate    = 'smartmail_activate_user.tpl';
                $aInfoMessages[] = sprintf(_NL_MA_NEW_USER_NEED_ACT, $user_email);
                break;
            case 1:
                $level           = 1;
                $mailtemplate    = 'smartmail_auto_activate_user.tpl';
                $aInfoMessages[] = sprintf(_NL_MA_NEW_USER_AUTO_ACT, $user_email);
                break;
            case 2:
            default:
                $level           = 0;
                $mailtemplate    = 'smartmail_admin_activate_user.tpl';
                $aInfoMessages[] = sprintf(_NL_MA_NEW_USER_ADMIN_ACT, $user_email);
        }

        $userObj->setVar('uname', $usernames[$i]);
        $userObj->setVar('user_avatar', 'blank.gif');
        $userObj->setVar('user_regdate', time());
        $userObj->setVar('timezone_offset', $xoopsConfig['default_TZ']);
        $actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
        $userObj->setVar('actkey', $actkey);
        $userObj->setVar('email', $email);
        $userObj->setVar('notify_method', 2);
        $userObj->setVar('level', $userObj);

        if ($this->insertUser($userObj)) {

            // if $groups=false, Add the user to Registered Users group
            if (!$groups) {
                $this->addUserToGroup(XOOPS_GROUP_USERS, $userObj->getVar('uid'));
            } else {
                foreach ($groups as $groupid) {
                    $this->addUserToGroup($groupid, $userObj->getVar('uid'));
                }
            }
        } else {
            return false;
        }

        if ($notifyUser) {
            // send some notifications
            $xoopsMailer = xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setTemplateDir(SMARTOBJECT_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/mail_template');
            $xoopsMailer->setTemplate('smartobject_notify_user_added_by_admin.tpl');
            $xoopsMailer->assign('XOOPS_USER_PASSWORD', $password);
            $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
            $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
            $xoopsMailer->assign('SITEURL', XOOPS_URL . '/');
            $xoopsMailer->assign('NAME', $userObj->getVar('name'));
            $xoopsMailer->assign('UNAME', $userObj->getVar('uname'));
            $xoopsMailer->setToUsers($userObj);
            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
            $xoopsMailer->setFromName($xoopsConfig['sitename']);
            $xoopsMailer->setSubject(sprintf(_CO_SOBJECT_NEW_USER_NOTIFICATION_SUBJECT, $xoopsConfig['sitename']));

            if (!$xoopsMailer->send(true)) {
                /**
                 * @todo trap error if email was not sent
                 */
                $xoopsMailer->getErrors(true);
            }
        }

        return true;
    }

    /**
     * Generates an array of usernames
     *
     * @param  string $email email of user
     * @param  int    $count number of names to generate
     * @return array  $names
     * @internal param string $name name of user
     * @author   xHelp Team
     *
     * @access   public
     */
    public function genUserNames($email, $count = 20)
    {
        $name = substr($email, 0, strpos($email, '@')); //Take the email adress without domain as username

        $names  = [];
        $userid = explode('@', $email);

        $basename    = '';
        $hasbasename = false;
        $emailname   = $userid[0];

        $names[] = $emailname;

        if (strlen($name) > 0) {
            $name = explode(' ', trim($name));
            if (count($name) > 1) {
                $basename = strtolower(substr($name[0], 0, 1) . $name[count($name) - 1]);
            } else {
                $basename = strtolower($name[0]);
            }
            $basename = xoops_substr($basename, 0, 60, '');
            //Prevent Duplication of Email Username and Name
            if (!in_array($basename, $names)) {
                $names[]     = $basename;
                $hasbasename = true;
            }
        }

        $i          = count($names);
        $onbasename = 1;
        while ($i < $count) {
            $num = $this->genRandNumber();
            if ($onbasename < 0 && $hasbasename) {
                $names[] = xoops_substr($basename, 0, 58, '') . $num;
            } else {
                $names[] = xoops_substr($emailname, 0, 58, '') . $num;
            }
            $i          = count($names);
            $onbasename = ~$onbasename;
            $num        = '';
        }

        return $names;
    }

    /**
     * Creates a random number with a specified number of $digits
     *
     * @param  int $digits number of digits
     * @return return int random number
     * @author xHelp Team
     *
     * @access public
     */
    public function genRandNumber($digits = 2)
    {
        $this->initRand();
        $tmp = [];

        for ($i = 0; $i < $digits; ++$i) {
            $tmp[$i] = (mt_rand() % 9);
        }

        return implode('', $tmp);
    }

    /**
     * Gives the random number generator a seed to start from
     *
     * @return void
     *
     * @access public
     */
    public function initRand()
    {
        static $randCalled = false;
        if (!$randCalled) {
            mt_srand((double)microtime() * 1000000);
            $randCalled = true;
        }
    }
}
