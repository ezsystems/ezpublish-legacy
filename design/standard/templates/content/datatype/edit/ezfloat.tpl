{default attribute_base=ContentObjectAttribute}
<input type="text" name="{$attribute_base}_data_float_{$attribute.id}" size="12" value="{$attribute.data_float|l10n(number)}"  minvalue="{$attribute.contentclass_attribute.data_float1}" 
maxvalue="{$attribute.contentclass_attribute.data_float2}" />
{/default}