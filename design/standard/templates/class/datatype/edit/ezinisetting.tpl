<div class="block">

<div class="element">
  <label>{"Ini file"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
  <input class="halfbox" type="text" name="ContentClass_ezinisetting_file_{$class_attribute.id}" value="{$class_attribute.data_text1}" size="30" maxlength="50">
</div>

<div class="element">
  <label>{"Ini Section"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
  <input class="halfbox" type="text" name="ContentClass_ezinisetting_section_{$class_attribute.id}" value="{$class_attribute.data_text2}" size="30" maxlength="50">
</div>

<div class="element">
  <label>{"Ini Parameter"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
  <input class="halfbox" type="text" name="ContentClass_ezinisetting_parameter_{$class_attribute.id}" value="{$class_attribute.data_text3}" size="30" maxlength="50">
</div>

</div>

<div class="block">
<div class="element">
  <label>{"Ini setting type"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
  <select name="ContentClass_ezinisetting_type_{$class_attribute.id}">
    <option value="text" {section show=$class_attribute.data_text4|eq("text")}selected="selected"{/section}>{"Text"|i18n("design/standard/class/datatype")}</option>
    <option value="boolean1" {section show=$class_attribute.data_text4|eq("boolean1")}selected="selected"{/section}>{"Enable/Disable"|i18n("design/standard/class/datatype")}</option>
    <option value="boolean2" {section show=$class_attribute.data_text4|eq("boolean2")}selected="selected"{/section}>{"True/False"|i18n("design/standard/class/datatype")}</option>
    <option value="integer" {section show=$class_attribute.data_text4|eq("integer")}selected="selected"{/section}>{"Integer"|i18n("design/standard/class/datatype")}</option>
    <option value="float" {section show=$class_attribute.data_text4|eq("float")}selected="selected"{/section}>{"Float"|i18n("design/standard/class/datatype")}</option>
  </select>
</div>
</div>
