{literal}
<script language="JavaScript1.2" type="text/javascript">
<!--
function toggleCheckboxes( formname, checkboxname )
{
    with( formname )
	{
        for( var i=0; i<elements.length; i++ )
        {
            if( elements[i].type == 'checkbox' && elements[i].name == checkboxname && elements[i].disabled == "" )
            {
                if( elements[i].checked == true )
                {
                    elements[i].checked = false;
                }
                else
                {
                    elements[i].checked = true;
                }
            }
	    }
    }
}
//-->
</script>
{/literal}

{let number_of_items=min( ezpreference( 'admin_role_list_limit' ), 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">
<h2 class="context-title">{'Roles'|i18n( 'design/admin/role/list' )} [{$role_count}]</h2>

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p>
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
<div class="break"></div>
</div>
</div>

<form name="roles" action={concat( $module.functions.list.uri, '/' )|ezurl} method="post" >

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Toggle selection" onclick="toggleCheckboxes( document.roles, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'design/admin/role/list' )}</th>
    <th class="tight"> &nbsp; </th>
    <th class="tight"> &nbsp; </th>
</tr>

{section var=Roles loop=$roles sequence=array( bglight, bgdark )}
    {let quoted_role=concat( '"', $Roles.item.name, '"' )|wash}
    <tr class="{$Roles.sequence}">
    <td>
	<input type="checkbox" name="DeleteIDArray[]" value="{$Roles.item.id}" />
    </td>
    <td>
    <a href={concat( '/role/view/', $Roles.item.id)|ezurl}>{$Roles.item.name}</a>
    </td>
    <td>
    <a href={concat( '/role/assign/', $Roles.item.id)|ezurl}><img src={'attach.png'|ezimage} alt="{'Assign'|i18n( 'design/admin/role/list ')}" title="{'Assign a user or a user group to the %quoted_role role.'|i18n( 'design/admin/role/list',, hash( '%quoted_role', $quoted_role ) )}" /></a>
    </td>
    <td>
    <a href={concat( '/role/edit/', $Roles.item.id)|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n('design/admin/role/list')}" title="{'Edit the %quoted_role role.'|i18n( 'design/admin/role/list',, hash( '%quoted_role', $quoted_role  ) )}" /></a>
    </td>
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

<div class="context-toolbar">
<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/role/list' )}" title="{'Remove selected roles.'|i18n( 'design/admin/role/list' )}" />
    <input class="button" type="submit" name="NewButton" value="{'Create new'|i18n( 'design/admin/role/list' )}" title="{'Create a new role.'|i18n( 'design/admin/role/list' )}" />
</div>
</div>
</div>

</form>

</div>