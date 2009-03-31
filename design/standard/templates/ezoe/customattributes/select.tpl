<select name="{$custom_attribute}" id="{$custom_attribute_id}_source"{if $custom_attribute_disabled} disabled="disabled"{/if} title="{$custom_attribute_title|wash}" class="{$custom_attribute_classes|implode(' ')}">
{if ezini_hasvariable( $custom_attribute_settings, 'Selection', 'ezoe_attributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'Selection', 'ezoe_attributes.ini' ) as $custom_value => $custom_name}
    <option value="{if $custom_value|ne('0')}{$custom_value|wash}{/if}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
{/foreach}
{elseif and( is_set($custom_attribute_selection), is_array($custom_attribute_selection) )}
{foreach $custom_attribute_selection as $custom_value => $custom_name}
    <option value="{if $custom_value|ne('0')}{$custom_value|wash}{/if}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
{/foreach}
{/if}
</select>