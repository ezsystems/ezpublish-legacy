<form action={concat($module.functions.removeclass.uri,"/",$GroupID,"/",$ClassID)|ezurl} method="post" name="ClassRemove">

<div class="maincontentheader">
<h1>Remove class - {$ClassName}</h1>
</div>

<p>Are you sure you will remove class {$ClassName}?</p>
<p>Remove class "{$ClassName}" will remove {$ClassObjectsCount} objects!</p>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}
</div>

</form>