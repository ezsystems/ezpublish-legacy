<h1>{"Roles list"|i18n('role/list')}</h1>

<form action={concat($module.functions.list.uri,"/")|ezurl} method="post" >

<table width="100%" cellspacing="0" >
<tr>
	<th align="left">ID</th>
	<th align="left">Role Name</th>
	<th align="left">Assign</th>
	<th align="left">Edit</th>
	<th align="left">Remove</th>

</tr>


{section name=All loop=$roles sequence=array(bglight,bgdark)}
<tr>
	<td width="10%" class="{$All:sequence}">{$All:item.id}</td>
	<td class="{$All:sequence}">
	<a href={concat("/role/view/",$All:item.id)|ezurl}>


	{$All:item.name}</a>
	</td>
<td class="{$All:sequence}"   >
	            <a href={concat("/role/assign/",$All:item.id)|ezurl}><img src={"attach.png"|ezimage} border="0"></a>
</td>
        <td class="{$All:sequence}">
	            <a href={concat("/role/edit/",$All:item.id)|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
        </td>
        <td class="{$All:sequence}">
		     <input type="checkbox" name="DeleteIDArray[]" value="{$All:item.id}" />
		          <img src={"editdelete.png"|ezimage} border="0">
        </td>

</tr>
{/section}
<tr>
<td colspan="4">
<h4> Temporary Versions of Roles </h4>
</td>
</tr>

{section name=Temp loop=$temp_roles sequence=array(bglight,bgdark)}
<tr>
	<td  width="10%" class="{$Temp:sequence}">{$Temp:item.id}</td>
	<td  class="{$Temp:sequence}">
	<a href={concat("/role/view/",$Temp:item.id)|ezurl}>


	{$Temp:item.name}</a>
	</td>
        <td class="{$Temp:sequence}" >&nbsp</td>
        <td class="{$Temp:sequence}">
	            <a href={concat("/role/edit/",$Temp:item.id)|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
        </td>
        <td  class="{$Temp:sequence}">
		     <input type="checkbox" name="DeleteIDArray[]" value="{$Temp:item.id}" />
		          <img src={"editdelete.png"|ezimage} border="0">
        </td>

</tr>
{/section}



</table>

<input type="submit" name="NewButton" value="New" />
<input type="submit" name="RemoveButton" value="Remove role(s)" />

</form>
