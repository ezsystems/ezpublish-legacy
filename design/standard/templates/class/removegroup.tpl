<form action={concat($module.functions.removegroup.uri,"/",$GroupID)|ezurl} method="post" name="GroupRemove">

<div class="maincontentheader">
<h1>Remove group - {$GroupName}</h1>
</div>

<div class="important">
<p>Are you sure you will remove {$GroupName}?</p>
<p>Remove group "{$GroupName}" will remove {$ClassCount}!</p>
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}
</div>

</form>