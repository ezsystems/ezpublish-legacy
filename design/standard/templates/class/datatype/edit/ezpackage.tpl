<div class="block">
<label>{"Package Type"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezpackage_type_{$class_attribute.id}" value="{$class_attribute.data_text1}" size="8" maxlength="20" />
</div>
<div class="block">
<label>{"View Mode"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<select name="ContentClass_ezpackage_view_mode_{$class_attribute.id}" size="1">
      <option value="0" {section show=eq($class_attribute.data_int1,0)}selected="selected"{/section}>combobox</option>
      <option value="1" {section show=eq($class_attribute.data_int1,1)}selected="selected"{/section}>icon view</option>
</select>
</div>