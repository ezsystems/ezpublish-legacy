{default attribute_base=ContentObjectAttribute}
<input type="text" name="{$attribute_base}_data_price_{$attribute.id}" size="12" value="{$attribute.content.price|l10n(clean_currency)}" />
{/default}