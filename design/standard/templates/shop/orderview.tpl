<div class="maincontentheader">
<h1>{"Order view"|i18n}</h1>
</div>

<div class="block">
<div class="element">
<label>Order ID:</label><div class="labelbreak"></div>
<p class="box">{$order.id}</p>
</div>
<div class="element">
<label>User:</label><div class="labelbreak"></div>
<p class="box">{$order.user.login}</p>
</div>
<div class="element">
<label>Created:</label><div class="labelbreak"></div>
<p class="box">{$order.created|l10n(date)}</p>
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
 </tr>
{section name=ProductItem loop=$order.items show=$order.items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$ProductItem:sequence}">
	<input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />

	{$ProductItem:item.id}
	<a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a>
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
	<td class="{$ProductItem:ProductItem:sequence}" align="right">
	{$ProductItem:item.discount_percent}%
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
    <td colspan="7">
<div class="feedback">
<h2>Empty basket</h2>
</div>
    </td>
</tr>
{/section}
</table>

<p class="comment">To be done: The structure of this template needs to be changed so that the headers of the table don't print if the basket is empty, and the "Empty..." message isn't inside the table at all. th[eZ]</p>

</form>