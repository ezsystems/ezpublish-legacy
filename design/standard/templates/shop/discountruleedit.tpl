<form action={concat($module.functions.discountruleedit.uri,"/",$discountgroup_id,"/",$discountrule.id)|ezurl} method="post" name="DiscountRuleEdit">

<div class="maincontentheader">
<h1>{"Editing rule"|i18n("design/standard/shop")}</h1>
</div>

<div class="block">
<label>{"Name:"|i18n("design/standard/shop")}</label><div class="labelbreak"></div>
<input type="text" name="discountrule_name" value="{$discountrule.name}" size=40>
</div>
<div class="block">
<label>{"Discount percent:"|i18n("design/standard/shop")}</label><div class="labelbreak"></div>
<input type="text" name="discountrule_percent" value="{$discountrule.discount_percent}" size=4>%
</div>
<p>{"Choose which classes or sections applied to this sub rule, 'Any' means the rule will applied to all."|i18n("design/standard/shop")}</p>
<div class="block">
<div class="element">
     <label>{"Class:"|i18n("design/standard/shop")}</label><div class="labelbreak"></div>
     <select name="Contentclasses[]" size="5" multiple >
     <option value="-1" {section show=$class_any_selected}selected="selected"{/section} >{"Any"|i18n("design/standard/shop")}</option>
     {section name=Classes loop=$product_class_list}
     <option value="{$Classes:item.id}" {switch match=$Classes:item.id}{case in=$class_limitation_list} selected="selected"{/case}{case}{/case}{/switch}>{$Classes:item.name}</option>
     {/section}
     </select>
</div>
<div class="element">
     <label>{"Section:"|i18n("design/standard/shop")}</label><div class="labelbreak"></div>
     <select name="Sections[]" size="5" multiple >
     <option value="-1" {section show=$section_any_selected}selected="selected"{/section}>{"Any"|i18n("design/standard/shop")}</option>
     {section name=Sections loop=$section_list}
     <option value="{$Sections:item.id}" {switch match=$Sections:item.id}{case in=$section_limitation_list} selected="selected"{/case}{case}{/case}{/switch}>{$Sections:item.name}

</option>
     {/section}
     </select>
</div>
<div class="break"></div>
</div>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value="Store"|i18n("design/standard/shop")}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value="Discard"|i18n("design/standard/shop")}
</div>
</form>
