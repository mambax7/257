<h2 style="text-align: left;"><{$lang_headlines}></h2>
<div style='padding: 1px; text-align: left;'>
    <ul style="list-style-image:url(Frameworks/moduleclasses/icons/16/rss.gif);">
        <!-- start site loop -->
        <{foreach item=site from=$feed_sites}>
            <li><{$site.editurl}>&nbsp;<a
                        href="<{$xoops_url}>/modules/xoopsheadline/index.php?id=<{$site.id}>"><{$site.name}></a></li>
        <{/foreach}>
        <!-- end site loop -->
    </ul>
</div>

<{$headline}>
