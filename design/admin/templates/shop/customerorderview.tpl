<div class="context-block">
<h2 class="context-title">{'Customer information'|i18n( 'design/admin/shop/customerorderview' )}</h2>

{shop_account_view_gui view=html order=$order_list[0]}

</div>

<div class="context-block">

<h2 class="context-title">{'Orders [%order_count]'|i18n( 'design/admin/shop/customerorderview',, hash( '%order_count', $order_list|count ) )}</h2>
{section show=$order_list}
<table class="list" cellspacing="0">
<tr>
	<th>{'ID'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Time'|i18n( 'design/admin/shop/customerorderview' )}</th>
</tr>
{section var=Orders loop=$order_list sequence=array( bglight, bgdark )}
<tr class="{$Orders.sequence}">
	<td><a href={concat( '/shop/orderview/', $Orders.item.id, '/' )|ezurl}>{$Orders.item.order_nr}</a></td>
	<td>{$Orders.item.total_ex_vat|l10n( currency )}</td>
	<td>{$Orders.item.total_inc_vat|l10n( currency )}</td>
	<td>{$Orders.item.created|l10n( shortdatetime )}</td>
</tr>
{/section}
</table>
</section>


</div>

<div class="context-block">
<h2 class="context-title">{'Purchased products [%product_count]'|i18n( 'design/admin/shop/customerorderview',, hash( '%product_count', $product_list|count ) )}</h2>

{section show=$product_list}
<table class="list" cellspacing="0">
<tr>
	<th>{'Product'|i18n( 'design/admin/shop/customerorderview' )}</th>
    <th>{'Quantity'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/customerorderview' )}</th>
</tr>

{section var=Products loop=$product_list sequence=array( bglight, bgdark )}
<tr class="{$Products.sequence}">
	<td>{content_view_gui view=text_linked content_object=$Products.product}</td>
    <td>{$Products.sum_count}</td>
	<td>{$Products.sum_ex_vat|l10n( currency )}</td>
	<td>{$Products.sum_inc_vat|l10n( currency )}</td>
</tr>
{/section}
</table>
