<h1>{"My drafts"|i18n('drafts/list')}</h1>

<form action={concat($module.functions.list.uri,"/")|ezurl} method="post" >

<table width="100%" cellspacing="0" >
<tr>

	<th align="left">ID</th>
	<th align="left">Name</th>
	<th align="left">Version</th>
	<th align="left">edit</th>
	<th align="left">Remove</th>


</tr>


{section name=All loop=$drafts sequence=array(bglight,bgdark)}
<tr>
	<td width="10%" class="{$All:sequence}">{$All:item.contentobject.id}</td>
	<td class="{$All:sequence}">
	<a href={concat("/content/versionview/",$All:item.contentobject.id,"/",$All:item.version,"/")|ezurl}>
	{$All:item.contentobject.name}</a>
	</td>
	<td class="{$All:sequence}">
	{$All:item.version}
</td>	
        <td class="{$All:sequence}">
	            <a href={concat("/content/edit/",$All:item.contentobject.id,,"/",$All:item.version,"/")|ezurl}><img src={"edit.png"|ezimage} border="0"></a>
        </td>
        <td class="{$All:sequence}">
		     <input type="checkbox" name="DeleteIDArray[]" value="{$All:item.id}" />
		          <img src={"editdelete.png"|ezimage} border="0">
        </td>

</tr>
{/section}
<tr>
</table><br/>
<input type="submit" name="RemoveButton" value="Remove draft" />
