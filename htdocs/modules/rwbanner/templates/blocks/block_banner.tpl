<{if $block.redim}>
    <{assign var=width value="width=\"`$block.larg`\""}>
    <{assign var=height value="height=\"`$block.alt`\""}>
<{else}>
    <{assign var=width value=""}>
    <{assign var=height value=""}>
<{/if}>
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
                                codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" <{$width}> <{$height}>>
                            <param name=movie value="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/rwbanner.swf">
                            <param name=quality value=high>
                            <PARAM NAME=FlashVars
                                   VALUE="alt=<{$block.alt}>&larg=<{$block.larg}>&fps=<{$banner.fps}>&banner=<{$banner.grafico}>&id=<{$banner.codigo}>&url=<{$xoops_url}>/modules/<{$module_dir}>&target=<{$banner.target}>">
                            <embed src="<{$xoops_url}>/modules/<{$module_dir}>/assets/images/rwbanner.swf"
                                   FlashVars="alt=<{$block.alt}>&larg=<{$block.larg}>&fps=<{$banner.fps}>&banner=<{$banner.grafico}>&id=<{$banner.codigo}>&url=<{$xoops_url}>/modules/<{$module_dir}>&target=<{$banner.target}>"
                                   quality=high
                                   pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
                                   ;
                                   type="application/x-shockwave-flash" <{$width}> <{$height}>>
                            </embed>
                        </object>
                    <{else}>
                        <img style="border:0;" src="<{$banner.grafico}>" <{$width}> <{$height}> alt="" border="0">
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
