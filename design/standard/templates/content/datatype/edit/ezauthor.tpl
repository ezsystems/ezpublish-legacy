{default attribute_base=ContentObjectAttribute}
<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>&nbsp;</th>
	<th>
	{"Name"|i18n("design/standard/content/datatype")}
	</th>
	<th>
	{"E-Mail"|i18n("design/standard/content/datatype")}
	</th>
</tr>
{section name=Author loop=$attribute.content.author_list sequence=array(bglight,bgdark) }
<tr>
	<td class="{$Author:sequence}" width="1">
	<input type="checkbox" name="{$attribute_base}_data_author_remove_{$attribute.id}[]" value="{$Author:item.id}" >
	</td>
	<td class="{$Author:sequence}">
	<input type="hidden" name="{$attribute_base}_data_author_id_{$attribute.id}[]" value="{$Author:item.id}">
	<input type="text" name="{$attribute_base}_data_author_name_{$attribute.id}[]" value="{$Author:item.name|wash}">
	</td>
	<td class="{$Author:sequence}">
	<input type="text" name="{$attribute_base}_data_author_email_{$attribute.id}[]" value="{$Author:item.email|wash}">
	</td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_new_author]" value="{'New author'|i18n('design/standard/content/datatype')}" />
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove_selected]" value="{'Remove Selected'|i18n('design/standard/content/datatype')}" />
</div>
{/default}
