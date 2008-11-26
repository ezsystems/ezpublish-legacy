{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{set $custom_attribute_default = $custom_attribute_default|explode(' ')}
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
{if ezini_hasvariable( $custom_attribute_settings, 'CssSizeType', 'ezoe_customattributes.ini' )}
    {def $css_size_types = ezini( $custom_attribute_settings, 'CssSizeType', 'ezoe_customattributes.ini' )}
{else}
    {def $css_size_types = hash('px', 'px', 'em', 'em', '%', '%')}
{/if}
<input type="text" size="2" name="{$custom_attribute}" id="{$custom_attribute_id}_source_0" value="{$custom_attribute_default[0]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
<select id="{$custom_attribute_id}_sizetype_0"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>

{set $custom_attribute_classes = $custom_attribute_classes|append( 'mceItemSkip' )}

<input type="text" size="2" id="{$custom_attribute_id}_source_1" value="{$custom_attribute_default[1]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
<select id="{$custom_attribute_id}_sizetype_1"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>

<input type="text" size="2" id="{$custom_attribute_id}_source_2" value="{$custom_attribute_default[2]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
<select id="{$custom_attribute_id}_sizetype_2"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>

<input type="text" size="2" id="{$custom_attribute_id}_source_3" value="{$custom_attribute_default[3]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
<select id="{$custom_attribute_id}_sizetype_3"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>

<script type="text/javascript">
<!--

eZOEPopupUtils.settings.customAttributeInitHandler['{$custom_attribute_id}_source_0'] = {literal} function( el, value )
{
    if ( ez.string.trim( value ) === '' ) return;
    var valArr = (value + ' 0 0 0 0').split(/\s/g), base_id = el.id.replace('_source_0', ''), inp, sel;
    for(var i = 0; i < 4; i++)
    {
        inp = ez.$( base_id + '_source_' + i ).el;
        inp.value = ez.num( valArr[i], 0, 'int' );
        ez.$( base_id + '_sizetype_' + i ).el.selectedIndex = ez.$$('#' + base_id + '_sizetype_' + i + ' option').map(function( o )
        {
            return o.el.value;
        }).indexOf( valArr[i].replace( inp.value, '' ) );
    }
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source_0'] = {literal} function( el, value )
{
    var inp, sel, tempval = [], base_id = el.id.replace('_source_0', '');
    for(var i = 0; i < 4; i++)
    {
        inp = ez.$( base_id + '_source_' + i ).el, sel = ez.$( base_id + '_sizetype_' + i ).el;
        tempval.push( inp.value + ( sel.selectedIndex !== -1 ? sel.options[sel.selectedIndex].value : '' ) );
    }
    return tempval.join(' ');
};{/literal}

//-->
</script>