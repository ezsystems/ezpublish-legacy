<div class="shop-basket">

    <form method="post" action={"/shop/basket/"|ezurl}>

    <h1>{"Basket"|i18n("design/base/shop")}</h1>

    {section show=$removed_items}
    <div class="warning">
        <h2>{"The following items were removed from your basket because the products were changed"|i18n("design/base/shop",,)}</h2>
        <ul>
        {section name=RemovedItem loop=$removed_items}
            <li> <a href={concat("/content/view/full/",$RemovedItem:item.contentobject.main_node_id,"/")|ezurl}>{$RemovedItem:item.contentobject.name|wash}</a></li>
        {/section}
        </ul>
    </div>
    {/section}

    {if not( $vat_is_known )}
    <div class="message-warning">
    <h2>{'VAT is unknown'|i18n( 'design/base/shop' )}</h2>
    {'VAT percentage is not yet known for some of the items being purchased.'|i18n( 'design/base/shop' )}<br/>
    {'This probably means that some information about you is not yet available and will be obtained during checkout.'|i18n( 'design/base/shop' )}
    </div>
    {/if}

    {if $error}
    <div class="error">
        {switch match=$error}
        {case match=1}
           <h2>{"Attempted to add object without price to basket."|i18n("design/standard/shop",,)}</h2>
        {/case}
        {case match="aborted"}
           <h2>{"Your payment was aborted."|i18n("design/standard/shop",,)}</h2>
        {/case}
        {case match="invaliditemcount"}
           <h2>{"Incorrect quantity! The quantity of the product(s) must be numeric and not less than 1."|i18n("design/standard/shop",,)}</h2>
        {/case}
        {/switch}
    </div>
    {/if}

    {section show=$basket.items}

    {def $currency = fetch( 'shop', 'currency', hash( 'code', $basket.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

    <h2>{"Product items"|i18n("design/base/shop")}</h2>

    <div class="content-basket">
    <table cellspacing="0">
    <tr>
	    <th>
        {"Quantity"|i18n("design/base/shop")}
        </th>
        <th>
        {"VAT"|i18n("design/base/shop")}
        </th>
        <th>
    	{"Price"|i18n("design/base/shop")}
        </th>
        <th>
	    {"Discount"|i18n("design/base/shop")}
        </th>
        <th>
     	{"Total price"|i18n("design/base/shop")}
        </th>
        <th>
       {* <input type="image" src={"t1/t1-bin.gif"|ezimage} name="RemoveProductItemButton" value="{'Remove'|i18n('design/base/shop')}" /> &nbsp; *}
        <input type="image" class="shop-basketbin" src={"1x1.gif"|ezimage} name="RemoveProductItemButton" value="{'Remove'|i18n('design/base/shop')}" />
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
        {if ne( $product_item.vat_value, -1 )}
            {$product_item.vat_value} %
        {else}
            {'unknown'|i18n( 'design/base/shop' )}
        {/if}
    	</td>
        <td class="{$product_item.sequence} product-details">
        {$product_item.price_inc_vat|l10n( 'currency', $locale, $symbol )}
    	</td>
	    <td class="{$product_item.sequence} product-details">
        {$product_item.discount_percent}%
        </td>
	    <td class="{$product_item.sequence} product-details product-price">
        {$product_item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
	    </td>
     	<td class="{$product_item.sequence} product-details">
	    <input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$product_item.item.id}" />
    	</td>
     </tr>
     {section show=$product_item.item.item_object.option_list}
     <tr>
         <td class="{$product_item.sequence}" colspan='4'>
         {"Selected options"|i18n("design/base/shop")}<br/>

         <table class="shop-option_list">
         {section var=option_item loop=$product_item.item_object.option_list}
         <tr>
             <td class="shop-option_name">{$option_item.name|wash}</td>
             <td class="shop-option_value">{$option_item.value}</td>
             <td class="shop-option_price">{if $option_item.price|ne( 0 )}{$option_item.price|l10n( 'currency', $locale, $symbol )}{/if}</td>
         </tr>
         {/section}
         </table>
         </td>
     </tr>
     {/section}
     {/section}
     <tr>
         <td class="product-subtotal" colspan="4"><b>{"Subtotal inc. VAT"|i18n("design/base/shop")}:</b></td> 
         <td class="product-subtotal"><b>{$basket.total_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
         <td class="product-subtotal">&nbsp;</td>
     </tr>
    {if is_set( $shipping_info )}
    {* Show shipping type/cost. *}
    <tr>
    <td class="product-subtotal" colspan="4">{if is_set($shipping_info.management_link)}<a href={$shipping_info.management_link|ezurl}>{/if}{if $shipping_info.description}{$shipping_info.description}{else}{'Shipping'|i18n( 'design/admin/shop/basket' )}{/if}{if is_set($shipping_info.management_link)}</a>{/if}:</td>
    <td class="product-subtotal">{$shipping_info.cost|l10n( 'currency', $locale, $symbol )}</td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    {* Show order total *}
    <tr>
    <td class="product-subtotal" colspan="4"><b>{'Order total'|i18n( 'design/admin/shop/basket' )}:</b></td>
    <td class="product-subtotal"><b>{$total_inc_shipping_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    {/if}
    </table>

    <h2>{"Basket summary"|i18n("design/base/shop")}:</h2>
    <table cellspacing="0">
    <td class="product-subtotal" colspan="4">{'Subtotal ex. VAT'|i18n( 'design/admin/shop/basket' )}:</td>
    <td class="product-subtotal">{$basket.total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    </td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    <tr>
    <td class="product-subtotal" colspan="4">{'Shipping total ex. VAT'|i18n( 'design/admin/shop/basket' )}:</td>
    <td class="product-subtotal">{$basket.items_info.additional_info.shipping_total.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    </td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    {foreach $basket.items_info.price_info as $vat_value => $sub_shipping}
    {if $sub_shipping.total_price_vat|gt(0)}
    <tr>
    <td class="product-subtotal" colspan="4">{'Total VAT'|i18n( 'design/admin/shop/basket' )} ({$vat_value}%):
    <td class="product-subtotal">{$sub_shipping.total_price_vat|l10n( 'currency', $locale, $symbol )}</td>
    </td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    {/if}
    {/foreach}
    <tr>
    <td class="product-subtotal" colspan="4"><b>{'Order total'|i18n( 'design/admin/shop/basket' )}:</b></td>
    <td class="product-subtotal"><b>{$basket.items_info.total_price_info.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
     </table>
      </div>

     <div class="buttonblock">
         <input class="shopbutton" type="submit" name="CheckoutButton" value="{'Checkout'|i18n('design/base/shop')}" /> &nbsp;
     </div>

     <div class="buttonblock">
         <input class="shopbutton" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n('design/base/shop')}" />
         <input class="shopbutton" type="submit" name="StoreChangesButton" value="{'Store quantities'|i18n('design/base/shop')}" />
     </div>

    {undef $currency $locale $symbol}
    {section-else}

    <div class="feedback">
        <h2>{"You have no products in your basket"|i18n("design/base/shop")}</h2>
    </div>

    {/section}

    </form>
</div>
