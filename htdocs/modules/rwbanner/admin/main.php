<?php
/*
                                  RW-Banner
                          Copyright (c) 2006 BrInfo
                          <http://www.brinfo.com.br>
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  You may not change or alter any portion of this comment or credits
  of supporting developers from this source code or any supporting
  source code which is considered copyrighted (c) material of the
  original comment or credit authors.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*/
/**
 * XOOPS rwbanner Main Admin
 *
 * @copyright::  {@link www.brinfo.com.br BrInfo - Soluções Web}
 * @license  ::    {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author   ::     Rodrigo Pereira Lima aka RpLima (http://www.brinfo.com.br)
 * @package  ::    rwbanner
 *
 */

use  XoopsModules\Rwbanner;

require_once __DIR__ . '/admin_header.php';

//require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
// require_once __DIR__ . '/../class/class.banner.php';
// require_once __DIR__ . '/../class/class.categoria.php';
// require_once __DIR__ . '/../class/class.tags.php';

$dir   = $xoopsModuleConfig['dir_images'];
$limit = $xoopsModuleConfig['total_reg_index'];

$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : 'lista');

switch ($op) {
    case 'lista':
        $order = isset($_GET['order']) ? $_GET['order'] : 'codigo';
        $seq   = isset($_GET['seq']) ? $_GET['seq'] : 'ASC';
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        // rwbanner_adminMenu (0,_AM_RWBANNER_INDEX);
        if (!is_dir($dir) || !is_writable($dir)) {
            echo '<br><br><br><br><br><br>
        <table style="font-size:10px;" width="100%" class="outer">
          <tr>
            <td align="center"><h3 style="margin:2px;">';
            printf(_AM_RWBANNER_NODIR, $dir);
            echo '</h3></td>';
            echo '</tr></table>';
        }
        lista_banners($order, $seq, $limit, $start);
        lista_tags();
        lista_categs();
        lista_users();
        xoops_cp_footer();
        break;
    case 'mudastatus':
        $id     = isset($_GET['id']) ? $_GET['id'] : '';
        $banner = new Rwbanner\Banner(null, $id);
        if ($banner->mudaStatus()) {
            redirect_header('main.php', 2, _AM_RWBANNER_MSG1);
        } else {
            redirect_header('index.php', 2, $banner->getError());
        }
        break;
    case 'mudastatus_tag':
        $id  = isset($_GET['id']) ? $_GET['id'] : '';
        $tag = new Rwbanner\Tag(null, $id);
        if ($tag->mudaStatus()) {
            redirect_header('main.php', 2, _AM_RWBANNER_MSG1);
        } else {
            redirect_header('index.php', 2, $tag->getError());
        }
        break;
    case 'deletar':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        xoops_cp_header();
        xoops_confirm(['id' => $id, 'op' => 'deletar_ok'], 'main.php', _AM_RWBANNER_MSG19);
        xoops_cp_footer();
        break;
    case 'deletar_ok':
        $id     = isset($_POST['id']) ? $_POST['id'] : '';
        $banner = new Rwbanner\Banner(null, $id);
        if ($banner->exclui()) {
            redirect_header('main.php', 2, _AM_RWBANNER_MSG101);
        } else {
            redirect_header('index.php', 2, $banner->getError());
        }
        break;
    case 'deletar_categ':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        xoops_cp_header();
        xoops_confirm(['id' => $id, 'op' => 'deletar_categ_ok'], 'main.php', _AM_RWBANNER_MSG3);
        xoops_cp_footer();
        break;
    case 'deletar_categ_ok':
        $id    = isset($_POST['id']) ? $_POST['id'] : '';
        $categ = new Rwbanner\Categoria(null, $id);
        if ($categ->exclui()) {
            redirect_header('main.php', 2, _AM_RWBANNER_MSG102);
        } else {
            redirect_header('index.php', 2, $categ->getError());
        }
        break;
    case 'deletar_tag':
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        xoops_cp_header();
        xoops_confirm(['id' => $id, 'op' => 'deletar_tag_ok'], 'main.php', _AM_RWBANNER_MSG20);
        xoops_cp_footer();
        break;
    case 'deletar_tag_ok':
        $id  = isset($_POST['id']) ? $_POST['id'] : '';
        $tag = new Rwbanner\Tag(null, $id);
        if ($tag->exclui()) {
            redirect_header('main.php', 2, _AM_RWBANNER_MSG21);
        } else {
            redirect_header('index.php', 2, $tag->getError());
        }
        break;
    case 'criardir':
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            $msg = _AM_RWBANNER_MSG17;
        } else {
            $msg = _AM_RWBANNER_MSG18;
        }
        redirect_header('index.php', 2, $msg);
        break;
}

/**
 * @param null   $order
 * @param string $seq
 * @param int    $limit
 * @param int    $start
 */
function lista_banners($order = null, $seq = '', $limit = 10, $start = 0)
{
    global $xoopsModule, $pathIcon16;

    $banner    = new Rwbanner\Banner();
    $total     = $banner->getRowNum();
    $ord       = $order;
    $order     = 'ORDER BY ' . $order;
    $order     .= ' ' . $seq;
    $img       = ('ASC' === $seq) ? '<img src="../assets/images/asc.gif">' : '<img src="../assets/images/desc.gif">';
    $img_cod   = ('codigo' === $ord) ? $img : '';
    $img_cli   = ('idcliente' === $ord) ? $img : '';
    $img_cat   = ('categoria' === $ord) ? $img : '';
    $img_exib  = ('exibicoes' === $ord) ? $img : '';
    $img_click = ('clicks' === $ord) ? $img : '';
    $img_dat   = ('data' === $ord) ? $img : '';
    $img_sts   = ('status' === $ord) ? $img : '';

    $lista_banners = $banner->getBanners(true, $order, null, $limit, $start);
    $extra_sel     = (0 != $start) ? '&start=' . $start : '';
    $seq           = ('ASC' === $seq) ? 'DESC' : 'ASC';

    rwbanner_collapsableBar('banners', 'bannersicon');
    echo "<img id='bannersicon' name='bannersicon' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/close12.gif alt=''></a>&nbsp;" . _AM_RWBANNER_LIST_BANNER . '</h3>';
    echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; text-align:justify; ">' . _AM_RWBANNER_LIST_BANNER_DESC . '</span>';
    echo "<div id='banners'>";

    echo '
  <table style="font-size:10px;" width="100%" class="outer">
    <tr class="head">
      <td align="center"><a href="main.php?order=codigo&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE2 . $img_cod . '</a></td>
      <td align="center"><a href="main.php?order=idcliente&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE3 . $img_cli . '</a></td>
      <td align="center"><a href="main.php?order=categoria&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE4 . $img_cat . '</a></td>
      <td align="center"><a href="main.php?order=exibicoes&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE5 . $img_exib . '</a></td>
      <td align="center">' . _AM_RWBANNER_TITLE6 . '</td>
      <td align="center"><a href="main.php?order=clicks&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE7 . $img_click . '</a></td>
      <td align="center">' . _AM_RWBANNER_TITLE39 . '</td>
      <td align="center">' . _AM_RWBANNER_TITLE8 . '</td>
      <td align="center"><a href="main.php?order=data&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE9 . $img_dat . '</a></td>
      <td align="center">' . _AM_RWBANNER_TITLE99 . '</td>
      <td align="center"><a href="main.php?order=status&seq=' . $seq . $extra_sel . '">' . _AM_RWBANNER_TITLE10 . $img_sts . '</a></td>
      <td align="center" width="12%">' . _AM_RWBANNER_TITLE11 . '</td>
    </tr>
  ';
    $class = 'even';
    for ($i = 0; $i <= count($lista_banners) - 1; ++$i) {
        if (1 == $lista_banners[$i]->getStatus()) {
            $status = '<img src=' . $pathIcon16 . '/green.gif' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_BANNER_STATUS1 . '" title="' . _AM_RWBANNER_BANNER_STATUS1 . '">';
            $estilo = '';
        } elseif (2 == $lista_banners[$i]->getStatus()) {
            $status = '<img src="../assets/images/wait.gif" width="20" height="20" border="0" alt="' . _AM_RWBANNER_BANNER_STATUS3 . '" title="' . _AM_RWBANNER_BANNER_STATUS3 . '">';
            $estilo = '';
        } else {
            $status = '<img src=' . $pathIcon16 . '/red.gif' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_BANNER_STATUS2 . '" title="' . _AM_RWBANNER_BANNER_STATUS2 . '">';
            $estilo = 'style="color:red;"';
        }
        if ('even' === $class) {
            $class = 'odd';
        } else {
            $class = 'even';
        }
        if (0 == $lista_banners[$i]->getMaxexib()) {
            $exibrest = _AM_RWBANNER_BANNER_EXIBREST;
        } else {
            $exibrest = round($lista_banners[$i]->getMaxexib() - $lista_banners[$i]->getExibicoes());
        }
        if (0 == $lista_banners[$i]->getMaxclick()) {
            $exibrestclick = _AM_RWBANNER_BANNER_EXIBREST;
        } else {
            $exibrestclick = round($lista_banners[$i]->getMaxclick() - $lista_banners[$i]->getClicks());
        }
        if (0 != $lista_banners[$i]->getClicks() && 0 != $lista_banners[$i]->getExibicoes()) {
            $perc = round(($lista_banners[$i]->getClicks() / $lista_banners[$i]->getExibicoes()) * 100, 2);
        } else {
            $perc = '0';
        }
        $data    = $lista_banners[$i]->getData();
        $periodo = $lista_banners[$i]->getPeriodo();
        $maxdata = somaData($data, $periodo);
        if (0 == $periodo) {
            $periodo = _AM_RWBANNER_BANNER_EXIBREST;
        } else {
            $periodo = converte($maxdata, 'BR', 0);
        }
        $titulo  = ('' != $lista_banners[$i]->getBannnerCategName()) ? $lista_banners[$i]->getBannnerCategName() : _AM_RWBANNER_NO_CATEG;
        $cliente = $lista_banners[$i]->getBannnerClientName();
        $lista_banners[$i]->clearDb();
        $data_cad = converte($lista_banners[$i]->getData(), 'BR', 0);
        echo '
      <tr class="' . $class . '" ' . $estilo . '>
        <td align="center">' . $lista_banners[$i]->getCodigo() . '</td>
        <td align="center"><a href="' . XOOPS_URL . '/userinfo.php?uid=' . $lista_banners[$i]->getIdcliente() . '" target="_blank">' . $cliente . '</a></td>
        <td align="center">' . $titulo . '</td>
        <td align="center">' . $lista_banners[$i]->getExibicoes() . '</td>
        <td align="center">' . $exibrest . '</td>
        <td align="center">' . $lista_banners[$i]->getClicks() . '</td>
        <td align="center">' . $exibrestclick . '</td>
        <td align="center">' . $perc . '%</td>
        <td align="center">' . $data_cad . '</td>
        <td align="center">' . $periodo . '</td>
        <td align="center"><a href="main.php?op=mudastatus&id=' . $lista_banners[$i]->getCodigo() . '">' . $status . '</a></td>';
        echo '
        <td align="center" width="10%">
          <a href="javascript:;" onClick="javascript: window.open(\'exibe.php?id='
             . $lista_banners[$i]->getCodigo()
             . '\',\'editar\',\'width='
             . ($lista_banners[$i]->getLargura() + 20)
             . ',height='
             . $lista_banners[$i]->getAltura()
             . ',toolbar=no\');"><img src='
             . $pathIcon16
             . '/search.png'
             . ' width="16" height="16" border="0" alt="'
             . _AM_RWBANNER_VALUE_BTN2
             . '" title="'
             . _AM_RWBANNER_VALUE_BTN2
             . '"></a>
          <a href="inser.php?id='
             . $lista_banners[$i]->getCodigo()
             . '&op=editar"><img src='
             . $pathIcon16
             . '/edit.png'
             . ' width="16" height="16" border="0" alt="'
             . _AM_RWBANNER_VALUE_BTN3
             . '" title="'
             . _AM_RWBANNER_VALUE_BTN3
             . '"></a>
          <a href="main.php?id='
             . $lista_banners[$i]->getCodigo()
             . '&op=deletar"><img src='
             . $pathIcon16
             . '/delete.png'
             . ' width="16" height="16" border="0" alt="'
             . _AM_RWBANNER_VALUE_BTN4
             . '" title="'
             . _AM_RWBANNER_VALUE_BTN4
             . '"></a>
        </td>
      </tr>';
    }
    if (0 !== strpos($_SERVER['QUERY_STRING'], 'start')) {
        $arr_qs    = explode('&', $_SERVER['QUERY_STRING']);
        $n         = (0 !== strpos($arr_qs[count($arr_qs) - 1], 'start')) ? $n = 1 : $n = 2;
        $extra_pag = '';
        for ($i = 0; $i <= count($arr_qs) - $n; ++$i) {
            $extra_pag .= $arr_qs[$i] . '&';
        }
        $extra_pag = substr($extra_pag, 0, strlen($extra_pag) - 1);
    } else {
        $extra_pag = '';
    }
    $pagenav = new \XoopsPageNav($total, $limit, $start, 'start', $extra_pag);
    $pag     = $pagenav->renderNav();
    echo '  <tr class="head">';
    echo '    <td align="left" colspan="12" nowrap="nowrap">' . _AM_RWBANNER_TOTAL_BANNER_LEG . ' ' . $total . '<br><div align="center">' . $pag . '</div></td>';
    echo '  </tr>';
    echo '</table> ';
    echo '</div><br>';
}

function lista_categs()
{
    global $xoopsModule;

    $categ        = new Rwbanner\Categoria();
    $lista_categs = $categ->getCategorias('ORDER BY cod ASC');
    global $pathIcon16;

    rwbanner_collapsableBar('categs', 'categsicon');
    echo "<img id='categssicon' name='categsicon' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/close12.gif alt=''></a>&nbsp;" . _AM_RWBANNER_LIST_CATEG . '</h3>';
    echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; text-align:justify; ">' . _AM_RWBANNER_LIST_CATEG_DESC . '</span>';
    echo "<div id='categs'>";

    echo '
  <table style="font-size:10px;" width="100%" class="outer">
  <tr class="head">
    <td align="center">' . _AM_RWBANNER_TITLE2 . '</td>
    <td align="center">' . _AM_RWBANNER_TITLE13 . '</td>
    <td align="center">' . _AM_RWBANNER_TITLE14 . '</td>
    <td align="center">' . _AM_RWBANNER_TITLE11 . '</td>
  </tr>
  ';
    $class = 'even';
    for ($i = 0; $i <= count($lista_categs) - 1; ++$i) {
        if ('even' === $class) {
            $class = 'odd';
        } else {
            $class = 'even';
        }
        $banner = new Rwbanner\Banner();
        $qtde   = 0;
        $qtde   = $banner->getRowNum($lista_categs[$i]->getCod());
        $banner->clearDb();
        echo '
      <tr class="' . $class . '">
        <td align="center">' . $lista_categs[$i]->getCod() . '</td>
        <td align="center" width="40%">' . $lista_categs[$i]->getTitulo() . '</td>
        <td align="center">' . $qtde . '</td>';
        echo '
        <td align="center" width="10%">
          <a href="insercateg.php?id=' . $lista_categs[$i]->getCod() . '&op=editar_categ"><img src=' . $pathIcon16 . '/edit.png' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_VALUE_BTN6 . '" title="' . _AM_RWBANNER_VALUE_BTN6 . '"></a>
          <a href="main.php?id=' . $lista_categs[$i]->getCod() . '&op=deletar_categ"><img src=' . $pathIcon16 . '/delete.png' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_VALUE_BTN7 . '" title="' . _AM_RWBANNER_VALUE_BTN7 . '"></a>
        </td>
      </tr>';
    }
    echo '</table> ';
    echo '</div><br>';
}

function lista_tags()
{
    global $xoopsModule, $pathIcon16;

    $tag        = new Rwbanner\Tag();
    $lista_tags = $tag->getTags('ORDER BY id ASC');

    rwbanner_collapsableBar('tags', 'tagsicon');
    echo "<img id='tagsicon' name='tagsicon' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/close12.gif alt=''></a>&nbsp;" . _AM_RWBANNER_LIST_TAG . '</h3>';
    echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; text-align:justify; ">' . _AM_RWBANNER_LIST_TAG_DESC . '</span>';
    echo "<div id='tags'>";

    echo '
  <table style="font-size:10px;" width="100%" class="outer">
  <tr class="head">
    <td align="center">' . _AM_RWBANNER_TAG_TITLE01 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE02 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE03 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE04 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE19 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE05 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE06 . '</td>
    <td align="center">' . _AM_RWBANNER_TAG_TITLE07 . '</td>
    <td align="center">' . _AM_RWBANNER_TITLE11 . '</td>
  </tr>
  ';
    $class = 'even';
    for ($i = 0; $i <= count($lista_tags) - 1; ++$i) {
        if ('even' === $class) {
            $class = 'odd';
        } else {
            $class = 'even';
        }
        if (1 == $lista_tags[$i]->getStatus()) {
            $status = '<img src=' . $pathIcon16 . '/green.gif' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_TAG_STATUS1 . '" title="' . _AM_RWBANNER_TAG_STATUS1 . '">';
            $estilo = '';
        } else {
            $status = '<img src=' . $pathIcon16 . '/red.gif' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_TAG_STATUS2 . '" title="' . _AM_RWBANNER_TAG_STATUS2 . '">';
            $estilo = 'style="color:red;"';
        }
        $categ = (0 != $lista_tags[$i]->getCateg()) ? $lista_tags[$i]->getTagCategName() : _AM_RWBANNER_TAG_TITLE13;
        $mods  = $lista_tags[$i]->getModuleName() ?: _AM_RWBANNER_TAG_TITLE17;
        echo '
      <tr class="' . $class . '" ' . $estilo . '>
        <td align="center">' . $lista_tags[$i]->getId() . '</td>
        <td align="left" width="40%">' . $lista_tags[$i]->getTitle() . '</td>
        <td align="center" width="40%"><{$' . $lista_tags[$i]->getName() . '}></td>
        <td align="center">' . $categ . '</td>
        <td align="center">' . $mods . '</td>
        <td align="center" >' . $lista_tags[$i]->getQtde() . '</td>
        <td align="center">' . $lista_tags[$i]->getCols() . '</td>
        <td align="center"><a href="main.php?op=mudastatus_tag&id=' . $lista_tags[$i]->getId() . '">' . $status . '</a></td>';
        echo '
        <td align="center" width="10%">
          <a href="insertag.php?id=' . $lista_tags[$i]->getId() . '&op=editar_tag"><img src=' . $pathIcon16 . '/edit.png' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_VALUE_BTN6 . '" title="' . _AM_RWBANNER_VALUE_BTN6 . '"></a>
          <a href="main.php?id=' . $lista_tags[$i]->getId() . '&op=deletar_tag"><img src=' . $pathIcon16 . '/delete.png' . ' width="16" height="16" border="0" alt="' . _AM_RWBANNER_VALUE_BTN7 . '" title="' . _AM_RWBANNER_VALUE_BTN7 . '"></a>
        </td>
      </tr>';
    }
    echo '</table> ';
    echo '</div><br>';
}

function lista_users()
{
    global $xoopsDB, $xoopsModule, $pathIcon16;

    $categ        = new Rwbanner\Categoria();
    $lista_categs = $categ->getCategorias('ORDER BY cod ASC');

    rwbanner_collapsableBar('users', 'usersicon');
    echo "<img id='userssicon' name='usersicon' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/close12.gif alt=''></a>&nbsp;" . _AM_RWBANNER_LIST_USERS . '</h3>';
    echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; text-align:justify; ">' . _AM_RWBANNER_LIST_USERS_DESC . '</span>';
    echo "<div id='users'>";

    echo '
  <table style="font-size:10px;" width="100%" class="outer">
  <tr class="head">
    <td align="center">' . _AM_RWBANNER_TITLE2 . '</td>
    <td align="left">' . _AM_RWBANNER_TITLE17 . '</td>
    <td align="left">' . _AM_RWBANNER_TITLE18 . '</td>
    <td align="left">' . _AM_RWBANNER_TITLE19 . '</td>
    <td align="center">' . _AM_RWBANNER_TITLE20 . '</td>
    <td align="center">' . _AM_RWBANNER_TITLE11 . '</td>
  </tr>
  ';
    $class = 'even';
    $query = $xoopsDB->queryF('SELECT uid,uname,name,email FROM ' . $xoopsDB->prefix('users'));
    while (false !== (list($uid, $uname, $name, $email) = $xoopsDB->fetchRow($query))) {
        $query1    = $xoopsDB->queryF('SELECT * FROM ' . $xoopsDB->prefix('rwbanner_banner') . ' WHERE idcliente=' . $uid);
        $qtbanners = $xoopsDB->getRowsNum($query1);
        if ($qtbanners > 0) {
            if ('even' === $class) {
                $class = 'odd';
            } else {
                $class = 'even';
            }
            $name = ('' != $name) ? $name : $uname;
            echo '
        <tr class="' . $class . '">
          <td align="center">' . $uid . '</td>
          <td align="left">' . $uname . '</td>
          <td align="left">' . $name . '</td>
          <td align="left">' . $email . '</td>
          <td align="center">' . $qtbanners . '</td>
          <td align="center">
            <a href="' . XOOPS_URL . '/userinfo.php?uid=' . $uid . '" target="_blank"><img src=' . $pathIcon16 . '/search.png' . ' width="16" height="16" border="0" alt="Ver Cliente" title="Ver Cliente"></a>
            <a href="' . XOOPS_URL . '/modules/system/admin.php?fct=users&uid=' . $uid . '&op=modifyUser" target="_blank"><img src=' . $pathIcon16 . '/edit.png' . ' width="16" height="16" border="0" alt="Editar Cliente" title="Editar Cliente"></a>
          </td>
        </tr>';
        }
    }
    echo '</table>';
    echo '</div>';
}
