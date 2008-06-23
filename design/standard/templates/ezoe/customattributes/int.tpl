{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'content.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'content.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'content.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'content.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Required', 'content.ini' )}
    {if ezini( $custom_attribute_settings, 'Required', 'content.ini' )|eq('true')}
        {set $custom_attribute_classes = $custom_attribute_classes|append( 'required' )}
    {/if}
{/if}
<input type="text" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />