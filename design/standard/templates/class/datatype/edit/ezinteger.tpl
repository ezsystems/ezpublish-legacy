<table>
<tr>
  <td width="100" align="left">
  Default value
  </td>
  <td align="left">  
  <input type="text" name="ContentClass_ezinteger_default_value_{$class_attribute.id}" value="{$class_attribute.data_int3}" size="8" maxlength="20" />
  </td>
  <td colspan="2">  
  </td>
</tr>
{switch name=input_state match=$class_attribute.data_int4}
  {case match=0}
  <tr>
    <td  width="100">
    Min integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_min_integer_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_max_integer_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
  </tr>
  {/case}
  {case match=1}
  <tr>
    <td  width="100">
    Min integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_min_integer_value_{$class_attribute.id}" value="{$class_attribute.data_int1}" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_max_integer_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
  </tr>  
  {/case}
  {case match=2}
  <tr>
    <td  width="100">
    Min integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_min_integer_value_{$class_attribute.id}" value="" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_max_integer_value_{$class_attribute.id}" value="{$class_attribute.data_int2}" size="8" maxlength="20" />
    </td> 
  </tr> 
  {/case}
  {case match=3}
  <tr>
    <td  width="100">
    Min integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_min_integer_value_{$class_attribute.id}" value="{$class_attribute.data_int1}" size="8" maxlength="20" />
    </td> 
    <td align="left"  width="110">
    Max integer value
    </td> 
    <td align="left">
    <input type="text" name="ContentClass_ezinteger_max_integer_value_{$class_attribute.id}" value="{$class_attribute.data_int2}" size="8" maxlength="20" />
    </td> 
  </tr>  
  {/case}
{/switch}
</table>