<div class="warning">
<h2 class="warning">Are you sure you will remove {$GroupName}?</h2>
<ul class="warning">
	<li>Remove group "{$GroupName}" will remove {$ClassCount}!</li>
</ul>
</div>

<form action={concat($module.functions.removegroup.uri,"/",$GroupID)|ezurl} method="post" name="GroupRemove">
<h1>Remove group - {$GroupName}</h1>

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