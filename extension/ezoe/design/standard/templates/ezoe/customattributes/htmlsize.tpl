{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'ezoe_attributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'ezoe_attributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'ezoe_attributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'ezoe_attributes.ini') ) )}
{/if}
{if $custom_attribute_default|ne('')}
    {def $custom_attribute_default_int = $custom_attribute_default|int
         $custom_attribute_default_type = $custom_attribute_default|explode( $custom_attribute_default_int ).1}
{else}
    {def $custom_attribute_default_int = ''
         $custom_attribute_default_type = ''}
{/if}
<input type="text" size="3" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default_int|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />
<select id="{$custom_attribute_id}_sizetype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
    <option value="">px</option>
    <option value="%"{if $custom_attribute_default_type|eq('%')} selected="selected"{/if}>%</option>
</select>
<script type="text/javascript">
eZOEPopupUtils.settings.customAttributeInitHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    el.value = eZOEPopupUtils.Int( value );
    document.getElementById( el.id.replace('_source', '_sizetype') ).selectedIndex = (value.indexOf('%') !== -1 ? 1 : 0 );
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    var sizetype = document.getElementById( el.id.replace('_source', '_sizetype') );
    if ( value === '0' )
        return '0';// Ignore % if 0 so TinyMCE shows a dotted border
    return value !== '' ? (value + sizetype.options[sizetype.selectedIndex].value) : '';
};{/literal}
</script>