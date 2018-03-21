<?php
//  ------------------------------------------------------------------------ //
//                                  RW-Banner                                //
//                    Copyright (c) 2006 BrInfo                              //
//                     <http://www.brinfo.com.br>                            //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author: Rodrigo Pereira Lima (BrInfo - Soluções Web)                      //
// Site: http://www.brinfo.com.br                                            //
// Project: RW-Banner                                                        //
// Descrição: Sistema de gerenciamento de mídias publicitárias               //
// ------------------------------------------------------------------------- //

use Xmf\Request;
use  XoopsModules\Rwbanner;

require_once __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';

require_once XOOPS_ROOT_PATH . '/class/uploader.php';
// require_once __DIR__ . '/../class/class.banner.php';
$local_folder = $xoopsModuleConfig['dir_images'];

$op = Request::getCmd('op', '');
$id = Request::getCmd('id', '');

if (isset($_POST['post'])) {
    $op = 'grava';
}
if (isset($_POST['form'])) {
    $form = $_POST['form'];
}

global $xoopsDB, $XoopsModule;
switch ($op) {
    case 'grava':
        $data = date('y:m:d h:i:s');
        if ('' == $form['grafico']) {
            //Inicio da rotina de upload de arquivo
            $maxfilesize = 500000;
            $uploader    = new \XoopsMediaUploader($local_folder, require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/mimetypes.inc.php', $maxfilesize);
            for ($i = 0, $iMax = count($_POST['xoops_upload_file']); $i <= $iMax; ++$i) {
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][$i])) {
                    if (!$uploader->upload()) {
                        redirect_header('index.php', 2, $uploader->getErrors());
                    } else {
                        $file_name = $uploader->getSavedFileName();
                    }
                }
            }
            //Fim da rotina de upload de arquivo
            $upfolder        = substr($local_folder, strlen(XOOPS_ROOT_PATH) + 1, strlen($local_folder));
            $form['grafico'] = (1 != $form['usarhtml']) ? XOOPS_URL . '/' . $upfolder . '/' . $file_name : '';
        }

        $form['usarhtml'] = isset($form['usarhtml']) ? $form['usarhtml'] : 0;
        $form['maxexib']  = isset($form['maxexibe']) ? $form['maxexibe'] : 0;
        $form['maxclick'] = isset($form['maxclick']) ? $form['maxclick'] : 0;
        $form['periodo']  = isset($form['periodo']) ? $form['periodo'] : 0;
        $form['showimg']  = 1;

        $banner = new Rwbanner\Banner($form);

        if (_AM_RWBANNER_BTN_OP1 == $_POST['post']) {
            if ($banner->grava()) {
                redirect_header('index.php', 1, _AM_RWBANNER_MSG8);
            } else {
                redirect_header('index.php', 1, _AM_RWBANNER_MSG10);
            }
        } elseif (_AM_RWBANNER_BTN_OP2 == $_POST['post']) {
            if (2 == $banner->getStatus() && 0 != $banner->getCategoria()) {
                $banner->setStatus(1);
            }
            if ($banner->edita()) {
                redirect_header('index.php', 1, _AM_RWBANNER_MSG2);
            } else {
                redirect_header('index.php', 1, _AM_RWBANNER_MSG11);
            }
        }
        break;
    case 'editar':
        xoops_cp_header();
        // rwbanner_adminMenu('',_AM_RWBANNER_TITLE21.' '.$id);
        $sql   = 'SELECT * FROM ' . $xoopsDB->prefix('rwbanner_banner') . ' WHERE codigo=' . $id;
        $query = $xoopsDB->query($sql);
        $form  = $xoopsDB->fetchArray($query);
        //echo '<br><br><br><br><br><br>';
        monta_form(_AM_RWBANNER_BTN_OP2);
        xoops_cp_footer();
        break;
    default:
        xoops_cp_header();
        // rwbanner_adminMenu(3,_AM_RWBANNER_VALUE_BTN1);
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        //echo '<br><br><br><br><br><br>';
        monta_form(_AM_RWBANNER_BTN_OP1);
        xoops_cp_footer();
        break;
}

/**
 * @param $value
 */
function monta_form($value)
{
    global $xoopsDB, $form, $file_name;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $id     = '';
    $status = '';

    echo '
  <script>
    function vai()
    {
      if (document.getElementById(\'banner\').value != "") {
        document.getElementById(\'form[grafico]\').value = "";
      }

      return true;
    }
  </script>
  ';

    $banner_form = new \XoopsThemeForm(_AM_RWBANNER_VALUE_BTN1, 'form', 'inser.php', 'post', false);
    $banner_form->setExtra('enctype="multipart/form-data"');
    $user_selbox  = new \XoopsFormSelectUser(_AM_RWBANNER_TITLE22, 'form[idcliente]', false, $form['idcliente']);
    $categ_selbox = new \XoopsFormSelect(_AM_RWBANNER_TITLE23, 'form[categoria]', $form['categoria']);
    $categ_selbox->addOption('rodrigo', '--------');
    $js = 'var campo = document.getElementById("form[categoria]");
  if (campo.value == \'rodrigo\') {
    alert("Você precisa selecionar uma categoria.");
    campo.focus();

    return false;
  }';
    if (method_exists($categ_selbox, 'getEspecValid')) {
        $categ_selbox->setEspecValid($js);
    }
    $query    = 'SELECT titulo,cod FROM ' . $xoopsDB->prefix('rwbanner_categorias');
    $consulta = $xoopsDB->queryF($query);
    while (false !== (list($titulo, $cod) = $xoopsDB->fetchRow($consulta))) {
        $categ_selbox->addOption($cod, $titulo);
    }

    if (0 == $form['maxexib']) {
        $exibe = _AM_RWBANNER_BANNER_EXIBREST;
        $check = 'checked';
        $disa  = 'disabled';
    } else {
        $exibe = $form['maxexib'];
        $check = '';
        $disa  = '';
    }
    $label = new \XoopsFormElementTray(_AM_RWBANNER_TITLE24, '');
    $max   = new \XoopsFormText('', 'form[maxexibe]', 10, 255, $exibe);
    $max->setExtra($disa);
    echo '
  <script>
    function checa()
    {
      if (document.getElementById(\'form[maxexibe]\').disabled === false) {
        document.getElementById(\'form[maxexibe]\').disabled = true;
        document.getElementById(\'form[maxexibe]\').value = "' . _AM_RWBANNER_BTN_OP3 . '";
      } else {
        document.getElementById(\'form[maxexibe]\').disabled = false;
        document.getElementById(\'form[maxexibe]\').value = "";
        document.getElementById(\'form[maxexibe]\').focus();
      }
    }
  </script>
  ';
    $ilimitado = new \XoopsFormCheckBox('', _AM_RWBANNER_BTN_OP3);
    $ilimitado->setExtra('onClick="javascript:checa();" ' . $check);
    $ilimitado->addOption(1, _AM_RWBANNER_BTN_OP3);
    if (0 == $form['maxclick']) {
        $exibe1 = _AM_RWBANNER_BANNER_EXIBREST;
        $check1 = 'checked';
        $disa1  = 'disabled';
    } else {
        $exibe1 = $form['maxclick'];
        $check1 = '';
        $disa1  = '';
    }
    $label1   = new \XoopsFormElementTray(_AM_RWBANNER_TITLE500, '');
    $maxclick = new \XoopsFormText('', 'form[maxclick]', 10, 255, $exibe1);
    $maxclick->setExtra($disa1);
    echo '
  <script>
    function checa1()
    {
      if (document.getElementById(\'form[maxclick]\').disabled === false) {
        document.getElementById(\'form[maxclick]\').disabled = true;
        document.getElementById(\'form[maxclick]\').value = "' . _AM_RWBANNER_BTN_OP3 . '";
      } else {
        document.getElementById(\'form[maxclick]\').disabled = false;
        document.getElementById(\'form[maxclick]\').value = "";
        document.getElementById(\'form[maxclick]\').focus();
      }
    }
  </script>
  ';
    $ilimitado1 = new \XoopsFormCheckBox('', _AM_RWBANNER_BTN_OP3);
    $ilimitado1->setExtra('onClick="javascript:checa1();" ' . $check1);
    $ilimitado1->addOption(1, _AM_RWBANNER_BTN_OP3);
    if (0 == $form['periodo']) {
        $exibe2 = _AM_RWBANNER_BANNER_EXIBREST;
        $check2 = 'checked';
        $disa2  = 'disabled';
    } else {
        $exibe2 = $form['periodo'];
        $check2 = '';
        $disa2  = '';
    }
    $label2  = new \XoopsFormElementTray(_AM_RWBANNER_TITLE5001, '');
    $periodo = new \XoopsFormText('', 'form[periodo]', 10, 255, $exibe2);
    $periodo->setExtra($disa2);
    echo '
  <script>
    function checa2()
    {
      if (document.getElementById(\'form[periodo]\').disabled === false) {
        document.getElementById(\'form[periodo]\').disabled = true;
        document.getElementById(\'form[periodo]\').value = "' . _AM_RWBANNER_BTN_OP3 . '";
      } else {
        document.getElementById(\'form[periodo]\').disabled = false;
        document.getElementById(\'form[periodo]\').value = "";
        document.getElementById(\'form[periodo]\').focus();
      }
    }
  </script>
  ';
    $ilimitado2 = new \XoopsFormCheckBox('', _AM_RWBANNER_BTN_OP3);
    $ilimitado2->setExtra('onClick="javascript:checa2();" ' . $check2);
    $ilimitado2->addOption(1, _AM_RWBANNER_BTN_OP3);
    $imagem = new \XoopsFormText(_AM_RWBANNER_TITLE25, 'form[grafico]', 45, 255, $form['grafico']);
    $js     = '
  var campo = document.getElementById("form[grafico]");
  var campo1 = document.getElementById("banner");
  if (campo.value == \'\' && campo1.value == \'\') {
    alert("Você precisa definir uma imagem para o banner. Coloque a url de uma no campo imagem ou faça o upload.");
    campo.focus();

    return false;
  }';
    if (method_exists($imagem, 'getEspecValid')) {
        $imagem->setEspecValid($js);
    }
    $max_size = 5000000;
    $file_box = new \XoopsFormFile(_AM_RWBANNER_TITLE51_ED, 'banner', $max_size);
    $file_box->setExtra('size ="45" onChange="vai();"');
    $file_box->setDescription($file_name);
    $link = new \XoopsFormText(_AM_RWBANNER_TITLE26, 'form[url]', 45, 255, $form['url']);
    echo '
  <script>
    function checar()
    {
      if (document.getElementById(\'form[htmlcode]\').disabled === true) {
        document.getElementById(\'form[htmlcode]\').disabled = false;
      } else {
        document.getElementById(\'form[htmlcode]\').disabled = true;
        document.getElementById(\'form[htmlcode]\').focus();
      }
    }
  </script>
  ';
    $usarhtml = new \XoopsFormCheckBox(_AM_RWBANNER_TITLE27, 'form[usarhtml]', $form['usarhtml']);
    $usarhtml->setExtra('onClick="javascript:checar();"');
    $usarhtml->addOption(1, _MI_RWBANNER_YES);
    $htmlcode = new \XoopsFormTextArea(_AM_RWBANNER_TITLE28, 'form[htmlcode]', $form['htmlcode']);
    if (1 != $form['usarhtml']) {
        $htmlcode->setExtra('disabled');
    }
    $target_selbox = new \XoopsFormSelect(_AM_RWBANNER_TITLE29, 'form[target]', $form['target']);
    $target_selbox->addOption('_blank', '_blank');
    $target_selbox->addOption('_self', '_self');
    $target_selbox->addOption('_parent', '_parent');
    $target_selbox->addOption('_top', '_top');
    $button_tray = new \XoopsFormElementTray('', '');
    if (_AM_RWBANNER_BTN_OP2 == $value) {
        $id     = new \XoopsFormHidden('form[codigo]', $form['codigo']);
        $status = new \XoopsFormHidden('form[status]', $form['status']);
    }
    $submit_btn = new \XoopsFormButton('', 'post', $value, 'submit');
    if (_AM_RWBANNER_BTN_OP2 == $value && 2 == $form['status']) {
        $obs = new \XoopsFormTextArea(_AM_RWBANNER_TITLE5000, 'form[obs]', $form['obs']);
        $obs->setDescription(_AM_RWBANNER_TITLE5000_DESC);
    }
    $banner_form->addElement($user_selbox);
    $banner_form->addElement($categ_selbox);
    if (method_exists($categ_selbox, 'getEspecValid')) {
        $banner_form->setRequired($categ_selbox);
    }
    $label->addElement($max);
    $label->addElement($ilimitado);
    $banner_form->addElement($label);
    $label1->addElement($maxclick);
    $label1->addElement($ilimitado1);
    $banner_form->addElement($label1);
    $label2->addElement($periodo);
    $label2->addElement($ilimitado2);
    $banner_form->addElement($label2);
    $banner_form->addElement($imagem);
    if (method_exists($imagem, 'getEspecValid')) {
        $banner_form->setRequired($imagem);
    }
    $banner_form->addElement($file_box);
    $banner_form->addElement($link);
    $banner_form->addElement($usarhtml);
    $banner_form->addElement($htmlcode);
    $banner_form->addElement($target_selbox);
    if (_AM_RWBANNER_BTN_OP2 == $value && 2 == $form['status']) {
        $banner_form->addElement($obs);
    }
    $button_tray->addElement($submit_btn);
    $banner_form->addElement($button_tray);
    $banner_form->addElement($id);
    $banner_form->addElement($status);
    $banner_form->display();
}
