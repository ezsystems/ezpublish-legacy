<div class="warning">
<h2 class="warning">Are you sure you will remove class {$ClassName}?</h2>
<ul class="warning">
	<li>Remove class "{$ClassName}" will remove {$ClassObjectsCount} objects!</li>
</ul>
</div>

<form action={concat($module.functions.removeclass.uri,"/",$GroupID,"/",$ClassID)|ezurl} method="post" name="ClassRemove">
<h1>Remove class - {$ClassName}</h1>


<table width="100%">
<tr>
<td>{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}</td>
<td>{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}</td>
<td width="99%"></td>
</tr>
</table>

</td></tr>
</table>

</form>