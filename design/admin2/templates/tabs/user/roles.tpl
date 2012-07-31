{* Member of roles window *}
{if $assigned_roles}
<table class="list" cellspacing="0" summary="{'List of roles assigned with and without limitations for current node.'|i18n( 'design/admin/node/view/full' )}">
<tr>
    <th>{'Name'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Limitation'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Roles loop=$assigned_roles sequence=array( 'bglight', 'bgdark' )}
    <tr class="{$Roles.sequence}">

        {* Role name. *}
        <td>{'role'|icon( 'small', 'Role'|i18n( 'design/admin/node/view/full' ) )}&nbsp;<a href={concat( '/role/view/', $Roles.item.id )|ezurl}>{$Roles.item.name|wash}</a></td>

        {* Limitation with link. *}
        <td>
        {if $Roles.item.limit_identifier}
            {if $Roles.item.limit_value|begins_with( '/' )}
                {let  limit_location_array=$Roles.item.limit_value|explode( '/' )
                    limit_location_pinpoint=$limit_location_array|count|sub(2)
                    limit_node_id=$limit_location_array[$limit_location_pinpoint]}
                    <a href={concat( '/content/view/full/', $limit_node_id )|ezurl}>{$Roles.item.limit_identifier|wash}&nbsp;({$Roles.item.limit_value|wash})</a>
                {/let}
            {else}
                <a href={concat( '/section/view/', $Roles.item.limit_value )|ezurl}>{$Roles.item.limit_identifier|wash}&nbsp;({$Roles.item.limit_value|wash})</a>
            {/if}
        {else}
            <i>{'No limitation'|i18n( 'design/admin/node/view/full' )}</i>
        {/if}
        </td>

        {* Edit. *}
        <td><a href={concat( '/role/edit/', $Roles.item.id )|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit role.'|i18n( 'design/admin/node/view/full' )}" title="{'Edit role.'|i18n( 'design/admin/node/view/full' )}" /></a></td>

    </tr>
{/section}
</table>
{else}
<div class="block">
    <p>{'There are no assigned roles.'|i18n( 'design/admin/node/view/full' )}</p>
</div>
{/if}
