{set $custom_attribute_classes = $custom_attribute_classes|append( 'int' )}
{if ezini_hasvariable( $custom_attribute_settings, 'Minimum', 'ezoe_customattributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('min', ezini($custom_attribute_settings, 'Minimum', 'ezoe_customattributes.ini') ) )}
{/if}
{if ezini_hasvariable( $custom_attribute_settings, 'Maximum', 'ezoe_customattributes.ini' )}
    {set $custom_attribute_classes = $custom_attribute_classes|append( concat('max', ezini($custom_attribute_settings, 'Maximum', 'ezoe_customattributes.ini') ) )}
{/if}
<table cellspacing="1" cellpadding="0" border="0">
<tr>
<td>
<input type="text" size="3" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_titles|wash}" />
<select id="{$custom_attribute_id}_sizetype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip sizetype_margin_fix">
{if ezini_hasvariable( $custom_attribute_settings, 'CssSizeType', 'ezoe_customattributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'CssSizeType', 'ezoe_customattributes.ini' ) as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
{else}
    <option value="px">px</option>
    <option value="em">em</option>
    <option value="%">%</option>
{/if}
</select>
</td>
<td>
<select id="{$custom_attribute_id}_bordertype"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip">
{if ezini_hasvariable( $custom_attribute_settings, 'CssBorderType', 'ezoe_customattributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'CssBorderType', 'ezoe_customattributes.ini' ) as $key => $value}
    <option value="{$key}">{$value}</option>
{/foreach}
{else}
    <option value="none">none</option>
    <option value="hidden">hidden</option>
    <option value="dotted">dotted</option>
    <option value="dashed">dashed</option>
    <option value="solid">solid</option>
    <option value="double">double</option>
    <option value="groove">groove</option>
    <option value="ridge">ridge</option>
    <option value="inset">inset</option>
    <option value="outset">outset</option>
{/if}
</select>
</td>
<td>
    <input type="text" size="9" id="{$custom_attribute_id}_color" onchange="document.getElementById('{$custom_attribute_id}_preview').style.backgroundColor = this.value;" onkeyup="document.getElementById('{$custom_attribute_id}_preview').style.backgroundColor = this.value;" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="mceItemSkip" />
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
    size.selectedIndex = ez.$$('#' + size.id + ' option').map(function( o ){
        return o.el.value;
    }).indexOf( valArr[0].replace( el.value, '' ) );
    border.selectedIndex = ez.$$('#' + border.id + ' option').map(function( o ){
        return o.el.value;
    }).indexOf( valArr[1] || 'solid' );
    color.value = valArr[2] || '';
    color.onchange();
};{/literal}

eZOEPopupUtils.settings.customAttributeSaveHandler['{$custom_attribute_id}_source'] = {literal} function( el, value )
{
    var size = document.getElementById( el.id.replace('_source', '_sizetype') ), border = document.getElementById( el.id.replace('_source', '_bordertype') ), color = document.getElementById( el.id.replace('_source', '_color') );
    return value + ( size.selectedIndex !== -1 ? size.options[size.selectedIndex].value : '' ) + ( border.selectedIndex !== -1 ? ' ' + border.options[border.selectedIndex].value : '' ) + ' ' + color.value;
};{/literal}

//-->
</script>