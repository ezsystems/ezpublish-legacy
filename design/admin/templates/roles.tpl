{let member_groups=fetch( user, member_of, hash( id, $node.contentobject_id ) )}
<div class="context-block">
<h2 class="context-title">{'Assigned roles'|i18n( 'design/admin/node/view/full' )} [{count( $member_groups )}]</h2>

<table class="list" cellspacing="0">
{section show=count( $member_groups )}
<tr>
    <th>{'Name'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Edit'|i18n( 'design/admin/node/view/full' )}</th>
</tr>
{section var=Roles loop=$member_groups sequence=array( bglight, bgdark )}
    <tr class="{$Roles.sequence}">
        {* Role name. *}
        <td><a href={concat( '/role/view/', $Roles.item.id )|ezurl}>{$Roles.item.name|wash}</a></td>
        <td><a href={concat( '/role/edit/', $Roles.item.id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit role.'|i18n( 'design/admin/node/view/full' )}" title="{'Edit role.'|i18n( 'design/admin/node/view/full' )}" /</a></td>
    </tr>
{/section}
{section-else}
<tr>
    <td>{'There are no assigned roles.'|i18n( 'design/admin/node/view/full' )}</td>
</tr>
{/section}
</table>

{/let}
</div>