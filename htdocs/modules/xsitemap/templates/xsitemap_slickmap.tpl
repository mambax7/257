<{if $module.name}>
    <li>
        <a href="<{$xoops_url}>/modules/<{$module.directory}>/"><{$module.name}></a>
        <{if $module.parent}>
            <ul>
                <{foreach item=parent from=$module.parent}>
                    <li>
                        <a href="<{$xoops_url}>/modules/<{$module.directory}>/<{$parent.url}>"><{$parent.title}></a>
                        <{if $show_subcategories}>
                            <{if $parent.child}>
                                <ul>
                                    <{foreach item=child from=$parent.child}>
                                        <li><a href="<{$xoops_url}>/modules/<{$module.directory}>/<{$child.url}>"><{$child.title}></a></li>
                                    <{/foreach}>
                                </ul>
                            <{/if}>
                        <{/if}>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>
        <{if $show_sublink}>
            <{if $module.sublinks}>
                <ul class="sublink">
                    <{foreach item=sublink from=$module.sublinks}>
                        <li><a href="<{$sublink.url}>"><{$sublink.name}></a></li>
                    <{/foreach}>
                </ul>
            <{/if}>
        <{/if}>
    </li>
<{/if}>
