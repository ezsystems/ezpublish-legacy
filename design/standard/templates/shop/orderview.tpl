<h1>{"Order view"|i18n}</h1>

Order ID: {$order.id} <br />

User: {$order.user.login}<br />
Created: {$order.created|l10n(date)}<br/>

<table cellspacing="0">
<tr>
	<th>
	Product
	</th>
	<th>
	Count
	</th>
	<th>
	VAT
	</th>
	<th>
	Price ex. VAT
	</th>
	<th>
	Price inc. VAT
	</th>
	<th>
	Total Price ex. VAT
	</th>
	<th>
	Total Price inc. VAT
	</th>
 </tr>
{section name=ProductItem loop=$order.items show=$order.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />

	{$ProductItem:item.id}
	<a href={concat("/content/view/full/",$ProductItem:item.contentobject.main_node_id,"/")|ezurl}>{$ProductItem:item.contentobject.name}</a>
	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.item_count}
	</td>
	<td class="{$ProductItem:sequence}" align="right">
	{$ProductItem:item.vat_value} %
	</td>
	<td class="{$ProductItem:sequence}" align="right">
	{$ProductItem:item.price_ex_vat|l10n(currency)}
	</td>
	<td class="{$ProductItem:sequence}" align="right">
	{$ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="{$ProductItem:sequence}" align="right">
	{$ProductItem:item.total_price_ex_vat|l10n(currency)}
	</td>
	<td class="{$ProductItem:sequence}" align="right">
	{$ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
</tr>
{section-else}
<tr>
	<td>Empty cart</td>
</tr>
{/section}

</table>

</form>