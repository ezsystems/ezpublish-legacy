<select name="{$id_name}">
{section name=DataType loop=$datatypes}
<option value="{$DataType:item.information.string}" {switch name=sw match=$DataType:item.information.string}{case match=$current}selected="selected"{/case}{case/}{/switch}>{$DataType:item.information.name}</option>
{/section}
</select>
