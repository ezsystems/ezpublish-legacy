<div class="block">
<label>{"Content Class Name"|i18n("design/standard/notification/rules")}</label><div class="labelbreak"></div>
<select name="contentClassName">
<option value="All">{"All"|i18n("design/standard/notification/rules")}</option>
{section name=ClassList loop=$class_list}
<option value="{$ClassList:item.name}" {switch name=sw match=$ClassList:item.name}{case match=$rule_list.contentclass_name}selected{/case}{case/}{/switch}>{$ClassList:item.name}</option>
{/section}
</select>
</div>

<div class="block">
<div class="element">
<label>{"Path"|i18n("design/standard/notification/rules")}</label><div class="labelbreak"></div>
<input class="box" type="text" name="path" value="{section show=$rule_list}{$rule_list.path}{/section}" size="50" />
</div>
<div class="element">
<label>{"Keyword"|i18n("design/standard/notification/rules")}</label><div class="labelbreak"></div>
<input class="box" type="text" name="keyword" value="{section show=$rule_list}{$rule_list.keyword}{/section}" size="50" />
</div>
<div class="break"></div>
</div>

<input type="hidden" name="ConstraintClassAttributeID" value="{$ClassAttributeID}" /> 
{section name=ConstraintList loop=$constraint_list}
{section name=CList loop=$ConstraintList:item}
<div class="block">
<label>{$ConstraintList:CList:item}:</label><div class="labelbreak"></div>
<input type="text" name="ConstraintValue[]" size="12" value="" />
<input type="hidden" name="ConstraintName[]" value="{$ConstraintList:CList:item}" />  
</div>
{/section}
{/section}
