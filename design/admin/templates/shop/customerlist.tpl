<form action={concat( '/shop/customerlist' )|ezurl} method="post">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Customers [%customers]'|i18n( 'design/admin/shop/customerlist',, hash( '%customers', $customer_list|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=$customer_list}
<table class="list" cellspacing="0">
<tr>
	<th class="wide">{'Name'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Orders'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Total (ex. VAT)'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Total (inc. VAT)'|i18n( 'design/admin/shop/customerlist' )}</th>
</tr>

{def $orders_info_count = false()
     $currency = false()
     $locale = false()
     $symbol = false()}

{section var=Customers loop=$customer_list sequence=array( bglight, bgdark )}

{set orders_info_count = $Customers.orders_info|count()}

{foreach $Customers.orders_info as $currency_code => $order_info }

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

<tr class="{$Customers.sequence}">
    {if $orders_info_count }
        <td rowspan="{$orders_info_count}"><a href={concat( '/shop/customerorderview/', $Customers.user_id, '/', $Customers.email )|ezurl}>{$Customers.account_name}</a></td>
        {set orders_info_count = false()}
    {/if}
    <td class="number" align="right">{$order_info.order_count}</td>
	<td class="number" align="right">{$order_info.sum_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="right">{$order_info.sum_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{/foreach}
{section-else}

{/section}
{undef $orders_info_count $currency $locale $symbol}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/shop/customerlist'
         item_count=$customer_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{section-else}
<div class="block">
<p>{'The customer list is empty.'|i18n( 'design/admin/shop/customerlist' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
