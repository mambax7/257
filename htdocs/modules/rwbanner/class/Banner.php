<?php namespace XoopsModules\Rwbanner;

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
 * XOOPS rwbanner Banner Class & Methods
 *
 * @copyright::  {@link www.brinfo.com.br BrInfo - Soluções Web}
 * @license  ::    {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author   ::     Rodrigo Pereira Lima aka RpLima (http://www.brinfo.com.br)
 * @package  ::    rwbanner
 *
 */

use XoopsModules\Rwbanner;

$dirname = basename(dirname(__DIR__));

require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';
require_once __DIR__ . '/helper.php';

$rwbannerConfigs = rwbanner_getModuleConfig();

define('_RWBANNER_DIRIMAGES', $rwbannerConfigs['dir_images']);

//Classe para gerenciamento dos banners

/**
 * Class RWbanners
 */
class Banner
{
    public $db;
    public $codigo;
    public $categoria;
    public $titulo;
    public $texto;
    public $url;
    public $grafico;
    public $usarhtml;
    public $htmlcode;
    public $showimg;
    public $exibicoes;
    public $maxexib;
    public $clicks;
    public $maxclick;
    public $data;
    public $periodo;
    public $status;
    public $target;
    public $idcliente;
    public $errormsg;
    public $larg;
    public $alt;
    public $obs;
    public $rwbanner;

    //Construtor

    /**
     * RWbanners constructor.
     * @param null $dados
     * @param null $id
     */
    public function __construct($dados = null, $id = null)
    {
        $this->rwbanner = Rwbanner\Helper::getInstance();
        if (null == $dados && null != $id) {
            $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
            $sql      = 'SELECT * FROM ' . $this->db->prefix('rwbanner_banner') . ' WHERE codigo=' . $id;
            $query    = $this->db->query($sql);
            $row      = $this->db->fetchArray($query);

            $this->codigo    = $row['codigo'];
            $this->categoria = $row['categoria'];
            $this->titulo    = $row['titulo'];
            $this->texto     = $row['texto'];
            $this->url       = $row['url'];
            $this->grafico   = $row['grafico'];
            $this->usarhtml  = $row['usarhtml'];
            $this->htmlcode  = $row['htmlcode'];
            $this->showimg   = $row['showimg'];
            $this->exibicoes = $row['exibicoes'];
            $this->maxexib   = $row['maxexib'];
            $this->clicks    = $row['clicks'];
            $this->maxclick  = $row['maxclick'];
            $this->data      = $row['data'];
            $this->periodo   = $row['periodo'];
            $this->status    = $row['status'];
            $this->target    = $row['target'];
            $this->idcliente = $row['idcliente'];
            $this->larg      = $this->setLargura();
            $this->alt       = $this->setAltura();
            $this->obs       = $row['obs'];
        } elseif (null != $dados) {
            $this->codigo    = isset($dados['codigo']) ? $dados['codigo'] : 0;
            $this->categoria = isset($dados['categoria']) ? $dados['categoria'] : '';
            $this->titulo    = isset($dados['titulo']) ? $dados['titulo'] : '';
            $this->texto     = isset($dados['texto']) ? $dados['texto'] : '';
            $this->url       = isset($dados['url']) ? $dados['url'] : '';
            $this->grafico   = isset($dados['grafico']) ? $dados['grafico'] : '';
            $this->usarhtml  = isset($dados['usarhtml']) ? $dados['usarhtml'] : '';
            $this->htmlcode  = isset($dados['htmlcode']) ? $dados['htmlcode'] : '';
            $this->showimg   = isset($dados['showimg']) ? $dados['showimg'] : '';
            $this->exibicoes = isset($dados['exibicoes']) ? $dados['exibicoes'] : 0;
            $this->maxexib   = isset($dados['maxexib']) ? $dados['maxexib'] : 0;
            $this->clicks    = isset($dados['clicks']) ? $dados['clicks'] : 0;
            $this->maxclick  = isset($dados['maxclick']) ? $dados['maxclick'] : 0;
            $this->data      = isset($dados['data']) ? $dados['data'] : '';
            $this->periodo   = isset($dados['periodo']) ? $dados['periodo'] : '';
            $this->status    = isset($dados['status']) ? $dados['status'] : $this->status;
            $this->target    = isset($dados['target']) ? $dados['target'] : '';
            $this->idcliente = isset($dados['idcliente']) ? $dados['idcliente'] : '';
            $this->larg      = isset($dados['larg']) ? $dados['larg'] : '';
            $this->alt       = isset($dados['alt']) ? $dados['alt'] : '';
            $this->obs       = isset($dados['obs']) ? $dados['obs'] : '';
        } else {
            $this->codigo    = 0;
            $this->categoria = '';
            $this->titulo    = '';
            $this->texto     = '';
            $this->url       = '';
            $this->grafico   = '';
            $this->usarhtml  = '';
            $this->htmlcode  = '';
            $this->showimg   = '';
            $this->exibicoes = 0;
            $this->maxexib   = '';
            $this->clicks    = '';
            $this->maxclick  = '';
            $this->data      = '';
            $this->periodo   = '';
            $this->status    = '';
            $this->target    = '';
            $this->idcliente = '';
            $this->larg      = '';
            $this->alt       = '';
            $this->obs       = '';
        }
    }

    // Métodos get e set de todos os atributos

    /**
     * @param $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

    /**
     * @return string
     */
    public function getTexTo()
    {
        return $this->texto;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $grafico
     */
    public function setGrafico($grafico)
    {
        $this->grafico = $grafico;
    }

    /**
     * @return string
     */
    public function getGrafico()
    {
        return $this->grafico;
    }

    /**
     * @param $usarhtml
     */
    public function setUsarhtml($usarhtml)
    {
        $this->usarhtml = $usarhtml;
    }

    /**
     * @return string
     */
    public function getUsarhtml()
    {
        return $this->usarhtml;
    }

    /**
     * @param $htmlcode
     */
    public function setHtmlcode($htmlcode)
    {
        $this->htmlcode = $htmlcode;
    }

    /**
     * @return string
     */
    public function getHtmlCode()
    {
        return $this->htmlcode;
    }

    /**
     * @param $showimg
     */
    public function setShowimg($showimg)
    {
        $this->showimg = $showimg;
    }

    /**
     * @return string
     */
    public function getShowimg()
    {
        return $this->showimg;
    }

    /**
     * @param $exibicoes
     */
    public function setExibicoes($exibicoes)
    {
        $this->exibicoes = $exibicoes;
    }

    /**
     * @return string
     */
    public function getExibicoes()
    {
        return $this->exibicoes;
    }

    /**
     * @param $maxexib
     */
    public function setMaxexib($maxexib)
    {
        $this->maxexib = $maxexib;
    }

    /**
     * @return string
     */
    public function getMaxexib()
    {
        return $this->maxexib;
    }

    /**
     * @param $clicks
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
    }

    /**
     * @return string
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * @param $maxclick
     */
    public function setMaxclick($maxclick)
    {
        $this->maxclick = $maxclick;
    }

    /**
     * @return string
     */
    public function getMaxclick()
    {
        return $this->maxclick;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $periodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }

    /**
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param $idcliente
     */
    public function setIdcliente($idcliente)
    {
        $this->idcliente = $idcliente;
    }

    /**
     * @return string
     */
    public function getIdcliente()
    {
        return $this->idcliente;
    }

    /**
     * @param $obs
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
    }

    /**
     * @return string
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->errormsg = $error;
    }

    public function getError()
    {
        return $this->errormsg;
    }

    //fim métodos set e get dos atributos

    public function clearDb()
    {
        $this->db = null;
    }

    //Insere um novo banner no banco de dados

    /**
     * @param  null $flag
     * @return bool
     */
    public function grava($flag = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $sts      = (null != $flag) ? $flag : 1;
        $sql      = 'INSERT INTO '
                    . $this->db->prefix('rwbanner_banner')
                    . ' (codigo, categoria, titulo, texto, url, grafico, usarhtml, htmlcode, showimg, exibicoes, maxexib, clicks, maxclick, data, periodo, status, target, idcliente, obs) VALUES ("'
                    . $this->codigo
                    . '", "'
                    . $this->categoria
                    . '", "'
                    . $this->titulo
                    . '", \''
                    . $this->texto
                    . '\', "'
                    . $this->url
                    . '", "'
                    . $this->grafico
                    . '", "'
                    . $this->usarhtml
                    . '", \''
                    . $this->htmlcode
                    . '\', "'
                    . $this->showimg
                    . '", \''
                    . $this->exibicoes
                    . '\', \''
                    . $this->maxexib
                    . '\', "'
                    . $this->clicks
                    . '", \''
                    . $this->maxclick
                    . '\', "'
                    . date('Y-m-d')
                    . '", "'
                    . $this->periodo
                    . '", "'
                    . $sts
                    . '", "'
                    . $this->target
                    . '", "'
                    . $this->idcliente
                    . '", "'
                    . $this->obs
                    . '")';
        if ($query = $this->db->queryF($sql)) {
            return true;
        } else {
            $this->setError($this->db->error());

            return false;
        }
    }

    //Edita o banner instanciado e salva as alterações no banco de dados

    /**
     * @return bool
     */
    public function edita()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $myts     = \MyTextSanitizer::getInstance();
        $sql      = 'UPDATE '
                    . $this->db->prefix('rwbanner_banner')
                    . ' SET categoria="'
                    . $this->categoria
                    . '", titulo="'
                    . $this->titulo
                    . '", texto="'
                    . $this->texto
                    . '", url="'
                    . $this->url
                    . '", grafico="'
                    . $this->grafico
                    . '", usarhtml="'
                    . $this->usarhtml
                    . '", htmlcode=\''
                    . $this->htmlcode
                    . '\', showimg="'
                    . $this->showimg
                    . '", exibicoes="'
                    . $this->exibicoes
                    . '", maxexib="'
                    . $this->maxexib
                    . '", clicks="'
                    . $this->clicks
                    . '", maxclick="'
                    . $this->maxclick
                    . '", periodo="'
                    . $this->periodo
                    . '", status="'
                    . $this->status
                    . '", target="'
                    . $this->target
                    . '", idcliente="'
                    . $this->idcliente
                    . '" WHERE codigo= '
                    . $this->codigo;
        if ($query = $this->db->queryF($sql)) {
            return true;
        } else {
            $this->setError($this->db->error());

            return false;
        }
    }

    //Exclui o banner instanciado do banco de dados

    /**
     * @return bool
     */
    public function exclui()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql      = 'DELETE FROM ' . $this->db->prefix('rwbanner_banner') . ' WHERE codigo= ' . $this->codigo;
        if ($query = $this->db->queryF($sql)) {
            return true;
        } else {
            $this->setError($this->db->error());

            return false;
        }
    }

    //Retorna um array associativo de todos os banners encontrados de acordo com os parâmetros.

    /**
     * @param  bool $admin
     * @param  null $order
     * @param  null $categ
     * @param  null $limit
     * @param  int  $start
     * @return array
     */
    public function getBanners($admin = false, $order = null, $categ = null, $limit = null, $start = 0)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $extra    = (null != $categ) ? ' WHERE categoria=' . $categ : '';
        $extra    .= (!$admin && null != $categ) ? ' and status=1' : ((!$admin
                                                                       && null == $categ) ? ' WHERE status=1' : '');
        $extra    .= (null != $order) ? ' ' . $order : '';
        $extra    .= (null != $limit) ? ' LIMIT ' . $start . ',' . $limit : '';
        $sql      = 'SELECT codigo FROM ' . $this->db->prefix('rwbanner_banner') . $extra;
        $query    = $this->db->query($sql);
        $banners  = [];
        while (false !== (list($id) = $this->db->fetchRow($query))) {
            $banner = new Banner(null, $id);
            unset($banner->db);
            unset($banner->errormsg);
            $banners[] =& $banner;
            unset($banner);
        }

        return $banners;
    }

    //Retorna o nome da categoria do banner

    /**
     * @return mixed
     */
    public function getBannnerCategName()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql      = 'SELECT titulo FROM ' . $this->db->prefix('rwbanner_categorias') . ' WHERE cod=' . $this->categoria;
        $query    = $this->db->query($sql);
        list($nome) = $this->db->fetchRow($query);

        return $nome;
    }

    //Retorna o nome do cliente do banner

    /**
     * @return mixed
     */
    public function getBannnerClientName()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql      = 'SELECT uname FROM ' . $this->db->prefix('users') . ' WHERE uid=' . $this->idcliente;
        $query    = $this->db->query($sql);
        list($nome) = $this->db->fetchRow($query);

        return $nome;
    }

    //Retorna a quantidade de registros encontrados de acordo com os parâmetros

    /**
     * @param  null $categ
     * @param  null $id
     * @return mixed
     */
    public function getRowNum($categ = null, $id = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $extra    = (null != $categ) ? ' and categoria=' . $categ : '';
        $extra    .= (null != $id) ? ' and status!=2 and idcliente=' . $id : '';
        $sql      = 'SELECT codigo FROM ' . $this->db->prefix('rwbanner_banner') . ' WHERE 1=1 ' . $extra;
        $query    = $this->db->query($sql);
        $total    = $this->db->getRowsNum($query);

        return $total;
    }

    //Recupera a largura e altura da categoria correspondente ao banner instanciado

    /**
     * @return mixed
     */
    public function setLargura()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql      = 'SELECT larg FROM ' . $this->db->prefix('rwbanner_categorias') . ' WHERE cod=' . $this->categoria;
        $query    = $this->db->query($sql);
        list($larg) = $this->db->fetchRow($query);

        return $larg;
    }

    /**
     * @return string
     */
    public function getLargura()
    {
        return $this->larg;
    }

    /**
     * @return mixed
     */
    public function setAltura()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $sql      = 'SELECT alt FROM ' . $this->db->prefix('rwbanner_categorias') . ' WHERE cod=' . $this->getCategoria();
        $query    = $this->db->query($sql);
        list($alt) = $this->db->fetchRow($query);

        return $alt;
    }

    /**
     * @return string
     */
    public function getAltura()
    {
        return $this->alt;
    }

    /**
     * @param  int    $categ
     * @param  int    $qtde
     * @param  int    $cols
     * @param  string $align
     * @return string
     */
    public function showBanner($categ = 0, $qtde = 1, $cols = 1, $align = 'center')
    {
        $cat     = (0 != $categ) ? $categ : null;
        $arr     = $this->getBanners(false, 'ORDER BY RAND()', $cat, $qtde);
        $cont    = 0;
        $showban = '<table border="0" cellpadding="0" cellspacing="5"><tr>';
        for ($i = 0; $i <= count($arr) - 1; ++$i) {
            $arr[$i]->incHits();
            ++$cont;
            if (1 == $arr[$i]->getUsarhtml()) {
                $bannerobject = '<div align="' . $align . '">';
                $bannerobject .= $arr[$i]->getHtmlCode();
                $bannerobject .= '</div>';
                $bannerobject .= (count($arr) > 1 && $cols <= 1) ? '<br>' : '';
            } else {
                if ('' == $arr[$i]->getUrl() || '#' === $arr[$i]->getUrl()) {
                    $bannerobject = '<div align="' . $align . '">';
                } else {
                    $bannerobject = '<div align="' . $align . '"><a href="' . $this->rwbanner->getDirName() . '/conta_click.php?id=' . $arr[$i]->getCodigo() . '" target="' . $arr[$i]->getTarget() . '">';
                }
                if (false !== stripos($arr[$i]->getGrafico(), '.swf')) {
                    $arq      = explode('/', $arr[$i]->getGrafico());
                    $grafico1 = _RWBANNER_DIRIMAGES . '/' . $arq[count($arq) - 1];
                    // require_once __DIR__ . '/../class/FlashHeader.php';
                    $f            = new FlashHeader($grafico1);
                    $result       = $f->getimagesize();
                    $fps          = $result['frameRate'];
                    $bannerobject .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="'
                                     . $arr[$i]->getLargura()
                                     . '" height="'
                                     . $arr[$i]->getAltura()
                                     . '">'
                                     . '<param name=movie value="'
                                     . __DIR__
                                     . '/../assets/images/rwbanner.swf">'
                                     . '<param name=quality value=high>'
                                     . '<PARAM NAME=FlashVars VALUE="alt='
                                     . $arr[$i]->getAltura()
                                     . '&larg='
                                     . $arr[$i]->getLargura()
                                     . '&fps='
                                     . $fps
                                     . '&banner='
                                     . $arr[$i]->getGrafico()
                                     . '&id='
                                     . $arr[$i]->getCodigo()
                                     . '&url='
                                     . dirname(__DIR__)
                                     . '&target='
                                     . $arr[$i]->getTarget()
                                     . '">'
                                     . '<embed src="'
                                     . __DIR__
                                     . '/../assets/images/images/rwbanner.swf" FlashVars="alt = '
                                     . $arr[$i]->getAltura()
                                     . ' &larg = '
                                     . $arr[$i]->getLargura()
                                     . ' &fps = '
                                     . $fps
                                     . ' &banner = '
                                     . $arr[$i]->getGrafico()
                                     . ' &id = '
                                     . $arr[$i]->getCodigo()
                                     . ' &url = '
                                     . dirname(__DIR__)
                                     . ' &target = '
                                     . $arr[$i]->getTarget()
                                     . '" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"; type="application/x-shockwave-flash" width="'
                                     . $arr[$i]->getLargura()
                                     . '" height="'
                                     . $arr[$i]->getAltura()
                                     . '">'
                                     . '</embed>'
                                     . '</object></div>';
                } else {
                    if (1 == $arr[$i]->getShowimg()) {
                        $bannerobject .= '<img style="border:0;" src="' . $arr[$i]->getGrafico() . '" alt="" width="' . $arr[$i]->getLargura() . '" height="' . $arr[$i]->getAltura() . '" border="0">';
                    } else {
                        $bannerobject .= '<p style="font-weight:normal; text-align:justify;"><span style="text-align:center; font-weight:bold;"<div class="center;">' . $arr[$i]->getTitulo() . '</div></span><br>' . $arr[$i]->gettexto() . '</p>';
                    }
                }
                $bannerobject .= '</a></div>';
                $bannerobject .= (count($arr) > 1 && $cols <= 1) ? '<br>' : '';
            }
            if ($cols > 0 && $qtde > 0) {
                $width = round($cols / $qtde);
            } else {
                $width = '100%';
            }
            $showban .= '<td width="' . $width . '">' . $bannerobject . '</td>';
            if ($cont == $cols) {
                $showban .= '</tr><tr>';
                $cont    = 0;
            }
        }
        $showban .= '</tr></table>';

        return $showban;
    }

    /**
     * @param  int    $id
     * @param  string $align
     * @return string
     */
    public function show1Banner($id = 0, $align = 'center')
    {
        $ban     = new Banner(null, $id);
        $showban = '<table border="0" cellpadding="0" cellspacing="5"><tr>';
        if (1 == $ban->getUsarhtml()) {
            $bannerobject = '<div align="' . $align . '">';
            $bannerobject .= $ban->getHtmlCode();
            $bannerobject .= '</div>';
        } else {
            if ('' == $ban->getUrl() || '#' === $ban->getUrl()) {
                $bannerobject = '<div align="' . $align . '">';
            } else {
                $bannerobject = '<div align="' . $align . '"><a href="' . $this->rwbanner->getDirName() . '/conta_click.php?id=' . $ban->getCodigo() . '" target="' . $ban->getTarget() . '">';
            }
            if (false !== stripos($ban->getGrafico(), '.swf')) {
                $arq      = explode('/', $ban->getGrafico());
                $grafico1 = _RWBANNER_DIRIMAGES . '/' . $arq[count($arq) - 1];
                // require_once __DIR__ . '/../class/FlashHeader.php';
                $f            = new FlashHeader($grafico1);
                $result       = $f->getimagesize();
                $fps          = $result['frameRate'];
                $bannerobject .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="'
                                 . $ban->getLargura()
                                 . '" height="'
                                 . $ban->getAltura()
                                 . '">'
                                 . '<param name=movie value="'
                                 . dirname(dirname(assets / images / _))
                                 . '/images/rwbanner.swf">'
                                 . '<param name=quality value=high>'
                                 . '<PARAM NAME=FlashVars VALUE="alt='
                                 . $ban->getAltura()
                                 . '&larg='
                                 . $ban->getLargura()
                                 . '&fps='
                                 . $fps
                                 . '&banner='
                                 . $ban->getGrafico()
                                 . '&id='
                                 . $ban->getCodigo()
                                 . '&url='
                                 . dirname(__DIR__)
                                 . '&target='
                                 . $ban->getTarget()
                                 . '">'
                                 . '<embed src="'
                                 . dirname(__DIR__)
                                 . '/images/rwbanner.swf" FlashVars="alt='
                                 . $ban->getAltura()
                                 . '&larg='
                                 . $ban->getLargura()
                                 . '&fps='
                                 . $fps
                                 . '&banner='
                                 . $ban->getGrafico()
                                 . '&id='
                                 . $ban->getCodigo()
                                 . '&url='
                                 . dirname(__DIR__)
                                 . '&target='
                                 . $ban->getTarget()
                                 . '" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"; type="application/x-shockwave-flash" width="'
                                 . $ban->getLargura()
                                 . '" height="'
                                 . $ban->getAltura()
                                 . '">'
                                 . '</embed>'
                                 . '</object>';
            } else {
                if (1 == $ban->getShowimg()) {
                    $bannerobject .= '<img style="border:0;" src="' . $ban->getGrafico() . '" alt="" width="' . $ban->getLargura() . '" height="' . $ban->getAltura() . '">';
                } else {
                    $bannerobject .= '<p style="font-weight:normal; text-align:justify;"><span style="text-align:center; font-weight:bold;"<div class="center;">' . $ban->getTitulo() . '</div></span><br>' . $ban->getTexTo() . '</p>';
                }
            }
            $bannerobject .= '</a></div>';
        }
        $showban .= '<td>' . $bannerobject . '</td>';
        $ban->incHits();
        $showban .= '</tr></table>';

        return $showban;
    }

    //Incrementa o número de exibições do banner e caso o limite de exibições, cliques ou período seja atingido desativa o banner
    public function incHits()
    {
        $hits = $this->getExibicoes();
        ++$hits;
        $data    = $this->getData();
        $periodo = $this->getPeriodo();
        $maxdata = somaData($data, $periodo);
        if ((0 == $this->getMaxexib() || $hits < $this->getMaxexib())
            && (0 == $this->getMaxclick()
                || $this->getClicks() < $this->getMaxclick())
            && (0 == $this->getPeriodo()
                || date('Y-m-d') <= $maxdata)) {
            $this->setExibicoes($hits);
        } elseif ($hits >= $this->getMaxexib()) {
            $this->setStatus(0);
        } elseif ($this->getClicks() >= $this->getMaxclick()) {
            $this->setStatus(0);
        } elseif (date('Y-m-d') > $maxdata) {
            $this->setStatus(0);
        }
        $this->edita();
    }

    //Incrementa o número de clicks do banner
    public function incClicks()
    {
        $clicks = $this->getClicks();
        ++$clicks;
        if (0 == $this->getMaxclick() || $this->getClicks() < $this->getMaxclick()) {
            $this->setClicks($clicks);
        } elseif ($this->getClicks() >= $this->getMaxclick()) {
            $this->setStatus(0);
        }
        $this->edita();
    }

    //Altera o status do banner. Se o parametro sts for passado altera o status atual pelo sts senão ele altera o status para o inverso do status atual Ex.: Status = 0; Novo Status = 1;

    /**
     * @param  null $sts
     * @return bool
     */
    public function mudaStatus($sts = null)
    {
        $this->status = isset($sts) ? $sts : (1 == $this->status) ? 0 : 1;

        return $this->edita();
    }

    //Retorna todos os banners de um determinado cliente

    /**
     * @param        $uid
     * @param  null  $order
     * @param  null  $categ
     * @param  null  $limit
     * @param  int   $start
     * @return array
     */
    public function getAllByClient($uid, $order = null, $categ = null, $limit = null, $start = 0)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $extra    = (null != $categ) ? ' and categoria=' . $categ : '';
        $extra    .= (null != $order) ? ' ' . $order : '';
        $extra    .= (null != $limit) ? ' LIMIT ' . $start . ',' . $limit : '';
        $sql      = 'SELECT codigo FROM ' . $this->db->prefix('rwbanner_banner') . ' WHERE idcliente=' . $uid . ' AND status!=2' . $extra;
        $query    = $this->db->query($sql);
        $banners  = [];
        while (false !== (list($id) = $this->db->fetchRow($query))) {
            $banner = new Banner(null, $id);
            unset($banner->db);
            unset($banner->errormsg);
            $banners[] =& $banner;
            unset($banner);
        }

        return $banners;
    }
}
