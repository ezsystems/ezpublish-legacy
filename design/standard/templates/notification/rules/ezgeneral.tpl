<div class="block">
<label>{"Content Class Name:"|i18n("design/standard/notification/rules")}</label><div class="labelbreak"></div>
<select name="contentClassName">
<option value="All">{"All"|i18n("design/standard/notification/rules")}</option>
{section name=ClassList loop=$class_list}
<option value="{$ClassList:item.name}" {switch name=sw match=$ClassList:item.name}{case match=$rule_list.contentclass_name}selected{/case}{case/}{/switch}>{$ClassList:item.name}</option>
{/section}
</select>
</div>
