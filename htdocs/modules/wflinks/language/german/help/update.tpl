<div id="help-template" class="outer">
    <h1 class="head">Help:
        <a class="ui-corner-all tooltip" href="<{$xoops_url}>/modules/<{$smarty.const._MI_WFL_DIRNAME}>/admin/index.php"
           title="Back to the administration of <{$smarty.const._MI_WFL_NAME}>"> <{$smarty.const._MI_WFL_NAME}>
            <img src="<{xoAdminIcons home.png}>"
                 alt="Back to the Administration of <{$smarty.const._MI_WFL_NAME}>">
        </a></h1>

    <h4 class="odd">Update</h4><br>

    <h4>Upgrade from WF-Links prior to version 1.05 RC5</h4><br><br>
    <ol>
        <li>Make a backup of the WF-Links database tables and a backup from the folder ../modules/wflinks on your
            server.
        </li>
        <li>Uninstall WF-Links.</li>
        <li>Overwrite the files on your server with the new ones.</li>
        <li>Install WF-Links.</li>
        <li>Restore the database table from point 1.</li>
        <li><strong>Update</strong> WF-Links from the Modules Administration.</li>
    </ol>

</div>
