<div class="block">
<label>{"Default value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<select name="ContentClass_ezdatetime_default_{$class_attribute.id}">
    <option value="0" {section show=eq($class_attribute.data_int1,0)}selected{/section}>{"Empty"|i18n("design/standard/class/datatype")}</option>
    <option value="1" {section show=eq($class_attribute.data_int1,1)}selected{/section}>{"Current datetime"|i18n("design/standard/class/datatype")}</option>
</select>
</div>
