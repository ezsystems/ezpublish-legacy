{default attribute_base=ContentObjectAttribute}
<input type="checkbox" name="{$attribute_base}_data_boolean_{$attribute.id}"  {$attribute.data_int|choose("",checked)} />
{/default}
