{$attribute.content.name}

<select>
{section name=OptionList loop=$attribute.content.option_list sequence=array(bglight,bgdark)}

<option>{$OptionList:item.value}</option>

{/section}
</select>
