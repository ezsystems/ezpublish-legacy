<form enctype="multipart/form-data" method="post" action={concat("/task/message/",$task.id,"/",$message.id)|ezurl}>

<div class="maincontentheader">
<h1>Edit task message '{$object.name}'</h1>
</div>

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<div class="warning">
<h2>Input did not validate</h2>
<ul>
    <li><i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id})</li>
</ul>
</div>
{section-else}
<div class="feedback">
<h2>Input was stored successfully</h2>
</div>
{/section}

{/section}

<div class="block">
<div class="element">
<label>From:</label><div class="labelbreak"></div>
{content_view_gui view=text_linked content_object=$message.task.creator.contentobject}
</div>
<div class="element">
<label>To:</label><div class="labelbreak"></div>
{content_view_gui view=text_linked content_object=$message.task.receiver.contentobject}
</div>
<div class="break"></div>
</div>

{section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
<div class="block">
<label>{$ContentObjectAttribute:item.contentclass_attribute.name}</label><div class="labelbreak"></div>
{attribute_edit_gui attribute=$ContentObjectAttribute:item}
</div>
{/section}

<div class="buttonblock">
<input type="submit" name="PreviewButton" value="{'Preview'|i18n('task/message')}" />
<input type="submit" name="ApplyButton" value="{'Apply'|i18n('task/message')}" />
<input type="submit" name="PublishButton" value="{'Send'|i18n('task/message')}" />
<input type="submit" name="CancelButton" value="{'Discard'|i18n('task/message')}" />
</div>

{*
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

*}

</form>
