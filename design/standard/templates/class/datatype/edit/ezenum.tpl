Multiple choice <input type="checkbox" name = "ContentClass_ezenum_ismultiple_value_{$class_attribute.id}" value="{$class_attribute.data_int1}" {section show=$class_attribute.data_int1}checked{/section} />
<br />
<input type="radio" name = "ContentClass_ezenum_isoption_value_{$class_attribute.id}" value="1" {section show=$class_attribute.data_int2}checked{/section} /> Option style
<input type="radio" name = "ContentClass_ezenum_isoption_value_{$class_attribute.id}" value="0" 
          {switch match=$class_attribute.data_int2}
            {case match=0}
	       checked
            {/case}
	  {/switch} /> Checkbox style
<br />
{section name=EnumList loop=$class_attribute.content.enum_list sequence=array(bglight,bgdark)}

<input type="hidden" name = "ContentClass_data_enumid_{$class_attribute.id}[]" value="{$EnumList:item.id}">
Enum Element <input type="text" name = "ContentClass_data_enumelement_{$class_attribute.id}[]" value="{$EnumList:item.enumelement}" size="10" maxlength="255">
&nbsp;&nbsp;Enum Value <input type="text" name = "ContentClass_data_enumvalue_{$class_attribute.id}[]" value="{$EnumList:item.enumvalue}" size="10" maxlength="255">
<input type="checkbox" name = "ContentClass_data_enumremove_{$class_attribute.id}[]" value="{$EnumList:item.id}">
<br />
{/section}

<input type="submit" name="CustomActionButton[{$class_attribute.id}_new_enumelement]" value="{'New Enum Element'|i18n}" />
<input type="submit" name="CustomActionButton[{$class_attribute.id}_remove_selected]" value="{'Remove Selected'|i18n}" />
<br />
