{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'ezoe_attributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'ezoe_attributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'ezoe_attributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'ezoe_attributes.ini') ) )}
{/if}
{if $custom_attribute_default|ne('')}
    {set $custom_attribute_default = $custom_attribute_default|append(' solid #000000')|explode(' ')}
	{def $custom_attribute_default_int = $custom_attribute_default.0|int
	     $custom_attribute_default_type = $custom_attribute_default|explode( $custom_attribute_default_int ).1}
{else}
    {set $custom_attribute_default = array('','','')}
	{def $custom_attribute_default_int = ''
	     $custom_attribute_default_type = ''}
{/if}
<table cellspacing="1" cellpadding="0" border="0">
<tr>
<td>
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
</td>
<td>
<select id="{$custom_attribute_id}_bordertype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
{if ezini_hasvariable( $custom_attribute_settings, 'CssBorderType', 'ezoe_attributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'CssBorderType', 'ezoe_attributes.ini' ) as $key => $value}
    <option value="{$key}"{if $custom_attribute_default.1|eq($key)} selected="selected"{/if}>{$value}</option>
{/foreach}
{else}
    <option value="none">none</option>
    <option value="hidden"{if $custom_attribute_default.1|eq('hidden')} selected="selected"{/if}>hidden</option>
    <option value="dotted"{if $custom_attribute_default.1|eq('dotted')} selected="selected"{/if}>dotted</option>
    <option value="dashed"{if $custom_attribute_default.1|eq('dashed')} selected="selected"{/if}>dashed</option>
    <option value="solid"{if $custom_attribute_default.1|eq('solid')} selected="selected"{/if}>solid</option>
    <option value="double"{if $custom_attribute_default.1|eq('double')} selected="selected"{/if}>double</option>
    <option value="groove"{if $custom_attribute_default.1|eq('groove')} selected="selected"{/if}>groove</option>
    <option value="ridge"{if $custom_attribute_default.1|eq('ridge')} selected="selected"{/if}>ridge</option>
    <option value="inset"{if $custom_attribute_default.1|eq('inset')} selected="selected"{/if}>inset</option>
    <option value="outset"{if $custom_attribute_default.1|eq('outset')} selected="selected"{/if}>outset</option>
{/if}
</select>
</td>
<td>
    <input type="text" size="9" id="{$custom_attribute_id}_color" onchange="document.getElementById('{$custom_attribute_id}_preview').style.backgroundColor = this.value;" onkeyup="document.getElementById('{$custom_attribute_id}_preview').style.backgroundColor = this.value;" value="{$custom_attribute_default.2|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip" />
</td>
<td>
    <a id="{$custom_attribute_id}_link" class="pickcolor" href="JavaScript:void(0);" onclick="tinyMCEPopup.pickColor(event, '{$custom_attribute_id}_color');"><span id="{$custom_attribute_id}_preview" style="background-color: {*$custom_attribute_default*}"></span></a>
</td>
</tr>
</table>
<script type="text/javascript">
<!--

eZOEPopupUtils.settings.customAttributeInitHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    
    var baseid = el.id.replace('_source', '_'), valArr = value.split(' ');
    el.value = ez.num( valArr[0], 0, 'int' );
    var size = document.getElementById( baseid + 'sizetype' ), border = document.getElementById( baseid+'bordertype' ), color = document.getElementById( baseid+'color' );
    size.selectedIndex = jQuery.inArray( valArr[0].replace( el.value, '' ), jQuery('#' + size.id + ' option').map(function( i, n )
    {
        return n.value;
    }));
    border.selectedIndex = jQuery.inArray( ( valArr[1] || 'solid' ), jQuery('#' + border.id + ' option').map(function( i, n )
    {
        return n.value;
    }));
    color.value = valArr[2] || '';
    color.onchange();
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    var size = document.getElementById( el.id.replace('_source', '_sizetype') ), border = document.getElementById( el.id.replace('_source', '_bordertype') ), color = document.getElementById( el.id.replace('_source', '_color') );
    if ( value === '' )
        return '';
    return value + ( size.selectedIndex !== -1 ? size.options[size.selectedIndex].value : '' ) + ( border.selectedIndex !== -1 ? ' ' + border.options[border.selectedIndex].value : '' ) + ' ' + color.value;
};{/literal}

//-->
</script>