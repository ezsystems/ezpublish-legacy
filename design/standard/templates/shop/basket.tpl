<form method="post" action={"/shop/basket/"|ezurl}>

<h1>{"Basket"|i18n}</h1>

{section name=Basket show=$basket.items|gt(0)}

Basket ID: {$basket.id}, Session ID: {$basket.session_id} <br />

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
{section name=ProductItem loop=$basket.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Basket:ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$Basket:ProductItem:item.id}" />
	{$Basket:ProductItem:item.id} - 
	<a href={concat("/content/view/full/",$Basket:ProductItem:item.contentobject.main_node_id,"/")|ezurl}>{$Basket:ProductItem:item.contentobject.name}</a>
	</td>
	<td class="{$Basket:ProductItem:sequence}">

	<input type="text" name="ProductItemCountList[]" value="{$Basket:ProductItem:item.item_count}" size="5" />

	</td>
	<td class="{$Basket:ProductItem:sequence}" align="right">
	{$Basket:ProductItem:item.vat_value} %
	</td>
	<td class="{$Basket:ProductItem:sequence}" align="right">
	{$Basket:ProductItem:item.price_ex_vat|l10n(currency)}
	</td>
	<td class="{$Basket:ProductItem:sequence}" align="right">
	{$Basket:ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="{$Basket:ProductItem:sequence}" align="right">
	{$Basket:ProductItem:item.total_price_ex_vat|l10n(currency)}
	</td>
	<td class="{$Basket:ProductItem:sequence}" align="right">
	{$Basket:ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
	<td>
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Basket:ProductItem:item.id}" />
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


{section name=Basket show=$basket.items|lt(1) }
You have no products in your basket.
{/section}


</form>