{literal}
<script language="JavaScript1.2" type="text/javascript">
<!--
function selectAll()
{
    with (document.roleList)
	{
        for (var i=0; i < elements.length; i++)
        {
            if (elements[i].type == 'checkbox' && elements[i].name == 'DeleteIDArray[]' && elements[i].disabled == "")
            elements[i].checked = true;
	    }
    }
}

function deSelectAll()
{
    with (document.roleList)
	{
        for (var i=0; i < elements.length; i++)
	    {
            if (elements[i].type == 'checkbox' && elements[i].name == 'DeleteIDArray[]' && elements[i].disabled == "")
            elements[i].checked = false;
	    }
    }
}
//-->
</script>
{/literal}

{let number_of_items=min( ezpreference( 'admin_role_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<h1>{"Roles"|i18n("design/standard/role")}</h1>

{* Items per page and view mode selector. *}
<div class="viewbar">
<div class="left">
Items:
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_role_list_limit/1'|ezurl}>10</a>
        25
        <a href={'/user/preferences/set/admin_role_list_limit/3'|ezurl}>50</a>
        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_role_list_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_role_list_limit/2'|ezurl}>25</a>
        50
        {/case}

        {case}
        10
        <a href={'/user/preferences/set/admin_role_list_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_role_list_limit/3'|ezurl}>50</a>
        {/case}

        {/switch}
</div>
<div class="break"></div>
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/role/list'
         item_count=$role_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>
{/let}

<form name="roleList" action={concat( $module.functions.list.uri, "/" )|ezurl} method="post" >

<table class="list" cellspacing="0">
<tr>
    <th class="tight"> &nbsp; </th>
    <th>{'Name:'|i18n('design/standard/role')}</th>
    <th class="tight"> &nbsp; </th>
    <th class="tight"> &nbsp; </th>
</tr>

{section show=$role_count|gt(0)}
{section var=Roles loop=$roles sequence=array( bglight, bgdark )}
<tr class="{$Roles.sequence}">
    <td>
	<input type="checkbox" name="DeleteIDArray[]" value="{$Roles.item.id}" />
    </td>
    <td>
    <a href={concat( '/role/view/', $Roles.item.id)|ezurl}>{$Roles.item.name}</a>
    </td>
    <td>
	<a href={concat( '/role/assign/', $Roles.item.id)|ezurl}><img src={"attach.png"|ezimage} alt="{'Assign'|i18n('design/standard/role')}" title="{'Assign this role to a user or a user group.'|i18n('design/standard/role')}" /></a>
    </td>
    <td>
	<a href={concat( '/role/edit/', $Roles.item.id)|ezurl}><img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/standard/role')}" title="{'Edit this role.'|i18n('design/standard/role')}" /></a>
    </td>
</tr>
{/section}
</table>

<a href="" onclick="selectAll(); return false;" title="{'Click here to select all the items that you are allowed to remove. Use the "Remove Selected" button to carry out the actual removal.'|i18n( 'design/admin/layout' )|wash()}">[ {'Select all'|i18n( 'design/admin/layout' )} ]</a>
<a href="" onclick="deSelectAll(); return false;" title="{'Click here to deselect the items that are selected in the list above.'|i18n( 'design/admin/layout' )}">[ {'Deselect all'|i18n( 'design/admin/layout' )} ]</a>
{section-else}

{* __FIX_ME__ Hmmm - is this supposed to happen? If so: maybe we should have a warning here or something. *}

{/section}

<div class="controlbar">
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/role')}" title="{'Click here to remove selected roles.'|i18n('design/standard/role')}" />
    <input class="button" type="submit" name="NewButton" value="{'Create new role'|i18n('design/standard/role')}" title="{'Create a new role.'|i18n( 'design/admin/layout' )}" />
</div>

</form>
