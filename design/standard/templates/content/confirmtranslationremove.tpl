<form action={concat($module.functions.translations.uri)|ezurl} method="post" name="TranlationRemove">

<div class="warning">
<h2>{"Are you sure you want to remove this(these) translations(s)?"|i18n("design/standard/content")}</h2>
<ul>
{section name=Result loop=$confirm_list}
    <li>{"Removing"|i18n("design/standard/content")} "{$Result:item.translation.name}" {"will remove the translation itself and "|i18n("design/standard/content")} {$Result:item.count} {"translated versions!"|i18n("design/standard/content")}</li>
<input type="hidden" name="ConfirmTranlationID[]" value="{$Result:item.translation.id}" /> 
{/section}
</ul>
</div>


<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/content")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/content")}
</div>

</form>
