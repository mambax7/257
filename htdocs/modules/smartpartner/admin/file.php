<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

require_once __DIR__ . '/admin_header.php';

global $smartPartnerFileHandler;

$op = '';
if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

/**
 * @param bool $showmenu
 * @param int  $fileid
 * @param int  $id
 */
function editfile($showmenu = false, $fileid = 0, $id = 0)
{
    global $smartPartnerFileHandler, $xoopsModule;

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    // if there is a parameter, and the id exists, retrieve data: we're editing a file
    if (0 != $fileid) {

        // Creating the File object
        $fileObj = new smartpartnerfile($fileid);

        if ($fileObj->notLoaded()) {
            redirect_header('javascript:history.go(-1)', 1, _AM_SPARTNER_NOFILESELECTED);
        }

        echo "<br>\n";
        echo "<span style='color: #2F5376; font-weight: bold; font-size: 16px; margin: 6px 6px 0 0; '>" . _AM_SPARTNER_FILE_EDITING . '</span>';
        echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . _AM_SPARTNER_FILE_EDITING_DSC . '</span>';
        smartpartner_collapsableBar('editfile', 'editfileicon', _AM_SPARTNER_FILE_INFORMATIONS);
    } else {
        // there's no parameter, so we're adding an item
        $fileObj = $smartPartnerFileHandler->create();
        $fileObj->setVar('id', $id);

        echo "<span style='color: #2F5376; font-weight: bold; font-size: 16px; margin: 6px 6px 0 0; '>" . _AM_SPARTNER_FILE_ADDING . '</span>';
        echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . _AM_SPARTNER_FILE_ADDING_DSC . '</span>';
        smartpartner_collapsableBar('addfile', 'addfileicon', _AM_SPARTNER_FILE_INFORMATIONS);
    }

    // FILES UPLOAD FORM
    $files_form = new \XoopsThemeForm(_AM_SPARTNER_UPLOAD_FILE, 'files_form', xoops_getenv('PHP_SELF'), 'post', true);
    $files_form->setExtra("enctype='multipart/form-data'");

    // NAME
    $name_text = new \XoopsFormText(_AM_SPARTNER_FILE_NAME, 'name', 50, 255, $fileObj->name());
    $name_text->setDescription(_AM_SPARTNER_FILE_NAME_DSC);
    $files_form->addElement($name_text, true);

    // DESCRIPTION
    $description_text = new \XoopsFormTextArea(_AM_SPARTNER_FILE_DESCRIPTION, 'description', $fileObj->description());
    $description_text->setDescription(_AM_SPARTNER_FILE_DESCRIPTION_DSC);
    $files_form->addElement($description_text);

    // FILE TO UPLOAD
    if (0 == $fileid) {
        $file_box = new \XoopsFormFile(_AM_SPARTNER_FILE_TO_UPLOAD, 'userfile', 0);
        $file_box->setExtra("size ='50'");
        $files_form->addElement($file_box);
    }

    $status_select = new \XoopsFormRadioYN(_AM_SPARTNER_FILE_STATUS, 'file_status', $fileObj->status());
    $status_select->setDescription(_AM_SPARTNER_FILE_STATUS_DSC);
    $files_form->addElement($status_select);

    $files_button_tray = new \XoopsFormElementTray('', '');
    $files_hidden      = new \XoopsFormHidden('op', 'uploadfile');
    $files_button_tray->addElement($files_hidden);

    if (0 == $fileid) {
        $files_butt_create = new \XoopsFormButton('', '', _AM_SPARTNER_UPLOAD, 'submit');
        $files_butt_create->setExtra('onclick="this.form.elements.op.value=\'uploadfile\'"');
        $files_button_tray->addElement($files_butt_create);

        $files_butt_another = new \XoopsFormButton('', '', _AM_SPARTNER_FILE_UPLOAD_ANOTHER, 'submit');
        $files_butt_another->setExtra('onclick="this.form.elements.op.value=\'uploadanother\'"');
        $files_button_tray->addElement($files_butt_another);
    } else {
        $files_butt_create = new \XoopsFormButton('', '', _AM_SPARTNER_MODIFY, 'submit');
        $files_butt_create->setExtra('onclick="this.form.elements.op.value=\'modify\'"');
        $files_button_tray->addElement($files_butt_create);
    }

    $files_butt_clear = new \XoopsFormButton('', '', _AM_SPARTNER_CLEAR, 'reset');
    $files_button_tray->addElement($files_butt_clear);

    $butt_cancel = new \XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
    $butt_cancel->setExtra('onclick="history.go(-1)"');
    $files_button_tray->addElement($butt_cancel);

    $files_form->addElement($files_button_tray);

    // fileid
    $files_form->addElement(new \XoopsFormHidden('fileid', $fileid));

    // id
    $files_form->addElement(new \XoopsFormHidden('id', $id));

    $files_form->display();

    if (0 != $fileid) {
        smartpartner_close_collapsable('editfile', 'editfileicon');
    } else {
        smartpartner_close_collapsable('addfile', 'addfileicon');
    }
}

$false = false;
/* -- Available operations -- */
switch ($op) {
    case 'uploadfile':
        smartpartner_upload_file(false, true, $false);
        exit;
        break;

    case 'uploadanother':
        smartpartner_upload_file(true, true, $false);
        exit;
        break;

    case 'mod':
        global $smartPartnerFileHandler;
        $fileid = isset($_GET['fileid']) ? $_GET['fileid'] : 0;
        $id     = isset($_GET['id']) ? $_GET['id'] : 0;
        if ((0 == $fileid) && (0 == $id)) {
            redirect_header('javascript:history.go(-1)', 3, _AM_SPARTNER_NOITEMSELECTED);
        }

        smartpartner_xoops_cp_header();
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        editfile(true, $fileid, $id);
        break;

    case 'modify':
        global $xoopsUser;

        $fileid = isset($_POST['fileid']) ? (int)$_POST['fileid'] : 0;

        // Creating the file object
        if (0 != $fileid) {
            $fileObj = new SmartpartnerFile($fileid);
        } else {
            $fileObj = $smartPartnerFileHandler->create();
        }

        // Putting the values in the file object
        $fileObj->setVar('name', $_POST['name']);
        $fileObj->setVar('description', $_POST['description']);
        $fileObj->setVar('status', (int)$_POST['file_status']);

        // Storing the file
        if (!$fileObj->store()) {
            redirect_header('partner.php?op=mod&id=' . $fileObj->id(), 3, _AM_SPARTNER_FILE_EDITING_ERROR . smartpartner_formatErrors($fileObj->getErrors()));
        }

        redirect_header('partner.php?op=mod&id=' . $fileObj->id(), 2, _AM_SPARTNER_FILE_EDITING_SUCCESS);
        break;

    case 'del':
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $_GET;

        $module_id    = $xoopsModule->getVar('mid');
        $gpermHandler = xoops_getHandler('groupperm');

        $fileid = isset($_POST['fileid']) ? (int)$_POST['fileid'] : 0;
        $fileid = isset($_GET['fileid']) ? (int)$_GET['fileid'] : $fileid;

        $fileObj = new SmartpartnerFile($fileid);

        $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
        $title   = isset($_POST['title']) ? $_POST['title'] : '';

        if ($confirm) {
            if (!$smartPartnerFileHandler->delete($fileObj)) {
                redirect_header('partner.php', 2, _AM_SPARTNER_FILE_DELETE_ERROR);
            }

            redirect_header('partner.php', 2, sprintf(_AM_SPARTNER_FILEISDELETED, $fileObj->name()));
        } else {
            // no confirm: show deletion condition
            $fileid = isset($_GET['fileid']) ? (int)$_GET['fileid'] : 0;

            smartpartner_xoops_cp_header();
            xoops_confirm([
                              'op'      => 'del',
                              'fileid'  => $fileObj->fileid(),
                              'confirm' => 1,
                              'name'    => $fileObj->name()
                          ], 'file.php', _AM_SPARTNER_DELETETHISFILE . ' <br>' . $fileObj->name() . ' <br> <br>', _AM_SPARTNER_DELETE);
            xoops_cp_footer();
        }

        exit();
        break;

    case 'default':
    default:
        smartpartner_xoops_cp_header();

        exit;
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule;

        echo "<br>\n";

        smartpartner_collapsableBar('toptable', 'toptableicon', _AM_SPARTNER_PUBLISHEDITEMS, _AM_SPARTNER_PUBLISHED_DSC);

        // Get the total number of published ITEM
        $totalitems = $smartPartnerItemHandler->getItemsCount(-1, [_SPARTNER_STATUS_PUBLISHED]);

        // creating the item objects that are published
        $itemsObj         = $smartPartnerItemHandler->getAllPublished($xoopsModuleConfig['perpage'], $startitem);
        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<td width='40' class='bg3' align='center'><b>" . _AM_SPARTNER_ITEMID . '</b></td>';
        echo "<td width='20%' class='bg3' align='left'><b>" . _AM_SPARTNER_ITEMCATEGORYNAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_SPARTNER_TITLE . '</b></td>';
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_CREATED . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . '</b></td>';
        echo '</tr>';
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->category();

                $modify = "<a href='partner.php?op=mod&id=" . $itemsObj[$i]->id() . "'><img src='" . $pathIcon16 . '/edit.png' . "' title='" . _AM_SPARTNER_EDITITEM . "' alt='" . _AM_SPARTNER_EDITITEM . "'></a>";
                $delete = "<a href='partner.php?op=del&id=" . $itemsObj[$i]->id() . "'><img src='" . $pathIcon16 . '/delete.png' . "' title='" . _AM_SPARTNER_EDITITEM . "' alt='" . _AM_SPARTNER_DELETEITEM . "'></a>";

                echo '<tr>';
                echo "<td class='head' align='center'>" . $itemsObj[$i]->id() . '</td>';
                echo "<td class='even' align='left'>" . $categoryObj->name() . '</td>';
                echo "<td class='even' align='left'><a href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/partner.php?id=' . $itemsObj[$i]->id() . "'>" . $itemsObj[$i]->title() . '</a></td>';
                echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub() . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td>";
                echo '</tr>';
            }
        } else {
            $id = -1;
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . _AM_SPARTNER_NOITEMS . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        echo "<br>\n";

        $pagenav = new \XoopsPageNav($totalitems, $xoopsModuleConfig['perpage'], $startitem, 'startitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo '</div>';

        $totalcategories = $smartPartnerCategoryHandler->getCategoriesCount(-1);
        if ($totalcategories > 0) {
            edititem();
        }

        break;
}
//smart_modFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
