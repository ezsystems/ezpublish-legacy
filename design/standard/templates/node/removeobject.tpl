<div class="warning">
<h2>{"Are you sure you want to remove this(these) node(s)?"|i18n("design/standard/node")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
    <li>{"Removing"|i18n("design/standard/node")} "{$Result:item.nodeName}" {"will remove the node itself and it's"|i18n("design/standard/node")} {$Result:item.childCount}{"!"|i18n("design/standard/node")}</li>
{/section}
</ul>
</div>

<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/node")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/node")}
</div>

</form>
