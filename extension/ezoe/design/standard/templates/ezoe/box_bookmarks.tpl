{default box_embed_mode         = true()
         box_class_filter_array = array()
         box_has_access         = true()}
    <div class="panel" id="bookmark_box" style="display: none; position: relative;">
        <div style="background-color: #eee; text-align: center">
        {if $box_embed_mode}
            <a id="embed_bookmark_go_back_link" title="Go back" href="JavaScript:void(0);" style="float: right;"><img width="16" height="16" border="0" src={"tango/emblem-unreadable.png"|ezimage} /></a>
        {/if}
    {if $box_has_access}
        </div>
        <div id="bookmarks_progress" class="progress-indicator" style="display: none;"></div>
        <table class="node_datalist" id="bookmarks_box_prev">
            <thead>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    {else}
        </div>
        <p>{"Your current user does not have the proper privileges to access this page."|i18n('design/standard/error/kernel')}</p>
    {/if}
    </div>
{/default}
<script type="text/javascript">
{literal}

eZOEPopupUtils.bookmarks = function( offset )
{
    // browse personal bookmarks by offset
    var postData = jQuery('#bookmark_box input, #bookmark_box select').serializeArray(), o = offset ? offset : 0;
    jQuery.ez('ezoe::bookmarks::' + o, postData, eZOEPopupUtils.bookmarksCallBack );
    jQuery('#bookmarks_progress' ).show();
};

eZOEPopupUtils.bookmarksCallBack = function( bookmarkData )
{
    // wrapper function for browseCallBack, called by ajax call in bookmarks()
    return eZOEPopupUtils.browseCallBack( bookmarkData, 'bookmarks', function( tbody, mode, ed )
    {
        var tr = document.createElement("tr"), td = document.createElement("td"), tag = document.createElement("span");
        tr.appendChild( document.createElement("td") );
        td.setAttribute('colspan', '3');
        tag.innerHTML = ed.getLang('ez.empty_bookmarks_result');
        td.appendChild( tag );
        tr.appendChild( td );
        tbody.appendChild( tr );
    });
};

eZOEPopupUtils.bookmarks();

{/literal}
</script>