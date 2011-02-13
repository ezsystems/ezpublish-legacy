{if $attribute.content.has_discount}
{"Price"|i18n("design/base")}: <span class="oldprice">{$attribute.content.inc_vat_price|l10n(currency)}</span><br/>
{"Your price"|i18n("design/base")}: <span class="currentprice">{$attribute.content.discount_price_inc_vat|l10n(currency)}</span><br />
{"You save"|i18n("design/base")}: <span class="pricesave">{sub($attribute.content.inc_vat_price,$attribute.content.discount_price_inc_vat)|l10n(currency)} ( {$attribute.content.discount_percent} % )</span>
{else}
<span class="currentprice">{$attribute.content.inc_vat_price|l10n(currency)}</span>
{/if}