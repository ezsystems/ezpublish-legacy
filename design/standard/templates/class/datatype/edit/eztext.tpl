<div class="block">
<label>{"Preferred number of rows"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<select name="ContentClass_eztext_cols_{$class_attribute.id}">
    <option value="2" {section show=eq($class_attribute.data_int1,2)}selected{/section}>2</option>
    <option value="5" {section show=eq($class_attribute.data_int1,5)}selected{/section}>5</option>
    <option value="10" {section show=eq($class_attribute.data_int1,10)}selected{/section}>10</option>
    <option value="15" {section show=eq($class_attribute.data_int1,15)}selected{/section}>15</option>
    <option value="20" {section show=eq($class_attribute.data_int1,20)}selected{/section}>20</option>
    <option value="25" {section show=eq($class_attribute.data_int1,25)}selected{/section}>25</option>
</select>
</div>
