{let name=CurrentObject current_object=fetch('content','version',hash(object_id,$attribute.contentobject_id,version_id,$attribute.version))}
<select name="ContentObjectAttribute_data_integer_{$attribute.id}" size="1">
      <option value="0">[none]</option>
      {section name=Relation loop=$:current_object.related_contentobject_array}
         {section show=eq($:item.contentclass_id,5)}
              <option value="{$:item.id}" {section show=eq($:item.id,$attribute.data_int)}selected{/section}>{$:item.name}</option>
	 {/section}
      {/section} 
</select>
{/let}
