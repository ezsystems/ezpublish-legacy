<form action="{$module.functions.edit.uri}/{$workflow.id}" method="post" name="WorkflowEdit">

<h1>Editing workflow - {$workflow.name}</h1>
<p>Created by {$workflow.creator_id} on {$workflow.created|l10n(shortdatetime)}</p>
<p>Modified by {$workflow.modifier_id} on {$workflow.modified|l10n(shortdatetime)}</p>
<table>
<tr><td>Name:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=Name id_name=Workflow_name value=$workflow.name}</td></tr>
<tr><td>Workflow Type: {$workflow.workflow_type.name}</td></tr>

<tr><td>

{section show=$can_store}
<p class="important">Workflow stored</p>
{/section}
{section show=$require_fixup}
<p class="important">Data requires fixup</p>
{/section}

<h2>Events</h2>
<hr/>
<table cellspacing="0" width="100%">
<tr>
    <th>Pos</th>
    <th>Description</th>
    <th>Type</th>
</tr>
{section name=Event loop=$event_list sequence=array(bglight,bgdark)}
<input type="hidden" name="WorkflowEvent_id[]" value="{$Event:item.id}" />
<input type="hidden" name="WorkflowEvent_placement[]" value="{$Event:item.placement}" />
<tr>
    <td width="1%">{$Event:number}({$Event:item.placement})</td>
    <td>{include uri="design:gui/lineedit.tpl" name=EventDescription id_name="WorkflowEvent_description[]" value=$Event:item.description}</td>
    <td>{$Event:item.workflow_type.group_name}/{$Event:item.workflow_type.name}</td>
    <td width="1%"><a href="{$module.functions.down.uri}/{$workflow.id}/{$Event:item.id}"><img src={"move-down.gif"|ezimage} height="12" width="12" border="0" alt="Down" /></a></td>
    <td width="1%"><a href="{$module.functions.up.uri}/{$workflow.id}/{$Event:item.id}"><img src={"move-up.gif"|ezimage} height="12" width="12" border="0" alt="Up" /></a></td>
    <td width="1%"><input type="checkbox" name="WorkflowEvent_id_checked[]" value="{$Event:item.id}" /></td>
</tr>
<tr>
    <td></td>
    <td colspan="5">{event_edit_gui event=$Event:item}</td>
</tr>
{/section}
</table>

<hr/>

<table width="100%">
<tr>
    <td width="99%"></td>
    <td>{include uri="design:gui/button.tpl" name=New id_name=NewButton value="New Event"}</td>
    <td>{include uri="design:workflow/workflowtypes.tpl" name=WorkflowTypes id_name=WorkflowTypeString workflowtypes=$workflow_type_list current=$workflow_type}</td>
    <td>{include uri="design:gui/button.tpl" name=Delete id_name=DeleteButton value=Delete}</td>
</tr>
</table>
<table width="100%">
<tr>
    <td width="99%"></td>
    <td>{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}</td>
    <td>{include uri="design:gui/button.tpl" name=Apply id_name=ApplyButton value=Apply}</td>
    <td>{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}</td>
</tr>
</table>

</td></tr>
</table>


</form>