<div class="shop-orderview">
    <h1>{"Order %1"|i18n("design/standard/shop",,array($order.order_nr))}</h1>

    {shop_account_view_gui view=html order=$order}

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
    </tr>
    {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$product_item.sequence} product-name" colspan="6">
        <a href={concat("/content/view/full/",$product_item.node_id,"/")|ezurl}>{$product_item.object_name}</a>
        </td>
    </tr>
    <tr>
        <td class="{$product_item.sequence} product-details">
            {$product_item.item_count}
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
         <strong>{$order.product_total_inc_vat|l10n(currency)}</strong>
         </td>
         <td class="product-subtotal">
         &nbsp;
         </td>

     </tr>
     </table>
     </div>



    <h2>{"Order summary"|i18n("design/standard/shop")}:</h2>
    <table class="list" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td class="bgdark">
        {"Subtotal of items"|i18n("design/standard/shop")}:
        </td>
        <td class="bgdark">
        {$order.product_total_inc_vat|l10n(currency)}
        </td>
    </tr>
    {section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$OrderItem:sequence}">
        {$OrderItem:item.description}:
    	</td>
        <td class="{$OrderItem:sequence}">
        {$OrderItem:item.price_inc_vat|l10n(currency)}
    	</td>
    </tr>
    {/section}
    <tr>
        <td class="bgdark">
        <b>{"Order total"|i18n("design/standard/shop")}</b>
        </td>
        <td class="bgdark">
        <b>{$order.total_ex_vat|l10n(currency)}</b>
        </td>
        <td class="bgdark">
        <b>{$order.total_inc_vat|l10n(currency)}</b>
        </td>
    </tr>
    </table>
</div>
