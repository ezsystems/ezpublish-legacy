<form action={concat($module.functions.discountgroupedit.uri,"/",$discount_group.id)|ezurl} method="post" name="DiscountGroupEdit">

<div class="maincontentheader">
<h1>Editing discount group - {$discount_group.name}</h1>
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
<input type="text" name="discount_group_name" value="{$discount_group.name}" size=40>
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Apply id_name=ApplyButton value=Apply}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>
</form>