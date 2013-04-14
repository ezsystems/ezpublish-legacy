<form name="roles" action={concat( $module.functions.list.uri, '/' )|ezurl} method="post" >

{let number_of_items=min( ezpreference( 'admin_role_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Roles (%role_count)'|i18n( 'design/admin/role/list',, hash( '%role_count', $role_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="button-left">
<p class="table-preferences">
{switch match=$number_of_items}
{case match=25}
<a href={'/user/preferences/set/admin_role_list_limit/1'|ezurl}>10</a>
<span class="current">25</span>
<a href={'/user/preferences/set/admin_role_list_limit/3'|ezurl}>50</a>
{/case}

{case match=50}
<a href={'/user/preferences/set/admin_role_list_limit/1'|ezurl}>10</a>
<a href={'/user/preferences/set/admin_role_list_limit/2'|ezurl}>25</a>
<span class="current">50</span>
{/case}

{case}
<span class="current">10</span>
<a href={'/user/preferences/set/admin_role_list_limit/2'|ezurl}>25</a>
<a href={'/user/preferences/set/admin_role_list_limit/3'|ezurl}>50</a>
{/case}

{/switch}
</p>
</div>
<div class="float-break"></div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Toggle selection'|i18n( 'design/admin/role/list')}" onclick="ezjs_toggleCheckboxes( document.roles, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'design/admin/role/list' )}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Roles loop=$roles sequence=array( bglight, bgdark )}
    {let role_name=$Roles.item.name|wash}
    <tr class="{$Roles.sequence}">
    <td class="tight"><input type="checkbox" name="DeleteIDArray[]" value="{$Roles.item.id}" title="{'Select role for removal.'|i18n( 'design/admin/role/list' )}" /></td>
    <td>{'role'|icon( 'small', 'Role'|i18n( 'design/admin/role/list' ) )}&nbsp;<a href={concat( '/role/view/', $Roles.item.id)|ezurl}>{$role_name}</a></td>
    <td><a href={concat( '/role/assign/', $Roles.item.id)|ezurl}><img src={'assign.gif'|ezimage} alt="{'Assign'|i18n( 'design/admin/role/list')}" title="{'Assign the <%role_name> role to a user or a user group.'|i18n( 'design/admin/role/list',, hash( '%role_name', $role_name ) )|wash}" /></a></td>
    <td><a href={concat( '/role/copy/', $Roles.item.id)|ezurl}><img src={'copy.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/role/list' )}" title="{'Copy the <%role_name> role.'|i18n( 'design/admin/role/list',, hash( '%role_name', $role_name ) )|wash}" /></a></td>
    <td><a href={concat( '/role/edit/', $Roles.item.id)|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/role/list' )}" title="{'Edit the <%role_name> role.'|i18n( 'design/admin/role/list',, hash( '%role_name', $role_name ) )|wash}" /></a></td>
    </tr>
{/let}
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/role/list'
         item_count=$role_count
         view_parameters=$view_parameters
         item_limit=$limit}
{/let}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/role/list' )}" title="{'Remove selected roles.'|i18n( 'design/admin/role/list' )}" />
    <input class="button" type="submit" name="NewButton" value="{'New role'|i18n( 'design/admin/role/list' )}" title="{'Create a new role.'|i18n( 'design/admin/role/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
