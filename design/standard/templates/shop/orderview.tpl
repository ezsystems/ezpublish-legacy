<div class="maincontentheader">
<h1>{"Order view"|i18n}</h1>
</div>

<b>Customer:</b> 
{content_view_gui view=text_linked content_object=$order.user.contentobject}
<br />

<b>Product items</b>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	Product:
	</th>
	<th>
	Count:
	</th>
	<th>
	VAT:
	</th>
	<th>
	Price ex. VAT:
	</th>
	<th>
	Price inc. VAT:
	</th>
	<th>
	Discount:
	</th>
	<th>
	Total Price ex. VAT:
	</th>
	<th>
	Total Price inc. VAT:
	</th>
	<th>
	&nbsp;
	</th>
</tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />
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



<b>Order summary</b>:<br />
<table class="list" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="bgdark">
    Subtotal of items:
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
    <b>Order total:</b>
    </td>
    <td class="bgdark">
    <b>{$order.total_ex_vat|l10n(currency)}</b>
    </td>
    <td class="bgdark">
    <b>{$order.total_inc_vat|l10n(currency)}</b>
    </td>
</tr>
</table>

</form>