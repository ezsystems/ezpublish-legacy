{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'ezoe_customattributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'ezoe_customattributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'ezoe_customattributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'ezoe_customattributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Required', 'ezoe_customattributes.ini' )}
    {if ezini( $custom_attribute_settings, 'Required', 'ezoe_customattributes.ini' )|eq('true')}
        {set $custom_attribute_classes = $custom_attribute_classes|append( 'required' )}
    {/if}
{/if}
<input type="text" size="3" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
<select id="{$custom_attribute_id}_sizetype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
    <option value="">px</option>
    <option value="%">%</option>
</select>
<script type="text/javascript">
<!--

eZOEPopupUtils.settings.customAttributeInitHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    el.value = ez.num( value, 0, 'int' );
    if ( value.indexOf('%') !== -1 )
    {
        document.getElementById( el.id.replace('_source', '_sizetype') ).selectedIndex = 1;
        
    }
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    var sizetype = document.getElementById( el.id.replace('_source', '_sizetype') );
    return value + sizetype.options[sizetype.selectedIndex].value;
};{/literal}

//-->
</script>