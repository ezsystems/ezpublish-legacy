<form action={concat($module.functions.list.uri)|ezurl} method="post" name="RuleList">

{section show=$rule_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{"ID:"|i18n("design/standard/notification")}</th>
    <th>{"Rule Type:"|i18n("design/standard/notification")}</th>
    <th>{"Class Name:"|i18n("design/standard/notification")}</th>
    <th>{"Path:"|i18n("design/standard/notification")}</th>
    <th>{"Keyword:"|i18n("design/standard/notification")}</th>
    <th>{"Additional constraint:"|i18n("design/standard/notification")}</th>
</tr>

{section name=Rule loop=$rule_list sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Rule:sequence}" width="1%">{$Rule:item.id}</td>
    <td class="{$Rule:sequence}">{$Rule:item.type}</td>
    <td class="{$Rule:sequence}">{$Rule:item.contentclass_name}</td>
    <td class="{$Rule:sequence}">{$Rule:item.path}</td>
    <td class="{$Rule:sequence}">{$Rule:item.keyword}</td>
    <td class="{$Rule:sequence}">{section show=$Rule:item.has_constraint}Yes{/section}</td>
    <td class="{$Rule:sequence}" width="1%"><a href={concat($module.functions.edit.uri,"/",$Rule:item.type,"/",$Rule:item.id)|ezurl}><img name="edit" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></td>
    <td class="{$Rule:sequence}" width="1%"><input type="checkbox" name="Rule_id_checked[]" value="{$Rule:item.id}" /></td>
</tr>
{/section}
</table>
{/section}

<div class="buttonblock">
<select name="notification_rule_type">
{section name=Rules loop=$rule_type}
<option value="{$Rules:item.information.name}">{$Rules:item.information.name}</option>
{/section}
</select>
{include uri="design:gui/button.tpl" name=new id_name=NewRuleButton value="New Rule"|i18n("design/standard/notification")}
{include uri="design:gui/button.tpl" name=delete id_name=DeleteRuleButton value="Delete"|i18n("design/standard/notification")}
{include uri="design:gui/button.tpl" name=send id_name=SendButton value="Send Message"|i18n("design/standard/notification")}
</div>
</form>
