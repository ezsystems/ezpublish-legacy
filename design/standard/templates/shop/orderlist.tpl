<div class="maincontentheader">
<h1>{"Order list"|i18n}</h1>
</div>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	ID
	</th>
	<th>
	Date
	</th>
	<th>
	Customer
	</th>
	<th>
	Total ex. VAT
	</th>
	<th>
	Total inc. VAT
	</th>
</tr>
{section name="Order" loop=$order_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Order:sequence}">
	{$Order:item.order_nr}
	</td>
	<td class="{$Order:sequence}">
	{$Order:item.created|l10n(date)}
	</td>
	<td class="{$Order:sequence}">
	{content_view_gui view=text_linked content_object=$Order:item.user.contentobject}
	</td>
	<td class="{$Order:sequence}">
	{$Order:item.total_ex_vat|l10n(currency)}
	</td>
	<td class="{$Order:sequence}">
	{$Order:item.total_inc_vat|l10n(currency)}
	</td>
	<td class="{$Order:sequence}">
	<a href={concat("/shop/orderview/",$Order:item.id,"/")|ezurl}>[ view ]</a>
	</td>
</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/shop/orderlist/',$node.node_id)
         item_count=$order_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
