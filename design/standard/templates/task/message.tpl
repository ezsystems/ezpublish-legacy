<form enctype="multipart/form-data" method="post" action={concat("/task/message/",$task.id,"/",$message.id)|ezurl}>

<h1>Edit task message '{$object.name}'</h1>

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<b>Input did not validate</b>
<i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id}) <br/>
{section-else}
<b>Input was stored successfully</b>
{/section}

{/section}

<table width="100%" >
<tr><td width="1%">From:</td><td width="1%"><b>{content_view_gui view=text_linked content_object=$message.task.creator.contentobject}</b></td><td width="99%"></td></tr>
<tr><td width="1%">To:</td>  <td width="1%"><b>{content_view_gui view=text_linked content_object=$message.task.receiver.contentobject}</b></td><td width="99%"></td></tr>

<tr>
	<td valign="top" colspan="3">

	{section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}

	<b>{$ContentObjectAttribute:item.contentclass_attribute.name}</b>

	<br />
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />

	{attribute_edit_gui attribute=$ContentObjectAttribute:item}

	<br />

	{/section}

	<br />

	<input type="submit" name="PreviewButton" value="{'Preview'|i18n('task/message')}" />

	&nbsp;

	<input type="submit" name="ApplyButton" value="{'Apply'|i18n('task/message')}" />

	&nbsp;

	<input type="submit" name="PublishButton" value="{'Send'|i18n('task/message')}" />
	<input type="submit" name="CancelButton" value="{'Discard'|i18n('task/message')}" />

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
