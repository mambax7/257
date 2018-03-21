<table class="outer" border="0" cellpadding="3" cellspacing="1" width="100%">
    <tr>
        <th colspan="2"><{$smarty.const._AM_SOBJECT_SENT_LINK_INFO}></th>
    </tr>
    <tr>
        <td width="150px" class="head"><{$smarty.const._CO_SOBJECT_LINK_DATE}></td>
        <td class="odd"><{$link.date}></td>
    </tr>
    <tr>
        <td width="150px" class="head"><{$smarty.const._AM_SOBJECT_SENT_LINKS_FROM}></td>
        <td class="even"><{$link.fromInfo}></td>
    </tr>
    <tr>
        <td width="150px" class="head"><{$smarty.const._AM_SOBJECT_SENT_LINKS_TO}></td>
        <td class="odd"><{$link.toInfo}></td>
    </tr>
    <tr>
        <td width="150px" class="head"><{$smarty.const._CO_SOBJECT_LINK_LINK}></td>
        <td class="even"><{$link.fullLink}></td>
    </tr>
    <tr>
        <td width="150px" class="head"><{$smarty.const._CO_SOBJECT_LINK_SUBJECT}></td>
        <td class="odd"><{$link.subject}></td>
    </tr>
    <tr>
        <td width="150px" class="head"><{$smarty.const._CO_SOBJECT_LINK_BODY}></td>
        <td class="even"><{$link.body}></td>
    </tr>
</table>