<form method="post" action={"content/action/"|ezurl}>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
 	<h1>{$object.name}</h1>
	</td>
</tr>
</table>

Current version: {$object.current_version}

<table width="100%">
{section name=ContentObjectAttribute loop=$version_attributes}
<tr>
	<td>
	<b>{$ContentObjectAttribute:item.contentclass_attribute.name}</b><br />
	{attribute_view_gui attribute=$ContentObjectAttribute:item}
	</td>
</tr>
{/section}
</table>

<h2>Related objects</h2>
<table width="100%" cellspacing="0">
{section name=Object loop=$related_contentobject_array show=$related_contentobject_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Object:sequence}">
	{content_view_gui view=text_linked content_object=$Object:item}
	</td>
</tr>
{section-else}
<tr>
	<td class="bglight">
	None
	</td>
</tr>
{/section}
</table>

<input type="hidden" name="ContentObjectID" value="{$object.id}" />

</form>
