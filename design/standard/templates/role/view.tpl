<h1>{"Role view"|i18n('role/view')}</h1>

<form action="{$module.functions.view.uri}/{$role.id}/" method="post" >
View role - {$role.name} <a href="/role/edit/{$role.id}/">[edit]</a><br/>


<table width="100%" cellspacing="0">
<tr>
	<th align="left">ID</th>
	<th align="left">Module</th>
	<th align="left">Function</th>
	<th align="left">Limitation</th>

</tr>
<h2>{"Role policies"|i18n('role/view')}</h2>
{section name=Policies loop=$policies sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Policies:sequence}">{$Policies:item.id}</td>
	<td class="{$Policies:sequence}">
	{$Policies:item.module_name}
	</td>
        <td class="{$Policies:sequence}">
	           {$Policies:item.function_name}
        </td>
        <td class="{$Policies:sequence}">
                {$Policies:item.limitation} 
        {section name=Limitation loop=$Policies:item.limitations}
           {$Policies:Limitation:item.identifier}({$Policies:Limitation:item.values_as_string})
	   {delimiter},{/delimiter}
        {/section}  
        </td>

</tr>
{/section}
</table>

<h2>{"Users and groups assigned to this role"|i18n('role/view')}:</h2>

<table width="100%" cellspacing="0">
<tr>
	<th>User</th>
</tr>
{section name=User loop=$user_array sequence=array(bglight,bgdark)}
<tr>
	<td  class="{$User:sequence}">
	{$User:item.name}
	</td>
	<td  class="{$User:sequence}">
	<input type="checkbox" value="{$User:item.id}" name="UserIDArray[]" />
	</td>
</tr>
{/section}
</table>

<br />
<input type="submit" name="AssignRoleButton" value="Assign role" /> &nbsp;
<input type="submit" name="RemoveRoleAssignmentButton" value="Remove role assignment" />

</form>
