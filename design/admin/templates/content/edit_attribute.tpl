{section var=attributes loop=$content_attributes}
<div class="block">

{* Only show edit GUI if we can edit, oterwise: show view GUI. *}
{section show=and( eq( $attributes.item.can_translate, 0 ), ne( $object.default_language, $attributes.item.language_code ) )}
    <label>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.can_translate|not}<span class="non-translatable">({'not translatable'|i18n( 'admin/content/edit' )})</span>{/section}:</label>

    {section show=$is_translating_content}
    <div class="translation">
    {/section}

    {attribute_view_gui attribute_base=$attribute_base attribute=$attributes.item}
    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />

    {section show=$is_translating_content}
    </div>
    {/section}

{section-else}

    <label{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required}<span class="required">({'required'|i18n( 'admin/content/edit' )})</span>{/section}{section show=$attributes.item.is_information_collector}<span class="collector">({'information collector'|i18n( 'admin/content/edit' )})</span>{/section}:</label>

    {section show=$is_translating_content}
    <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes[$attributes.key]}
    </div>
    <div class="translation">
    {/section}

    {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item}
    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />

    {section show=$is_translating_content}
    </div>
    {/section}

{/section}

</div>
{/section}
