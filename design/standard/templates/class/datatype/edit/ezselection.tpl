<div class="block">
<input type="checkbox" name="ContentClass_ezselection_ismultiple_value_{$class_attribute.id}" {section show=$class_attribute.data_int1}checked{/section} /><label>{"Multiple choice"|i18n("design/standard/class/datatype")}</label>
</div>


{section name=Option loop=$class_attribute.content.options}
<input type="text" name="ContentClass_ezselection_option_name_array_{$class_attribute.id}[{$Option:item.id}]" value="{$Option:item.name}" /><br />
{/section}

<input type="submit" name="ContentClass_ezselection_newoption_button_{$class_attribute.id}" value="{"New option"|i18n("design/standard/class/datatype")}"/>




