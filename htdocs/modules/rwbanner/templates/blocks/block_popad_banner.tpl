<{$block.conteudo}>
<style type="text/css">
    <!--
    #sponsorAdDiv {
        position: absolute;
        height: 1;
        width: 1px;
        top: 0;
        left: 0;
        color: #000000;
    }

    -->
</style>
<{assign var="width" value="`$block.width`"}>
<{assign var="height" value="`$block.height`"}>
<script type="text/javascript">
    adTime =<{$block.time}>;  // seconds ad reminder is shown
    chanceAd =<{$block.vezes}>; // ad will be shown 1 in X times (put 1 for everytime)
    var ns = (document.layers);
    var ie = (document.all);
    var w3 = (document.getElementById && !ie);
    var calunit = ns ? "" : "px"
    adCount = 0;

    function setCookie(name, value, expires, path, domain, secure) {
        var curCookie = name + "=" + escape(value) +
                ((expires) ? "; expires=" + expires.toGMTString() : "") +
                ((path) ? "; path=" + path : "") +
                ((domain) ? "; domain=" + domain : "") +
                ((secure) ? "; secure" : "");
        document.cookie = curCookie;
    }

    function getCookie(name) {
        var dc = document.cookie;
        var prefix = name + "=";
        var begin = dc.indexOf("; " + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) return null;
        } else
            begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1)
            end = dc.length;
        return unescape(dc.substring(begin + prefix.length, end));
    }

    function initAd() {
        if (!ns && !ie && !w3) return;
        if (ie)      adDiv = eval('document.all.sponsorAdDiv.style');
        else if (ns) adDiv = eval('document.layers["sponsorAdDiv"]');
        else if (w3) adDiv = eval('document.getElementById("sponsorAdDiv").style');
        if (ie || w3)
            adDiv.visibility = "visible";
        else
            adDiv.visibility = "show";
        if (getCookie('rwbanner') != null)
            var i = getCookie('rwbanner');
        else
            var i = 0;
        if (i == chanceAd) {
            showAd();
            setCookie('rwbanner', 1);
        } else if (i == 0) {
            showAd();
            setCookie('rwbanner', 1);
        } else {
            closeAd();
            var i = getCookie('rwbanner');
            i++;
            setCookie('rwbanner', i);
        }
    }
    function showAd() {
        if (adCount < adTime * 10) {
            adCount += 1;
            if (ie) {
                documentWidth = truebody().offsetWidth / 2 + truebody().scrollLeft - 20;
                documentHeight = truebody().offsetHeight / 2 + truebody().scrollTop - 20;
            }
            else if (ns) {
                documentWidth = window.innerWidth / 2 + window.pageXOffset - 20;
                documentHeight = window.innerHeight / 2 + window.pageYOffset - 20;
            }
            else if (w3) {
                documentWidth = self.innerWidth / 2 + window.pageXOffset - 20;
                documentHeight = self.innerHeight / 2 + window.pageYOffset - 20;
            }
            adDiv.left = documentWidth - <{$width/2}>+calunit;
            adDiv.top = documentHeight - <{$height/2}>+calunit;
            setTimeout("showAd()", 100);
        } else closeAd();
    }
    function closeAd() {
        if (ie || w3)
            adDiv.display = "none";
        else
            adDiv.visibility = "hide";
    }

    function truebody() {
        return (document.compatMode && document.compatMode != "BackCompat") ? document.documentElement : document.body
    }

    if (window.onload) {
        var oldonload = window.onload;
        window.onload = function () {
            oldonload();
            initAd();
        }
    } else {
        onload = initAd;
    }
    //End-->
</script>


<div id="sponsorAdDiv" style="visibility:hidden;">
    <table width="<{$width}>px" height="<{$height}>px" bgcolor="#<{$block.border}>">
        <tr>
            <td>
                <table width="<{$width-5}>px" height="<{$height-5}>px" bgcolor="#<{$block.bgcolor}>">
                    <tr>
                        <td align="center" valign="middle">

                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <{assign var=cont value="0"}>
                                    <{foreach item=banner from=$block.banners}>
                                    <{assign var=cont value="`$cont+1`"}>
                                    <{if $banner.usarhtml == 1}>
                                    <td>
                                        <{if $banner.url != '' && $banner.url != '#'}>
                                        <a href="<{$xoops_url}>/modules/<{$module_dir}>/conta_click.php?id=<{$banner.codigo}>"
                                           target="<{$banner.target}>">
                                            <{/if}>
                                            <div align="center">
                                                <{$banner.htmlcode}>
                                                <{if $block.qtde > "1"}>
                                                    <br>
                                                <{/if}>
                                            </div>
                                            <{if $banner.url != '' && $banner.url != '#'}>
                                        </a>
                                        <{/if}>
                                    </td>
                                    <{if $cont == $block.cols}>
                                </tr>
                                <tr>
                                    <{assign var=cont value="0"}>
                                    <{/if}>
                                    <{else}>
                                    <td>
                                        <div align="center">
                                            <{if $banner.url != '' && $banner.url != '#'}>
                                            <a href="<{$xoops_url}>/modules/<{$module_dir}>/conta_click.php?id=<{$banner.codigo}>"
                                               target="<{$banner.target}>">
                                                <{/if}>
                                                <{if $banner.swf == 1}>
                                                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                                                            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
                                                            width="<{$block.larg}>"
                                                            height="<{$block.alt}>">
                                                        <param name=movie
                                                               value="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/rwbanner.swf">
                                                        <param name=quality value=high>
                                                        <PARAM NAME=FlashVars
                                                               VALUE="alt=<{$block.alt}>&larg=<{$block.larg}>&fps=<{$banner.fps}>&banner=<{$banner.grafico}>&id=<{$banner.codigo}>&url=<{$xoops_url}>/modules/<{$module_dir}>&target=<{$banner.target}>">
                                                        <embed src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/rwbanner.swf"
                                                               FlashVars="alt=<{$block.alt}>&larg=<{$block.larg}>&fps=<{$banner.fps}>&banner=<{$banner.grafico}>&id=<{$banner.codigo}>&url=<{$xoops_url}>/modules/<{$module_dir}>&target=<{$banner.target}>"
                                                               quality=high
                                                               pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
                                                               ;
                                                               type="application/x-shockwave-flash"
                                                               width="<{$block.larg}>" height="<{$block.alt}>">
                                                        </embed>
                                                    </object>
                                                <{else}>
                                                    <img src="<{$banner.grafico}>" alt="">
                                                <{/if}>
                                                <{if $banner.url != '' && $banner.url != '#'}>
                                            </a>
                                            <{/if}>
                                        </div>
                                        <{if $block.qtde > "1"}>
                                            <br>
                                        <{/if}>
                                    </td>
                                    <{if $cont == $block.cols}>
                                </tr>
                                <tr>
                                    <{assign var=cont value="0"}>
                                    <{/if}>
                                    <{/if}>
                                    <{/foreach}>
                                </tr>
                            </table>

                            <{if $block.mostrar == "1"}>
                            (<{$block.lang_mb_text1}>)
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<{else}>
    </td></tr></table></td></tr></table></div>
<{/if}>
