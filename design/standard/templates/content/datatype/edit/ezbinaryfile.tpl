<div class="block">
<label>{"Filename:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000"/>
<input name="ContentObjectAttribute_data_binaryfilename_{$attribute.id}" type="file" />
</div>

{section show=or($attribute.content,$attribute.content.filename)}
<div class="block">
<div class="element">
<label>{"Existing filename:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.filename}</p>
</div>
<div class="element">
<label>{"Existing orignal filename:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">	{$attribute.content.original_filename}</p>
</div>
<div class="element">
<label>{"Existing mime/type:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.mime_type}</p>
</div>
<div class="element">
<input class="smallbutton" type="submit" name="CustomActionButton[{$attribute.id}_delete_binary]" value="{'Delete'|i18n('design/standard/content/datatype')}" />
</div>
<div class="break"></div>
</div>
{/section}
