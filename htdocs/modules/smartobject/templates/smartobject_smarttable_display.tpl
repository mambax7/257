<form>
    <div style="margin-bottom: 12px;">
         <{foreach from=$smartobject_introButtons item=introButton}>
            <input type="button" name="<{$introButton.name}>" onclick="location='<{$introButton.location}>'" value="<{$introButton.value}>">
        <{/foreach}>
    </div>
</form>

<{if $smartobject_pagenav}>
    <div style="text-align:right; padding-bottom: 3px;"><{$smartobject_pagenav}></div>
<{/if}>

<form name='pick' id='pick' action='<{$smartobject_optionssel_action}>' method='POST' style='margin: 0;'>
    <table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-left: 1px solid silver; border-top: 1px solid silver; border-right: 1px solid silver;'>
        <tr>
            <td>
                <{if $smartobject_optionssel_fieldsForFilter}>
                    <span style='font-weight: bold; font-size: 12px;'><{$smarty.const._CO_OBJ_FILTER}> : </span>
                    <select name='filtersel' onchange='submit()'>
                        <{foreach from=$smartobject_optionssel_fieldsForFilter key=key item=field}>
                            <option value='<{$key}>' <{$field.selected}> > <{$field.caption}></option>
                        <{/foreach}>
                    </select>
                    <{if $smartobject_optionssel_filterOptionsArray}>
                        <select name='filteroptionssel' onchange='submit()'>
                            <{foreach from=$smartobject_optionssel_filterOptionsArray key=key item=field}>
                                <option value='<{$key}>' <{$field.selected}> > <{$field.caption}></option>
                            <{/foreach}>
                        </select>
                    <{/if}>
                <{/if}>
            </td>
            <td align='right'>
                <{$smarty.const._CO_OBJ_SORT}>
                <select name='sortsel' onchange='submit()'>
                    <{foreach from=$smartobject_optionssel_fieldsForSorting key=key item=field}>
                        <option value='<{$key}>' <{$field.selected}> > <{$field.caption}></option>
                    <{/foreach}>
                </select>
                <select name='ordersel' onchange='submit()'>
                    <{foreach from=$smartobject_optionssel_ordersArray key=key item=field}>
                        <option value='<{$key}>' <{$field.selected}> > <{$field.caption}></option>
                    <{/foreach}>
                </select>
                <{$smarty.const._CO_OBJ_LIMIT}>
                <select name='limitsel' onchange='submit()'>
                    <{foreach from=$smartobject_optionssel_limitsArray key=key item=field}>
                        <option value='<{$key}>' <{$field.selected}> > <{$field.caption}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>
    </table>
</form>

<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>
    <tr>
     <{foreach from=$smartobject_columns item=column}>
        <td class='bg3' width="<{$column.width}>" align='<{$column.align}>'><b><{$column.caption}></b></td>
     <{/foreach}>
     <td width='<{$smartobject_actions_column_width}>' class='bg3' align='center'><b><{$smarty.const._CO_SOBJECT_ACTIONS}></b></td>
    </tr>

    <{if $smartobject_objects}>
        <{foreach from=$smartobject_objects item=smartobject_object}>
            <tr>
                <{foreach from=$smartobject_object.columns item=column}>
                    <td class="<{$smartobject_object.class}>" width="<{$column.width}>" align="<{$column.align}>"><{$column.value}></td>
                <{/foreach}>
                <td class="<{$smartobject_object.class}>" align='center'>
                    <{foreach from=$smartobject_object.actions item=action}>
                        <{$action}>
                    <{/foreach}>
                </td>
            </tr>
        <{/foreach}>
    <{else}>
        <tr>
            <td class='head' style='text-align: center; font-weight: bold;' colspan="<{$smartobject_colspan}>"><{$smarty.const._CO_OBJ_NO_OBJECTS}></td>
        </tr>
    <{/if}>
</table>
<{if $smartobject_pagenav}>
    <div style="text-align:right; padding-top: 3px;"><{$smartobject_pagenav}></div>
<{/if}>
