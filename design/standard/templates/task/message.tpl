<form enctype="multipart/form-data" method="post" action="/content/edit/{$object.id}/{$edit_version}/">

{$task_id}

{$message.id}<br/>
{$message.task_id}<br/>
{$message.contentobject_id}<br/>
{$message.creator_id}<br/>
{$message.creator_type}<br/>

{$message.contentobject.name}<br/>

<h1>Edit message '{$object.name}'</h1>

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<b>Input did not validate</b>
<i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id}) <br/>
{section-else}
<b>Input was stored successfully</b>
{/section}

{/section}

<br />


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
{*	<input type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
	<input type="submit" name="TranslateButton" value="{'Translate'|i18n('content/object')}" />*}

{*	<br /><br /> *}
{*	<input type="submit" name="StoreButton" value="{'Store Draft'|i18n('content/object')}" />*}
{*	<input type="submit" name="PublishButton" value="{'Send for publishing'|i18n('content/object')}" />*}
	<input type="submit" name="PublishButton" value="{'Send'|i18n('content/object')}" />
	<input type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
	&nbsp;

	</td>
{*
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
*}
</tr>
</table>

</form>
