<table width="100%" cellpadding="2" cellspacing="0">

<tr>
	<td>
	<b>{"Image filename"|i18n('content/edit/image')}</b>:<br/>
	</td>
</tr>
<tr>
	<td>
	<input type="hidden" name="MAX_FILE_SIZE" value="$attribute.contentclass_attribute.data_int1}000000" />
	<input name="ContentObjectAttribute_data_imagename_{$attribute.id}" type="file" />
	</td>
</tr>
{section show=or($attribute.content,$attribute.content.filename)}
<tr>
	<td>
	<b>{"Existing filename"|i18n('content/edit/image')}</b>:<br/>
	</td>
	<td>
	<b>{"Existing orignal filename"|i18n('content/edit/image')}</b>:<br/>
	</td>
	<td>
	<b>{"Existing mime/type"|i18n('content/edit/image')}</b>:<br/>
	</td>
	<td>
	</td>
</tr>
<tr>
	<td>
	{$attribute.content.filename}
	</td>
	<td>
	{$attribute.content.original_filename}
	</td>
	<td>
	{$attribute.content.mime_type}
	</td>
	<td>
	<input type="submit" name="CustomActionButton[{$attribute.id}_delete_image]" value="{'Delete'|i18n('content/edit/image')}" />
	</td>
</tr>
<tr>
  <td>
  <img src="/var/storage/variations/{$attribute.content.mime_type_category}/{$attribute.content.small.additional_path}{$attribute.content.small.filename}" />
 </td>
</tr>
{/section}

</table>
