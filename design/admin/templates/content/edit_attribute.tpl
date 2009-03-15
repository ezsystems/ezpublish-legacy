{default $view_parameters=array()}
{section var=attributes loop=$content_attributes}
<div class="block ezcca-edit-datatype-{$attributes.item.data_type_string} ezcca-edit-{$attributes.item.contentclass_attribute_identifier}">
{* Show view GUI if we can't edit, oterwise: show edit GUI. *}
{section show=and( eq( $attributes.item.can_translate, 0 ), ne( $object.initial_language_code, $attributes.item.language_code ) )}
    <label>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</label>
    {section show=$is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
        </div>
    {section-else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
    {/section}
{section-else}
    {section show=$is_translating_content}
        <label{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}{section show=$attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes[$attributes.key] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {section show=$attributes.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
            </fieldset>
        {section-else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
        {/section}
        </div>
    {section-else}
        {section show=$attributes.display_info.edit.grouped_input}
            <fieldset>
            <legend{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}{section show=$attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}</legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
            </fieldset>
        {section-else}
            <label{section show=$attributes.item.has_validation_error} class="message-error"{/section}>{$attributes.item.contentclass_attribute_name|wash}{section show=$attributes.item.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}{section show=$attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/section}:</label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
        {/section}
    {/section}
{/section}
</div>
{/section}
{/default}
