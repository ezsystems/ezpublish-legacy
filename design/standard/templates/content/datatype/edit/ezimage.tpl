<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="$attribute.contentclass_attribute.data_int1}000000" />

{section show=or($attribute.content,$attribute.content.filename)}
<label>{"Image filename:"|i18n("design/standard/content/datatype")}</label><p class="box">{$attribute.content.filename}</p><div class="labelbreak"></div>

{section-else}
<label>{"Image filename:"|i18n("design/standard/content/datatype")}</label><p class="box">{$attribute.content.filename}</p><div class="labelbreak"></div>

{/section}
<input class="box" name="ContentObjectAttribute_data_imagename_{$attribute.id}" type="file" />
</div>

{section show=or($attribute.content,$attribute.content.filename)}

<div class="block">
<img src={concat("/var/storage/variations/",$attribute.content.mime_type_category,"/",$attribute.content.small.additional_path,$attribute.content.small.filename)|ezroot} alt="" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_delete_image]" value="{'Remove image'|i18n('design/standard/content/datatype')}" />
</div>
{/section}
