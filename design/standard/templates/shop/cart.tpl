<form method="post" action="/shop/cart/">

<h1>{"Cart"|i18n}</h1>

{section name=Cart show=$cart.items|gt(0) }

Cart ID: {$cart.id}, Session ID: {$cart.session_id} <br />

<table>
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
	<th>
	</th>
</tr>
{section name=ProductItem loop=$cart.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Cart:ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$Cart:ProductItem:item.id}" />
	{$Cart:ProductItem:item.id} - 
	<a href="/content/view/full/{$Cart:ProductItem:item.contentobject.main_node_id}/">{$Cart:ProductItem:item.contentobject.name}</a>
	</td>
	<td class="{$Cart:ProductItem:sequence}">

	<input type="text" name="ProductItemCountList[]" value="{$Cart:ProductItem:item.item_count}" size="5" />

	</td>
	<td class="{$Cart:ProductItem:sequence}" align="right">
	{$Cart:ProductItem:item.vat_value} %
	</td>
	<td class="{$Cart:ProductItem:sequence}" align="right">
	{$Cart:ProductItem:item.price_ex_vat|l10n(currency)}
	</td>
	<td class="{$Cart:ProductItem:sequence}" align="right">
	{$Cart:ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="{$Cart:ProductItem:sequence}" align="right">
	{$Cart:ProductItem:item.total_price_ex_vat|l10n(currency)}
	</td>
	<td class="{$Cart:ProductItem:sequence}" align="right">
	{$Cart:ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
	<td>
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Cart:ProductItem:item.id}" />
	</td>
</tr>
{/section}

</table>
<br />
<input type="submit" name="StoreChangesButton" value="Store" /> &nbsp;
<input type="submit" name="RemoveProductItemButton" value="Remove item(s)" />

<table width="100%">
<tr>
	<td>
	<input type="submit" name="ContinueShoppingButton" value="Continue shopping" />
	</td>
	<td align="right">
	<input type="submit" name="CheckoutButton" value="Checkout" /> &nbsp;
	</td>
</tr>
</table>

{/section}


{section name=Cart show=$cart.items|lt(1) }
You have no products in your cart.
{/section}


</form>