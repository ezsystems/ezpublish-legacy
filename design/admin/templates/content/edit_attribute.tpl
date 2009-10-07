{default $view_parameters=array()}
{section var=attributes loop=$content_attributes}
<div class="block ezcca-edit-datatype-{$attributes.item.data_type_string} ezcca-edit-{$attributes.item.contentclass_attribute_identifier}">
{* Show view GUI if we can't edit, oterwise: show edit GUI. *}
{if and( eq( $attributes.item.can_translate, 0 ), ne( $object.initial_language_code, $attributes.item.language_code ) )}
    <label>{$attributes.item.contentclass_attribute_name|wash}{if $attributes.item.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:</label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attributes.item.has_validation_error} class="message-error"{/if}>{$attributes.item.contentclass_attribute_name|wash}{if $attributes.item.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}{if $attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:</label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes[$attributes.key] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attributes.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
        {/if}
        </div>
    {else}
        {if $attributes.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attributes.item.has_validation_error} class="message-error"{/if}>{$attributes.item.contentclass_attribute_name|wash}{if $attributes.item.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}{if $attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}</legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
            </fieldset>
        {else}
            <label{if $attributes.item.has_validation_error} class="message-error"{/if}>{$attributes.item.contentclass_attribute_name|wash}{if $attributes.item.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}{if $attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:</label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attributes.item view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attributes.item.id}" />
        {/if}
    {/if}
{/if}
</div>
{/section}
{/default}
