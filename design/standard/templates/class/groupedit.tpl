<form action={concat($module.functions.groupedit.uri,"/",$classgroup.id)|ezurl} method="post" name="GroupEdit">

<div class="maincontentheader">
<h1>{"Editing class group"|i18n("design/standard/class/edit")} - {$classgroup.name}</h1>
<div>

<div class="byline">
<p class="created">{"Created by"|i18n("design/standard/class/edit")} {$classgroup.creator_id} {"on"|i18n("design/standard/class/edit")} {$classgroup.created|l10n(shortdatetime)}</p>
<p class="modified">{"Modified by"|i18n("design/standard/class/edit")} {$classgroup.modifier_id} {"on"|i18n("design/standard/class/edit")} {$classgroup.modified|l10n(shortdatetime)}</p>
</div>

<div class="block">
<label>{"Name:"|i18n("design/standard/class/edit")}</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=Name id_name=Group_name value=$classgroup.name}
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value="Store"|i18n("design/standard/class/edit")}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value="Discard"|i18n("design/standard/class/edit")}
</div>

</form>
