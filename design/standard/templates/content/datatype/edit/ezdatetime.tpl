<div class="block">
<div class="element">
<label>{"Day:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_datetime_day_{$attribute.id}" size="3" value="{section show=eq($attribute.contentclass_attribute.data_int1,1)}{$attribute.content.day}{/section}" />
</div>
<div class="element">
<label>{"Month:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_datetime_month_{$attribute.id}" size="3" value="{section show=eq($attribute.contentclass_attribute.data_int1,1)}{$attribute.content.month}{/section}" />
</div>
<div class="element">
<label>{"Year:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_datetime_year_{$attribute.id}" size="5" value="{section show=eq($attribute.contentclass_attribute.data_int1,1)}{$attribute.content.year}{/section}" />
</div>
<div class="element">
<label>{"Hour:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_datetime_hour_{$attribute.id}" size="3" value="{section show=eq($attribute.contentclass_attribute.data_int1,1)}{$attribute.content.hour}{/section}" />
</div>
<div class="element">
<label>{"Minute:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_datetime_minute_{$attribute.id}" size="3" value="{section show=eq($attribute.contentclass_attribute.data_int1,1)}{$attribute.content.minute}{/section}" />
</div>
<div class="break"></div>
</div>
