<div class="block">
<label>{"Package Name"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
{let package_list=fetch(package,list,hash(filter_array,array(array(type,$attribute.contentclass_attribute.data_text1) ) ) )}
<select name="ContentObjectAttribute_ezpackage_data_text_{$attribute.id}" size="1">
      <option value="0">[none]</option>
      {section name=Package loop=$:package_list}
          <option value="{$:item.name}" {section show=eq($:item.name,$attribute.data_text)}selected{/section}>{$:item.name}</option>
      {/section} 
</select>
{/let}
</div>
<div class="block">
<label>{"View Mode"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<select name="ContentObjectAttribute_ezpackage_data_int_{$attribute.id}" size="1">
      <option value="0" {section show=eq($attribute.data_int,0)}selected{/section}>plain view</option>
      <option value="1" {section show=eq($attribute.data_int,1)}selected{/section}>icon view</option>
</select>
</div>