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

<form name="role" action={concat( $module.functions.view.uri, '/', $role.id, '/')|ezurl} method="post" >

<div class="context-block">
<h2 class="context-title">{'role'|icon( 'normal', 'Role'|i18n( 'design/admin/role/view' ) )}&nbsp;{'%role_name [Role]'|i18n( 'design/admin/role/view',, hash( '%role_name', $role.name ) )|wash}</h2>

<div class="context-attributes">
<div class="block">
<label>{'Policies'|i18n( 'design/admin/role/view' )} [{$policies|count}]</label><div class="labelbreak"></div>
<table class="list" cellspacing="0">
<tr>
    <th>{'Module'|i18n(' design/admin/role/view ')}</th>
    <th>{'Function'|i18n(' design/admin/role/view ')}</th>
    <th>{'Limitation'|i18n(' design/admin/role/view ')}</th>
</tr>
{section var=Policies loop=$policies sequence=array( bglight, bgdark )}
<tr class="{$Policies.sequence}">
    <td>
         {section show=eq( $Policies.item.module_name, '*' )}
             <i>{'all modules'|i18n( 'design/admin/role/view' )} </i>
             {section-else}
             {$Policies.item.module_name}
        {/section}
    </td>
    <td>
         {section show=eq( $Policies.item.function_name, '*' )}
             <i>{'all functions'|i18n( 'design/admin/role/view' )} </i>
             {section-else}
             {$Policies.item.function_name}
        {/section}
    </td>
    <td>
        {section show=$Policies.item.limitations}
          {section var=Limitations loop=$Policies.item.limitations}
              {$Limitations.item.identifier|wash}(
              {section var=LimitationValues loop=$Limitations.item.values_as_array_with_names}
                  {$LimitationValues.item.Name|wash}
                  {delimiter}, {/delimiter}
              {/section})
              {delimiter}, {/delimiter}
          {/section}
        {section-else}
        <i>{'No limitations'|i18n( 'design/admin/role/view' )}</i>
        {/section}
    </td>
</tr>
{/section}
</table>
</div>
</div>


<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="EditRoleButton" value="{'Edit'|i18n( 'design/admin/role/view' )}" title="{'Click here to edit this role.'|i18n( 'design/admin/role/view' )}" />
</div>
</div>
</div>

<div class="context-block">

<h2 class="context-title">{'Users and groups using the <%role_name> role [%users_count]'|i18n( 'design/admin/role/view',, hash('%role_name', $role.name, '%users_count', $user_array|count) )|wash}</h2>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Toggle selection" onclick="toggleCheckboxes( document.role, 'IDArray[]' ); return false;"/></th>
    <th>{'User/group'|i18n( 'design/admin/role/view' )}</th>
    <th>{'Limitation'|i18n( 'design/admin/role/view' )}</th>
</tr>
{section var=Users loop=$user_array sequence=array( bglight, bgdark )}
<tr class="{$Users.sequence}">
    <td><input type="checkbox" value="{$Users.item.user_role_id}" name="IDArray[]" /></td>

<td>    {$Users.item.user_object.content_class.identifier|class_icon( 'small', $Users.item.user_object.content_class.name )}
<a href={$Users.item.user_object.main_node.url_alias|ezurl}>{$Users.item.user_object.name|wash}</a></td>
    <td>
        {section show=$Users.item.limit_ident}
          {$Users.item.limit_ident|wash}( {$Users.item.limit_value|wash} )
        {section-else}
        <i>{'No limitations'|i18n( 'design/admin/role/view' )}</i>
        {/section}
    </td>
</tr>
{/section}
</table>
<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="RemoveRoleAssignmentButton" value="{'Remove selected'|i18n( 'design/admin/role/view' )}" title="{'Remove selected assignments'|i18n( 'design/admin/role/view' )}" />
<input class="button" type="submit" name="AssignRoleButton" value="{'Assign'|i18n( 'design/admin/role/view' )}" title="{'Assign role to user or group'|i18n( 'design/admin/role/view' )}" />
<input class="button" type="submit" name="AssignRoleLimitedButton" value="{'Assign limited'|i18n( 'design/admin/role/view' )}" title="{'Assign role to user or group'|i18n( 'design/admin/role/view' )}" />on
<select name="AssignRoleType">
    <option value="subtree">{'Subtree'|i18n( 'design/admin/role/view' )}</option>
    <option value="section">{'Section'|i18n( 'design/admin/role/view' )}</option>
</select>
</div>
</div>
</div>

</form>