{$attribute.content.name}

<select>
{section name=OptionList loop=$attribute.content.option_list sequence=array(bglight,bgdark)}

<option value="{$OptionList:item.value}">{$OptionList:item.value}</option>

{/section}
</select>
