<div class="warning">
<h2 class="warning">Are you sure you will remove {$NodeName}?</h2>
<ul class="warning">
	<li>Remove "{$NodeName}" will remove itself and its {$ChildObjectsCount}!</li>
</ul>
</div>

<form action={concat($module.functions.removeobject.uri,"/",$NodeID)|ezurl} method="post" name="ObjectRemove">
<h1>Remove - {$NodeName}</h1>

<table width="100%">
<tr>
<td>{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}</td>
<td>{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}</td>
<td width="99%"></td>
</tr>
</table>
</form>