<form method="post" action={"/shop/basket/"|ezurl}>

<h1>{"Basket"|i18n("design/standard/shop")}</h1>
{section show=$removed_items}

<div class="warning">
<h2>{"The following items were removed from your basket, because the products were changed"|i18n("design/standard/node",,)}</h2>

<ul>
{section name=RemovedItem loop=$removed_items}
    <li> <a href={concat("/content/view/full/",$RemovedItem:item.contentobject.main_node_id,"/")|ezurl}>{$RemovedItem:item.contentobject.name}</a></li>
{/section}
</ul>
</div>
{/section}

{section name=Basket show=$basket.items|gt(0)}

<div class="basket">
<table class="list" cellspacing="0">
<tr>
	<th>
	{"Product"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Price inc. VAT"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Count"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Total Price inc. VAT"|i18n("design/standard/shop")}:
	</th>
	<th>
	&nbsp;
	</th>
</tr>
{section name=ProductItem loop=$basket.items sequence=array(bglight,bgdark)}
<tr class="{$Basket:ProductItem:sequence}">
	<td class="product">
	<input type="hidden" name="ProductItemIDList[]" value="{$Basket:ProductItem:item.id}" />
	{*{$Basket:ProductItem:item.id}-*} 
	<a href={concat("/content/view/full/",$Basket:ProductItem:item.node_id,"/")|ezurl}>{$Basket:ProductItem:item.object_name}</a>
	</td>
	<td class="price">
	{$Basket:ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="amount">

	<input type="text" name="ProductItemCountList[]" value="{$Basket:ProductItem:item.item_count}" size="5" />

	</td>
	<td class="totalprice">
	{$Basket:ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
	<td class="delete">
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Basket:ProductItem:item.id}" />
	</td>
</tr>
{section show=$Basket:ProductItem:item.item_object.option_list}
<tr>
  <td class="{$Basket:ProductItem:sequence}" colspan="4">
     <table width="100%"  border="0">
<tr>
<td colspan="3">
Selected options
</td> 
</tr>
     {section name=Options loop=$Basket:ProductItem:item.item_object.option_list}
      <tr> 
        <td width="33%">{$Basket:ProductItem:Options:item.name}</td>
        <td width="33%">{$Basket:ProductItem:Options:item.value}</td>
        <td width="33%">{$Basket:ProductItem:Options:item.price|l10n(currency)}</td>
      </tr>
    {/section}
     </table>
   </td>
  <td class="{$Basket:ProductItem:sequence}" colspan="5">
  </td>
</tr>
{/section}
{/section}
<tr>
     <td colspan="3">
     </td>
     <td>
     <b>{"Subtotal Inc. VAT:"|i18n("design/standard/shop")}</b>
     </td>
     <td>
     </td>
</tr>
<tr>
<td colspan="3">
</td>
<td>
{$basket.total_inc_vat|l10n(currency)}
</td>
<td>
</td>
</tr>
</table>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="RemoveProductItemButton" value="{'Remove'|i18n('design/standard/shop')}" /> &nbsp;
<input class="button" type="submit" name="StoreChangesButton" value="{'Store'|i18n('design/standard/shop')}" /> 
</div>

<div class="buttonblock">
<input class="button" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n('design/standard/shop')}" />
<input class="button" type="submit" name="CheckoutButton" value="{'Checkout'|i18n('design/standard/shop')}" /> &nbsp;
</div>

{/section}


{section name=Basket show=$basket.items|lt(1) }

<div class="feedback">
<h2>{"You have no products in your basket"|i18n("design/standard/shop")}</h2>
</div>

{/section}


</form>
