<form action="{$module.functions.groupedit.uri}/{$workflow_group.id}" method="post" name="WorkflowGroupEdit">

<h1>Editing workflow group - {$workflow_group.name}</h1>

{section show=$is_remove_tried|not}

<p>Created by {$workflow_group.creator_id} on {$workflow_group.created|l10n(shortdatetime)}</p>
<p>Modified by {$workflow_group.modifier_id} on {$workflow_group.modified|l10n(shortdatetime)}</p>
<table>
<tr><td>Name:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=Name id_name=WorkflowGroup_name value=$workflow_group.name}</td></tr>

<tr><td>
</table>


<table width="100%">
<tr>
    <td>{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}</td>
    <td>{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}</td>
    <td width="99%"></td>
</tr>
</table>

</td></tr>
</table>

</form>