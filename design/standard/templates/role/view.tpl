<h1>{"Role view"|i18n('role/view')}</h1>

<form action={concat($module.functions.view.uri,"/",$role.id,"/")|ezurl} method="post" >
View role - {$role.name} <a href={concat("/role/edit/",$role.id,"/")|ezurl}>[edit]</a><br/>


<table width="100%" cellspacing="0">
<tr>
	<th align="left">ID</th>
	<th align="left">Module</th>
	<th align="left">Function</th>
	<th align="left">Limitation</th>

</tr>
<h2>{"Role policies"|i18n('role/view')}</h2>
{section name=Policy loop=$policies sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Policy:sequence}">{$Policy:item.id}</td>
	<td class="{$Policy:sequence}">
	{$Policy:item.module_name}
	</td>
        <td class="{$Policy:sequence}">
	           {$Policy:item.function_name}
        </td>
        <td class="{$Policy:sequence}">
                {$Policy:item.limitation} 
        {section name=Limitation loop=$Policy:item.limitations}
           {$Policy:Limitation:item.identifier}(

	          {section name=LimitationValues loop=$Policy:Limitation:item.values_as_array_with_names}

                   {$Policy:Limitation:LimitationValues:item.Name}
                  {delimiter},{/delimiter}
                  {/section})

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
