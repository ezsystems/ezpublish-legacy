<div class="block">
<input type="checkbox" name = "ContentClass_ezenum_ismultiple_value_{$class_attribute.id}" value="{$class_attribute.data_int1}" {section show=$class_attribute.data_int1}checked{/section} /><label>Multiple choice</label>
</div>

<div class="block">
<input type="radio" name = "ContentClass_ezenum_isoption_value_{$class_attribute.id}" value="1" {section show=$class_attribute.data_int2}checked{/section} /><label>Option style</label>
<input type="radio" name = "ContentClass_ezenum_isoption_value_{$class_attribute.id}" value="0" 
          {switch match=$class_attribute.data_int2}
            {case match=0}
	       checked
            {/case}
	  {/switch} /><label>Checkbox style</label>
</div>

{section name=EnumList loop=$class_attribute.content.enum_list sequence=array(bglight,bgdark)}

<div class="block">
<input type="hidden" name = "ContentClass_data_enumid_{$class_attribute.id}[]" value="{$EnumList:item.id}" />
<div class="element">
<label>Enum Element:</label><div class="labelbreak"></div>
<input class="halfbox" type="text" name = "ContentClass_data_enumelement_{$class_attribute.id}[]" value="{$EnumList:item.enumelement}" size="10" maxlength="255" />
</div>
<div class="element">
<label>Enum Value:</label><div class="labelbreak"></div>
<input type="text" name = "ContentClass_data_enumvalue_{$class_attribute.id}[]" value="{$EnumList:item.enumvalue}" size="10" maxlength="255" />
</div>
<div class="element">
<input type="checkbox" name = "ContentClass_data_enumremove_{$class_attribute.id}[]" value="{$EnumList:item.id}" />
</div>
<div class="break"></div>
</div>

{/section}


<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$class_attribute.id}_new_enumelement]" value="{'New Enum Element'|i18n}" />
<input class="button" type="submit" name="CustomActionButton[{$class_attribute.id}_remove_selected]" value="{'Remove Selected'|i18n}" />
</div>
