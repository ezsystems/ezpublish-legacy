{default attribute_base=ContentObjectAttribute}
<input type="text" name = "{$attribute_base}_data_option_name_{$attribute.id}" value="{$attribute.content.name}" />

<div class="block">
<label>{"Options"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
{section name=OptionList loop=$attribute.content.option_list sequence=array(bglight,bgdark)}
<input type="hidden" name="{$attribute_base}_data_option_id_{$attribute.id}[]" value="{$OptionList:item.id}" />
<input type="text" name="{$attribute_base}_data_option_value_{$attribute.id}[]" value="{$OptionList:item.value}" />
<input type="text" name="{$attribute_base}_data_option_additional_price_{$attribute.id}[]" value="{$OptionList:item.additional_price}" />
<input type="checkbox" name="{$attribute_base}_data_option_remove_{$attribute.id}[]" value="{$OptionList:item.id}" /><br />
{/section}
</div>
<div class="buttonblock">
<input class="smallbutton" type="submit" name="CustomActionButton[{$attribute.id}_new_option]" value="{'New option'|i18n('design/standard/content/datatype')}" />
<input class="smallbutton" type="submit" name="CustomActionButton[{$attribute.id}_remove_selected]" value="{'Remove Selected'|i18n('design/standard/content/datatype')}" />
</div>
{/default}
