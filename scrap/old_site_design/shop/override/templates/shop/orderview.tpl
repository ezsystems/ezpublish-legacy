<h1>{"Order %1"|i18n("design/standard/shop",,array($order.order_nr))}</h1>

<label>{"Customer"|i18n("design/standard/shop")}:</label> 
{shop_account_view_gui view=html order=$order}

<div class="basket">
<table class="list" cellspacing="0">
<tr>
	<th>
	{"Product"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Price inc. VAT"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Count"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Total Price inc. VAT"|i18n("design/standard/shop")}:
	</th>
</tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
<tr class="{$ProductItem:sequence}">
	<td class="product">
	<a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a>
	</td>
	<td class="price">
	{$ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="amount">
	{$ProductItem:item.item_count}
	</td>
	<td class="totalprice">
	{$ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
</tr>
{/section}
</table>
</div>


<b>{"Order summary"|i18n("design/standard/shop")}:</b><br />
<table class="list" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="bgdark">
    {"Subtotal of items"|i18n("design/standard/shop")}:
    </td>
    <td class="bgdark">
    {$order.product_total_ex_vat|l10n(currency)}
    </td>
    <td class="bgdark">
    {$order.product_total_inc_vat|l10n(currency)}
    </td>
</tr>

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$OrderItem:sequence}">
	{$OrderItem:item.description}:
	</td>
	<td class="{$OrderItem:sequence}">
	{$OrderItem:item.price_ex_vat|l10n(currency)}
	</td>
	<td class="{$OrderItem:sequence}">
	{$OrderItem:item.price_inc_vat|l10n(currency)}
	</td>
</tr>
{/section}
<tr>
    <td class="bgdark">
    <b>{"Order total"|i18n("design/standard/shop")}</b>
    </td>
    <td class="bgdark">
    <b>{$order.total_ex_vat|l10n(currency)}</b>
    </td>
    <td class="bgdark">
    <b>{$order.total_inc_vat|l10n(currency)}</b>
    </td>
</tr>
</table>
