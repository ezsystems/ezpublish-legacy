<form action={concat($module.functions.grouplist.uri)|ezurl} method="post" name="GroupList">

<h1>{"Defined workflow groups"|i18n('content/class')}</h1>
<table width="100%" cellspacing="0">
<tr>
  <th align="left"><a href={concat($module.functions.list.uri,"/id")|ezurl}>{"ID"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.list.uri,"/name")|ezurl}>{"Name"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.list.uri,"/creator")|ezurl}>{"Creator"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.list.uri,"/modifier")|ezurl}>{"Modifier"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.list.uri,"/created")|ezurl}>{"Created"|i18n('content/class')}</a></th>
  <th align="left"><a href={concat($module.functions.list.uri,"/modified")|ezurl}>{"Modified"|i18n('content/class')}</a></th>
</tr>

{section name=Groups loop=$groups sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Groups:sequence}" width="3%">{$Groups:item.id}</td>
  <td class="{$Groups:sequence}"><a href={concat($module.functions.workflowlist.uri,"/",$Groups:item.id)|ezurl}>{$Groups:item.name}</a></td>
  <td class="{$Groups:sequence}">{$Groups:item.creator_id}</td>
  <td class="{$Groups:sequence}">{$Groups:item.modifier_id}</td>
  <td class="{$Groups:sequence}">{$Groups:item.created|l10n(shortdatetime)}</td>
  <td class="{$Groups:sequence}">{$Groups:item.modified|l10n(shortdatetime)}</td>
  <td class="{$Groups:sequence}" width="3%"><a href={concat($module.functions.groupedit.uri,"/",$Groups:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$Groups:sequence}" width="3%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Groups:item.id}"></td>
</tr>
{/section}
</table>
<table width="100%">
<tr>
  <td>{include uri="design:gui/button.tpl" name=newgroup id_name=NewGroupButton value="New Group"|i18n('content/class')}</td>
  <td>{include uri="design:gui/button.tpl" name=deletegroup id_name=DeleteGroupButton value="Delete"|i18n('content/class')}</td>
  <td width="99%"></td>
</tr>
</table>

</form>