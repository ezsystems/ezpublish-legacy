{default attribute_base=ContentObjectAttribute}
<div class="block">
<label>{"ISBN"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_isbn_field1_{$attribute.id}" size="2" value="{$attribute.content.field1}" />-<input type="text" name="{$attribute_base}_isbn_field2_{$attribute.id}" size="6" value="{$attribute.content.field2}" />-<input type="text" name="{$attribute_base}_isbn_field3_{$attribute.id}" size="6" value="{$attribute.content.field3}" />-<input type="text" name="{$attribute_base}_isbn_field4_{$attribute.id}" size="2" value="{$attribute.content.field4}" />
</div>
{/default}