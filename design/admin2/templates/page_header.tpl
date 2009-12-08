<div id="header-search">
<form action={'/content/search/'|ezurl} method="get">
    {def $current_node_id         = first_set( $module_result.node_id, 0 )
         $selected_search_node_id = first_set( $search_subtree_array[0], 0 )
         $searching_disabled      = $ui_context|eq( 'edit' )}
    {if $searching_disabled}
        <select name="SubTreeArray" title="{'Search location, to be able to narrow down the search results!'|i18n('design/admin/pagelayout')}" disabled="disabled">
            <option value="1" title="{'Search everthing!'|i18n( 'design/admin/pagelayout' )}">{'Everything'|i18n( 'design/admin/pagelayout' )}</option>
        </select>
        <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" disabled="disabled" defaultValue="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
        <input id="searchbutton" class="button-disabled" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
        <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
    {else}
        <select name="SubTreeArray" title="{'Search location, to be able to narrow down the search results!'|i18n('design/admin/pagelayout')}">
            <option value="1" title="{'Search everthing!'|i18n( 'design/admin/pagelayout' )}">{'Everything'|i18n( 'design/admin/pagelayout' )}</option>
            <option value="{ezini( 'NodeSettings', 'RootNode', 'content.ini' )}" title="{'Search content!'|i18n( 'design/admin/pagelayout' )}">{'Content'|i18n( 'design/admin/pagelayout' )}</option>
            <option value="{ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' )}" title="{'Search media!'|i18n( 'design/admin/pagelayout' )}">{'Media'|i18n( 'design/admin/pagelayout' )}</option>
            <option value="{ezini( 'NodeSettings', 'UserRootNode', 'content.ini' )}" title="{'Search users!'|i18n( 'design/admin/pagelayout' )}">{'Users'|i18n( 'design/admin/pagelayout' )}</option>
            {if $selected_search_node_id|gt( 1 )}
                <option value="{$selected_search_node_id}" selected="selected">{'The same location'|i18n( 'design/admin/pagelayout' )}</option>
            {elseif $current_node_id}
                <option value="{$current_node_id}">{'Current location'|i18n( 'design/admin/pagelayout' )}</option>
            {/if}
        </select>
        <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" defaultValue="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
        <input id="searchbutton" class="button" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
        {if eq( $ui_context, 'browse' ) }
            <input name="Mode" type="hidden" value="browse" />
            <input name="BrowsePageLimit" type="hidden" value="{min( ezpreference( 'admin_list_limit' ), 3)|choose( 10, 10, 25, 50 )}" />
            <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
        {else}
            <p class="advanced hide"><a href={'/content/advancedsearch'|ezurl} title="{'Advanced search.'|i18n( 'design/admin/pagelayout' )}">{'Advanced'|i18n( 'design/admin/pagelayout' )}</a></p>
        {/if}
    {/if}
    {undef $current_node_id $selected_search_node_id $searching_disabled}
</form>
</div>

<div id="header-logo">
    <a href="http://ez.no" title="eZ Publish {fetch( 'setup', 'version' )}" target="_blank">&nbsp;</a>
</div>

<div id="header-usermenu">
    <a href={'/user/logout'|ezurl} title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}" id="header-usermenu-logout">{'Logout'|i18n( 'design/admin/pagelayout' )}</a>
</div>