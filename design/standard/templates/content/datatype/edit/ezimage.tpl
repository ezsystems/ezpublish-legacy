<div class="block">
<input type="hidden" name="MAX_FILE_SIZE" value="$attribute.contentclass_attribute.data_int1}000000" />
<label>{"Image filename"|i18n('content/edit/image')}:</label><div class="labelbreak"></div>
<input class="box" name="ContentObjectAttribute_data_imagename_{$attribute.id}" type="file" />
</div>

{section show=or($attribute.content,$attribute.content.filename)}
<div class="block">
<div class="element">
<label>{"Existing filename"|i18n('content/edit/image')}:</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.filename}</p>
</div>
<div class="element">
<label>{"Existing orignal filename"|i18n('content/edit/image')}:</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.original_filename}</p>
</div>
<div class="element">
<label>{"Existing mime/type"|i18n('content/edit/image')}:</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.mime_type}</p>
</div>
<div class="break"></div>
</div>

<div class="block">
<img src={concat("/var/storage/variations/",$attribute.content.mime_type_category,"/",$attribute.content.small.additional_path,$attribute.content.small.filename)|ezroot} alt="" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_delete_image]" value="{'Delete'|i18n('content/edit/image')}" />
</div>
{/section}
