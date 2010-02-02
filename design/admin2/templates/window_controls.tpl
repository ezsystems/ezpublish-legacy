{* Window controls *}
{def $node_url_alias      = $node.url_alias
     $node_tab_index      = first_set( $view_parameters.tab, ezpreference( 'admin_navigation_content' ), 'preview' )
     $available_languages = fetch( 'content', 'prioritized_languages' )
     $translations        = $node.object.languages
     $translations_count  = $translations|count
     $states              = $node.object.allowed_assign_state_list
     $states_count        = $states|count
     $related_objects_count = fetch( 'content', 'related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $reverse_related_objects_count = fetch( 'content', 'reverse_related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
}
{if or( $node_tab_index|eq( '0' ), $node_tab_index|eq( '1' ) )}
    {set $node_tab_index = 'preview'}
{/if}
<ul class="tabs">
    {* Content preview *}
    <li id="node-tab-preview" class="first{if $node_tab_index|eq('preview')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/preview' )|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Details *}
    <li id="node-tab-details" class="middle{if $node_tab_index|eq('details')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/details' )|ezurl} title="{'Show details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Translations *}
    {if fetch( 'content', 'translation_list' )|count|gt( 1 )}
    {if $available_languages|count|gt( 1 ) }
    <li id="node-tab-translations" class="middle{if $node_tab_index|eq('translations')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/translations' )|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations (%count)'|i18n( 'design/admin/node/view/full',,hash('%count', $translations_count ) )}</a>
    </li>
    {/if}
    {/if}

    {* Locations *}
    <li id="node-tab-locations" class="middle{if $node_tab_index|eq('locations')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/locations' )|ezurl} title="{'Show location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $node.object.assigned_nodes|count ) )}</a>
    </li>

    {* Relations *}
    <li id="node-tab-relations" class="middle{if $node_tab_index|eq('relations')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/relations' )|ezurl} title="{'Show object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', sum( $related_objects_count, $reverse_related_objects_count ) ) )}</a>
    </li>

    {* State assignment *}
    <li id="node-tab-states" class="last{if $node_tab_index|eq('states')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/states' )|ezurl} title="{'Show state assignment widget.'|i18n( 'design/admin/node/view/full' )}">{'Object states (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $states_count ) )}</a>
    </li>
</ul>
<div class="float-break"></div>


{* Preview window *}
<div id="node-tab-preview-content" class="tab-content{if $node_tab_index|ne('preview')} hide{else} selected{/if}">
    {include uri='design:preview.tpl'}
<div class="break"></div>
</div>

{* Details window *}
<div id="node-tab-details-content" class="tab-content{if $node_tab_index|ne('details')} hide{else} selected{/if}">
    {include uri='design:details.tpl'}
<div class="break"></div>
</div>

{* Translations window *}
<div id="node-tab-translations-content" class="tab-content{if $node_tab_index|ne('translations')} hide{else} selected{/if}">
    {include uri='design:translations.tpl'}
<div class="break"></div>
</div>

{* Locations window *}
<div id="node-tab-locations-content" class="tab-content{if $node_tab_index|ne('locations')} hide{else} selected{/if}">
    {include uri='design:locations.tpl'}
<div class="break"></div>
</div>

{* Relations window *}
<div id="node-tab-relations-content" class="tab-content{if $node_tab_index|ne('relations')} hide{else} selected{/if}">
    {include uri='design:relations.tpl'}
<div class="break"></div>
</div>

{* States window *}
<div id="node-tab-states-content" class="tab-content{if $node_tab_index|ne('states')} hide{else} selected{/if}">
    {include uri='design:states.tpl'}
<div class="break"></div>
</div>

{ezscript_require( 'node_tabs.js' )}
{undef}