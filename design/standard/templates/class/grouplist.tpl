<form action={"class/grouplist"|ezurl} method="post" name="GroupList">

<h1>{"Defined class groups"|i18n('content/class')}</h1>

<table width="100%" cellspacing="0">
<tr>
  <th align="left">{"ID"|i18n('content/class')}</th>
  <th align="left">{"Name"|i18n('content/class')}</th>
  <th align="left">{"Creator"|i18n('content/class')}</th>
  <th align="left">{"Modifier"|i18n('content/class')}</th>
  <th align="left">{"Created"|i18n('content/class')}</th>
  <th align="left">{"Modified"|i18n('content/class')}</th>
</tr>

{section name=Group loop=$groups sequence=array(bglight,bgdark)}
<tr>
  <td class="{$Group:sequence}" width="3%">{$Group:item.id}</td>
  <td class="{$Group:sequence}"><a href={concat($module.functions.classlist.uri,"/",$Group:item.id)|ezurl}>{$Group:item.name}</a></td>
  <td class="{$Group:sequence}">{$Group:item.creator_id}</td>
  <td class="{$Group:sequence}">{$Group:item.modifier_id}</td>
  <td class="{$Group:sequence}">{$Group:item.created|l10n(shortdatetime)}</td>
  <td class="{$Group:sequence}">{$Group:item.modified|l10n(shortdatetime)}</td>
  <td class="{$Group:sequence}" width="3%"><a href={concat($module.functions.groupedit.uri,"/",$Group:item.id)|ezurl}><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>
  <td class="{$Group:sequence}" width="3%"><input type="checkbox" name="ContentClass_id_checked[]" value="{$Group:item.id}"></td>
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