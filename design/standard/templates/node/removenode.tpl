<div class="warning">
<h2 class="warning">Are you sure you will remove {$object.name} from node {$node.object.name}?</h2>
<ul class="warning">
	<li>Delete this assignment  will remove it's {$ChildObjectsCount}!</li>
</ul>
</div>


<form enctype="multipart/form-data" method="post" action={concat("/content/removenode/",$object.id,"/",$edit_version,"/",$node.node_id,"/")|ezurl}>

<h1>Deleting node assignment of {$object.name}  </h1>

<input type="hidden" name=RemoveNodeID value={$node.node_id} />
<table width="100%">
<tr>
<td>{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value=Confirm}</td>
<td>{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value=Cancel}</td>
<td width="99%"></td>
</tr>
</table>


</form>
