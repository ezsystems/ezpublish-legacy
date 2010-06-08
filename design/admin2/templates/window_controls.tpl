{* Window controls *}
{def $node_url_alias      = $node.url_alias
     $tabs_disabled       = ezpreference( 'admin_navigation_content' )|not
     $default_tab         = 'view'
     $node_tab_index      = first_set( $view_parameters.tab, $default_tab )
     $read_open_tab_by_cookie = true()
     $available_languages = fetch( 'content', 'prioritized_languages' )
     $translations        = $node.object.languages
     $translations_count  = $translations|count
     $states              = $node.object.allowed_assign_state_list
     $states_count        = $states|count
     $related_objects_count = fetch( 'content', 'related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $reverse_related_objects_count = fetch( 'content', 'reverse_related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $valid_tabs = array( $default_tab, 'details', 'translations', 'locations', 'relations', 'states' )
}

{if $tabs_disabled}
    <div class="button-left"><a id="maincontent-show" class="show-hide-tabs" href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Enable &quot;Tabs&quot; by default while browsing content.'|i18n( 'design/admin/parts/my/menu' )}">+</a></div>
{else}
    <div class="button-left"><a id="maincontent-hide" class="show-hide-tabs" href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Disable &quot;Tabs&quot; by default while browsing content.'|i18n( 'design/admin/parts/my/menu' )}">-</a></div>
{/if}

{if $valid_tabs|contains( $node_tab_index )|not()}
    {set $node_tab_index = $default_tab}
{elseif is_set( $view_parameters.tab )}
    {* Force tabs enabled if there is a tab index in the url and it's valid *}
    {set $tabs_disabled = false()}
    {* Signal to node_tab.js that tab is forced by url *}
    {set $read_open_tab_by_cookie = false()}
{/if}

<ul class="tabs{if $tabs_disabled} disabled{/if}{if $read_open_tab_by_cookie} tabs-by-cookie{/if}">
    {* Content (pre)view *}
    <li id="node-tab-view" class="first{if $node_tab_index|eq('view')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'View'|i18n( 'design/admin/node/view/full' )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/view' )|ezurl} title="{'Show simplified view of content.'|i18n( 'design/admin/node/view/full' )}">{'View'|i18n( 'design/admin/node/view/full' )}</a>
        {/if}
    </li>

    {* Details *}
    <li id="node-tab-details" class="middle{if $node_tab_index|eq('details')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/details' )|ezurl} title="{'Show details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
        {/if}
    </li>

    {* Translations *}
    {if fetch( 'content', 'translation_list' )|count|gt( 1 )}
    {if $available_languages|count|gt( 1 ) }
    <li id="node-tab-translations" class="middle{if $node_tab_index|eq('translations')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Translations (%count)'|i18n( 'design/admin/node/view/full',,hash('%count', $translations_count ) )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/translations' )|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations (%count)'|i18n( 'design/admin/node/view/full',,hash('%count', $translations_count ) )}</a>
        {/if}
    </li>
    {/if}
    {/if}

    {* Locations *}
    <li id="node-tab-locations" class="middle{if $node_tab_index|eq('locations')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Locations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $node.object.assigned_nodes|count ) )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/locations' )|ezurl} title="{'Show location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $node.object.assigned_nodes|count ) )}</a>
        {/if}
    </li>

    {* Relations *}
    <li id="node-tab-relations" class="middle{if $node_tab_index|eq('relations')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Relations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', sum( $related_objects_count, $reverse_related_objects_count ) ) )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/relations' )|ezurl} title="{'Show object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', sum( $related_objects_count, $reverse_related_objects_count ) ) )}</a>
        {/if}
    </li>

    {* Ordering *}
    <li id="node-tab-ordering" class="last{if $node_tab_index|eq('ordering')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Ordering'|i18n( 'design/admin/node/view/full' )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/ordering' )|ezurl} title="{'Show published ordering overview.'|i18n( 'design/admin/node/view/full' )}">{'Ordering'|i18n( 'design/admin/node/view/full' )}</a>
        {/if}
    </li>
</ul>
<div class="float-break"></div>

{if $tabs_disabled}
<div class="tabs-content disabled"></div>
{else}
<div class="tabs-content">
    {include uri='design:windows.tpl'}
</div>
{/if}

{ezscript_require( 'node_tabs.js' )}
{undef}