<div class="block">
<label>{"Default value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_default_value_{$class_attribute.id}" value="{$class_attribute.data_float3|l10n(number)}" size="8" maxlength="20" />
</div>
{switch name=input_state match=$class_attribute.data_float4}
  {case match=1}

<div class="block">
<div class="element">
<label>{"Min float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
    <input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="{$class_attribute.data_float1|l10n(number)}" size="8" maxlength="20" />
</div>
<div class="element">
<label>{"Max float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
</div>
<div class="break"></div>
</div>

  {/case}
  {case match=2}

<div class="block">
<div class="element">
<label>{"Min float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
</div>
<div class="element">
<label>{"Max float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="{$class_attribute.data_float2|l10n(number)}" size="8" maxlength="20" />
</div>
<div class="break"></div>
</div>

  {/case}
  {case match=3}

<div class="block">
<div class="element">
<label>{"Min float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="{$class_attribute.data_float1|l10n(number)}" size="8" maxlength="20" />
</div>
<div class="element">
<label>{"Max float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="{$class_attribute.data_float2|l10n(number)}" size="8" maxlength="20" />
</div>
<div class="break"></div>
</div>    

  {/case}
  {case}

<div class="block">
<div class="element">
<label>{"Min float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
</div>
<div class="element">
<label>{"Max float value"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
</div>
<div class="break"></div>
</div>    

  {/case}
{/switch}
