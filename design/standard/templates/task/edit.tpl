<form action={concat("task/edit/",$task.id)|ezurl} method="post" name="TaskEdit">

<h1>Creating new task</h1>

<h1>
{section show=$task.task_type|eq(1)}
{"Task"|i18n('task')}
{section-else}
{"Assignment"|i18n('task')}
{/section}
</h1>

<table cellpadding="0">
<tr><td>Date:</td><td>{$task.created|l10n('shortdatetime')} ({$task.modified|l10n('shortdatetime')})</td></tr>
<tr><td>From:</td><td><b>{content_view_gui view=text_linked content_object=$task.creator.contentobject}</b>
<td></tr>
<tr><td>To:</td><td>
{section show=$task.receiver_id|gt(0)}
<b>{content_view_gui view=text_linked content_object=$task.receiver.contentobject}</b>
{section-else}
{"Please select receiver"|i18n('task')}
{/section}
</td></tr>
</table>

{section show=$task.parent_task_id|gt(0)}
Parent type: {$task.parent_task_type|choose('None','Task','Workflow')}<br/>
Parent ID: <a href={concat("task/list/",,$task.parent_task_id)|ezurl}>{$task.parent_task_id}</a><br/>
{/section}

{section show=$task.task_type|eq(2)}
<h3>{"Assignment"|i18n('task')}</h3>
<table cellspacing="0">
<tr><td>Access Type:</td>
<td>Object Type:</td></tr>
<tr><td><select name="Task_access_type">
<option value="1"{$task.access_type|eq(1)|choose('','selected="selected"')}>{"Read"|i18n('task')}</option>
<option value="2"{$task.access_type|eq(2)|choose('','selected="selected"')}>{"Read/Write"|i18n('task')}</option>
</select></td><td><select name="Task_object_type">
<option value="1"{$task.object_type|eq(1)|choose('','selected="selected"')}>{"Content Object"|i18n('task')}</option>
<option value="2"{$task.object_type|eq(2)|choose('','selected="selected"')}>{"Content Class"|i18n('task')}</option>
<option value="3"{$task.object_type|eq(3)|choose('','selected="selected"')}>{"Work Flow"|i18n('task')}</option>
<option value="4"{$task.object_type|eq(4)|choose('','selected="selected"')}>{"Role"|i18n('task')}</option>
</select></td><tr/>
<tr><td>
  {section show=$task.object_id|gt(0)}
  Assignment for object: {$task.object_id}
  {/section}

  <br/>
  {include uri="design:gui/button.tpl" name=SelectObject id_name=SelectObjectButton value="Choose Object"|i18n('task')}
</td></tr>
</table>
{/section}

<hr/>

<table width="100%">
<tr>
<td>
{section show=$task.task_type|eq(1)}
<input class="stdbutton" type="submit" Name="SetAssignmentButton" value="Convert To Assignment">
{section-else}
<input class="stdbutton" type="submit" Name="SetTaskButton" value="Convert To Task">
{/section}
</td>
  <td>&nbsp;&nbsp;</td>
  <td>{include uri="design:gui/button.tpl" name=SelectReceiver id_name=SelectReceiverButton value="Change Receiver"|i18n('task')}</td>
  <td>&nbsp;&nbsp;</td>
  <td>{include uri="design:gui/button.tpl" name=SendTask id_name=SendTaskButton value="Activate"|i18n('task')}</td>
  <td>{include uri="design:gui/button.tpl" name=DiscardTask id_name=DiscardTaskButton value="Discard"|i18n('task')}</td>
  <td width="99%"></td>
</tr>
</table>

</form>
