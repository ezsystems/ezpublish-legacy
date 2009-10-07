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

{def $currency = false()
     $locale = false()
     $symbol = false()}

{section show=$order_list}
<table class="list" cellspacing="0">
<tr>
	<th>{'ID'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Time'|i18n( 'design/admin/shop/customerorderview' )}</th>
	<th>{'Status'|i18n( 'design/admin/shop/customerorderview' )}</th>
</tr>
{section var=Orders loop=$order_list sequence=array( bglight, bgdark )}
{set currency = fetch( 'shop', 'currency', hash( 'code', $Orders.item.productcollection.currency_code ) ) }
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{else}
    {set locale = false()
         symbol = false()}
{/if}

<tr class="{$Orders.sequence}">
	<td><a href={concat( '/shop/orderview/', $Orders.item.id, '/' )|ezurl}>{$Orders.item.order_nr}</a></td>
	<td class="number" align="right">{$Orders.item.total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="right">{$Orders.item.total_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td>{$Orders.item.created|l10n( shortdatetime )}</td>
	<td>{$Orders.item.status_name|wash}</td>
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

{def $quantity_text = ''
     $total_ex_vat_text = ''
     $total_inc_vat_text = ''
     $br_tag = ''}

{section var=Products loop=$product_list sequence=array( bglight, bgdark )}

    {set quantity_text = ''
         total_ex_vat_text = ''
         total_inc_vat_text = ''
         br_tag = ''}

    {foreach $Products.product_info as $currency_code => $info}
        {if $currency_code}
            {set currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) ) }
        {else}
            {set currency = false()}
        {/if}
        {if $currency}
            {set locale = $currency.locale
                 symbol = $currency.symbol}
        {else}
            {set locale = false()
                 symbol = false()}
        {/if}

        {set quantity_text = concat( $quantity_text, $br_tag, $info.sum_count) }
        {set total_ex_vat_text = concat($total_ex_vat_text, $br_tag, $info.sum_ex_vat|l10n( 'currency', $locale, $symbol )) }
        {set total_inc_vat_text = concat($total_inc_vat_text, $br_tag, $info.sum_inc_vat|l10n( 'currency', $locale, $symbol )) }

        {if $br_tag|not()}
            {set br_tag = '<br />'}
        {/if}
    {/foreach}

    <tr class="{$Products.sequence}">
       {if and( $Products.product, $Products.product.main_node )}
            {let node_url=$Products.product.main_node.url_alias}
                <td class="name">{$Products.product.class_identifier|class_icon( small, $Products.product.class_name )}&nbsp;{if $node_url}<a href={$node_url|ezurl}>{/if}{$Products.product.name|wash}{if $node_url}</a>{/if}</td>
            {/let}
        {else}
            <td class="name">{false()|class_icon( small )}&nbsp;{$Products.name|wash}</td>
        {/if}
        <td class="number" align="right">{$quantity_text}</td>
        <td class="number" align="right">{$total_ex_vat_text}</td>
        <td class="number" align="right">{$total_inc_vat_text}</td>
    </tr>
{/section}
</table>
{/section}

{undef}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

