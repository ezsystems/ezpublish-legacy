<h2>{"Customer Information"|i18n("design/standard/shop")}</h2>
<br />
{shop_account_view_gui view=html order=$order_list[0]}


<h2>{"Order list"|i18n("design/standard/shop")}</h2>
{section show=$order_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"ID"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Date"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total inc. VAT"|i18n("design/standard/shop")}
	</th>
    <th>
	</th>
</tr>
{section name="Order" loop=$order_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Order:sequence}">
	{$Order:item.order_nr}
	</td>
	<td class="{$Order:sequence}">
	{$Order:item.created|l10n(shortdatetime)}
	</td>
	<td class="{$Order:sequence}">
	{$Order:item.total_ex_vat|l10n(currency)}
	</td>
	<td class="{$Order:sequence}">
	{$Order:item.total_inc_vat|l10n(currency)}
	</td>
	<td class="{$Order:sequence}">
	<a href={concat("/shop/orderview/",$Order:item.id,"/")|ezurl}>[ view ]</a>
	</td>
</tr>
{/section}
</table>
</section>


<div class="maincontentheader">
  <h2>{"Purchase list"|i18n("design/standard/shop")}</h2>
</div>

{section show=$product_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"Product"|i18n("design/standard/shop")}
	</th>
    <th>
	{"Amount"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total inc. VAT"|i18n("design/standard/shop")}
	</th>
</tr>

{section var="Product" loop=$product_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Product.sequence}">
    {content_view_gui view=text_linked content_object=$Product.product}
	</td>
    <td class="{$Product.sequence}">
    {$Product.sum_count}
	<td class="{$Product.sequence}">
	{$Product.sum_ex_vat|l10n(currency)}
	</td>
	<td class="{$Product.sequence}">
	{$Product.sum_inc_vat|l10n(currency)}
	</td>
</tr>
{/section}
</table>