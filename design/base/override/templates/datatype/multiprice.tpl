{def $multiprice = $attribute.content
     $currency_code = $multiprice.currency
     $currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) )
     $locale = false()
     $symbol = false()}

{if $currency}
    {set $symbol = $currency.symbol
         $locale = $currency.locale}
{/if}

    {if $multiprice.has_discount}
        {"Price"|i18n("design/base")}: <span class="oldprice">{$multiprice.inc_vat_price|l10n( 'currency', $locale, $symbol )}</span><br/>
        {"Your price"|i18n("design/base")}: <span class="currentprice">{$multiprice.discount_price_inc_vat|l10n( 'currency', $locale, $symbol )}</span><br />
        {"You save"|i18n("design/base")}: <span class="pricesave">{sub($multiprice.inc_vat_price,$multiprice.discount_price_inc_vat)|l10n( 'currency', $locale, $symbol )} ( {$multiprice.discount_percent} % )</span>
    {else}
        <span class="currentprice">{$multiprice.inc_vat_price|l10n('currency', $locale, $symbol )}</span>
    {/if}

{undef}