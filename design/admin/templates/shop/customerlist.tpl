<form action={concat( '/shop/customerlist' )|ezurl} method="post">

<div class="context-block">
<h2 class="context-title">{'Customers'|i18n( 'design/standard/shop' )}</h2>

{section show=$customer_list}
<table class="list" cellspacing="0">
<tr>
	<th>{'Name'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th>{'Orders'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/customerlist' )}</th>
</tr>

{section var=Customer loop=$customer_list sequence=array( bglight, bgdark )}
<tr class="{$Customer.sequence}">
	<td><a href={concat( '/shop/customerorderview/', $Customer.user_id, '/', $Customer.email )|ezurl}>{$Customer.account_name}</a></td>
	<td>{$Customer.order_count}</td>
	<td>{$Customer.sum_ex_vat|l10n( currency )}</td>
	<td>{$Customer.sum_inc_vat|l10n( currency )}</td>
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

</div>

</form>