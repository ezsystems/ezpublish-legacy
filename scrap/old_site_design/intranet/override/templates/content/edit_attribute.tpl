<div class="attribute">
    <div class="list">

    {section var=attribute loop=$content_attributes sequence=array( even, odd )}
    <div class="{$attribute.sequence}">
        <div class="item">

    {* only show edit GUI if we can edit *}
    {section show=and(eq($attribute.item.contentclass_attribute.can_translate,0),
                      ne($object.default_language,$attribute.item.language_code) ) }
        <div class="title">
            {$attribute.item.contentclass_attribute.name|wash}
        </div>
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        <div class="content">
            {attribute_view_gui attribute_base=$attribute_base attribute=$attribute.item}
        </div>
    {section-else}
        <div class="title">
            {$attribute.item.contentclass_attribute.name|wash}
        </div>
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        <div class="content">
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute.item}
        </div>

    {/section}

        </div>
    </div>
    {/section}
    </div>
</div>
