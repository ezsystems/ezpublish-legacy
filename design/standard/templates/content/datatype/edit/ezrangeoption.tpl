{default attribute_base=ContentObjectAttribute}
<input type="text" name = "{$attribute_base}_data_rangeoption_name_{$attribute.id}" value="{$attribute.content.name}" />

<div class="block">
<div class="element">
<label>{"Start value"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_rangeoption_start_value_{$attribute.id}" size="3" value="{$attribute.content.start_value}" />
</div>

<label>{"Stop value"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_rangeoption_stop_value_{$attribute.id}" size="3" value="{$attribute.content.stop_value}" />
</div>

<label>{"Step value"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_rangeoption_step_value_{$attribute.id}" size="3" value="{$attribute.content.step_value}" />
</div>

</div>
{/default}