<table width="100%" cellpadding="2" cellspacing="0">

<tr>
	<td class="bglight">
	<b>{"Filename"|i18n}</b>:<br/>
	</td>
</tr>
<tr>
	<td class="bglight">
	<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
	<input name="ContentObjectAttribute_data_binaryfilename_{$attribute.id}" type="file" />

	</td>
</tr>
{section show=or($attribute.content,$attribute.content.filename)}
<tr>
	<td class="bglight">
	<b>{"Existing filename"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Existing orignal filename"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Existing mime/type"|i18n}</b>:<br/>
	</td>
	</td>
	<td class="bglight">
	</td>
</tr>
<tr>
	<td class="bglight">
	{$attribute.content.filename}
	</td>
	<td class="bglight">
	{$attribute.content.original_filename}
	</td>
	<td class="bglight">
	{$attribute.content.mime_type}
	</td>
	<td class="bglight">
	<input type="submit" name="CustomActionButton[{$attribute.id}_delete_binary]" value="{'Delete'|i18n}" />
	</td>
</tr>
{/section}

</table>
