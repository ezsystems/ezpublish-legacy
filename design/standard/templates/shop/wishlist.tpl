<form method="post" action={"/shop/wishlist/"|ezurl}>

<h1>{"Wish list"|i18n}</h1>

Wish list ID: {$wish_list.id} <br />

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
{section name=ProductItem loop=$wish_list.items show=$wish_list.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />
	{$ProductItem:item.id} - 
	<a href={concat("/content/view/full/",$ProductItem:item.contentobject.main_node_id,"/")|ezurl}>{$ProductItem:item.contentobject.name}</a>
	</td>
	<td class="{$ProductItem:sequence}">

	<input type="text" name="ProductItemCountList[]" value="{$ProductItem:item.item_count}" size="5" />

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
	<td>
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$ProductItem:item.id}" />
	</td>
</tr>
{section-else}
<tr>
	<td>Empty wish list</td>
</tr>
{/section}

</table>
<br />
<input type="submit" name="StoreChangesButton" value="Store" /> &nbsp;
<input type="submit" name="RemoveProductItemButton" value="Remove item(s)" />

</form>
