{* Window controls *}
{def $node_url_alias = $node.url_alias
     $node_tab_index      = first_set( $view_parameters.tab, ezpreference( 'admin_navigation_content' ), 'preview' )
     $available_languages = fetch( 'content', 'prioritized_languages' )
     $translations        = $node.object.languages
     $translations_count  = $translations|count
     $states              = $node.object.allowed_assign_state_list
     $states_count        = $states|count
     $related_objects_count = fetch( 'content', 'related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $reverse_related_objects_count = fetch( 'content', 'reverse_related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $assigned_policies   = fetch( 'user', 'user_role', hash( 'user_id', $node.contentobject_id ) )
     $valid_tabs = array( 'preview', 'details', 'translations', 'locations', 'relations', 'roles', 'policies' )
}
{if or( $node_tab_index|eq( '0' ), $node_tab_index|eq( '1' ) )}
    {set $node_tab_index = 'preview'}
{elseif $valid_tabs|contains( $node_tab_index )|not()}
    {set $node_tab_index = 'preview'}
{/if}
<ul class="tabs context-user">
    {* Content preview *}
    <li id="node-tab-preview" class="first{if $node_tab_index|eq('preview')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/preview' )|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Details *}
    <li id="node-tab-details" class="middle{if $node_tab_index|eq('details')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/details' )|ezurl} title="{'Show details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Translations *}
    {if fetch( 'content', 'translation_list' )|count|gt( 1 )}
    {if $available_languages|count|gt( 1 ) }
    <li id="node-tab-translations" class="middle{if $node_tab_index|eq('translations')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/translations' )|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations (%count)'|i18n( 'design/admin/node/view/full',,hash('%count', $translations_count ) )}</a>
    </li>
    {/if}
    {/if}

    {* Locations *}
    <li id="node-tab-locations" class="middle{if $node_tab_index|eq('locations')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/locations' )|ezurl} title="{'Show location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $node.object.assigned_nodes|count ) )}</a>
    </li>

    {* Relations *}
    <li id="node-tab-relations" class="middle{if $node_tab_index|eq('relations')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/relations' )|ezurl} title="{'Show object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', sum( $related_objects_count, $reverse_related_objects_count ) ) )}</a>
    </li>

    {* State assignment 
    <li id="node-tab-states" class="last{if $node_tab_index|eq('states')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/states' )|ezurl} title="{'Show state assignment widget.'|i18n( 'design/admin/node/view/full' )}">{'Object states (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $states_count ) )}</a>
    </li>
    *}

    {* Roles *}
    <li id="node-tab-roles" class="middle{if $node_tab_index|eq('roles')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/roles' )|ezurl} title="{'Show role overview.'|i18n( 'design/admin/node/view/full' )}">{'Roles'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Policies *}
    <li id="node-tab-policies" class="middle{if $node_tab_index|eq('policies')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/policies' )|ezurl} title="{'Show policy overview.'|i18n( 'design/admin/node/view/full' )}">{'Policies (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $assigned_policies|count ) )}</a>
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

{* States window 
<div id="node-tab-states-content" class="tab-content{if $node_tab_index|ne('states')} hide{else} selected{/if}">
    {include uri='design:states.tpl'}
<div class="break"></div>
</div> *}

{* Member of roles window *}
<div id="node-tab-roles-content" class="tab-content{if $node_tab_index|ne('roles')} hide{else} selected{/if}">
    {include uri='design:roles.tpl'}
<div class="break"></div>
</div>

{* Policy list window *}
<div id="node-tab-policies-content" class="tab-content{if $node_tab_index|ne('policies')} hide{else} selected{/if}">
    {include uri='design:policies.tpl'}
<div class="break"></div>
</div>

{ezscript_require( 'node_tabs.js' )}
{undef}