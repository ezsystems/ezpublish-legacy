{default attribute_base=ContentObjectAttribute}
<div class="block">
<label>{"URL"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="70" name="{$attribute_base}_ezurl_url_{$attribute.id}" value="{$attribute.content|wash(xhtml)}" />
</div>
<div class="block">
<label>{"Text"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" type="text" size="70" name="{$attribute_base}_ezurl_text_{$attribute.id}" value="{$attribute.data_text|wash(xhtml)}" />
</div>
{/default}