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

{def $currency = false()
     $locale = false()
     $symbol = false()
     $order_count_text = ''
     $sum_ex_vat_text = ''
     $sum_inc_vat_text = ''
     $br_tag = ''}

{section var=Customers loop=$customer_list sequence=array( bglight, bgdark )}

    {set order_count_text = ''
         sum_ex_vat_text = ''
         sum_inc_vat_text = ''
         br_tag = ''}

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

        {set order_count_text = concat( $order_count_text, $br_tag, $order_info.order_count) }
        {set sum_ex_vat_text = concat($sum_ex_vat_text, $br_tag, $order_info.sum_ex_vat|l10n( 'currency', $locale, $symbol )) }
        {set sum_inc_vat_text = concat($sum_inc_vat_text, $br_tag, $order_info.sum_inc_vat|l10n( 'currency', $locale, $symbol )) }

        {if $br_tag|not()}
            {set br_tag = '<br />'}
        {/if}
    {/foreach}
    <tr class="{$Customers.sequence}">
        <td class="name"><a href={concat( '/shop/customerorderview/', $Customers.user_id, '/', $Customers.email|wash )|ezurl}>{$Customers.account_name|wash}</a></td>
        <td class="number" align="right">{$order_count_text}</td>
    	<td class="number" align="right">{$sum_ex_vat_text}</td>
    	<td class="number" align="right">{$sum_inc_vat_text}</td>
    </tr>

{section-else}

{/section}
{undef}
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
