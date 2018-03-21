<{if $smartobject_css}>
    <{$smartobject_css}>
<{/if}>
<div id="smartobject_rating_container">
    <div class="item">
        <{if $smartobject_rated}>
            <div class="itemHead"><b><{$smarty.const._SOBJECT_RATING_ALREADY_RATED}><{$smartobject_user_rate}></b></div>
        <{elseif $smartobject_user_can_rate}>
            <div class="itemHead"><{$smarty.const._SOBJECT_RATING_RATE_THIS}></div>
            <div class="odd">
                <form name='smartobject_rating_form' id='smartobject_rating_form' method='post'>
                    <div style="float: left;">
                        <input name="smartobject_rating_value" value="1" type="radio">1
                        <input name="smartobject_rating_value" value="2" type="radio">2
                        <input name="smartobject_rating_value" value="3" type="radio">3
                        <input name="smartobject_rating_value" value="4" type="radio">4
                        <input name="smartobject_rating_value" value="5" type="radio">5
                    </div>
                    <div style="float: right;">
                        <input name="smartobject_rating_submit" value="<{$smarty.const._SOBJECT_RATING_RATE}>"
                               type="submit">
                    </div>
                </form>
                <br>
            </div>
        <{/if}>
        <div class="odd" style="display: block;">
            <div><b><{$smarty.const._SOBJECT_RATING_VOTERS_TOTAL}></b><{$smartobject_rating_stats_total}></div>
            <div><b><{$smarty.const._SOBJECT_RATING_AVERAGE}></b><{$smartobject_rating_stats_average}></div>
        </div>

    </div>
</div>
