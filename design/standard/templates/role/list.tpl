<h1>{"Roles list"|i18n('role/list')}</h1>

<form action="{$module.functions.list.uri}/" method="post" >

<table width="100%" cellspacing="0">
<tr>
	<th align="left">ID</th>
	<th align="left">Role Name</th>
	<th align="left">Edit</th>
	<th align="left">Remove</th>

</tr>


{section name=All loop=$roles sequence=array(bglight,bgdark)}
<tr>
	<td class="{$All:sequence}">{$All:item.id}</td>
	<td class="{$All:sequence}">
	<a href="/role/view/{$All:item.id}">


	{$All:item.name}</a>
	</td>
        <td class="{$All:sequence}">
	            <a href="/role/edit/{$All:item.id}"><img src={"edit.png"|ezimage} border="0"></a>
        </td>
        <td class="{$All:sequence}">
		     <input type="checkbox" name="DeleteIDArray[]" value="{$All:item.id}" />
		          <img src={"editdelete.png"|ezimage} border="0">
        </td>

</tr>
{/section}

<tr>
<td>
<h4> Temporary Versions of Roles </h4>
<td/>
<tr/>
{section name=Temp loop=$temp_roles sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Temp:sequence}">{$Temp:item.id}</td>
	<td class="{$Temp:sequence}">
	<a href="/role/view/{$Temp:item.id}">


	{$Temp:item.name}</a>
	</td>
        <td class="{$Temp:sequence}">
	            <a href="/role/edit/{$Temp:item.id}"><img src={"edit.png"|ezimage} border="0"></a>
        </td>
        <td class="{$Temp:sequence}">
		     <input type="checkbox" name="DeleteIDArray[]" value="{$Temp:item.id}" />
		          <img src={"editdelete.png"|ezimage} border="0">
        </td>

</tr>
{/section}



</table>

<input type="submit" name="NewButton" value="New" />
<input type="submit" name="RemoveButton" value="Remove role(s)" />

</form>
