{default attribute_base='ContentObjectAttribute'
         class='full'}
<input class="{eq($class,'full')|choose('halfbox','box')}" type="text" size="70" name="{$attribute_base}_ezstring_data_text_{$attribute.id}" value="{$attribute.data_text|wash(xhtml)}" {* maxlength="{$attribute.contentclass_attribute.data_int1}" *} />
{/default}