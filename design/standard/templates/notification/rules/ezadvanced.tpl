<tr>
<td>
Content Class Name:
</td>
<td>
<select name="contentClassName">
<option value="All">All</option>
{section name=ClassList loop=$class_list}
<option value="{$ClassList:item.name}" {switch name=sw match=$ClassList:item.name}{case match=$rule_list.contentclass_name}selected{/case}{case/}{/switch}>{$ClassList:item.name}</option>
{/section}
</select>
</td>
</tr>
<tr>
<td>
Path:
</td>
<td>
<input type="text" name="path" value="{section show=$rule_list}{$rule_list.path}{/section}" size="50">
</td>
</tr>
<tr>
<td>
Keyword:
</td>
<td>
<input type="text" name="keyword" value="{section show=$rule_list}{$rule_list.keyword}{/section}" size="50">
</td>
</tr>
<tr>
</tr>


<input type="hidden" name="ConstraintClassAttributeID" value="{$ClassAttributeID}"> 
{section name=ConstraintList loop=$constraint_list}
<tr>
{section name=CList loop=$ConstraintList:item}
<td>{$ConstraintList:CList:item}:</td>
<td><input type="text" name="ConstraintValue[]" size="12" value="">
    <input type="hidden" name="ConstraintName[]" value="{$ConstraintList:CList:item}"> 
</td>
{/section}
</tr>
{/section}



