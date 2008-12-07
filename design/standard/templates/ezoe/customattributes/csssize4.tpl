{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{set $custom_attribute_default = $custom_attribute_default|explode(' ')}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'ezoe_customattributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'ezoe_customattributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'ezoe_customattributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'ezoe_customattributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'CssSizeType', 'ezoe_customattributes.ini' )}
    {def $css_size_types = ezini( $custom_attribute_settings, 'CssSizeType', 'ezoe_customattributes.ini' )}
{else}
    {def $css_size_types = hash('px', 'px', 'em', 'em', '%', '%')}
{/if}

<table class="csssize4_input_layout" border="0" cellpadding="0" cellspacing="1" summary="Size inputs for all 4 edges">
<thead>
<tr>
	<td align="center"><label for="{$custom_attribute_id}_source">{'Top'|i18n('design/standard/ezoe')}</label></td>
	<td align="center"><label for="{$custom_attribute_id}_source_1">{'Right'|i18n('design/standard/ezoe')}</label></td>
	<td align="center"><label for="{$custom_attribute_id}_source_2">{'Bottom'|i18n('design/standard/ezoe')}</label></td>
	<td align="center"><label for="{$custom_attribute_id}_source_3">{'Left'|i18n('design/standard/ezoe')}</label></td>
</tr>
</thead>
<tbody>
<tr>
<td>
<input type="text" size="2" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default[0]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_titles|wash}" />
<select id="{$custom_attribute_id}_sizetype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>
</td>
{set $custom_attribute_classes = $custom_attribute_classes|append( 'mceItemSkip' )}
<td>
<input type="text" size="2" id="{$custom_attribute_id}_source_1" value="{$custom_attribute_default[1]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_titles|wash}" />
<select id="{$custom_attribute_id}_sizetype_1"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>
</td>
<td>
<input type="text" size="2" id="{$custom_attribute_id}_source_2" value="{$custom_attribute_default[2]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_titles|wash}" />
<select id="{$custom_attribute_id}_sizetype_2"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>
</td>
<td>
<input type="text" size="2" id="{$custom_attribute_id}_source_3" value="{$custom_attribute_default[3]|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_titles|wash}" />
<select id="{$custom_attribute_id}_sizetype_3"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
</select>
</td>
</tr>
<tbody>
</table>
<script type="text/javascript">
<!--

eZOEPopupUtils.settings.customAttributeInitHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    if ( ez.string.trim( value ) === '' ) return;
    var valArr = (value + ' 0 0 0 0').split(/\s/g), base_id = el.id.replace('_source', ''), inp, sel, tid;
    for(var i = 0; i < 4; i++)
    {
        tid = (i === 0 ? '' : '_' + i);
        inp = ez.$( base_id + '_source' + tid ).el;
        inp.value = ez.num( valArr[i], 0, 'int' );
        ez.$( base_id + '_sizetype' + tid ).el.selectedIndex = ez.$$('#' + base_id + '_sizetype' + tid + ' option').map(function( o )
        {
            return o.el.value;
        }).indexOf( valArr[i].replace( inp.value, '' ) );
    }
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    var inp, sel, tempval = [], base_id = el.id.replace('_source', ''), tid;
    for(var i = 0; i < 4; i++)
    {
        tid = (i === 0 ? '' : '_' + i);
        inp = ez.$( base_id + '_source' + tid ).el, sel = ez.$( base_id + '_sizetype' + tid ).el;
        tempval.push( inp.value + ( sel.selectedIndex !== -1 ? sel.options[sel.selectedIndex].value : '' ) );
    }
    return tempval.join(' ');
};{/literal}

//-->
</script>