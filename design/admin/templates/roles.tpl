{let member_groups=fetch( user, member_of, hash( id, $node.contentobject_id ) )}
<div class="context-block">
<h2 class="context-title">{'Roles assigned to this user'|i18n( 'design/admin/node/view/full' )} [{count( $member_groups )}]</h2>

<table class="list" cellspacing="0">
<tr>
    <th>{'Name'|i18n( 'design/admin/node/view/full' )}</th>
</tr>
{section var=Roles show=count($member_groups ) loop=$member_groups sequence=array( bglight, bgdark )}
    <tr class="{$Roles.sequence}">
        <td><a href={concat( '/role/view/', $Roles.item.id )|ezurl}>{$Roles.item.name|wash}</a></td>
    </tr>
{section-else}
<tr><td>asdf</td></tr>
{/section}
</table>

{/let}
</div>