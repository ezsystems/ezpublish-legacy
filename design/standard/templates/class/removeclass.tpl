<div class="warning">
<h2>{"Are you sure you want to remove this(these) class(es)?"|i18n("design/standard/class/edit")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
	<li>{"Remove class"|i18n("design/standard/class/edit")} "{$Result:item.className}" {"will remove"|i18n("design/standard/class/edit")} {$Result:item.objectCount}!</li>
{/section}
</ul>
</div>
<form action={concat($module.functions.removeclass.uri,"/",$GroupID,"/",$ClassID)|ezurl} method="post" name="ClassRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/class/edit")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/class/edit")}
</div>

</form>
