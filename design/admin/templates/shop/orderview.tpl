<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Order #%order_id'|i18n( 'design/admin/shop/orderview',, hash( '%order_id', $order.order_nr ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Conten START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{shop_account_view_gui view=html order=$order}

<b>{'Product items'|i18n( 'design/admin/shop/orderview' )}</b>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{'Product'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Count'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'VAT'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Price ex. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Price inc. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Discount'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Total Price ex. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Total Price inc. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>&nbsp;</th>
</tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
<tr>
	<td>{node_view_gui content_node=$ProductItem:item.item_object.contentobject.main_node view=line}</td>
	<td>{$ProductItem:item.item_count}</td>
	<td>{$ProductItem:item.vat_value}&nbsp;%</td>
	<td>{$ProductItem:item.price_ex_vat|l10n(currency)}</td>
	<td>{$ProductItem:item.price_inc_vat|l10n(currency)}</td>
	<td>{$ProductItem:item.discount_percent}&nbsp;%</td>
	<td>{$ProductItem:item.total_price_ex_vat|l10n(currency)}</td>
	<td>{$ProductItem:item.total_price_inc_vat|l10n(currency)}</td>
</tr>
{/section}
</table>

<b>{'Order summary'|i18n( 'design/admin/shop/orderview' )}:</b><br />
<table class="list" cellspacing="0">
<tr>
    <td>{'Subtotal of items'|i18n( 'design/admin/shop/orderview' )}:</td>
    <td>{$order.product_total_ex_vat|l10n(currency)}</td>
    <td>{$order.product_total_inc_vat|l10n(currency)}</td>
</tr>

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
<tr>
	<td>{$OrderItem:item.description}:</td>
	<td>{$OrderItem:item.price_ex_vat|l10n(currency)}</td>
	<td>{$OrderItem:item.price_inc_vat|l10n(currency)}</td>
</tr>
{/section}
<tr>
    <td><b>{'Order total'|i18n( 'design/admin/shop/orderview' )}</b></td>
    <td><b>{$order.total_ex_vat|l10n(currency)}</b></td>
    <td><b>{$order.total_inc_vat|l10n(currency)}</b></td>
</tr>
</table>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="" value="{'Remove'|i18n( 'design/admin/shop/orderview' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>
