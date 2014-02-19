{if ezini_hasvariable( $custom_attribute_settings, 'Rows', 'ezoe_attributes.ini' )}
    {def $rows = ezini( $custom_attribute_settings, 'Rows', 'ezoe_attributes.ini' )}
{else}
    {def $rows = 3}
{/if}
<textarea name="{$custom_attribute}" id="{$custom_attribute_id}_source" {if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" rows="{$rows}">{$custom_attribute_default|wash()}</textarea>