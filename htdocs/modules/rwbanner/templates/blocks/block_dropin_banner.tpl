<{$block.conteudo}>
<script language="JavaScript1.2">

    // Drop-in content box- By Dynamic Drive
    // For full source code and more DHTML scripts, visit http://www.dynamicdrive.com
    // This credit MUST stay intact for use
    <{if $block.type == 3}>
    //Specify rate of display (1/freq)
    var freq =<{$block.freq}>

            function setCookie_Dropin(name, value, expires, path, domain, secure) {
                var curCookie = name + "=" + escape(value) +
                        ((expires) ? "; expires=" + expires.toGMTString() : "") +
                        ((path) ? "; path=" + path : "") +
                        ((domain) ? "; domain=" + domain : "") +
                        ((secure) ? "; secure" : "");
                document.cookie = curCookie;
            }

    function getCookie_Dropin(name) {
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

    if (window.onload) {
        var oldonload = window.onload;
        window.onload = function () {
            oldonload();
            if (getCookie_Dropin('rwbanner_dropin') != null)
                var i = getCookie_Dropin('rwbanner_dropin');
            else
                var i = 0;
            if (i == freq) {
                initbox();
                setCookie_Dropin('rwbanner_dropin', 1);
            } else if (i == 0) {
                initbox();
                setCookie_Dropin('rwbanner_dropin', 1);
            } else {
                var i = getCookie_Dropin('rwbanner_dropin');
                i++;
                setCookie_Dropin('rwbanner_dropin', i);
            }
        }
    } else {
        window.onload = function () {
            if (getCookie_Dropin('rwbanner_dropin') != null)
                var i = getCookie_Dropin('rwbanner_dropin');
            else
                var i = 0;
            if (i == freq) {
                initbox();
                setCookie_Dropin('rwbanner_dropin', 1);
            } else if (i == 0) {
                initbox();
                setCookie_Dropin('rwbanner_dropin', 1);
            } else {
                var i = getCookie_Dropin('rwbanner_dropin');
                i++;
                setCookie_Dropin('rwbanner_dropin', i);
            }
        }
    }

    <{/if}>
    var ie = document.all
    var dom = document.getElementById
    var ns4 = document.layers
    var calunits = document.layers ? "" : "px"

    var bouncelimit = 32 //(must be divisible by 8)
    var direction = "<{$block.direction}>"

    function initbox() {
        if (!dom && !ie && !ns4)
            return
        crossobj = (dom) ? document.getElementById("dropin").style : ie ? document.all.dropin : document.dropin
        scroll_top = (ie) ? truebody().scrollTop : window.pageYOffset
        crossobj.top = scroll_top - 250 + calunits
        crossobj.visibility = (dom || ie) ? "visible" : "show"
        dropstart = setInterval("dropin()", 50)
    }

    function dropin() {
        scroll_top = (ie) ? truebody().scrollTop : window.pageYOffset
        if (parseInt(crossobj.top) < 100 + scroll_top)
            crossobj.top = parseInt(crossobj.top) + 40 + calunits
        else {
            clearInterval(dropstart)
            bouncestart = setInterval("bouncein()", 50)
        }
    }

    function bouncein() {
        crossobj.top = parseInt(crossobj.top) - bouncelimit + calunits
        if (bouncelimit < 0)
            bouncelimit += 8
        bouncelimit = bouncelimit * -1
        if (bouncelimit == 0) {
            clearInterval(bouncestart)
        }
    }

    function dismissbox() {
        if (window.bouncestart) clearInterval(bouncestart)
        crossobj.visibility = "hidden"
    }

    function truebody() {
        return (document.compatMode && document.compatMode != "BackCompat") ? document.documentElement : document.body
    }
    <{if $block.type == 1}>
    if (window.onload) {
        var oldonload = window.onload;
        window.onload = function () {
            oldonload();
            initbox();
        }
    } else {
        window.onload = initbox;
    }
    <{elseif $block.type == 2}>
    function get_cookie_Dropin(Name) {
        var search = Name + "="
        var returnvalue = ""
        if (document.cookie.length > 0) {
            offset = document.cookie.indexOf(search)
            if (offset != -1) {
                offset += search.length
                end = document.cookie.indexOf(";", offset)
                if (end == -1)
                    end = document.cookie.length;
                returnvalue = unescape(document.cookie.substring(offset, end))
            }
        }
        return returnvalue;
    }

    function Dropinornot() {
        if (get_cookie_Dropin("rwbanner_dropin") == "") {
            if (window.onload) {
                var oldonload = window.onload;
                window.onload = function () {
                    oldonload();
                    initbox();
                }
            } else {
                window.onload = initbox;
            }
            document.cookie = "rwbanner_dropin=yes"
        }
    }
    Dropinornot()
    <{/if}>
</script>
<div id="dropin"
     style="position:absolute;visibility:hidden;padding: 0 10px 10px;left:<{$block.left}>px;top:<{$block.top}>px;width:<{$block.width}>px;height:<{$block.height}>px; background-color:#<{$block.bgcolor}>; border:1px solid #000000;">

    <div align="right" style="width:100%; padding-top:5px; padding-bottom:5px;"><a
                style="color:#000000; font-weight:bold;" href="#"
                onClick="dismissbox();return false"><{$block.lang_close}></a></div>

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
                                    width="<{$block.larg}>" height="<{$block.alt}>">
                                <param name=movie
                                       value="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/rwbanner.swf">
                                <param name=quality value=high>
                                <PARAM NAME=FlashVars
                                       VALUE="alt=<{$block.alt}>&larg=<{$block.larg}>&fps=<{$banner.fps}>&banner=<{$banner.grafico}>&id=<{$banner.codigo}>&url=<{$xoops_url}>/modules/<{$module_dir}>&target=<{$banner.target}>">
                                <embed src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/rwbanner.swf"
                                       FlashVars="alt=<{$block.alt}>&larg=<{$block.larg}>&fps=<{$banner.fps}>&banner=<{$banner.grafico}>&id=<{$banner.codigo}>&url=<{$xoops_url}>/modules/<{$module_dir}>&target=<{$banner.target}>"
                                       quality=high
                                       pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
                                       ; type="application/x-shockwave-flash"
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

</div>
