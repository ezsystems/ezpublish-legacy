<form method="post" action={"/shop/basket/"|ezurl}>

{section name=Basket show=$basket.items|gt(0)}

<img src={"shoppingbasket.gif"|ezimage} border="0" alt="Shopping basket" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr>
	<th bgcolor="#ECF9DF" width="50%">
	<span class="small">Product</span>
	</th>
	<th bgcolor="#ECF9DF">
	<span class="small">Count</span>
	</th>
	<th bgcolor="#ECF9DF" align="right">
	<span class="small">Price</span>
	</th>
	<th bgcolor="#ECF9DF">
	</th>
</tr>
{section name=ProductItem loop=$basket.items sequence=array(D9F3BE,ECF9DF)}
<tr>
	<td bgcolor="#{$Basket:ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$Basket:ProductItem:item.id}" />

	<a href={concat("/content/view/full/",$Basket:ProductItem:item.node_id,"/")|ezurl}>{$Basket:ProductItem:item.object_name|wash}</a>
	</td>
	<td bgcolor="#{$Basket:ProductItem:sequence}">
	<input type="text" name="ProductItemCountList[]" value="{$Basket:ProductItem:item.item_count}" size="3" />
	</td>
	<td bgcolor="#{$Basket:ProductItem:sequence}" align="right">
	<span class="small">{$Basket:ProductItem:item.total_price_inc_vat|l10n(currency)}</span>
	</td>
	<td bgcolor="#{$Basket:ProductItem:sequence}" align="right">
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Basket:ProductItem:item.id}" />
	</td>
</tr>
{/section}
<tr>
	<td bgcolor="#ECF9DF" colspan="2">
	<span class="small">
	Total:
	</span>
	</td>
	<td bgcolor="#ECF9DF" align="right">
	<span class="small">
	{$basket.total_inc_vat|l10n(currency)}
	</span>
	</td>
	<td bgcolor="#ECF9DF" align="right">
	&nbsp;
	</td>
</tr>
</table>
<br />
<input type="submit" name="StoreChangesButton" value="Store" /> &nbsp;
<input type="submit" name="RemoveProductItemButton" value="Remove item(s)" />

<table width="100%">
<tr>
	<td>
	<input type="image" src={"continueshopping.gif"|ezimage} name="ContinueShoppingButton" value="Continue shopping" />
	</td>
	<td align="right">
	<input type="image" src={"proceed.gif"|ezimage}  name="CheckoutButton" value="Checkout" /> &nbsp;
	</td>
</tr>
</table>

{/section}


{section name=Basket show=$basket.items|lt(1) }
You have no products in your basket.
{/section}


</form>