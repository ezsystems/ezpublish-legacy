<div class="warning">
<h2>{"Are you sure you want to discard the draft?"|i18n("design/standard/content/edit")}</h2>
</div>
<form action={"content/removeeditversion"|ezurl} method="post" name="EditVersionRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/standard/content/edit")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/content/edit")}
</div>

</form>
