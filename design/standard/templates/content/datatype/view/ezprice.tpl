<table>
<tr>
<td>
Price ex. VAT:</td>
<td> {$attribute.content.ex_vat_price|l10n(currency)} <br /></td>
<tr>
<td>Price inc. VAT:</td>
<td>{$attribute.content.inc_vat_price|l10n(currency)}</td>
</tr>
{section show=$attribute.content.has_discount}
<tr>
<td>Discount price ex.VAT:</td>
<td>{$attribute.content.discount_price_ex_vat|l10n(currency)}</td>
</tr>
<tr>
<td>Discount price inc.VAT:</td>
<td>{$attribute.content.discount_price_inc_vat|l10n(currency)} </td>
</tr>
{/section}
</table>
