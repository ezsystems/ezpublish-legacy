<form action={"shop/vattype"|ezurl} method="post" name="VatType">

<h1>{"VAT Types"|i18n("shop")}</h1>
{section show=$vattype_array}
<table width="100%">
<tr>
    <th>{"Name"|i18n("shop")}:</th>
    <th>{"Percentage"|i18n("shop")}:</th>
    <th></th>
</tr>
{section name="VatType" loop=$vattype_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$VatType:sequence}">
	<input type="text" name="vattype_name_{$VatType:item.id}" value="{$VatType:item.name}" size="15">
	</td>
	<td class="{$VatType:sequence}">
	<input type="text" name="vattype_percentage_{$VatType:item.id}" value="{$VatType:item.percentage}" size="4">%
	</td>
	<td class="{$VatType:sequence}">
	<input type="checkbox" name="vatTypeIDList[]" value="{$VatType:item.id}">
	</td>
</tr>
{/section}
</table>
{/section}
<div class="buttonblock">
{include uri="design:gui/button.tpl" name=newvattype id_name=AddVatTypeButton value="New"|i18n("shop")}
{include uri="design:gui/button.tpl" name=removevattype id_name=RemoveVatTypeButton value="Remove"|i18n("shop")}
</div>
</br>
{include uri="design:gui/button.tpl" name=savevattype id_name=SaveVatTypeButton value="Store"|i18n("shop")}
</form>
