<div class="warning">
<h2>{"Are you sure you want to remove these nodes?"|i18n("design/standard/node")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
    <li>{"Removing %1 will remove the node itself and it's %2 children!"|i18n("design/standard/node",,hash("%1",$Result:item.nodeName,"%2",$Result:item.childCount))}</li>
{/section}
</ul>
</div>

<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/node")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/node")}
</div>

</form>
