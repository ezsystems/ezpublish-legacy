{*?template charset=iso-8859-1 ?*}
<form action="{$module.functions.classlist.uri}/{$GroupID}" method="post" name="ClassEdit">

<h1>{"Defined class types for "|i18n('content/class')}{$group_name} </h1>

{section show=$groupclasses}
<table width="100%" cellspacing="0">
<tr>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/id">{"ID"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/name">{"Name"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/identifier">{"Identifier"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/status">{"Status"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/creator">{"Creator"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/modifier">{"Modifier"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/created">{"Created"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/modified">{"Modified"|i18n('content/class')}</a></th>
</tr>

{section name=Classes loop=$groupclasses sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Classes:sequence}" width="3%">{$Classes:item.id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.name}</td>
  <td class="{$Classes:sequence}">{$Classes:item.identifier}</td>
  <td class="{$Classes:sequence}">{$Classes:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$Classes:sequence}">{$Classes:item.creator_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modifier_id}</td>
  <td class="{$Classes:sequence}">{$Classes:item.created|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}">{$Classes:item.modified|l10n(shortdatetime)}</td>
  <td class="{$Classes:sequence}" width="1%"><a href="{$module.functions.edit.uri}/{$Classes:item.id}"><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$Classes:sequence}" width="1%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Classes:item.id}"></td>
</tr>
{/section}
</table>
{/section}

{section show=$temp_groupclasses}
<br/>
<br/>
<h3>{"Temporary class types for "|i18n('content/class')}{$group_name} </h3> 
<p>{"Items are sorted by modification date."|i18n('content/class')}
</p>
<table width="100%" cellspacing="0">
<tr>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/id">{"ID"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/name">{"Name"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/identifier">{"Identifier"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/status">{"Status"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/creator">{"Creator"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/modifier">{"Modifier"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/created">{"Created"|i18n('content/class')}</a></th>
  <th align="left"><a href="{$module.functions.classlist.uri}/{$GroupID}/modified">{"Modified"|i18n('content/class')}</a></th>
</tr>

{section name=TempClasses loop=$temp_groupclasses sequence=array(bglight,bgdark)}
<tr>
  <td class="{$TempClasses:sequence}" width="1%">{$TempClasses:item.id}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.name}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.identifier}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.version_status|choose("Temporary","Defined","Modified")}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.creator_id}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.modifier_id}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.created|l10n(shortdatetime)}</td>
  <td class="{$TempClasses:sequence}">{$TempClasses:item.modified|l10n(shortdatetime)}</td>
  <td class="{$TempClasses:sequence}" width="1%"><a href="{$module.functions.edit.uri}/{$TempClasses:item.id}"><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$TempClasses:sequence}" width="1%"><input type="checkbox" name="TempContentClass_id_checked[]" value="{$TempClasses:item.id}"></td>
</tr>
{/section}
</table>
{/section}

<table width="100%">
<tr>
  <td width="99%"></td>
  <td>{include uri="design:gui/button.tpl" name=new id_name=NewButton value="New Class"|i18n('content/class')}</td>
  <td>{include uri="design:gui/button.tpl" name=delete id_name=DeleteButton value="Delete"|i18n('content/class')}</td>
</tr>
</table>
<input type="hidden" name = "CurrentGroupID" value="{$GroupID}">
<input type="hidden" name = "CurrentGroupName" value="{$group_name}">
</form>