<div class="warning">
<h2>Are you sure you will remove this(these) node(s)?</h2>
<ul>
{section name=Result loop=$DeleteResult}
	<li>Remove "{$Result:item.nodeName}" will remove itself and its {$Result:item.childCount}!</li>
{/section}
</ul>
</div>

<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}
</div>

</form>