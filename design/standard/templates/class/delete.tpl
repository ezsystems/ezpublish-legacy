<div class="warning">
<h2 class="warning">Are you sure you will delete class {$ClassName}?</h2>
<ul class="warning">
	<li>Delete this class will cause {$ClassObjectsCount} removed!</li>
</ul>
</div>

<form action="{$module.functions.delete.uri}/{$GroupID}/{$ClassID}" method="post" name="ClassDelete">

<h1>Deleting class - {$ClassName}</h1>


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