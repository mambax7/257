<{if $showform}>
    <div id="smartobject_sendlink">
        <{$sendlink.javascript}>
        <form name="<{$sendlink.name}>" action="<{$sendlink.action}>" method="<{$sendlink.method}>" <{$sendlink.extra}>>
            <table class="outer" cellspacing="1">
                <tr>
                    <th colspan="2"><{$sendlink.title}></th>
                </tr>
                <!-- start of form elements loop -->
                <{foreach item=element from=$sendlink.elements}>
                    <{if $element.hidden != true}>
                        <tr>
                            <td class="head"><{$element.caption}>
                                <{if $element.description}>
                                    <div style="font-weight: normal;"><{$element.description}></div>
                                <{/if}>
                            </td>
                            <td class="<{cycle values="even,odd"}>"><{$element.body}></td>
                        </tr>
                    <{else}>
                        <{$element.body}>
                    <{/if}>
                <{/foreach}>
                <!-- end of form elements loop -->
            </table>
        </form>
    </div>
<{else}>
    <div style="margin-top: 190px; text-align: center;">
        <{if $send_success}>
            <h2><{$smarty.const._CO_SOBJECT_SEND_SUCCESS}></h2>
            <{$smarty.const._CO_SOBJECT_SEND_SUCCESS_INFO}>
            <br>
            <br>
            <br>
            <br>
            <div><a href="javascript:window.close();"><{$smarty.const._CO_SOBJECT_CLOSE_WINDOW}></a></div>
        <{/if}>

        <{if $send_error}>
            <h2><{$send_error}></h2>
            <div><a href="javascript:window.close();"><{$smarty.const._CO_SOBJECT_CLOSE_WINDOW}></a></div>
        <{/if}>
    </div>
<{/if}>
