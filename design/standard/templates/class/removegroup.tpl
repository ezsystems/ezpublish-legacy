<div class="warning">
<h2>Are you sure you will remove this(these) group(s)?</h2>
<ul>
{section name=Result loop=$DeleteResult}
	<li>Remove group "{$Result:item.groupName}" will remove {$Result:item.deletedClassName}!</li>
{/section}
</ul>
</div>
<form action={concat($module.functions.removegroup.uri)|ezurl} method="post" name="GroupRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}
</div>

</form>

