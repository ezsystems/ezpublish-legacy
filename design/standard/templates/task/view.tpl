<form action="{$module.functions.view.uri}" method="post" name="TaskList">

{section show=$task_id|gt(1)}
<h1>Task view</h1>
{section-else}
<h1>Task list</h1>
{/section}

<table cellspacing="0" width="100%">
<tr><td colspan="6"><h2>Incoming</h2></td></tr>
<tr>
  <th>ID</th><th>Title</th><th>Type</th><th>Status</th><th>Creator</th><th>Created</th><th colspan="2">Modified</th>
</tr>
{section name=Incoming loop=$incoming_task_list sequence=array('bglight','bgdark')}
<tr>
  <td class="{$Incoming:sequence}"><a href="{$module.functions.view.uri}/{$Incoming:item.id}">{$Incoming:item.id}</a></td>
  <td class="{$Incoming:sequence}">
{section show=$Incoming:item.task_type|eq(1)}
{let message=$Incoming:item.first_message}
  {section show=$Incoming:message}
  {$Incoming:message.name}
  {/section}
{/let}
{section-else}
  {section show=$Incoming:item.object_id|gt(0)}
  Assigned object '{$Incoming:item.contentobject.name}'
  {/section}
{/section}
  </td>
  <td class="{$Incoming:sequence}">{$Incoming:item.task_type|choose('None','Task','Assignment')}</td>
  <td class="{$Incoming:sequence}">{$Incoming:item.status|choose('None','Temporary','Open','Closed','Cancelled')}</td>
  <td class="{$Incoming:sequence}">{$Incoming:item.creator.login}</td>
  <td class="{$Incoming:sequence}">{$Incoming:item.created|l10n('shortdatetime')}&nbsp;&nbsp;</td>
  <td class="{$Incoming:sequence}">{$Incoming:item.modified|l10n('shortdatetime')}</td>
  <td class="{$Incoming:sequence}" width="1%"><input type="checkbox" name="Task_id_checked[]" value="{$Incoming:item.id}"></td>
</tr>
{/section}

<tr><td colspan="6"><h2>Outgoing</h2></td></tr>
<tr>
  <th>ID</th><th>Title</th><th>Type</th><th>Status</th><th>Receiver</th><th>Created</th><th>Modified</th>
</tr>
{section name=Outgoing loop=$outgoing_task_list sequence=array('bglight','bgdark')}
<tr>
  <td class="{$Outgoing:sequence}"><a href="{$module.functions.view.uri}/{$Outgoing:item.id}">{$Outgoing:item.id}</a></td>
  <td class="{$Outgoing:sequence}">
{section show=$Outgoing:item.task_type|eq(1)}
{let message=$Outgoing:item.first_message}
  {section show=$Outgoing:message}
  {$Outgoing:message.name}
  {/section}
{/let}
{section-else}
  {section show=$Outgoing:item.object_id|gt(0)}
  Assigned object '{$Outgoing:item.contentobject.name}'
  {/section}
{/section}
  </td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.task_type|choose('None','Task','Assignment')}</td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.status|choose('None','Temporary','Open','Closed','Cancelled')}</td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.receiver.login}</td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.created|l10n('shortdatetime')}&nbsp;&nbsp;</td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.modified|l10n('shortdatetime')}</td>
  <td class="{$Outgoing:sequence}" width="1%"><input type="checkbox" name="Task_id_checked[]" value="{$Outgoing:item.id}"></td>
</tr>
{/section}
</table>

<table width="100%">
<tr>
  <td>{include uri="design:gui/button.tpl" name=NewTask id_name=NewTaskButton value="New Task"|i18n('task')}</td>
  <td>{include uri="design:gui/button.tpl" name=NewAssignment id_name=NewAssignmentButton value="New Assignment"|i18n('task')}</td>
  <td>&nbsp;&nbsp;&nbsp;</td>
  <td>{include uri="design:gui/button.tpl" name=NewMessage id_name=NewMessageButton value="New Message"|i18n('task')}</td>
  <td>{include uri="design:gui/button.tpl" name=CloseTask id_name=CloseTaskButton value="Close Task"|i18n('task')}</td>
  <td>{include uri="design:gui/button.tpl" name=CancelTask id_name=CancelTaskButton value="Cancel Task"|i18n('task')}</td>
  <td width="99%"></td>
</tr>
</table>

</form>
