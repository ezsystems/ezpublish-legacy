<div class="maincontentheader">
  <h1>{"Order %1"|i18n("design/standard/shop",,array($order.order_nr))}</h1>
</div>

<b>{"Customer"|i18n("design/standard/shop")}</b> 
{shop_account_view_gui view=html order=$order}

<br />

<b>{"Product items"|i18n("design/standard/shop")}</b>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"Product"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Count"|i18n("design/standard/shop")}
	</th>
	<th>
	{"VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Price ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Price inc. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Discount"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total Price ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total Price inc. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	&nbsp;
	</th>
</tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ProductItem:sequence}">
	<a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a>
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.item_count}
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.vat_value} %
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.price_ex_vat|l10n(currency)}
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.discount_percent}%
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.total_price_ex_vat|l10n(currency)}
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
</tr>
{/section}
</table>



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
