<div class="view-shop">
    <div class="basket-design">

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

    {section show=$basket.items|gt(0)}

    <div class="content-basket">
    <table cellspacing="0">
    <tr>
	    <th>
        {"Quantity"|i18n("design/standard/shop")}
        </th>
        <th>
        {"VAT"|i18n("design/standard/shop")}
        </th>
        <th>
    	{"Price"|i18n("design/standard/shop")}
        </th>
        <th>
	    {"Discount"|i18n("design/standard/shop")}
        </th>
        <th>
     	{"Total Price"|i18n("design/standard/shop")}
        </th>
        <th>
        <input type="image" src={"t1/t1-bin.gif"|ezimage} name="RemoveProductItemButton" value="{'Remove'|i18n('design/standard/shop')}" /> &nbsp;
        </th>
    </tr>
    {section var=product_item loop=$basket.items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$product_item.sequence} product-name" colspan="6">
        <a href={concat("/content/view/full/",$product_item.node_id,"/")|ezurl}>{$product_item.object_name}</a>
        </td>
    </tr>
    <tr>
        <td class="{$product_item.sequence} product-details">
            <input type="hidden" name="ProductItemIDList[]" value="{$product_item.id}" />
            <input type="text" name="ProductItemCountList[]" value="{$product_item.item_count}" size="5" />
    	</td>
	    <td class="{$product_item.sequence} product-details">
        {$product_item.vat_value} %
    	</td>
        <td class="{$product_item.sequence} product-details">
        {$product_item.price_inc_vat|l10n(currency)}
    	</td>
	    <td class="{$product_item.sequence} product-details">
        {$product_item.discount_percent}%
        </td>
	    <td class="{$product_item.sequence} product-details product-price">
        {$product_item.total_price_inc_vat|l10n(currency)}
	    </td>
     	<td class="{$product_item.sequence} product-details">
	    <input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$product_item.item.id}" />
    	</td>
     </tr>
     {section show=$product_item.item.item_object.option_list}
     <tr>
         <td class="{$product_item.sequence}" colspan='4'>
         Selected options
         {section name=Options loop=$product_item.item_object.option_list}
             {$product_item.name}<br/>
             {$product_item.value}<br/>
             {$product_item.price|l10n(currency)}<br/>
         {/section}
         </td>
     </tr>
     {/section}
     {/section}
     <tr>
         <td class="product-subtotal" colspan='5'>
         {"Subtotal Inc. VAT:"|i18n("design/standard/shop")}
         <strong>{$basket.total_inc_vat|l10n(currency)}</strong>
         </td>
         <td class="product-subtotal">
         &nbsp;
         </td>

     </tr>
     </table>
     </div>

     <div class="buttonblock">
        <input class="button" type="submit" name="StoreChangesButton" value="{'Store'|i18n('design/standard/shop')}" />
     </div>

     <div class="buttonblock">
         <input class="button" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n('design/standard/shop')}" />
         <input class="defaultbutton" type="submit" name="CheckoutButton" value="{'Checkout'|i18n('design/standard/shop')}" /> &nbsp;
     </div>

    {/section}

    {section name=Basket show=$basket.items|lt(1) }

    <div class="feedback">
        <h2>{"You have no products in your basket"|i18n("design/standard/shop")}</h2>
    </div>

     {/section}

    </form>

    </div>
</div>