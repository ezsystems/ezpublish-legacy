{* Default preview template for admin interface. *}
{* Will be used if there is no suitable override for a specific class. *}

{* Display all the attributes using their default template. *}
{section var=Attributes loop=$node.object.contentobject_attributes}
    <div class="block">
    {if $Attributes.item.display_info.view.grouped_input}
    <fieldset>
        <legend>{$Attributes.item.contentclass_attribute.name|wash}{if $Attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}</legend>
        {attribute_view_gui attribute=$Attributes.item}
    </fieldset>
    {else}
        <label>{$Attributes.item.contentclass_attribute.name|wash}{if $Attributes.item.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:</label>
        {attribute_view_gui attribute=$Attributes.item}
    {/if}
    </div>
{/section}
