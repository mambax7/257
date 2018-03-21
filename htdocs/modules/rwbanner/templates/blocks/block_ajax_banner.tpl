<script language="javascript" type="text/javascript">
    var req<{$block.id_div}>

    function loadXMLDoc <{$block.id_div}>(url) {
        req<{$block.id_div}> = null;
        // Procura por um objeto nativo (Mozilla/Safari)
        if (window.XMLHttpRequest) {
            req<{$block.id_div}> = new XMLHttpRequest();
            req<{$block.id_div}>.onreadystatechange = processReqChange<{$block.id_div}>;
            req<{$block.id_div}>.open("GET", url, true);
            req<{$block.id_div}>.send(null);
            // Procura por uma versão ActiveX (IE)
        } else if (window.ActiveXObject) {
            req<{$block.id_div}> = new ActiveXObject("Microsoft.XMLHTTP");
            if (req<{$block.id_div}>) {
                req<{$block.id_div}>.onreadystatechange = processReqChange<{$block.id_div}>;
                req<{$block.id_div}>.open("GET", url, true);
                req<{$block.id_div}>.send();
            }
        }
    }

    function processReqChange <{$block.id_div}>() {
        if (req<{$block.id_div}>.readyState == 1) {
            document.getElementById("ajax_banner<{$block.id_div}>").innerHTML = "<div style='height:<{$block.alt}>px;'><strong><{$block.lang_carreg}></strong></div>";
        }
        if (req<{$block.id_div}>.readyState == 4) {
            if (req<{$block.id_div}>.status == 200) {
                document.getElementById("ajax_banner<{$block.id_div}>").innerHTML = req<{$block.id_div}>.responseText;
            } else {
                alert("Houve um problema ao obter os dados:\n" + req<{$block.id_div}>.statusText);
            }
        }
    }

    function buscarBanner <{$block.id_div}>() {
        loadXMLDoc<{$block.id_div}>("<{$xoops_url}>/modules/<($module_dir}>/include/getajaxbanner.php?categ=<{$block.categ}>&qtde=<{$block.qtde}>&cols=<{$block.cols}>");
    }

    buscarBanner<{$block.id_div}>();

    setInterval("buscarBanner<{$block.id_div}>()", <{$block.tempo}>);
</script>

<div style="margin:0; padding:0; height:<{$block.alt}>px; vertical-align:middle;"
     id="ajax_banner<{$block.id_div}>"></div>
