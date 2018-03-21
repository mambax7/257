<div class="sitemap">
    <h1 style="color:#333;"><{$smarty.const._MA_XSITEMAP}></h1>
    <{if 0 != $show_sublink}>
        <ul id="utilityNav">
            <li><a href="<{$xoops_url}>/register.php"><{$smarty.const._MA_XSITEMAP_REGISTER}></a></li>
            <li><a href="<{$xoops_url}>/user.php"><{$smarty.const._MA_XSITEMAP_LOGIN}></a></li>
            <li><a href="<{$xoops_url}>/modules/xsitemap"><{$smarty.const._MA_XSITEMAP_SITEMAP}></a></li>
        </ul>
    <{/if}>
    <ul id="primaryNav" class="col<{$num_col}>">
        <li id="home"><a href="<{$xoops_url}>/"><{$smarty.const._MA_XSITEMAP_HOME}></a></li>
        <{foreach name=i item=module from=$xsitemap.modules}>
            <{include file="db:xsitemap_slickmap.tpl"}>
        <{/foreach}>
    </ul>
</div>
