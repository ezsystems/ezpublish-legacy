<form action={concat($module.functions.groupedit.uri,"/",$classgroup.id)|ezurl} method="post" name="GroupEdit">

<div class="maincontentheader">
<h1>Editing class group - {$classgroup.name}</h1>
<div>

<div class="byline">
<p class="created">Created by {$classgroup.creator_id} on {$classgroup.created|l10n(shortdatetime)}</p>
<p class="modified">Modified by {$classgroup.modifier_id} on {$classgroup.modified|l10n(shortdatetime)}</p>
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=Name id_name=Group_name value=$classgroup.name}
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>

</form>