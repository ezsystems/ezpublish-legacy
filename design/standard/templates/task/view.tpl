{section show=$task_id|eq(0)}
<form action={concat("task/view/")|ezurl} method="post" name="TaskList">
{section-else}
<form action={concat("task/view/",$task_id)|ezurl} method="post" name="TaskList">
{/section}

<div class="maincontentheader">
{section show=$task_id|gt(0)}
<h1>Task view</h1>
{section-else}
<h1>Task list</h1>
{/section}
</div>

<p>
{section show=$task_id|gt(0)}
  {section show=$task.parent_task_id|gt(0)}
<a href={concat("task/view/",$task.parent_task_id)|ezurl}>Parent</a>
  {section-else}
<a href={concat("task/view/")|ezurl}>Parent</a>
  {/section}
{/section}
</p>

{sequence name=MessageSeq loop=array('bglight','bgdark')}

{section show=$task_id|gt(0)}

<div class="block">
<div class="element">
<label>From:</label><div class="labelbreak"></div>
{content_view_gui view=text_linked content_object=$task.creator.contentobject}
</div>
<div class="element">
<label>To:</label><div class="labelbreak"></div>
{content_view_gui view=text_linked content_object=$task.receiver.contentobject}
</div>
<div class="break"></div>
</div>

<div class="block">
<div class="element">
<label>Status:</label><div class="labelbreak"></div>
{$task.status|choose('None','Temporary','Open','Closed','Cancelled')}
</div>
<div class="element">
<label>Date:</label><div class="labelbreak"></div>
{$task.created|l10n('shortdatetime')}
</div>
<div class="break"></div>
</div>

<h2>Message</h2>

  {section name=Message loop=$task.messages max=1}
    {section show=$Message:item.contentobject_id|gt(0)}
      {let object=$Message:item.contentobject}
        <div class="block">
        {content_view_gui view=$view_type content_object=$Message:object}
        </div>
      {/let}
    {/section}
  {sequence name=MessageSeq}
  {/section}

{section name=Message loop=$task.messages|gt(1)}
{* <h2>Messages</h2> Why is this subheadline here, when already used above? *}
  {section name=Message loop=$task.messages offset=1}
    {section show=$Message:item.contentobject_id|gt(0)}
      {let object=$Message:item.contentobject}
        <div class="block">
        <label>From:</label><div class="labelbreak"></div>
        {$Message:item.creator.login}
        </div>
        <div class="block">
        {content_view_gui view=$view_type content_object=$Message:object}
        </div>
      {/let}
    {/section}
  {sequence name=MessageSeq}
  {/section}
{/section}

{/section}

{section show=$task_id|eq(0)}
<h2>Incoming</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="1%">ID:</th>
    <th>Title:</th>
    <th>Type:</th>
    <th>Status:</th>
    <th>Creator:</th>
    <th>Created:</th>
    <th colspan="2">Modified:</th>
</tr>
{section name=Incoming loop=$incoming_task_list sequence=array('bglight','bgdark')}
<tr>
    <td class="{$Incoming:sequence}"><a href={concat("task/view/",$Incoming:item.id)|ezurl}>{$Incoming:item.id}</a></td>
    <td class="{$Incoming:sequence}">
{section show=$Incoming:item.task_type|eq(1)}
{let message=$Incoming:item.first_message}
  {section show=$Incoming:message}
    <a href={concat("task/view/",$Incoming:item.id)|ezurl}>{$Incoming:message.name}</a>
  {/section}
{/let}
{section-else}
  {section show=$Incoming:item.object_id|gt(0)}
  Assigned object '{content_view_gui view=text_linked content_object=$Incoming:item.contentobject}'
    {section show=$Incoming:item.access_type|eq(2)}
    <a href={concat('/content/edit/',$Incoming:item.object_id)|ezurl(double)}>[ edit ]</a>
    {/section}
  {/section}
{/section}
  </td>
  <td class="{$Incoming:sequence}">{$Incoming:item.task_type|choose('None','Task','Assignment')}</td>
  <td class="{$Incoming:sequence}">{$Incoming:item.status|choose('None','Temporary','Open','Closed','Cancelled')}</td>
  <td class="{$Incoming:sequence}">{content_view_gui view=text_linked content_object=$Incoming:item.creator.contentobject}</td>
  <td class="{$Incoming:sequence}"><span class="small">{$Incoming:item.created|l10n('shortdatetime')}</span></td>
  <td class="{$Incoming:sequence}"><span class="small">{$Incoming:item.modified|l10n('shortdatetime')}</span></td>
  <td class="{$Incoming:sequence}" width="1%"><input type="checkbox" name="Task_id_checked[]" value="{$Incoming:item.id}"></td>
</tr>
{/section}
</table>
{/section}


{section show=$task_id|eq(0)}
<h2>Outgoing</h2>
{section-else}
  {section show=$outgoing_task_list|gt(0)}
<h2>Sub tasks</h2>
  {/section}
{/section}
  {section show=$outgoing_task_list|gt(0)}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="1%">ID:</th>
    <th>Title:</th>
    <th>Type:</th>
    <th>Status:</th>
    <th>Receiver:</th>
    <th>Created:</th>
    <th>Modified:</th>
</tr>
  {/section}
{section name=Outgoing loop=$outgoing_task_list sequence=array('bglight','bgdark')}
<tr>
  <td class="{$Outgoing:sequence}"><a href={concat("task/view/",$Outgoing:item.id)|ezurl}>{$Outgoing:item.id}</a></td>
  <td class="{$Outgoing:sequence}">
{section show=$Outgoing:item.task_type|eq(1)}
{let message=$Outgoing:item.first_message}
  {section show=$Outgoing:message}
  <a href={concat("task/view/",$Outgoing:item.id)|ezurl}>{$Outgoing:message.name}</a>
  {/section}
{/let}
{section-else}
  {section show=$Outgoing:item.object_id|gt(0)}
  Assigned object '{content_view_gui view=text_linked content_object=$Outgoing:item.contentobject}'
  {/section}
{/section}
  </td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.task_type|choose('None','Task','Assignment')}</td>
  <td class="{$Outgoing:sequence}">{$Outgoing:item.status|choose('None','Temporary','Open','Closed','Cancelled')}</td>
  <td class="{$Outgoing:sequence}">{content_view_gui view=text_linked content_object=$Outgoing:item.receiver.contentobject}</td>
  <td class="{$Outgoing:sequence}"><span class="small">{$Outgoing:item.created|l10n('shortdatetime')}</span></td>
  <td class="{$Outgoing:sequence}"><span class="small">{$Outgoing:item.modified|l10n('shortdatetime')}</span></td>
  <td class="{$Outgoing:sequence}" width="1%"><input type="checkbox" name="Task_id_checked[]" value="{$Outgoing:item.id}"></td>
</tr>
{/section}
</table>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=NewTask id_name=NewTaskButton value="New Task"|i18n('task')}
{include uri="design:gui/button.tpl" name=NewAssignment id_name=NewAssignmentButton value="New Assignment"|i18n('task')}
{include uri="design:gui/button.tpl" name=NewMessage id_name=NewMessageButton value="New Message"|i18n('task')}
<select name="ClassID">
{section name=Classes loop=$class_list}
<option value="{$Classes:item.id}">{$Classes:item.name}</option>
{/section}
</select>
{include uri="design:gui/button.tpl" name=CloseTask id_name=CloseTaskButton value="Close Task"|i18n('task')}
{include uri="design:gui/button.tpl" name=CancelTask id_name=CancelTaskButton value="Cancel Task"|i18n('task')}
</div>

</form>
