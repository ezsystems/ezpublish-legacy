    {* Roles *}
    <li id="node-tab-roles" class="{if $last}last{else}middle{/if}{if $node_tab_index|eq('roles')} selected{/if}">
        {if $tabs_disabled}
            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{'Roles (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $assigned_roles|count ) )}</span>
        {else}
            <a href={concat( $node_url_alias, '/(tab)/roles' )|ezurl} title="{'Show role overview.'|i18n( 'design/admin/node/view/full' )}">{'Roles (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $assigned_roles|count ) )}</a>
        {/if}
    </li>
