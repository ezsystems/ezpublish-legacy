<a href={"/manual/user/e-commerce#Order"|ezurl} target="_ezpublishmanual"><img src={"help.gif"|ezimage} align="right" /> </a>

<div class="maincontentheader">
<h1>{"Order list"|i18n("design/standard/shop")}</h1>
</div>


{section show=$order_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"ID"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Date"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Customer"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total inc. VAT"|i18n("design/standard/shop")}
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
{section-else}

<div class="feedback">
<h2>{"The order list is empty"|i18n("design/standard/shop")}</h2>
</div>

{/section}


{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/shop/orderlist/',$node.node_id)
         item_count=$order_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
