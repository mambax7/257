<{if $smartobject_single_view_header_value && !$smartobject_header_as_row}>
    <h1><{$smartobject_single_view_header_value}></h1>
<{/if}>

<table class="outer" cellspacing="1" width="100%">
    <{if $smartobject_single_view_header_value && $smartobject_header_as_row}>
        <tr>
            <th class="bg3" width="200"><{$smartobject_single_view_header_caption}></th>
            <th class="bg3"><{$smartobject_single_view_header_value}></th>
        </tr>
    <{/if}>
    <{foreach from=$smartobject_object_array key=key item=field name=singleviewloop}>
        <tr>
            <td class="head" width="200"><{$field.caption}></td>
            <td class="<{cycle values="even,odd"}>"><{$field.value}></td>
        </tr>
    <{/foreach}>
</table>