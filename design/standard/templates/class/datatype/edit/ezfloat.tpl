<table>
<tr>
  <td width="100" align="left">
  Default value
  </td>
  <td align="left">  
  <input type="text" name="ContentClass_ezfloat_default_value_{$class_attribute.id}" value="{$class_attribute.data_float3}" size="8" maxlength="20" />
  </td>
  <td colspan="2">  
  </td>
</tr>
{switch name=input_state match=$class_attribute.data_float4}
  {case match=1}
  <tr>
    <td  width="100">
    Min float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="{$class_attribute.data_float1}" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
  </tr>  
  {/case}
  {case match=2}
  <tr>
    <td  width="100">
    Min float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="{$class_attribute.data_float2}" size="8" maxlength="20" />
    </td> 
  </tr> 
  {/case}
  {case match=3}
  <tr>
    <td  width="100">
    Min float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="{$class_attribute.data_float1}" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="{$class_attribute.data_float2}" size="8" maxlength="20" />
    </td> 
  </tr>  
  {/case}
  {case}
  <tr>
    <td  width="100">
    Min float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_min_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max float value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezfloat_max_float_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
  </tr>
  {/case}
{/switch}
</table>