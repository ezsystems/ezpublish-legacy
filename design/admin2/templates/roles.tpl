{let member_groups=fetch( user, member_of, hash( id, $node.contentobject_id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Assigned roles [%roles_count]'|i18n( 'design/admin/node/view/full',, hash( '%roles_count', $member_groups|count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{section show=count( $member_groups )}
<table class="list" cellspacing="0" summary="{'List of roles assigned with and without limitations for current node.'|i18n( 'design/admin/node/view/full' )}">
<tr>
    <th>{'Name'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Limitation'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Roles loop=$member_groups sequence=array( bglight, bgdark )}
    <tr class="{$Roles.sequence}">

        {* Role name. *}
        <td>{'role'|icon( 'small', 'Role'|i18n( 'design/admin/node/view/full' ) )}&nbsp;<a href={concat( '/role/view/', $Roles.item.id )|ezurl}>{$Roles.item.name|wash}</a></td>

        {* Limitation with link. *}
        <td>
        {section show=$Roles.item.limit_identifier}
         {section show=$Roles.item.limit_value|begins_with( '/' )}
              {let  limit_location_array=$Roles.item.limit_value|explode( '/' )
                    limit_location_pinpoint=$limit_location_array|count|sub(2)
                    limit_node_id=$limit_location_array[$limit_location_pinpoint]}
              <a href={concat( '/content/view/full/', $limit_node_id )|ezurl}>{$Roles.item.limit_identifier|wash}&nbsp;({$Roles.item.limit_value|wash})</a>
              {/let}
          {section-else}
              <a href={concat( '/section/view/', $Roles.item.limit_value )|ezurl}>{$Roles.item.limit_identifier|wash}&nbsp;({$Roles.item.limit_value|wash})</a>
          {/section}
        {section-else}
        <i>{'No limitation'|i18n( 'design/admin/node/view/full' )}</i>
        {/section}
        </td>

        {* Edit. *}
        <td><a href={concat( '/role/edit/', $Roles.item.id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit role.'|i18n( 'design/admin/node/view/full' )}" title="{'Edit role.'|i18n( 'design/admin/node/view/full' )}" /></a></td>

    </tr>
{/section}
</table>
{section-else}
<div class="block">
    <p>{'There are no assigned roles.'|i18n( 'design/admin/node/view/full' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

</div>

{/let}
