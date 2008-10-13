<select name="{$custom_attribute}" id="{$custom_attribute_id}_source"{if $custom_attribute_disabled} disabled="disabled"{/if}>
{foreach ezini( $custom_attribute_settings, 'Selection', 'ezoe_customattributes.ini' ) as $custom_value => $custom_name}
    <option value="{$custom_value|wash}"{if $custom_value|eq( $custom_attribute_default )} selected="selected"{/if}>{$custom_name|wash}</option>
{/foreach}
</select>