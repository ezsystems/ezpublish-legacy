{def $multiprice = $attribute.content
     $currency_code = $multiprice.currency
     $currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) )
     $locale = false()
     $symbol = false()}

    {if $currency}
        {set symbol = $currency.symbol
             locale = $currency.locale}
    {/if}

    {def $price_value = ''}
    {if $multiprice.has_discount}
        {"Price"|i18n("design/base")}: <span class="oldprice">{$multiprice.inc_vat_price|l10n( 'currency', $locale, $symbol )}</span><br/>
        {"Your price"|i18n("design/base")}: <span class="currentprice">{$multiprice.discount_price_inc_vat|l10n( 'currency', $locale, $symbol )}</span><br />
        {"You save"|i18n("design/base")}: <span class="pricesave">{sub($multiprice.inc_vat_price,$multiprice.discount_price_inc_vat)|l10n( 'currency', $locale, $symbol )} ( {$multiprice.discount_percent} % )</span>
    {else}
        {if gt( $multiprice.inc_vat_price, 0 )}
            {set price_value = $multiprice.inc_vat_price|l10n('currency', $locale, $symbol )}
        {else}
            {set price_value = 'N/A'|i18n( 'design/base' )}
        {/if}
        <span class="currentprice">{$price_value}</span>
    {/if}
    {undef $price_value}
{undef}
