<form enctype="multipart/form-data" method="post" action={concat("task/messageview/",$message.id)|ezurl}>

<h1>Task message '{$message.contentobject.name}'</h1>

<table width="100%" >
<tr><td width="1%">From:</td><td width="98%"><b>{content_view_gui view=text_linked content_object=$message.task.creator.contentobject}</b></td><td width="1%"></td></tr>
<tr><td width="1%">To:</td>  <td width="98%"><b>{content_view_gui view=text_linked content_object=$message.task.receiver.contentobject}</b></td><td width="1%"></td></tr>

<tr>
	<td valign="top" colspan="3">

	{section name=ContentObjectAttribute loop=$message.contentobject.contentobject_attributes sequence=array(bglight,bgdark)}

	<b>{$ContentObjectAttribute:item.contentclass_attribute.name}</b>

	<br />
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />

	{attribute_view_gui attribute=$ContentObjectAttribute:item}

	<br />

	{/section}

	<br />

	<input type="submit" name="EditButton" value="{'Edit'|i18n('task/message')}" />
	&nbsp;
	<input type="submit" name="PublishButton" value="{'Send'|i18n('task/message')}" />
	<input type="submit" name="CancelButton" value="{'Discard'|i18n('task/message')}" />

	</td>
</tr>
</table>

</form>
