<tr>
<td>
{"Content Class Name:"|i18n("design/standard/notification/rules")}
</td>
<td>
<select name="contentClassName">
<option value="All">{"All"|i18n("design/standard/notification/rules")}</option>
{section name=ClassList loop=$class_list}
<option value="{$ClassList:item.name}" {switch name=sw match=$ClassList:item.name}{case match=$rule_list.contentclass_name}selected{/case}{case/}{/switch}>{$ClassList:item.name}</option>
{/section}
</select>
</td>
</tr>
<tr>
