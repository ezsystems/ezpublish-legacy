<div class="warning">
<h2>{"Are you sure you want to remove this(these) group(s)?"|i18n("design/standard/class/edit")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
	<li>{"Remove group"|i18n("design/standard/class/edit")} "{$Result:item.groupName}" {"will remove"|i18n("design/standard/class/edit")} {$Result:item.deletedClassName}!</li>
{/section}
</ul>
</div>
<form action={concat($module.functions.removegroup.uri)|ezurl} method="post" name="GroupRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/class/edit")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/class/edit")}
</div>

</form>
