{* Customer information *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Customer information'|i18n( 'design/admin/shop/customerorderview' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="context-attributes">
{shop_account_view_gui view=html order=$order_list[0]}
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>


{* Orders *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Orders [%order_count]'|i18n( 'design/admin/shop/customerorderview',, hash( '%order_count', $order_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

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
	<td class="number" align="right">{$Orders.item.total_ex_vat|l10n( currency )}</td>
	<td class="number" align="right">{$Orders.item.total_inc_vat|l10n( currency )}</td>
	<td>{$Orders.item.created|l10n( shortdatetime )}</td>
</tr>
{/section}
</table>
{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>


{* Purchased products *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Purchased products [%product_count]'|i18n( 'design/admin/shop/customerorderview',, hash( '%product_count', $product_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

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
    {section show=and( $Products.product, $Products.product.main_node )}
    {let node_url=$Products.product.main_node.url_alias}
    <td>{$Products.product.class_identifier|class_icon( small, $Products.product.class_name )}&nbsp;{section show=$node_url}<a href={$node_url|ezurl}>{/section}{$Products.product.name|wash}{section show=$node_url}</a>{/section}</td>
    {/let}
    {section-else}
    <td>{false()|class_icon( small )}&nbsp;{$Products.name|wash}</td>
    {/section}
    <td class="number" align="right">{$Products.sum_count}</td>
	<td class="number" align="right">{$Products.sum_ex_vat|l10n( currency )}</td>
	<td class="number" align="right">{$Products.sum_inc_vat|l10n( currency )}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

