<form method="post" action={"/shop/basket/"|ezurl}>

<div class="maincontentheader">
<h1>{"Basket"|i18n}</h1>
</div>

{section name=Basket show=$basket.items|gt(0)}

<div class="block">
<div class="element">
<label>Basket ID:</label><div class="labelbreak"></div>
<p class="box">{$basket.id}</p>
</div>
<div class="element">
<label>Session ID:</label><div class="labelbreak"></div>
<p class="box">{$basket.session_id}</p>
</div>
<div class="break"></div>
</div>

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
{section name=ProductItem loop=$basket.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Basket:ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$Basket:ProductItem:item.id}" />
	{$Basket:ProductItem:item.id} - 
	<a href={concat("/content/view/full/",$Basket:ProductItem:item.node_id,"/")|ezurl}>{$Basket:ProductItem:item.object_name}</a>
	</td>
	<td class="{$Basket:ProductItem:sequence}">

	<input type="text" name="ProductItemCountList[]" value="{$Basket:ProductItem:item.item_count}" size="5" />

	</td>
	<td class="{$Basket:ProductItem:sequence}">
	{$Basket:ProductItem:item.vat_value} %
	</td>
	<td class="{$Basket:ProductItem:sequence}">
	{$Basket:ProductItem:item.price_ex_vat|l10n(currency)}
	</td>
	<td class="{$Basket:ProductItem:sequence}">
	{$Basket:ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="{$Basket:ProductItem:sequence}">
	{$Basket:ProductItem:item.discount_percent}%
	</td>
	<td class="{$Basket:ProductItem:sequence}">
	{$Basket:ProductItem:item.total_price_ex_vat|l10n(currency)}
	</td>
	<td class="{$Basket:ProductItem:sequence}">
	{$Basket:ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
	<td>
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Basket:ProductItem:item.id}" />
	</td>
</tr>
{/section}

</table>

<div class="buttonblock">
<input class="button" type="submit" name="StoreChangesButton" value="Store" /> &nbsp;
<input class="button" type="submit" name="RemoveProductItemButton" value="Remove item(s)" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="ContinueShoppingButton" value="Continue shopping" />
<input class="button" type="submit" name="CheckoutButton" value="Checkout" /> &nbsp;
</div>

{/section}


{section name=Basket show=$basket.items|lt(1) }

<div class="feedback">
<h2>You have no products in your basket</h2>
</div>

{/section}


</form>