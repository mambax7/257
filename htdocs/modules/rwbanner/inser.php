<?php
//  ------------------------------------------------------------------------ //
//                                  RW-Banner                                //
//                    Copyright (c) 2006 Web Applications                    //
//                     <http://www.bcsg.com.br/rwbanner>                    //
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
// Author: Rodrigo Pereira Lima (Web Applications)                           //
// Site: http://www.bcsg.com.br/rwbanner                                     //
// Project: RW-Banner                                                        //
// Descrição: Sistema de gerenciamento de mídias publicitárias               //
// ------------------------------------------------------------------------- //

use Xmf\Request;
use XoopsModules\Rwbanner;

include __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once __DIR__ . '/class/Banner.php';
$local_folder = $xoopsModuleConfig['dir_images'];

$op = Request::getCmd('op', '');
$id = Request::getCmd('id', '');

$op = Request::hasVar('post', 'POST') ? 'grava' : $op;

if (isset($_POST['form'])) {
    $form = $_POST['form'];
}

global $xoopsDB, $xoopsModule;
switch ($op) {
    case 'grava':
        $data = date('y:m:d h:i:s');
        if ('' == $form['grafico']) {
            //Inicio da rotina de upload de arquivo
            $maxfilesize = 500000;
            $uploader    = new \XoopsMediaUploader($local_folder, require_once __DIR__ . '/include/mimetypes.inc.php', $maxfilesize);
            for ($i = 0, $iMax = count($_POST['xoops_upload_file']); $i <= $iMax; $i++) {
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][$i])) {
                    if (!$uploader->upload()) {
                        redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/index.php', 2, $uploader->getErrors());
                    } else {
                        $file_name = $uploader->getSavedFileName();
                    }
                }
            }
            //Fim da rotina de upload de arquivo
            $form['grafico'] = dirname(__DIR__) . $file_name;
        }

        $form['usarhtml'] = isset($form['usarhtml']) ? $form['usarhtml'] : 0;
        $form['maxexib']  = isset($form['maxexibe']) ? $form['maxexibe'] : 0;
        $form['maxclick'] = isset($form['maxclick']) ? $form['maxclick'] : 0;
        $form['periodo']  = isset($form['periodo']) ? $form['periodo'] : 0;
        $form['showimg']  = 1;

        $banner = new Rwbanner\Banner($form);

        if (_ADD == $_POST['post']) {
            if ($banner->grava(2)) {
                redirect_header('index.php', 1, _MD_RWBANNER_MSG8);
            } else {
                redirect_header('index.php', 1, _MD_RWBANNER_MSG10);
            }
        } elseif (_EDIT == $_POST['post']) {
            if ($banner->edita()) {
                redirect_header('index.php', 1, _MD_RWBANNER_MSG2);
            } else {
                redirect_header('index.php', 1, _MD_RWBANNER_MSG11);
            }
        }
        break;
    case 'editar':
        require_once XOOPS_ROOT_PATH . '/header.php';
        $sql   = 'SELECT * FROM ' . $xoopsDB->prefix('rwbanner_banner') . ' WHERE codigo=' . $id;
        $query = $xoopsDB->query($sql);
        $form  = $xoopsDB->fetchArray($query);
        monta_form(_EDIT) ;
        require_once XOOPS_ROOT_PATH . '/footer.php';
        break;
    default:
        require_once XOOPS_ROOT_PATH . '/header.php';
        monta_form(_ADD);
        require_once XOOPS_ROOT_PATH . '/footer.php';
        break;
}

/**
 * @param $value
 */
function monta_form($value)
{
    global $xoopsDB, $form, $file_name, $xoopsUser, $xoopsModuleConfig;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $arr_perm = $xoopsModuleConfig['campos_perm'];
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
    $uid         = isset($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $categ       = (_MD_RWBANNER_BTN_OP1 == $value) ? 0 : $form['categoria'];
    $title       = (_MD_RWBANNER_BTN_OP1 == $value) ? _MD_RWBANNER_VALUE_BTN1 : _MD_RWBANNER_VALUE_BTN3;
    $banner_form = new \XoopsThemeForm($title, 'form', 'inser.php', 'post', false);
    $banner_form->setExtra('enctype="multipart/form-data"');
    $user_selbox  = new \XoopsFormHidden('form[idcliente]', $uid);
    $categ_selbox = new \XoopsFormHidden('form[categoria]', $categ);
    if (0 == $form['maxexib']) {
        $exibe = _MD_RWBANNER_EXIBREST;
        $check = 'checked';
        $disa  = 'disabled';
    } else {
        $exibe = $form['maxexib'];
        $check = '';
        $disa  = '';
    }
    $label = new \XoopsFormElementTray(_MD_RWBANNER_TITLE24, '');
    $max   = new \XoopsFormText('', 'form[maxexibe]', 10, 255, $exibe);
    $max->setExtra($disa);
    echo '
  <script>
    function checa()
    {
      if (document.getElementById(\'form[maxexibe]\').disabled === false) {
        document.getElementById(\'form[maxexibe]\').disabled = true;
        document.getElementById(\'form[maxexibe]\').value = "' . _MD_RWBANNER_BTN_OP3 . '";
      } else {
        document.getElementById(\'form[maxexibe]\').disabled = false;
        document.getElementById(\'form[maxexibe]\').value = "";
        document.getElementById(\'form[maxexibe]\').focus();
      }
    }
  </script>
  ';
    $ilimitado = new \XoopsFormCheckBox('', _MD_RWBANNER_BTN_OP3);
    $ilimitado->setExtra('onClick="javascript:checa();" ' . $check);
    $ilimitado->addOption(1, _MD_RWBANNER_BTN_OP3);
    if (0 == $form['maxclick']) {
        $exibe1 = _MD_RWBANNER_EXIBREST;
        $check1 = 'checked';
        $disa1  = 'disabled';
    } else {
        $exibe1 = $form['maxclick'];
        $check1 = '';
        $disa1  = '';
    }
    $label1   = new \XoopsFormElementTray(_MD_RWBANNER_TITLE500, '');
    $maxclick = new \XoopsFormText('', 'form[maxclick]', 10, 255, $exibe1);
    $maxclick->setExtra($disa1);
    echo '
  <script>
    function checa1()
    {
      if (document.getElementById(\'form[maxclick]\').disabled === false) {
        document.getElementById(\'form[maxclick]\').disabled = true;
        document.getElementById(\'form[maxclick]\').value = "' . _MD_RWBANNER_BTN_OP3 . '";
      } else {
        document.getElementById(\'form[maxclick]\').disabled = false;
        document.getElementById(\'form[maxclick]\').value = "";
        document.getElementById(\'form[maxclick]\').focus();
      }
    }
  </script>
  ';
    $ilimitado1 = new \XoopsFormCheckBox('', _MD_RWBANNER_BTN_OP3);
    $ilimitado1->setExtra('onClick="javascript:checa1();" ' . $check1);
    $ilimitado1->addOption(1, _MD_RWBANNER_BTN_OP3);
    if (0 == $form['periodo']) {
        $exibe2 = _MD_RWBANNER_EXIBREST;
        $check2 = 'checked';
        $disa2  = 'disabled';
    } else {
        $exibe2 = $form['periodo'];
        $check2 = '';
        $disa2  = '';
    }
    $label2  = new \XoopsFormElementTray(_MD_RWBANNER_TITLE5001, '');
    $periodo = new \XoopsFormText('', 'form[periodo]', 10, 255, $exibe2);
    $periodo->setExtra($disa2);
    echo '
  <script>
    function checa2()
    {
      if (document.getElementById(\'form[periodo]\').disabled === false) {
        document.getElementById(\'form[periodo]\').disabled = true;
        document.getElementById(\'form[periodo]\').value = "' . _MD_RWBANNER_BTN_OP3 . '";
      } else {
        document.getElementById(\'form[periodo]\').disabled = false;
        document.getElementById(\'form[periodo]\').value = "";
        document.getElementById(\'form[periodo]\').focus();
      }
    }
  </script>
  ';
    $ilimitado2 = new \XoopsFormCheckBox('', _MD_RWBANNER_BTN_OP3);
    $ilimitado2->setExtra('onClick="javascript:checa2();" ' . $check2);
    $ilimitado2->addOption(1, _MD_RWBANNER_BTN_OP3);
    $imagem = new \XoopsFormText(_MD_RWBANNER_TITLE25, 'form[grafico]', 45, 255, $form['grafico']);
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
    $file_box = new \XoopsFormFile(_MD_RWBANNER_TITLE51_ED, 'banner', $max_size);
    $file_box->setExtra('size ="45" onChange="vai();"');
    $file_box->setDescription($file_name);
    $link = new \XoopsFormText(_MD_RWBANNER_TITLE26, 'form[url]', 45, 255, $form['url']);
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
    $usarhtml = new \XoopsFormCheckBox(_MD_RWBANNER_TITLE27, 'form[usarhtml]');
    $usarhtml->setExtra('onClick="javascript:checar();"');
    $usarhtml->addOption(1, _MD_RWBANNER_YES);
    $htmlcode = new \XoopsFormTextArea(_MD_RWBANNER_TITLE28, 'form[htmlcode]', $form['htmlcode']);
    $htmlcode->setExtra('disabled');
    $target_selbox = new \XoopsFormHidden('form[target]', '_blank');
    $button_tray   = new \XoopsFormElementTray('', '');
    if (_EDIT == $value) {
        $id     = new \XoopsFormHidden('form[codigo]', $form['codigo']);
        $status = new \XoopsFormHidden('form[status]', $form['status']);
    }
    $submit_btn = new \XoopsFormButton('', 'post', $value, 'submit');

    $obs = new \XoopsFormTextArea(_MD_RWBANNER_TITLE5000, 'form[obs]', $form['obs']);
    $obs->setDescription(_MD_RWBANNER_TITLE5000_DESC);
    $banner_form->addElement($user_selbox);
    $banner_form->addElement($categ_selbox);
    if (in_array('maxexibe', $arr_perm) || _MD_RWBANNER_BTN_OP1 == $value) {
        $label->addElement($max);
        $label->addElement($ilimitado);
        $banner_form->addElement($label);
    } else {
        $banner_form->addElement(new \XoopsFormHidden('form[maxexibe]', $form['maxexibe']));
    }
    if (in_array('maxclick', $arr_perm) || _MD_RWBANNER_BTN_OP1 == $value) {
        $label1->addElement($maxclick);
        $label1->addElement($ilimitado1);
        $banner_form->addElement($label1);
    } else {
        $banner_form->addElement(new \XoopsFormHidden('form[maxclick]', $form['maxclick']));
    }
    if (in_array('periodo', $arr_perm) || _MD_RWBANNER_BTN_OP1 == $value) {
        $label2->addElement($periodo);
        $label2->addElement($ilimitado2);
        $banner_form->addElement($label2);
    } else {
        $banner_form->addElement(new \XoopsFormHidden('form[periodo]', $form['periodo']));
    }
    if (in_array('grafico', $arr_perm) || _MD_RWBANNER_BTN_OP1 == $value) {
        $banner_form->addElement($imagem);
        if (method_exists($imagem, 'getEspecValid')) {
            $banner_form->setRequired($imagem);
        }
        $banner_form->addElement($file_box);
    } else {
        $banner_form->addElement(new \XoopsFormHidden('form[grafico]', $form['grafico']));
    }
    if (in_array('url', $arr_perm) || _MD_RWBANNER_BTN_OP1 == $value) {
        $banner_form->addElement($link);
    } else {
        $banner_form->addElement(new \XoopsFormHidden('form[url]', $form['url']));
    }
    if (in_array('grafico', $arr_perm) || _MD_RWBANNER_BTN_OP1 == $value) {
        $banner_form->addElement($usarhtml);
        $banner_form->addElement($htmlcode);
    } else {
        $banner_form->addElement(new \XoopsFormHidden('form[usarhtml]', $form['usarhtml']));
        $banner_form->addElement(new \XoopsFormHidden('form[htmlcode]', $form['htmlcode']));
    }
    if (_MD_RWBANNER_BTN_OP1 == $value) {
        $banner_form->addElement($obs);
    }
    $banner_form->addElement($target_selbox);
    $button_tray->addElement($submit_btn);
    $banner_form->addElement($button_tray);
    $banner_form->addElement($id);
    $banner_form->addElement($status);
    $banner_form->display();
}
