<form action={concat("/shop/customlist")|ezurl} method="post" name="Customlist">
<div class="maincontentheader">
  <h1>{"Custom list"|i18n("design/standard/shop")}</h1>
</div>

{section show=$custom_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"Custom"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Number of orders"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total inc. VAT"|i18n("design/standard/shop")}
	</th>
</tr>

{section var="Custom" loop=$custom_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Custom.sequence}">
    <a href={concat("/shop/customorderview/",$Custom.user_id,"/", $Custom.email)|ezurl}>{$Custom.account_name}</a>
	</td>
	<td class="{$Custom.sequence}">
    {$Custom.order_count}
	</td>
	<td class="{$Custom.sequence}">
	{$Custom.sum_ex_vat|l10n(currency)}
	</td>
	<td class="{$Custom.sequence}">
	{$Custom.sum_inc_vat|l10n(currency)}
</tr>
{/section}
</table>
{section-else}

<div class="feedback">
  <h2>{"The custom list is empty"|i18n("design/standard/shop")}</h2>
</div>

{/section}

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/shop/customlist/')
         item_count=$custom_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</form>