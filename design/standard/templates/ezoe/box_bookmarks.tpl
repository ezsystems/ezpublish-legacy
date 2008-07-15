{default embed_mode         = true()
         class_filter_array = array()
         has_access         = true()}
    <div class="panel" style="display: none; position: relative;">
        <div style="background-color: #eee; text-align: center">
        {if $embed_mode}
            <a id="embed_bookmarks_go_back_link" title="Go back" href="JavaScript:void(0);" style="float: right;"><img width="16" height="16" border="0" src={"tango/emblem-unreadable.png"|ezimage} /></a>
        {/if}
    {if $has_access}
        </div>
        <div id="bookmarks_progress" class="progress-indicator" style="display: none;"></div>
        <table id="bookmarks_box_prev">
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
<!--
{literal}

eZOEPopupUtils.bookmarksAjax = ez.ajax( { 'charset': 'UTF-8' } );

eZOEPopupUtils.bookmarks = function( offset )
{
    // browse personal bookmarks by offset
    // global objects: eZOeMCE   
    eZOEPopupUtils.bookmarksAjax.load( eZOeMCE['extension_url'] + '/bookmarks/' + (offset || 0), '', eZOEPopupUtils.bookmarksCallBack  );
    ez.$('bookmarks_progress' ).show();
};

eZOEPopupUtils.bookmarksCallBack = function( r )
{
    // wrapper function for browseCallBack, called by ajax call in bookmarks()
    return eZOEPopupUtils.browseCallBack( r, 'bookmarks' );
};

eZOEPopupUtils.bookmarks();

{/literal}
//-->
</script>