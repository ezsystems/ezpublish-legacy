<form action={concat($module.functions.discountruleedit.uri,"/",$discount_rule.id)|ezurl} method="post" name="DiscountRuleEdit">

<div class="maincontentheader">
<h1>Editing discount rule - {$discount_rule.name}</h1>
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
<input type="text" name="discount_name" value="{$discount_rule.name}" size=40>
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Apply id_name=ApplyButton value=Apply}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>
</form>