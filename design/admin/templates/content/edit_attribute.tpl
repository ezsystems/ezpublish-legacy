{section var=attributes loop=$content_attributes}

<div class="block">

{* Only show edit GUI if we can edit, oterwise: show view GUI. *}
{section show=and( eq( $attributes.item.can_translate, 0 ), ne( $object.default_language, $attributes.item.language_code ) )}
    <label>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.can_translate|not}<span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</label>

    {section show=$is_translating_content}
    <div class="translation">
    {/section}

    {attribute_view_gui attribute_base=$attribute_base attribute=$attributes.item}
    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />

    {section show=$is_translating_content}
    </div>
    {/section}

{section-else}

    {section show=$is_translating_content}
    <label{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required}<span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}{section show=$attributes.item.is_information_collector}<span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</label>
    <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes[$attributes.key]}
    </div>
    <div class="translation">
    {section-else}

        {section show=eq( ezini( 'EditSettings', $attributes.item.data_type_string, 'datatype.ini' ), 'multi' )}
            <fieldset>
            <legend{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required}<span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}{section show=$attributes.item.is_information_collector}<span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</legend>
        {section-else}
            <label{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required}<span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}{section show=$attributes.item.is_information_collector}<span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</label>
        {/section}

    {/section}

    {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item}
    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />

    {section show=$is_translating_content}
    </div>
    {section-else}
        {section show=eq( ezini( 'EditSettings', $attributes.item.data_type_string, 'datatype.ini' ), 'multi' )}
        </fieldset>
        {/section}
    {/section}

{/section}

</div>
{/section}
