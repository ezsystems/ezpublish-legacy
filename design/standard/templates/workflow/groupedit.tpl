<form action={concat($module.functions.groupedit.uri,"/",$workflow_group.id)|ezurl} method="post" name="WorkflowGroupEdit">

<div class="maincontentheader">
<h1>Editing workflow group - {$workflow_group.name}</h1>
</div>

<div class="byline">
<p class="created">Created by {$workflow_group.creator_id} on {$workflow_group.created|l10n(shortdatetime)}</p>
<p class="modified">Modified by {$workflow_group.modifier_id} on {$workflow_group.modified|l10n(shortdatetime)}</p>
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=Name id_name=WorkflowGroup_name value=$workflow_group.name}
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>

</form>