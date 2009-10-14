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
{if ezini_hasvariable( $custom_attribute_settings, 'CssSizeType', 'ezoe_attributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'CssSizeType', 'ezoe_attributes.ini' ) as $key => $value}
    <option value="{$key}"{if $custom_attribute_default_type|eq($key)} selected="selected"{/if}>{$value}</option>
{/foreach}
{else}
    <option value="px">px</option>
    <option value="em"{if $custom_attribute_default_type|eq('em')} selected="selected"{/if}>em</option>
    <option value="%"{if $custom_attribute_default_type|eq('%')} selected="selected"{/if}>%</option>
{/if}
</select>
<script type="text/javascript">
<!--

eZOEPopupUtils.settings.customAttributeInitHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    el.value = ez.num( value, 0, 'int' );
    var selid = el.id.replace('_source', '_sizetype'), size = document.getElementById( selid );
    size.selectedIndex = jQuery.inArray( value.replace( el.value, '' ), jQuery('#' + selid + ' option').map(function( i, n )
    {
        return n.value;
    }));
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    var sel = document.getElementById( el.id.replace('_source', '_sizetype') );
    return value !== '' ? (value + ( sel.selectedIndex !== -1 ? sel.options[sel.selectedIndex].value : '' )) : '';
};{/literal}

//-->
</script>