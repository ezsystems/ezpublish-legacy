<div class="warning">
<h2>Are you sure you will remove this(these) class(es)?</h2>
<ul>
{section name=Result loop=$DeleteResult}
	<li>Remove class "{$Result:item.className}" will remove {$Result:item.objectCount}!</li>
{/section}
</ul>
</div>
<form action={concat($module.functions.removeclass.uri,"/",$GroupID,"/",$ClassID)|ezurl} method="post" name="ClassRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}
</div>

</form>