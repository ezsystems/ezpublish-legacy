
{section show=$attribute.content.has_discount}
{*
Price ex. VAT: <strike>{$attribute.content.ex_vat_price|l10n(currency)}</strike> <br/>
Your price, ex VAT: {$attribute.content.discount_price_ex_vat|l10n(currency)}<br />
*}
Price: <strike>{$attribute.content.inc_vat_price|l10n(currency)}</strike> <br/>
Your price: {$attribute.content.discount_price_inc_vat|l10n(currency)}<br />
You save: {sub($attribute.content.inc_vat_price,$attribute.content.discount_price_inc_vat)|l10n(currency)} ( {$attribute.content.discount_percent} % )

{section-else}
Price: {$attribute.content.ex_vat_price|l10n(currency)} <br/>
{*
Price ex. VAT: {$attribute.content.ex_vat_price|l10n(currency)} <br/>
Price inc. VAT: {$attribute.content.inc_vat_price|l10n(currency)}<br/>
*}
{/section}

