<div class="warning">
<h2>{"Are you sure you want to remove these nodes?"|i18n("design/standard/node")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
    <li>{"Removing %1 will remove the node itself and it's %2 children. %3"|i18n("design/standard/node",,array($Result:item.nodeName,$Result:item.childCount,$Result:item.additionalWarning))}</li>
{/section}
</ul>
</div>

<p><b>{"Note:"|i18n("design/standard/node")}</b> {"Removed nodes can be retrieved later. You will find them in the trash."|i18n("design/standard/node")}</p>
<br/>


<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/node")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/node")}
</div>

</form>
