{default attribute_base='ContentObjectAttribute'
         html_class='full'}
{let data_int=cond( is_set( $#collection_attributes[$attribute.id]), $#collection_attributes[$attribute.id].data_int,
                    $attribute.data_int )}
<input class="{eq($html_class,'half')|choose('box','halfbox')}" type="checkbox" name="{$attribute_base}_data_boolean_{$attribute.id}" {$data_int|choose("",checked)} />
{/let}
{/default}