{let member_groups=fetch( user, member_of, hash( id, $node.contentobject_id ) )}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Assigned roles [%roles_count]'|i18n( 'design/admin/node/view/full',, hash( '%roles_count', $member_groups|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
{section show=count( $member_groups )}
<tr>
    <th>{'Name'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Limitation'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Roles loop=$member_groups sequence=array( bglight, bgdark )}
    <tr class="{$Roles.sequence}">

        {* Role name. *}
        <td>{'role'|icon( 'small', 'Role'|i18n( '/design/admin/node/view/full' ) )}&nbsp;<a href={concat( '/role/view/', $Roles.item.id )|ezurl}>{$Roles.item.name|wash}</a></td>

        {* Limitation. *}
        <td>
        {section show=$Roles.item.limit_identifier}
            {$Roles.item.limit_identifier|wash} ({$Roles.item.limit_value|wash})
        {section-else}
            <i>{'No limitation'|i18n( 'design/admin/node/view/full' )}</i>
        {/section}

        </td>

        {* Edit. *}
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

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
