<div class="warning">
<h2>{"Are you sure you want to remove these classes?"|i18n("design/standard/class/edit")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
	<li>{"Removing class %1 will remove %2!"|i18n("design/standard/class/edit",,array($Result:item.className|wash,$Result:item.objectCount))}</li>
{/section}
</ul>
</div>
<form action={concat($module.functions.removeclass.uri,"/",$GroupID,"/",$ClassID)|ezurl} method="post" name="ClassRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/class/edit")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/class/edit")}
</div>

</form>
