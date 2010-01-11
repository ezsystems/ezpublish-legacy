{* Window controls *}
{def $node_url_alias = $node.url_alias
     $node_tab_index = first_set( $view_parameters.tab, ezpreference( 'admin_navigation_content' ), 'preview' )}
{if or( $node_tab_index|eq( '0' ), $node_tab_index|eq( '1' ) )}
    {set $node_tab_index = 'preview'}
{/if}
<ul class="tabs context-user">
    {* Content preview *}
    <li id="node-tab-preview" class="first{if $node_tab_index|eq('preview')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/preview' )|ezurl} title="{'Hide preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Details *}
    <li id="node-tab-details" class="middle{if $node_tab_index|eq('details')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/details' )|ezurl} title="{'Hide details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Translations *}
    {if fetch( 'content', 'translation_list' )|count|gt( 1 )}
    {if fetch( 'content', 'prioritized_languages' )|count|gt( 1 ) }
    <li id="node-tab-translations" class="middle{if $node_tab_index|eq('translations')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/translations' )|ezurl} title="{'Hide available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}
    {/if}

    {* Locations *}
    <li id="node-tab-locations" class="middle{if $node_tab_index|eq('locations')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/locations' )|ezurl} title="{'Hide location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Relations *}
    <li id="node-tab-relations" class="middle{if $node_tab_index|eq('relations')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/relations' )|ezurl} title="{'Hide object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* State assignment *}
    <li id="node-tab-states" class="last{if $node_tab_index|eq('states')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/states' )|ezurl} title="{'Hide state assignment widget.'|i18n( 'design/admin/node/view/full' )}">{'Object states'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Roles *}
    <li id="node-tab-roles" class="middle{if $node_tab_index|eq('roles')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/roles' )|ezurl} title="{'Hide role overview.'|i18n( 'design/admin/node/view/full' )}">{'Roles'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Policies *}
    <li id="node-tab-policies" class="middle{if $node_tab_index|eq('policies')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/policies' )|ezurl} title="{'Hide policy overview.'|i18n( 'design/admin/node/view/full' )}">{'Policies'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
</ul>
{undef $node_url_alias $node_tab_index}
<div class="float-break"></div>
