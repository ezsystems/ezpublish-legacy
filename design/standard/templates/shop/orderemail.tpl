
{"Order"|i18n("design/standard/shop")} {$order.order_nr}


{"Customer:"|i18n("design/standard/shop")}
{shop_account_view_gui view=ascii order=$order}


{"Product items"|i18n("design/standard/shop")}
{"Product:"|i18n("design/standard/shop")}|{"Count:"|i18n("design/standard/shop")}|{"VAT:"|i18n("design/standard/shop")}|{"Price ex. VAT:"|i18n("design/standard/shop")}|{"Price inc. VAT:"|i18n("design/standard/shop")}|{"Discount:"|i18n("design/standard/shop")}|{"Total Price ex. VAT:"|i18n("design/standard/shop")}|{"Total Price inc. VAT:"|i18n("design/standard/shop")}
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}

{$ProductItem:item.object_name}|{$ProductItem:item.item_count}|{$ProductItem:item.vat_value} %|{$ProductItem:item.price_ex_vat|l10n(currency)}|{$ProductItem:item.price_inc_vat|l10n(currency)}|{$ProductItem:item.discount_percent}%|{$ProductItem:item.total_price_ex_vat|l10n(currency)}|{$ProductItem:item.total_price_inc_vat|l10n(currency)}
{/section}

{"Order summary:"|i18n("design/standard/shop")}
{"Subtotal of items:"|i18n("design/standard/shop")}:  {$order.product_total_ex_vat|l10n(currency)}  {$order.product_total_inc_vat|l10n(currency)}

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
{$OrderItem:item.description}: 	{$OrderItem:item.price_ex_vat|l10n(currency)}	{$OrderItem:item.price_inc_vat|l10n(currency)}
{/section}

{"Order total:"|i18n("design/standard/shop")}{$order.total_ex_vat|l10n(currency)}{$order.total_inc_vat|l10n(currency)}
