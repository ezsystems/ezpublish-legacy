<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{"Name"|i18n}:
	</th>
	<th colspan="2">
	{"E-mail"|i18n}:
	</th>
</tr>
{section name=Author loop=$attribute.content.author_list sequence=array(bglight,bgdark) }
<tr>
	<td class="{$Author:sequence}">
	<input type="hidden" name="ContentObjectAttribute_data_author_id_{$attribute.id}[]" value="{$Author:item.id}">
	<input type="text" name="ContentObjectAttribute_data_author_name_{$attribute.id}[]" value="{$Author:item.name}">
	</td>
	<td class="{$Author:sequence}">
	<input type="text" name="ContentObjectAttribute_data_author_email_{$attribute.id}[]" value="{$Author:item.email}">
	</td>
	<td class="{$Author:sequence}">
	<input type="checkbox" name="ContentObjectAttribute_data_author_remove_{$attribute.id}[]" value="{$Author:item.id}" >
	</td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_new_author]" value="{'New author'|i18n}" />
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove_selected]" value="{'Remove Selected'|i18n}" />
</div>