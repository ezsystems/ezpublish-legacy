<select name="{$id_name}">
{section name=DataType loop=$datatypes}
<option value="{$DataType:item.information.string}" {section show=$current}{switch name=sw match=$DataType:item.information.string}{case match=$current}selected="selected"{/case}{case}{/case}{/switch}{/section}>{$DataType:item.information.name|wash}</option>
{/section}
</select>
