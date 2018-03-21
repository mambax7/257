<{$block.conteudo}>

<link rel="stylesheet" href="<{$xoops_url}>/modules/<{$module_dir}>/assets/css/lightbox.css" type="text/css"
      media="screen">
<script language="JavaScript1.2">
    var fileLoadingImage = "<{$xoops_url}>/modules/<{$module_dir}>/assets/images/loading.gif";
    var fileBottomNavCloseImage = "<{$xoops_url}>/modules/<{$module_dir}>/assets/images/close.gif";
    <{foreach item=banner from=$block.banners}>
    var bannerLink = "<{$xoops_url}>/modules/<{$module_dir}>/conta_click.php?id=<{$banner.codigo}>";
    var bannerTarget = "<{$banner.target}>";
    <{/foreach}>
</script>
<script src="<{$xoops_url}>/modules/<{$module_dir}>/assets/js/prototype.js" type="text/javascript"></script>
<script src="<{$xoops_url}>/modules/<{$module_dir}>/assets/js/scriptaculous.js?load=effects"
        type="text/javascript"></script>
<script src="<{$xoops_url}>/modules/<{$module_dir}>/assets/js/lightbox.js" type="text/javascript"></script>

<script language="JavaScript1.2">
    <{if $block.type == 1}>
    if (window.onload) {
        var oldonload = window.onload;
        window.onload = function () {
            oldonload();
            initLightbox();
        }
    } else {
        window.onload = initLightbox;
    }
    <{elseif $block.type == 2}>
    function get_cookie_lightBox(Name) {
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

    function showLightBoxornot() {
        if (get_cookie_lightBox("rwbanner_lightbox") == "") {
            if (window.onload) {
                var oldonload = window.onload;
                window.onload = function () {
                    oldonload();
                    initLightbox();
                }
            } else {
                window.onload = initLightbox;
            }
            document.cookie = "rwbanner_lightbox=yes"
        }
    }
    showLightBoxornot()
    <{elseif $block.type == 3}>
    var freq =<{$block.freq}>

            function setCookie_lightBox(name, value, expires, path, domain, secure) {
                var curCookie = name + "=" + escape(value) +
                        ((expires) ? "; expires=" + expires.toGMTString() : "") +
                        ((path) ? "; path=" + path : "") +
                        ((domain) ? "; domain=" + domain : "") +
                        ((secure) ? "; secure" : "");
                document.cookie = curCookie;
            }

    function getCookie_lightBox(name) {
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
            if (getCookie_lightBox('rwbanner_lightbox') != null)
                var i = getCookie_lightBox('rwbanner_lightbox');
            else
                var i = 0;
            if (i == freq) {
                initLightbox();
                setCookie_lightBox('rwbanner_lightbox', 1);
            } else if (i == 0) {
                initLightbox();
                setCookie_lightBox('rwbanner_lightbox', 1);
            } else {
                var i = getCookie_lightBox('rwbanner_lightbox');
                i++;
                setCookie_lightBox('rwbanner_lightbox', i);
            }
        }
    } else {
        window.onload = function () {
            if (getCookie_lightBox('rwbanner_lightbox') != null)
                var i = getCookie_lightBox('rwbanner_lightbox');
            else
                var i = 0;
            if (i == freq) {
                initLightbox();
                setCookie_lightBox('rwbanner_lightbox', 1);
            } else if (i == 0) {
                initLightbox();
                setCookie_lightBox('rwbanner_lightbox', 1);
            } else {
                var i = getCookie_lightBox('rwbanner_lightbox');
                i++;
                setCookie_lightBox('rwbanner_lightbox', i);
            }
        }
    }
    <{/if}>
</script>
<div style="display:none;">
    <{foreach item=banner from=$block.banners}>
        <a id=rwbanner_lightbox href="<{$banner.grafico}>" rel="lightbox"><img id="bannerImg" src="<{$banner.grafico}>"
                                                                                width="<{$block.larg}>"
                                                                                height="<{$block.alt}>"></a>
    <{/foreach}>
</div>
