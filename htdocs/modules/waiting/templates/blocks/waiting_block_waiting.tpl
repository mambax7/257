<{foreach item=module from=$block.modules}>
    <b><{$module.name}></b>
    <{foreach item=pending from=$module.pending name=pendinglp}>
        <{if $smarty.foreach.pendinglp.first}><ul><{/if}>
        <li>
            <a href="<{$pending.adminlink}>"><{$pending.lang_linkname}></a>:

            <{if $pending.pendingnum}>
                <span class='bold red'><{$pending.pendingnum}></span>
            <{else}>
                <{$pending.pendingnum}>
            <{/if}>
        </li>
        <{if $smarty.foreach.pendinglp.last}></ul><{/if}>
    <{/foreach}>
<{/foreach}>
