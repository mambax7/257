<style>
    .desativ {
        color: #FF0000;
    }
</style>
<{if $xoops_isuser}>
    <div align="center" style="width:100%; display:inline;" nowrap>
        <form method="GET">
            <{$block.select}>
            <input type="submit" value="<{$block.lang_button1}>">
        </form>
        <form method="GET">
            <{$block.select1}>
            <input type="submit" value="<{$block.lang_button2}>">
        </form>
    </div>
    <table class="outer" style="font-size:9px;">
        <tr class="head" style="height:20px;">
            <td align="center" style="padding-top:5px;"><{$block.lang_cod}><{$block.img_cod}></td>
            <td align="center" style="padding-top:5px;"><{$block.lang_imp}><{$block.img_exib}></td>
            <td align="center" style="padding-top:5px;"><{$block.lang_rest}></td>
            <td align="center" style="padding-top:5px;"><{$block.lang_clic}><{$block.img_click}></td>
            <td align="center" style="padding-top:5px;"><{$block.lang_perc}></td>
            <td align="center" style="padding-top:5px;"><{$block.lang_data}><{$block.img_data}></td>
            <td align="center" style="padding-top:5px;"><{$block.lang_periodo}></td>
        </tr>
        <{foreach item=row from=$block.rows}>
            <tr class='<{cycle values="even, odd"}>'>
                <td align="center" class="<{$row.class}>"><{$row.link}></td>
                <td align="center" class="<{$row.class}>"><{$row.exibicoes}></td>
                <td align="center" class="<{$row.class}>"><{$row.exibrest}></td>
                <td align="center" class="<{$row.class}>"><{$row.clicks}></td>
                <td align="center" class="<{$row.class}>"><{$row.perc}></td>
                <td align="center" class="<{$row.class}>"><{$row.data}></td>
                <td align="center" class="<{$row.class}>"><{$row.periodo}></td>
            </tr>
        <{/foreach}>
        <tr class="head" align="center" style="height:20px;">
            <td colspan="7" style="padding-top:5px;"><{$block.pag}></td>
        </tr>
    </table>
    <div style="font-size:9px; text-align:justify;">
        <{$block.lang_msg1}>
    </div>
<{else}>
    <div style="font-size:9px; text-align:justify;">
        <{$block.lang_msg2}>
    </div>
<{/if}>
