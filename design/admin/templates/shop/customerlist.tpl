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

{section var=Customers loop=$customer_list sequence=array( bglight, bgdark )}
<tr class="{$Customers.sequence}">
	<td><a href={concat( '/shop/customerorderview/', $Customers.user_id, '/', $Customers.email )|ezurl}>{$Customers.account_name}</a></td>
	<td>{$Customers.order_count}</td>
	<td>{$Customers.sum_ex_vat|l10n( currency )}</td>
	<td>{$Customers.sum_inc_vat|l10n( currency )}</td>
</tr>
{/section}

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

<p>{'The customer list is empty.'|i18n( 'design/admin/shop/customerlist' )}</p>

{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
