<form action={concat($module.functions.discountruleedit.uri,"/",$discount_rule.id)|ezurl} method="post" name="DiscountRuleEdit">

<div class="maincontentheader">
<h1>Editing discount rule - {$discount_rule.name}</h1>
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
<input type="text" name="discount_name" value="{$discount_rule.name}" size=20>
</div>
<div class="block">
<label>Discount percent:</label><div class="labelbreak">
<input type="text" name="discount_percent" value="{$discount_rule.discount_percent}" size=3>%
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>
</form>