{set-block scope=root variable=subject}{"ez.no: Orderconfirmation %1"|i18n("design/standard/shop",,array($order.order_nr))}{/set-block}

Order confirmation from {ezini("SiteSettings","SiteName")}

Thank you for shopping at {ezini("SiteSettings","SiteURL")}

Your order number is: {$order.order_nr}


{shop_account_view_gui view=ascii order=$order}

Comment:
{$order.account_information.comment}

{let product_name_text="Product name" product_price_text="Price ex. VAT" product_qty_text="Qty." product_vat_text="VAT" product_discount_text="Discount" product_total_price_text="Total inc. VAT" total_text="Total"
     product_name_cnt=$product_name_text|count() product_price_cnt=$product_price_text|count() product_qty_cnt=$product_qty_text|count() product_vat_cnt=$product_vat_text|count() product_discount_cnt=$product_discount_text|count() product_total_price_cnt=$product_total_price_text|count() total_text_cnt=$total_text|count()
     product_name_max=$product_name_cnt product_price_max=$product_price_cnt product_qty_max=$product_qty_cnt product_total_price_max=$product_total_price_cnt product_vat_max=$product_vat_cnt product_discount_max=$product_discount_cnt
     line_cnt=0 product_total_ex_vat_ex_discount=0
}
{section loop=$order.product_items show=$order.product_items}
{set product_name_max=max($product_name_max,$:item.object_name|count)}
{set product_qty_max=max($product_qty_max,concat($:item.item_count)|count)}
{set product_price_max=max($product_price_max,$:item.price_ex_vat|l10n(currency)|count)}
{set product_vat_max=max($product_vat_max,sub($:item.price_inc_vat,$:item.price_ex_vat)|l10n(currency)|count)}
{set product_discount_max=max($product_discount_max,concat($:item.discount_percent,"%")|count)}
{set product_total_price_max=max($product_total_price_max,$:item.total_price_inc_vat|l10n(currency)|count)}
{set product_total_ex_vat_ex_discount=sum($product_total_ex_vat_ex_discount,$:item.price_ex_vat)}
{/section}
{set product_name_max=max($product_name_max,$total_text_cnt)}
{set product_price_max=max($product_price_max,$order.product_total_ex_vat|l10n(currency))}
{set product_total_price_max=max($product_total_price_max,$order.product_total_inc_vat|l10n(currency))}
{set line_cnt=sum($product_name_max,2,$product_qty_max,2,$product_price_max,2,$product_vat_max,2,$product_discount_max,2,$product_total_price_max)}

The items you've ordered:

{$product_name_text}{section name=Space loop=sum(sub($product_name_max,$product_name_cnt),2)} {/section}
{$product_qty_text}{section name=Space loop=sum(sub($product_qty_max,$product_qty_cnt),2)} {/section}
{$product_price_text}{section name=Space loop=sum(sub($product_price_max,$product_price_cnt),2)} {/section}
{$product_vat_text}{section name=Space loop=sum(sub($product_vat_max,$product_vat_cnt),2)} {/section}
{$product_discount_text}{section name=Space loop=sum(sub($product_discount_max,$product_discount_cnt),2)} {/section}
{$product_total_price_text}

{section loop=$line_cnt}-{/section}


{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
{$:item.object_name}
{section name=Space loop=sum(sub($product_name_max,$ProductItem:item.object_name|count),2)} {/section}
{$:item.item_count}
{section name=Space loop=sum(sub($product_qty_max,concat($ProductItem:item.item_count)|count),2)} {/section}
{$:item.price_ex_vat|l10n(currency)}
{section name=Space loop=sum(sub($product_price_max,$ProductItem:item.price_ex_vat|l10n(currency)|count),2)} {/section}
{sub($:item.price_inc_vat,$:item.price_ex_vat)|l10n(currency)}
{section name=Space loop=sum(sub($product_vat_max,sub($ProductItem:item.price_inc_vat,$ProductItem:item.price_ex_vat)|l10n(currency)|count),2)} {/section}
{concat($:item.discount_percent,"%")}
{section name=Space loop=sum(sub($product_discount_max,concat($:item.discount_percent,"%")|count),2)} {/section}
{$:item.total_price_inc_vat|l10n(currency)}
{delimiter}


{/delimiter}
{/section}


{section name=Space loop=$line_cnt}-{/section}

{$total_text}
{section name=Space loop=sum(sub($product_name_max,$total_text|count),2)} {/section}
{section name=Space loop=sum($product_qty_max,2)} {/section}
{$product_total_ex_vat_ex_discount|l10n(currency)}
{section name=Space loop=sum(sub($product_price_max,$product_total_ex_vat_ex_discount|l10n(currency)|count),2)} {/section}
{section name=Space loop=sum($product_vat_max,2)} {/section}
{section name=Space loop=sum($product_discount_max,2)} {/section}
{$order.product_total_inc_vat|l10n(currency)}

{section loop=$line_cnt}={/section}


{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
{$OrderItem:item.description}: 	{$OrderItem:item.price_inc_vat|l10n(currency)}
{/section}


Order total: {$order.total_inc_vat|l10n(currency)}

Should you have any questions, feel free to send an e-mail to {ezini("MailSettings","AdminEmail")}

Best regards,
{ezini("SiteSettings","SiteURL")}

{/let}
