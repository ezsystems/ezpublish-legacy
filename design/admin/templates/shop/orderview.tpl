<div class="context-block">
<h2 class="context-title">{'Order #%order_id'|i18n( 'design/admin/shop/orderview',, hash( '%order_id', $order.order_nr ) )}</h2>

{shop_account_view_gui view=html order=$order}

<b>{"Product items"|i18n("design/standard/shop")}</b>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{"Product"|i18n("design/standard/shop")}</th>
	<th>{"Count"|i18n("design/standard/shop")}</th>
	<th>{"VAT"|i18n("design/standard/shop")}</th>
	<th>{"Price ex. VAT"|i18n("design/standard/shop")}</th>
	<th>{"Price inc. VAT"|i18n("design/standard/shop")}</th>
	<th>{"Discount"|i18n("design/standard/shop")}</th>
	<th>{"Total Price ex. VAT"|i18n("design/standard/shop")}</th>
	<th>{"Total Price inc. VAT"|i18n("design/standard/shop")}</th>
	<th>&nbsp;</th>
</tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
<tr>
	<td><a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a></td>
	<td>{$ProductItem:item.item_count}</td>
	<td>{$ProductItem:item.vat_value}&nbsp;%</td>
	<td>{$ProductItem:item.price_ex_vat|l10n(currency)}</td>
	<td>{$ProductItem:item.price_inc_vat|l10n(currency)}</td>
	<td>{$ProductItem:item.discount_percent}&nbsp;%</td>
	<td>{$ProductItem:item.total_price_ex_vat|l10n(currency)}</td>
	<td>{$ProductItem:item.total_price_inc_vat|l10n(currency)}</td>
</tr>
{/section}
</table>

<b>{"Order summary"|i18n("design/standard/shop")}:</b><br />
<table class="list" cellspacing="0">
<tr>
    <td>{"Subtotal of items"|i18n("design/standard/shop")}:</td>
    <td>{$order.product_total_ex_vat|l10n(currency)}</td>
    <td>{$order.product_total_inc_vat|l10n(currency)}</td>
</tr>

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
<tr>
	<td>{$OrderItem:item.description}:</td>
	<td>{$OrderItem:item.price_ex_vat|l10n(currency)}</td>
	<td>{$OrderItem:item.price_inc_vat|l10n(currency)}</td>
</tr>
{/section}
<tr>
    <td><b>{"Order total"|i18n("design/standard/shop")}</b></td>
    <td><b>{$order.total_ex_vat|l10n(currency)}</b></td>
    <td><b>{$order.total_inc_vat|l10n(currency)}</b></td>
</tr>
</table>

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="" value="{'Remove'|i18n( 'design/admin/shop/orderview' )}" />
</div>
</div>

</div>
