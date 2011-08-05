{if fetch( 'user', 'has_access_to', hash( 'module', 'content', 'function', 'bookmark' ) )}

<div id="bookmarks">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

    {if and( ne( $ui_context, 'edit' ), ne( $ui_context, 'browse' ) )}
        <h4>{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</h4>
    {else}
     {if eq( $ui_context, 'edit' )}
        <h4><span class="disabled">{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {else}
        <h4>{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</h4>
     {/if}
    {/if}

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">


    {def $bookmark_list = fetch( 'content', 'bookmarks', hash( 'limit', 20 ) )
         $bookmark_node = 0}
    {if $bookmark_list}
    <ul>
        {foreach $bookmark_list as $bookmark}
            {set $bookmark_node = $bookmark.node}
            {if ne( $ui_context, 'edit' )}
                 <li>
                 {if ne( $ui_context, 'browse')}
                     <a href="#" onclick="ezpopmenu_showTopLevel( event, 'BookmarkMenu', ez_createAArray( new Array( '%nodeID%', '{$bookmark.node_id}', '%objectID%', '{$bookmark.contentobject_id}', '%bookmarkID%', '{$bookmark.id}', '%languages%', {$bookmark_node.object.language_js_array|wash} ) ) , '{$bookmark.name|shorten(18)|wash(javascript)}'); return false;">{$bookmark_node.class_identifier|class_icon( small, '[%classname] Click on the icon to display a context-sensitive menu.'|i18n( 'design/admin/pagelayout',, hash( '%classname', $bookmark_node.class_name  ) ) )}</a>&nbsp;<a href={$bookmark_node.url_alias|ezurl}>{$bookmark_node.name|wash}</a></li>
                 {else}
                     {if $bookmark_node.is_container}
                         {$bookmark_node.class_identifier|class_icon( small, $bookmark_node.class_name )}&nbsp;<a href={concat( '/content/browse/', $bookmark_node.node_id)|ezurl}>{$bookmark_node.name|wash}</a></li>
                     {else}
                         {$bookmark_node.class_identifier|class_icon( small, $bookmark_node.class_name )}&nbsp;{$bookmark_node.name|wash}</li>
                     {/if}
                 {/if}
             {else}
                 <li>{$bookmark_node.class_identifier|class_icon( ghost, $bookmark_node.class_name )}&nbsp;<span class="disabled">{$bookmark_node.name|wash}</span></li>
             {/if}
        {/foreach}
    </ul>
    {/if}
    {undef $bookmark_list $bookmark_node}

    <div class="block">
    {* Show "Add to bookmarks" button if we're viewing an actual node. *}
    {if and( is_set( $module_result.content_info.node_id ), $ui_context|ne( 'edit' ), $ui_context|ne( 'browse' ) )}
	    <form method="post" action={'content/action'|ezurl}>
	    <input type="hidden" name="ContentNodeID" value="{$module_result.content_info.node_id}" />
	    <input class="button" type="submit" name="ActionAddToBookmarks" value="{'Add to bookmarks'|i18n( 'design/admin/pagelayout' )}" title="{'Add the current item to your bookmarks.'|i18n( 'design/admin/pagelayout' )}" />
	    </form>
    {else}
	    <form method="post" action={'content/action'|ezurl}>
	    <input class="button-disabled" type="submit" value="{'Add to bookmarks'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
	    </form>
	{/if}
    </div>

{* DESIGN: Content END *}</div></div></div>                     
</div>

{/if}