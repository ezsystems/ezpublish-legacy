{default attribute_base='ContentObjectAttribute'}
{let attribute_content=$attribute.content}
<div class="block">

    <input type="hidden" name="MAX_FILE_SIZE" value="{$attribute.contentclass_attribute.data_int1}000000" />

    <label>{"Local image file for upload"|i18n("design/standard/content/datatype")}</label>
    <div class="labelbreak"></div>

    <input class="box" name="{$attribute_base}_data_imagename_{$attribute.id}" type="file" />

    <div class="block">
        <label>{"Alternative image text"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
        <input class="box" name="{$attribute_base}_data_imagealttext_{$attribute.id}" type="text" value="{$attribute_content.alternative_text|wash(xhtml)}" />
    </div>

</div>

{section show=$attribute_content.original.is_valid}

    <div class="block">
        <label>{"Image preview"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
        {attribute_view_gui image_class=ezini( 'ImageSettings', 'DefaultEditAlias', 'content.ini' ) attribute=$attribute}
    </div>
    <div class="block">
        <label>{"Original filename for image"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
        <p>{$attribute_content.original_filename|wash}</p>
    </div>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_delete_image]" value="{'Remove image'|i18n('design/standard/content/datatype')}" />
</div>
{/section}

{/let}
{/default}
