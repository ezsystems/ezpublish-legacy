<form method="post" action={"/shop/basket/"|ezurl}>

{section name=Basket show=$basket.items|gt(0)}

<img src={"shoppingbasket.gif"|ezimage} border="0" alt="Shopping basket" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr>
	<th bgcolor="#E8E4CE" width="50%">
	<span class="small">Product</span>
	</th>
	<th bgcolor="#E8E4CE">
	<span class="small">Count</span>
	</th>
	<th bgcolor="#E8E4CE">
	<span class="small">Price</span>
	</th>
	<th bgcolor="#E8E4CE">
	</th>
</tr>
{section name=ProductItem loop=$basket.items sequence=array(EEEADD,EEEAE0)}
<tr>
	<td bgcolor="{$Basket:ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$Basket:ProductItem:item.id}" />
	<a class="small" href={concat("/content/view/full/",$Basket:ProductItem:item.contentobject.main_node_id,"/")|ezurl}>{$Basket:ProductItem:item.contentobject.name}</a>
	</td>
	<td bgcolor="{$Basket:ProductItem:sequence}">
	<input type="text" name="ProductItemCountList[]" value="{$Basket:ProductItem:item.item_count}" size="3" />
	</td>
	<td bgcolor="{$Basket:ProductItem:sequence}" align="right">
	<span class="small">{$Basket:ProductItem:item.total_price_inc_vat|l10n(currency)}</span>
	</td>
	<td bgcolor="{$Basket:ProductItem:sequence}" align="right">
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