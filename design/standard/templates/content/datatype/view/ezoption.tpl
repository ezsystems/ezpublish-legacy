{$attribute.content.name}

<select name="eZOption[{$attribute.id}]">
{section name=Option loop=$attribute.content.option_list sequence=array(bglight,bgdark)}

<option value="{$Option:item.value}">{$Option:item.value}</option>

{/section}
</select>