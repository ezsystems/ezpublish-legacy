<div class="maincontentheader">
<h1>{"Roles list"|i18n('role/list')}</h1>
</div>

<form action={concat($module.functions.list.uri,"/")|ezurl} method="post" >

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="1%">ID:</th>
    <th width="97%">Role Name:</th>
    <th width="1%">Assign:</th>
    <th width="1%">Edit:</th>
    <th width="1%">Remove:</th>
</tr>

{section name=All loop=$roles sequence=array(bglight,bgdark)}
<tr>
    <td class="{$All:sequence}">{$All:item.id}</td>
    <td class="{$All:sequence}">
    <a href={concat("/role/view/",$All:item.id)|ezurl}>{$All:item.name}</a>
    </td>
    <td class="{$All:sequence}">
	<a href={concat("/role/assign/",$All:item.id)|ezurl}><img src={"attach.png"|ezimage} alt="" /></a>
    </td>
    <td class="{$All:sequence}">
	<a href={concat("/role/edit/",$All:item.id)|ezurl}><img src={"edit.png"|ezimage} alt="" /></a>
    </td>
    <td class="{$All:sequence}">
	<input type="checkbox" name="DeleteIDArray[]" value="{$All:item.id}" /><img src={"editdelete.png"|ezimage} alt="" />
    </td>
</tr>
{/section}
</table>

<h2> Temporary Versions of Roles </h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="1%">ID:</th>
    <th width="97%">Role Name:</th>
    <th width="1%">Edit:</th>
    <th width="1%">Remove:</th>
</tr>
{section name=Temp loop=$temp_roles sequence=array(bglight,bgdark)}
<tr>
	<td width="1%" class="{$Temp:sequence}">{$Temp:item.id}</td>
	<td width="97%" class="{$Temp:sequence}">
	<a href={concat("/role/view/",$Temp:item.id)|ezurl}>{$Temp:item.name}</a>
	</td>
    <td width="1%" class="{$Temp:sequence}">
	<a href={concat("/role/edit/",$Temp:item.id)|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
    </td>
    <td width="1%" class="{$Temp:sequence}">
	<input type="checkbox" name="DeleteIDArray[]" value="{$Temp:item.id}" /><img src={"editdelete.png"|ezimage} alt="" />
    </td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="NewButton" value="New" />
<input class="button" type="submit" name="RemoveButton" value="Remove role(s)" />
</div>

</form>
