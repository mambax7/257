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
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

xoops_load('XoopsheadlineUtility', $xoopsModule->getVar('dirname'));
$op = 'list';

if (isset($_GET['op']) && ('delete' === $_GET['op'] || 'edit' === $_GET['op'] || 'flush' === $_GET['op'])) {
    $op          = $_GET['op'];
    $headline_id = (int)$_GET['headline_id'];
}

/* headline_id - an array of integers
 * headline_display
 * headline_asblock
 */
//@TODO: Replace following routine by only importing known variables
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

switch ($op) {
    case 'list':
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $hlman    = xoops_getModuleHandler('headline');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('headline_weight');
        $criteria->setOrder('ASC');
        $headlines = $hlman->getObjects($criteria);
        $count     = count($headlines);

        global $thisModDir;

        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        echo "\n<div style='margin-bottom: 2em;'>\n"
             . '<h4>'
             . _AM_HEADLINES_HEADLINES
             . "</h4>\n"
             . "<form name='xoopsheadline_form' action='main.php' method='post'>\n"
             . "  <table class='outer' style='margin: 1px;' id='hllist'>\n"
             . "    <thead><tr style='text-align: left;'>\n"
             . '      <th>'
             . _AM_HEADLINES_ORDER
             . "</th>\n"
             . '      <th>'
             . _AM_HEADLINES_SITENAME
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_CACHETIME
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_ENCODING
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_DISPLAY
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_ASBLOCK
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_ACTIONS
             . "</th>\n"
             . "      <th>&nbsp;</th>\n"
             . "    </tr></thead>\n";
        $cachetime = [
            '3600'    => sprintf(_HOUR, 1),
            '18000'   => sprintf(_HOURS, 5),
            '86400'   => sprintf(_DAY, 1),
            '259200'  => sprintf(_DAYS, 3),
            '604800'  => sprintf(_WEEK, 1),
            '2592000' => sprintf(_MONTH, 1)
        ];
        $encodings = ['utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'];
        $tdclass   = 'odd';
        echo '    <tbody>';
        for ($i = 0; $i < $count; $i++) {
            echo "    <tr>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><input style='text-align: right;' type='text' maxlength='3' size='4' name='headline_weight[]' value='"
                 . $headlines[$i]->getVar('headline_weight')
                 . "'></td>\n"
                 . "      <td class='{$tdclass}' style='vertical-align: middle; padding-left: 1em;'><a href='"
                 . XOOPS_URL
                 . "/modules/{$thisModDir}/index.php?id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'>"
                 . $headlines[$i]->getVar('headline_name')
                 . "</a></td>\n"
                 //               . "      <td class='{$tdclass}' style='vertical-align: middle; padding-left: 1em;'>" . $headlines[$i]->getVar('headline_name') . "</td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><select name=\"headline_cachetime[]\">";
            foreach ($cachetime as $value => $name) {
                $sel = ($value == $headlines[$i]->getVar('headline_cachetime')) ? ' selected="selected"' : '';
                echo "<option value=\"{$value}\"{$sel}>{$name}</option>";
            }
            echo "</select></td>\n" . "      <td class='center {$tdclass}' style='vertical-align: middle;'><select name=\"headline_encoding[]\">";
            foreach ($encodings as $value => $name) {
                $sel = ($value == $headlines[$i]->getVar('headline_encoding')) ? ' selected = "selected"' : '';
                echo "<option value=\"{$value}\"{$sel}>{$name}</option>";
            }
            $chkd = (1 == $headlines[$i]->getVar('headline_display')) ? ' checked="checked"' : '';
            $chkb = (1 == $headlines[$i]->getVar('headline_asblock')) ? ' checked="checked"' : '';
            echo "</select></td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><input type=\"checkbox\" value=\"1\" name=\"headline_display["
                 . $headlines[$i]->getVar('headline_id')
                 . "]\"{$chkd}></td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><input type=\"checkbox\" value=\"1\" name=\"headline_asblock["
                 . $headlines[$i]->getVar('headline_id')
                 . "]\"{$chkb}></td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><a href='main.php?op=edit&amp;headline_id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'><img src={$pathIcon16}/edit.png alt='"
                 . _EDIT
                 . "' title='"
                 . _EDIT
                 . "'></a>&nbsp;\n"
                 . "          <a href='main.php?op=delete&amp;headline_id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'><img src={$pathIcon16}/delete.png alt='"
                 . _DELETE
                 . "' title='"
                 . _DELETE
                 . "'></a>\n"
                 . "          <a href='main.php?op=flush&amp;headline_id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'><img src='../assets/images/reload.png' alt='"
                 . _AM_HEADLINES_CACHEFL
                 . "' title='"
                 . _AM_HEADLINES_CACHEFL
                 . "'></a>\n"
                 . "          <input type='hidden' name='headline_id[]' value='"
                 . $headlines[$i]->getVar('headline_id')
                 . "'>\n"
                 . "      </td>\n"
                 . "    </tr>\n";
            $tdclass = ('odd' === $tdclass) ? 'even' : 'odd';
        }

        echo "    </tbody>\n"
             . "    <tfoot><tr><td class='center {$tdclass}' colspan='7' style='padding: .5em;'>\n"
             . "      <input type='hidden' name='op' value='update'>\n"
             . "      <input type='submit' name='headline_submit' value='"
             . _AM_HEADLINES_UPDATE
             . "'>\n"
             . "    </td></tr></tfoot>\n"
             . "  </table>\n"
             . "</form>\n"
             . "</div>\n"
             . "<div style='margin-bottom: 1em;'>\n"
             . "<h4 style='padding-left: 1em;'>"
             . _AM_HEADLINES_ADDHEADL
             . "</h4>\n";
        $form = new \XoopsThemeForm(_AM_HEADLINES_ADDHEADL, 'xoopsheadline_form_new', 'main.php', 'post', true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_SITENAME, 'headline_name', 50, 255), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URL, 'headline_url', 50, 255, 'http://'), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URLEDFXML, 'headline_rssurl', 50, 255, 'http://'), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_ORDER, 'headline_weight', 4, 3, 0));

        $enc_sel = new \XoopsFormSelect(_AM_HEADLINES_ENCODING, 'headline_encoding', 'utf-8');
        $enc_sel->addOptionArray($encodings);
        $form->addElement($enc_sel);

        $cache_sel = new \XoopsFormSelect(_AM_HEADLINES_CACHETIME, 'headline_cachetime', 86400);
        $cache_sel->addOptionArray([
                                       '3600'    => _HOUR,
                                       '18000'   => sprintf(_HOURS, 5),
                                       '86400'   => _DAY,
                                       '259200'  => sprintf(_DAYS, 3),
                                       '604800'  => _WEEK,
                                       '2592000' => _MONTH
                                   ]);
        $form->addElement($cache_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_MAINSETT . '</span>', 'center');
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPLAY, 'headline_display', 1, _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_mainimg', 0, _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPFULL, 'headline_mainfull', 0, _YES, _NO));

        $mmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_mainmax', 10);
        $mmax_sel->addOptionArray([
                                      '1'  => 1,
                                      '5'  => 5,
                                      '10' => 10,
                                      '15' => 15,
                                      '20' => 20,
                                      '25' => 25,
                                      '30' => 30
                                  ]);
        $form->addElement($mmax_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_BLOCKSETT . '</span>', 'center');
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_ASBLOCK, 'headline_asblock', 1, _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_blockimg', 0, _YES, _NO));

        $bmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_blockmax', 5);
        $bmax_sel->addOptionArray([
                                      '1'  => 1,
                                      '5'  => 5,
                                      '10' => 10,
                                      '15' => 15,
                                      '20' => 20,
                                      '25' => 25,
                                      '30' => 30
                                  ]);
        $form->addElement($bmax_sel);

        $form->insertBreak();
        $form->addElement(new \XoopsFormHidden('op', 'addgo'));
        $form->addElement(new \XoopsFormButtonTray('headline_submit', _SUBMIT));
        $form->display();
        echo "</div>\n";
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'update':
        $hlman = xoops_getModuleHandler('headline');
        $i     = 0;
        $msg   = '';
        foreach ($headline_id as $id) {
            $hl =& $hlman->get($id);
            if (!is_object($hl)) {
                $i++;
                continue;
            }
            $headline_display[$id] = empty($headline_display[$id]) ? 0 : $headline_display[$id];
            $headline_asblock[$id] = empty($headline_asblock[$id]) ? 0 : $headline_asblock[$id];
            $old_cachetime         = $hl->getVar('headline_cachetime');
            $hl->setVar('headline_cachetime', $headline_cachetime[$i]);
            $old_display = $hl->getVar('headline_display');
            $hl->setVar('headline_display', $headline_display[$id]);
            $hl->setVar('headline_weight', $headline_weight[$i]);
            $old_asblock = $hl->getVar('headline_asblock');
            $hl->setVar('headline_asblock', $headline_asblock[$id]);
            $old_encoding = $hl->getVar('headline_encoding');
            if (!$hlman->insert($hl)) {
                $msg .= '<br>' . sprintf(_AM_HEADLINES_FAILUPDATE, $hl->getVar('headline_name'));
            } else {
                if ('' === $hl->getVar('headline_xml')) {
                    $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($hl);
                    if (!$renderer->updateCache()) {
                        xoops_error($hl->getErrors(true));
                        require_once __DIR__ . '/admin_footer.php';
                    }
                }
            }
            $i++;
        }
        if ('' != $msg) {
            xoops_cp_header();
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error($msg);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'addgo':
        if ($GLOBALS['xoopsSecurity']->check()) {
            $hlman = xoops_getModuleHandler('headline', $xoopsModule->getVar('dirname', 'n'));
            $hl    = $hlman->create();
            $hl->setVar('headline_name', $headline_name);
            $hl->setVar('headline_url', $headline_url);
            $hl->setVar('headline_rssurl', $headline_rssurl);
            $hl->setVar('headline_display', $headline_display);
            $hl->setVar('headline_weight', $headline_weight);
            $hl->setVar('headline_asblock', $headline_asblock);
            $hl->setVar('headline_encoding', $headline_encoding);
            $hl->setVar('headline_cachetime', $headline_cachetime);
            $hl->setVar('headline_mainfull', $headline_mainfull);
            $hl->setVar('headline_mainimg', $headline_mainimg);
            $hl->setVar('headline_mainmax', $headline_mainmax);
            $hl->setVar('headline_blockimg', $headline_blockimg);
            $hl->setVar('headline_blockmax', $headline_blockmax);
            $hl->setVar('headline_xml', $headline_blockmax);
            $hlIdx = $hlman->insert($hl);
            if (!$hlIdx) {
                $msg         = sprintf(_AM_HEADLINES_FAILUPDATE, $hl->getVar('headline_name'));
                $msg         .= '<br>' . $hl->getErrors();
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));
                echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
                xoops_error($msg);
                require_once __DIR__ . '/admin_footer.php';
                exit();
            } else {
                if ('' == $hl->getVar('headline_xml')) {
                    $hlObj    = $hlman->get($hlIdx);
                    $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($hlObj);
                    if (!$renderer->updateCache()) {
                        xoops_error($hlObj->getErrors(true));
                        require_once __DIR__ . '/admin_footer.php';
                    }
                }
            }
        } else {
            redirect_header('main.php', 2, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'edit':
        if ($headline_id <= 0) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hlman = xoops_getModuleHandler('headline');
        $hl    = $hlman->get($headline_id);
        if (!is_object($hl)) {
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(_AM_HEADLINES_EDITHEADL, 'xoopsheadline_form', 'main.php', 'post', true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_SITENAME, 'headline_name', 100, 255, $hl->getVar('headline_name')), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URL, 'headline_url', 100, 255, $hl->getVar('headline_url')), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URLEDFXML, 'headline_rssurl', 100, 255, $hl->getVar('headline_rssurl')), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_ORDER, 'headline_weight', 4, 3, $hl->getVar('headline_weight')));

        $enc_sel = new \XoopsFormSelect(_AM_HEADLINES_ENCODING, 'headline_encoding', $hl->getVar('headline_encoding'));
        $enc_sel->addOptionArray(['utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII']);
        $form->addElement($enc_sel);

        $cache_sel = new \XoopsFormSelect(_AM_HEADLINES_CACHETIME, 'headline_cachetime', $hl->getVar('headline_cachetime'));
        $cache_sel->addOptionArray([
                                       '3600'    => _HOUR,
                                       '18000'   => sprintf(_HOURS, 5),
                                       '86400'   => _DAY,
                                       '259200'  => sprintf(_DAYS, 3),
                                       '604800'  => _WEEK,
                                       '2592000' => _MONTH
                                   ]);
        $form->addElement($cache_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_MAINSETT . '</span>', 'center');
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPLAY, 'headline_display', $hl->getVar('headline_display'), _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_mainimg', $hl->getVar('headline_mainimg'), _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPFULL, 'headline_mainfull', $hl->getVar('headline_mainfull'), _YES, _NO));

        $mmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_mainmax', $hl->getVar('headline_mainmax'));
        $mmax_sel->addOptionArray([
                                      '1'  => 1,
                                      '5'  => 5,
                                      '10' => 10,
                                      '15' => 15,
                                      '20' => 20,
                                      '25' => 25,
                                      '30' => 30
                                  ]);
        $form->addElement($mmax_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_BLOCKSETT . '</span>', 'center');
        $form->insertBreak(_AM_HEADLINES_BLOCKSETT);
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_ASBLOCK, 'headline_asblock', $hl->getVar('headline_asblock'), _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_blockimg', $hl->getVar('headline_blockimg'), _YES, _NO));

        $bmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_blockmax', $hl->getVar('headline_blockmax'));
        $bmax_sel->addOptionArray([
                                      '1'  => 1,
                                      '5'  => 5,
                                      '10' => 10,
                                      '15' => 15,
                                      '20' => 20,
                                      '25' => 25,
                                      '30' => 30
                                  ]);
        $form->addElement($bmax_sel);

        $form->insertBreak();
        $form->addElement(new \XoopsFormHidden('headline_id', $hl->getVar('headline_id')));
        $form->addElement(new \XoopsFormHidden('op', 'editgo'));
        $form->addElement(new \XoopsFormButtonTray('headline_submit', _SUBMIT));
        echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4><br>';
        //echo '<a href="main.php">'. _AM_HEADLINES_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$hl->getVar('headline_name').'<br><br>';
        $form->display();
        require_once __DIR__ . '/admin_footer.php';
        exit();
        break;
    case 'editgo':
        $headline_id = $headline_id;
        if ($headline_id <= 0) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hlman = xoops_getModuleHandler('headline');
        $hl    =& $hlman->get($headline_id);
        if (!is_object($hl)) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hl->setVar('headline_name', $headline_name);
        $hl->setVar('headline_url', $headline_url);
        $hl->setVar('headline_encoding', $headline_encoding);
        $hl->setVar('headline_rssurl', $headline_rssurl);
        $hl->setVar('headline_display', $headline_display);
        $hl->setVar('headline_weight', $headline_weight);
        $hl->setVar('headline_asblock', $headline_asblock);
        $hl->setVar('headline_cachetime', $headline_cachetime);
        $hl->setVar('headline_mainfull', $headline_mainfull);
        $hl->setVar('headline_mainimg', $headline_mainimg);
        $hl->setVar('headline_mainmax', $headline_mainmax);
        $hl->setVar('headline_blockimg', $headline_blockimg);
        $hl->setVar('headline_blockmax', $headline_blockmax);

        if (!$GLOBALS['xoopsSecurity']->check() || !$hlman->insert($hl)) {
            $msg         = sprintf(_AM_HEADLINES_FAILUPDATE, $hl->getVar('headline_name'));
            $msg         .= '<br>' . $hl->getErrors();
            $msg         .= '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error($msg);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        } else {
            if ('' == $hl->getVar('headline_xml')) {
                $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($hl);
                if (!$renderer->updateCache()) {
                    xoops_error($hl->getErrors(true));
                    require_once __DIR__ . '/admin_footer.php';
                }
            }
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'delete':
        if ($headline_id <= 0) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hlman = xoops_getModuleHandler('headline');
        $hl    =& $hlman->get($headline_id);
        if (!is_object($hl)) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $name = $hl->getVar('headline_name');
        echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
        //        echo '<a href="main.php">'. _AM_HEADLINES_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$name.'<br><br>';
        xoops_confirm(['op' => 'deletego', 'headline_id' => $hl->getVar('headline_id')], 'main.php', sprintf(_AM_HEADLINES_WANTDEL, $name));
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'deletego':
        $headline_id = $headline_id;
        if ($headline_id <= 0) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hlman = xoops_getModuleHandler('headline');
        $hl    =& $hlman->get($headline_id);
        if (!is_object($hl)) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        if (!$GLOBALS['xoopsSecurity']->check() || !$hlman->delete($hl)) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(sprintf(_AM_HEADLINES_FAILUPDELETE, $hl->getVar('headline_name')) . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'flush':
        if ($headline_id <= 0) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hlman = xoops_getModuleHandler('headline');
        $hl    =& $hlman->get($headline_id);
        if (!is_object($hl)) {
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $name = $hl->getVar('headline_name');
        echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
        xoops_confirm(['op' => 'flushgo', 'headline_id' => $hl->getVar('headline_id')], 'main.php', sprintf(_AM_HEADLINES_WANTFLUSH, $name));
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'flushgo':
        if ($headline_id <= 0) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $hlman = xoops_getModuleHandler('headline');
        $hl    =& $hlman->get($headline_id);
        if (!is_object($hl)) {
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__)) . '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>' . "<div style='margin: 1em;'>\n";
            xoops_error(sprintf(_AM_HEADLINES_FAILFLUSH, $hl->getVar('headline_name')) . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            echo "</div>\n";
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $renderer =& XoopsheadlineUtility::xoopsheadline_getrenderer($hl);
        if (!$renderer->updateCache()) {
            xoops_error($hl->getErrors(true));
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        redirect_header('main.php', 2, _AM_HEADLINES_CACHEUPD);
        break;
}
