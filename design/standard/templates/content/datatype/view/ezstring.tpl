{switch match=$attribute.contentclass_attribute.is_information_collector}
{case match=1}
<input type="text" size="70" name="ContentObjectAttribute_ezstring_data_text_{$attribute.id}" value="{$attribute.data_text}" {* maxlength="{$attribute.contentclass_attribute.data_int1}" *} />
{/case}
{case}
{$attribute.data_text}
{/case}
{/switch}
