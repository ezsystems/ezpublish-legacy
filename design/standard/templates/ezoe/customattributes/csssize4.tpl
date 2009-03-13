{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{set $custom_attribute_default = $custom_attribute_default|explode(' ')}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'ezoe_attributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'ezoe_attributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'ezoe_attributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'ezoe_attributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'CssSizeType', 'ezoe_attributes.ini' )}
    {def $css_size_types = ezini( $custom_attribute_settings, 'CssSizeType', 'ezoe_attributes.ini' )}
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
{if $custom_attribute_default[0]|ne('')}
    {def $custom_attribute_default_int = $custom_attribute_default[0]|int
         $custom_attribute_default_type = $custom_attribute_default[0]|explode( $custom_attribute_default_int ).1}
{else}
    {def $custom_attribute_default_int = ''
         $custom_attribute_default_type = ''}
{/if}
<input type="text" size="2" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default_int|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />
<select id="{$custom_attribute_id}_sizetype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}"{if $custom_attribute_default_type|eq($key)} selected="selected"{/if}>{$value}</option>
{/foreach}
</select>
</td>
{set $custom_attribute_classes = $custom_attribute_classes|append( 'mceItemSkip' )}
{if and( is_set($custom_attribute_default[1]), $custom_attribute_default[1]|ne('') )}
    {set $custom_attribute_default_int = $custom_attribute_default[1]|int}
    {set $custom_attribute_default_type = $custom_attribute_default[1]|explode( $custom_attribute_default_int ).1}
{else}
    {set $custom_attribute_default_int = ''}
    {set $custom_attribute_default_type = ''}
{/if}
<td>
<input type="text" size="2" id="{$custom_attribute_id}_source_1" value="{$custom_attribute_default_int|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />
<select id="{$custom_attribute_id}_sizetype_1"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}"{if $custom_attribute_default_type|eq($key)} selected="selected"{/if}>{$value}</option>
{/foreach}
</select>
</td>
{if and( is_set($custom_attribute_default[2]), $custom_attribute_default[2]|ne('') )}
    {set $custom_attribute_default_int = $custom_attribute_default[2]|int}
    {set $custom_attribute_default_type = $custom_attribute_default[2]|explode( $custom_attribute_default_int ).1}
{else}
    {set $custom_attribute_default_int = ''}
    {set $custom_attribute_default_type = ''}
{/if}
<td>
<input type="text" size="2" id="{$custom_attribute_id}_source_2" value="{$custom_attribute_default_int|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />
<select id="{$custom_attribute_id}_sizetype_2"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}"{if $custom_attribute_default_type|eq($key)} selected="selected"{/if}>{$value}</option>
{/foreach}
</select>
</td>
{if and( is_set($custom_attribute_default[3]), $custom_attribute_default[3]|ne('') )}
    {set $custom_attribute_default_int = $custom_attribute_default[3]|int}
    {set $custom_attribute_default_type = $custom_attribute_default[3]|explode( $custom_attribute_default_int ).1}
{else}
    {set $custom_attribute_default_int = ''}
    {set $custom_attribute_default_type = ''}
{/if}
<td>
<input type="text" size="2" id="{$custom_attribute_id}_source_3" value="{$custom_attribute_default_int|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />
<select id="{$custom_attribute_id}_sizetype_3"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{foreach $css_size_types as $key => $value}
    <option value="{$key}"{if $custom_attribute_default_type|eq($key)} selected="selected"{/if}>{$value}</option>
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
    var valArr = (value +'').split(/\s/g), base_id = el.id.replace('_source', ''), inp, sel, tid;
    for(var i = 0, l = ez.min( valArr.length, 4 ); i < l; i++)
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
    var inp, sel, tempval = [], base_id = el.id.replace('_source', ''), tid, hasValue = false;
    for(var i = 0; i < 4; i++)
    {
        tid = (i === 0 ? '' : '_' + i);
        inp = ez.$( base_id + '_source' + tid ).el, sel = ez.$( base_id + '_sizetype' + tid ).el;
        if ( inp.value !== '' ) hasValue = true;
        tempval.push( inp.value + ( sel.selectedIndex !== -1 ? sel.options[sel.selectedIndex].value : '' ) );
    }
    return hasValue ? tempval.join(' ') : '';
};{/literal}

//-->
</script>