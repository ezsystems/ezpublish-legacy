<form action={"shop/vattype"|ezurl} method="post" name="VatType">

<h1>{"VAT Types"|i18n("design/standard/shop")}</h1>
<table class="list" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <th>{"Name"|i18n("design/standard/shop")}</th>
    <th>{"Percentage"|i18n("design/standard/shop")}</th>
    <th></th>
</tr>
{section name="VatType" loop=$vattype_array sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td>
        <input type="text" name="vattype_name_{$VatType:item.id}" value="{$VatType:item.name}" size="15">
    </td>
    <td>
        <input type="text" name="vattype_percentage_{$VatType:item.id}" value="{$VatType:item.percentage}" size="4">%
    </td>
    <td width="1">
        <input type="checkbox" name="vatTypeIDList[]" value="{$VatType:item.id}" />
    </td>
</tr>
{/section}
</table>

<div class="buttonblock">
    {include uri="design:gui/button.tpl" name=newvattype id_name=AddVatTypeButton value="New"|i18n("design/standard/shop")}
    {include uri="design:gui/button.tpl" name=removevattype id_name=RemoveVatTypeButton value="Remove"|i18n("design/standard/shop")}
</div>

<br/>
{include uri="design:gui/defaultbutton.tpl" name=savevattype id_name=SaveVatTypeButton value="Store"|i18n("design/standard/shop")}

</form>