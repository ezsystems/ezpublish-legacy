{let option_id=cond( is_set( $#collection_attributes[$attribute.id]), $#collection_attributes[$attribute.id].data_int,
                     false() )}
<h2>{$attribute.content.name}</h2>

<div class="block">

{section name=OptionList loop=$attribute.content.option_list sequence=array(bglight,bgdark)}
    <input type="radio" name="ContentObjectAttribute_data_option_value_{$attribute.id}" value="{$OptionList:item.id}"
           {section show=$OptionList:item.id|eq($option_id)}checked="checked"{/section}
           />{$OptionList:item.value}<br />
{/section}
</div>
{/let}
