<form action="{$module.functions.edit.uri}/{$task.id}" method="post" name="TaskEdit">

<h1>Creating new task</h1>

{section show=$task.task_type|eq(1)}
Task
<input class="stdbutton" type="submit" Name="SetAssignmentButton" value="Make Assignment">
{section-else}
<input class="stdbutton" type="submit" Name="SetTaskButton" value="Make Task">
Assignment
{/section}

<br/>

Created by: {$task.creator.login}<br/>
{section show=$task.receiver_id|gt(0)}
Assigned to: {$task.receiver.login}
{/section}
{include uri="design:gui/button.tpl" name=SelectReceiver id_name=SelectReceiverButton value="Assign"|i18n('task')}<br/>
<br/>
Created at: {$task.created|l10n('shortdatetime')}<br/>
Modified at: {$task.modified|l10n('shortdatetime')}<br/>

{section show=$task.parent_task_id|gt(0)}
Parent type: {$task.parent_task_type|choose('None','Task','Workflow')}<br/>
Parent ID: <a href="{$module.functions.list.uri}/{$task.parent_task_id}">{$task.parent_task_id}</a><br/>
{/section}

{section show=$task.task_type|eq(2)}
Access Type:<br/>
<select name="Task_access_type">
<option value="1"{$task.access_type|eq(1)|choose('','selected="selected"')}>{"Read"|i18n('task')}</option>
<option value="2"{$task.access_type|eq(2)|choose('','selected="selected"')}>{"Read/Write"|i18n('task')}</option>
</select><br/>
Object Type:<br/>
<select name="Task_object_type">
<option value="1"{$task.object_type|eq(1)|choose('','selected="selected"')}>{"Content Object"|i18n('task')}</option>
<option value="2"{$task.object_type|eq(2)|choose('','selected="selected"')}>{"Content Class"|i18n('task')}</option>
<option value="3"{$task.object_type|eq(3)|choose('','selected="selected"')}>{"Work Flow"|i18n('task')}</option>
<option value="4"{$task.object_type|eq(4)|choose('','selected="selected"')}>{"Role"|i18n('task')}</option>
</select><br/>
<p/>
{section show=$task.object_id|gt(0)}
Assignment for object: {$task.object_id}
{/section}
{include uri="design:gui/button.tpl" name=SelectObject id_name=SelectObjectButton value="Choose Object"|i18n('task')}<br/>

{/section}

<hr/>

<table width="100%">
<tr>
  <td>{include uri="design:gui/button.tpl" name=SendTask id_name=SendTaskButton value="Send"|i18n('task')}</td>
  <td>{include uri="design:gui/button.tpl" name=DiscardTask id_name=DiscardTaskButton value="Discard"|i18n('task')}</td>
  <td width="99%"></td>
</tr>
</table>

</form>
