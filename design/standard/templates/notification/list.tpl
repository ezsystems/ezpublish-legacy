<form action={concat($module.functions.list.uri)|ezurl} method="post" name="RuleList">

{section show=$rule_list}
<table width="100%" cellspacing="0">
<tr>
  <th align="left">ID</th>
  <th align="left">Rule Type</th>
  <th align="left">Class Name</th>
  <th align="left">Path</th>
  <th align="left">Keyword</th>
  <th align="left">Additional constraint</th>
</tr>

{section name=Rule loop=$rule_list sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Rule:sequence}" width="3%">{$Rule:item.id}</td>
    <td class="{$Rule:sequence}">{$Rule:item.type}</td>
    <td class="{$Rule:sequence}">{$Rule:item.contentclass_name}</td>
    <td class="{$Rule:sequence}">{$Rule:item.path}</td>
    <td class="{$Rule:sequence}">{$Rule:item.keyword}</td>
    <td class="{$Rule:sequence}">{section show=$Rule:item.has_constraint}Yes{/section}</td>
    <td class="{$Rule:sequence}" width="1%"><a href={concat($module.functions.edit.uri,"/",$Rule:item.type,"/",$Rule:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
    <td class="{$Rule:sequence}" width="1%"><input type="checkbox" name="Rule_id_checked[]" value="{$Rule:item.id}"></td>
</tr>
{/section}
</table>
{/section}

<table width="100%">
<tr>
<td width="99%"></td>
<td>{include uri="design:gui/button.tpl" name=new id_name=NewRuleButton value="New Rule"}</td>
<td><select name="notification_rule_type">
{section name=Rules loop=$rule_type}
<option value="{$Rules:item.information.name}">{$Rules:item.information.name}</option>
{/section}
</select>
</td>
<td>{include uri="design:gui/button.tpl" name=delete id_name=DeleteRuleButton value="Delete"}</td>
<td>{include uri="design:gui/button.tpl" name=send id_name=SendButton value="Send Message"}</td>
</tr>
</table>
</form>
