<form method="post" action={"/shop/wishlist/"|ezurl}>

<div class="maincontentheader">
<h1>{"Wish list"|i18n}</h1>
</div>

<div class="block">
<label>Wish list ID:</label><div class="labelbreak"></div>
<p class="box">{$wish_list.id}</p>
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
{section name=ProductItem loop=$wish_list.items show=$wish_list.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />
	{$ProductItem:item.id} - 
	<a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a>
	</td>
	<td class="{$ProductItem:sequence}">

	<input type="text" name="ProductItemCountList[]" value="{$ProductItem:item.item_count}" size="5" />

	</td>
	<td class="{$ProductItem:sequence}">
	{$ProductItem:item.vat_value}%
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
	<td>
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$ProductItem:item.id}" />
	</td>
</tr>
{section-else}
<tr>
	<td>
<div class="feedback">
<h2>Empty wish list</h2>
</div>
    </td>
</tr>
{/section}
</table>

<p class="comment">To be done: The structure of this template needs to be changed so that the headers of the table don't print if the basket is empty, and the "Empty..." message isn't inside the table at all. th[eZ]</p>

<div class="buttonblock">
<input class="button" type="submit" name="StoreChangesButton" value="Store" />
<input class="button" type="submit" name="RemoveProductItemButton" value="Remove item(s)" />
</div>

</form>
