{default attribute_base=ContentObjectAttribute}
<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />

{section show=or($attribute.content)}
<label>{"Image filename"|i18n("design/standard/content/datatype")}</label><p class="box">{$attribute.content.filename|wash(xhtml)}</p><div class="labelbreak"></div>

<input class="box" name="{$attribute_base}_data_imagename_{$attribute.id}" type="file" />

<div class="block">
<label>{"Alternative image text"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input class="box" name="{$attribute_base}_data_imagealttext_{$attribute.id}" type="text" value="{$attribute.content.alternative_text|wash(xhtml)}" />
</div>

{section-else}
<div class="labelbreak"></div>
<label>{"Image filename"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>

<input class="box" name="{$attribute_base}_data_imagename_{$attribute.id}" type="file" />

{/section}

</div>

{section show=or($attribute.content)}

<div class="block">
<img src={$attribute.content.small.full_path|ezroot} alt="" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_delete_image]" value="{'Remove image'|i18n('design/standard/content/datatype')}" />
</div>
{/section}
{/default}
