<div class="warning">
<h2>{"Are you sure you want to remove"|i18n("design/standard/node")} {$object.name} {"from node"|i18n("design/standard/node")} {$node.object.name}{"?"|i18n("design/standard/node")}</h2>
<ul>
    <li>{"Removing this assignment will also remove it's"|i18n("design/standard/node")} {$ChildObjectsCount} {"!"|i18n("design/standard/node")}</li>
</ul>
</div>


<form enctype="multipart/form-data" method="post" action={concat("/content/removenode/",$object.id,"/",$edit_version,"/",$node.node_id,"/")|ezurl}>

<h1>{"Removing node assignment of"|i18n("design/standard/node")} {$object.name}</h1>

<input type="hidden" name=RemoveNodeID value={$node.node_id} />
<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/node")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/node")}
</div>
</form>
