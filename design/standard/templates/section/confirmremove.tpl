<div class="warning">
<h2>{"Are you sure you want to remove these sections?"|i18n("design/standard/section")}</h2>
<ul>
{section name=Result loop=$delete_result}
    <li> {$Result:item.name} - {$Result:item.id}</li>
{/section}
</ul>
Removing these sections can corrupt permisions, sitedesigns and a lot of other things in the system. You should be exactly sure what are you doing.
</div>

<form action={concat($module.functions.list.uri)|ezurl} method="post" name="SectionRemove">

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmRemoveSectionButton value="Confirm"|i18n("design/standard/section")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/standard/section")}
</div>

</form>
