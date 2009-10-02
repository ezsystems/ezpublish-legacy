{set-block scope=root variable=subject}{"Order"|i18n("design/standard/shop")}: {$order.order_nr}{/set-block}
{"Order"|i18n("design/standard/shop")}: {$order.order_nr}

{shop_account_view_gui view=ascii order=$order}


{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()
         $order_info = $order.order_info}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

{"Product items"|i18n("design/standard/shop")}:

{section name=ProductItem loop=$order.product_items}
{$ProductItem:item.object_name}: {$ProductItem:item.item_count} x {$ProductItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}: {$ProductItem:item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}

{/section}

{"Subtotal inc. VAT"|i18n("design/base/shop")}: {$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}

{foreach $order_info.additional_info as $order_item_type => $additional_info}
{if $order_item_type|eq('ezcustomshipping')}
{"Shipping total inc. VAT"|i18n("design/base/shop")}: {$additional_info.total.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
{else}
{"Item total inc. VAT"|i18n("design/base/shop")}: {$additional_info.total.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
{/if}

{/foreach}

{"Total inc. VAT"|i18n("design/base/shop")}: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}


{"Order summary"|i18n("design/base/shop")}:

{"Subtotal of items ex. VAT"|i18n("design/base/shop")}: {$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}

{foreach $order_info.additional_info as $order_item_type => $additional_info}
{if $order_item_type|eq('ezcustomshipping')}
{"Shipping total ex. VAT"|i18n("design/base/shop")}: {$additional_info.total.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
{else}
{"Item total ex. VAT"|i18n("design/base/shop")}: {$additional_info.total.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
{/if}

{/foreach}


{if $order_info.additional_info|count|gt(0)}
{foreach $order_info.price_info.items as $vat_value => $order_info
           sequence array(bglight, bgdark) as $sequence}
{if $order_info.total_price_vat|gt(0)}
{"Total VAT"|i18n("design/base/shop")} ({$vat_value}%) {$order_info.total_price_vat|l10n( 'currency', $locale, $symbol )}

{/if}
{/foreach}
{/if}
{"Order total"|i18n("design/base/shop")}: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}
{undef $currency $locale $symbol}