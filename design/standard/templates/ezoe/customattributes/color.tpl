<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
    <input type="text" size="9" name="{$custom_attribute}" id="{$custom_attribute_id}_source" onchange="document.getElementById('{$custom_attribute_id}_preview').style.backgroundColor = this.value;" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" />
</td>
<td>
    <a id="{$custom_attribute_id}_link" class="pickcolor" href="JavaScript:void(0);" onclick="tinyMCEPopup.pickColor(event, '{$custom_attribute_id}_source');"><span id="{$custom_attribute_id}_preview" style="background-color: {$custom_attribute_default}"></span></a>
</td>
</tr>
</table>