{default attribute_base='ContentObjectAttribute'
         class='full'}
<textarea class="{eq($class,'full')|choose('halfbox','box')}" name="{$attribute_base}_data_text_{$attribute.id}" cols="70" rows="{$attribute.contentclass_attribute.data_int1}">{$attribute.content|wash}</textarea>
{/default}