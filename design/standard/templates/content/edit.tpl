<form enctype="multipart/form-data" method="post" action="/content/edit/{$object.id}/{$edit_version}/">

<h1>Edit {$class.name} - {$object.name}</h1>
{"Editing version"|i18n('content/object')}: {$edit_version} {"Current version"|i18n('content/object')}: {$object.current_version} <br />

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<b>Input did not validate</b>
<i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id}) <br/>
{section-else}
<b>Input was stored successfully</b>
{/section}

{/section}

<br />

<table width="50%">
{section name=Node loop=$assigned_node_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Node:sequence}">
	{$Node:item.node_id} -
	{$Node:item.parent_node_id} -

	{$Node:item.name}
	</td>
	<td class="{$Node:sequence}">
	{switch name=sw match=$main_node_id}
	   {case match=$Node:item.node_id}
	   <input type="radio" name="MainNodeID" checked="checked" value="{$Node:item.node_id}" />
	   {/case}
	   {case}
	   <input type="radio" name="MainNodeID" value="{$Node:item.node_id}" />
	   {/case}
	{/switch}
        <input type="checkbox" name="DeleteParentIDArray[]" value="{$Node:item.node_id}" />
	</td>
</tr>
{/section}
</table>
<input type="submit" name="BrowseNodeButton" value="{'Find node(s)'|i18n('content/object')}" />
<input type="submit" name="DeleteNodeButton" value="{'Delete node(s)'|i18n('content/object')}" />

<table width="100%" >
<tr>
	<td valign="top">

	{section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}

	<b>{$ContentObjectAttribute:item.contentclass_attribute.name}</b>

	<br />
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />

	{attribute_edit_gui attribute=$ContentObjectAttribute:item}

	<br />

	{/section}

	<br />

	<input type="submit" name="PreviewButton" value="{'Preview'|i18n('content/object')}" />
	<input type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
	<input type="submit" name="TranslateButton" value="{'Translate'|i18n('content/object')}" />
	<input type="submit" name="PermissionButton" value="{'Permission'|i18n('content/object')}" />

	<br /><br />
	<input type="submit" name="StoreButton" value="{'Store Draft'|i18n('content/object')}" />
	<input type="submit" name="PublishButton" value="{'Send for publishing'|i18n('content/object')}" />
	<input type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
	&nbsp;

	</td>
	<td bgcolor="eeeeee" valign="top">
	<h2>{"Object list"|i18n('content/object')}</h2>

	<table width="100%" cellspacing="0">
	{section name=Object loop=$related_contentobjects sequence=array(bglight,bgdark)}
	<tr>
		<td class="{$Object:sequence}">
		{content_view_gui view=line content_object=$Object:item}
		</td>
	</tr>
	{/section}
	</table>

	<br />

	<input type="submit" name="BrowseObjectButton" value="{'Find object'|i18n('content/object')}" />

	</td>
</tr>
</table>

</form>