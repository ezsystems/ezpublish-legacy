
{def $search_node_id = first_set( $search_subtree_array[0], $module_result.path[0].node_id, 0 )}
{if $search_node_id}
<form action={'/content/search/'|ezurl} method="get">
    {if $ui_context_edit}
        <input id="searchtext" name="SearchText" class="disabled" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" disabled="disabled" title="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
        <input type="hidden" name="SubTreeArray" value="{$search_node_id}" />
        <input id="searchbutton" class="button-disabled hide" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
        <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
    {else}
        <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" title="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
        <input type="hidden" name="SubTreeArray" value="{$search_node_id}" />
        <input id="searchbutton" class="button hide" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
        {if eq( $ui_context, 'browse' ) }
            <input name="Mode" type="hidden" value="browse" />
            <input name="BrowsePageLimit" type="hidden" value="{min( ezpreference( 'admin_list_limit' ), 3)|choose( 10, 10, 25, 50 )}" />
            <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
        {else}
            <p class="advanced hide"><a href={'/content/advancedsearch'|ezurl} title="{'Advanced search.'|i18n( 'design/admin/pagelayout' )}">{'Advanced'|i18n( 'design/admin/pagelayout' )}</a></p>
        {/if}
    {/if}
</form>
{/if}
{undef $search_node_id}