<h2>{$attribute.content.name}</h2>

<div class="block">

{section name=OptionList loop=$attribute.content.option_list sequence=array(bglight,bgdark)}
<input type="radio" name="ContentObjectAttribute_data_option_value_{$attribute.id}" value="{$OptionList:item.id}" />{$OptionList:item.value}{$OptionList:item.id}<br />
{/section}
</div>
