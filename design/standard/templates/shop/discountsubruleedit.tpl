<form action={concat($module.functions.discountsubruleedit.uri,"/",$discountrule_id,"/",$discountsubrule.id)|ezurl} method="post" name="DiscountSubRuleEdit">

<div class="maincontentheader">
<h1>Editing sub rule</h1>
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
<input type="text" name="discountsubrule_name" value="{$discountsubrule.name}" size=40>
</div>
<div class="block">
<label>Discount percent:</label><div class="labelbreak"></div>
<input type="text" name="discountsubrule_percent" value="{$discountsubrule.discount_percent}" size=4>%
</div>
<p>Choose which classes or sections applied to this sub rule, <b>ANY</b> means the rule will applied to all.</p>
<div class="block">
<div class="element">
     <label>Class:</label><div class="labelbreak"></div>
     <select name="Contentclasses[]" size="5" multiple >
     <option value="-1" {section show=$class_any_selected}selected="selected"{/section} >Any</option>
     {section name=Classes loop=$product_class_list}
     <option value="{$Classes:item.id}" {switch match=$Sections:item.id}{case in=$section_limitation_list} selected="selected"{/case}{case}{/case}{/switch}>{$Classes:item.name}</option>
     {/section}
     </select>
</div>
<div class="element">
     <label>Section:</label><div class="labelbreak"></div>
     <select name="Sections[]" size="5" multiple >
     <option value="-1" {section show=$class_any_selected}selected="selected"{/section}>Any</option>
     {section name=Sections loop=$section_list}
     <option value="{$Sections:item.id}" {switch match=$Sections:item.id}{case in=$section_limitation_list} selected="selected"{/case}{case}{/case}{/switch}>{$Sections:item.name}

</option>
     {/section}
     </select>
</div>
<div class="break"></div>
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>
</form>