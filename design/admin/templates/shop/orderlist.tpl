<form action={concat("/shop/orderlist")|ezurl} method="post" name="Orderlist">
<div class="context-block">
<h2 class="context-title">{'Orders [%count]'|i18n( 'design/admin/shop/orderlist',, hash( '%count', $order_list|count ) )}</h2>

{'Sort Result by'|i18n('design/admin/shop/orderlist' )}: <select name="SortField">
     <option value="created" {switch match=$sort_field}{case match="created"} selected="selected"{/case}{case}{/case}{/switch}>{'Order Time'|i18n('design/admin/shop/orderlist' )}</option>
     <option value="user_name" {switch match=$sort_field}{case match="user_name"} selected="selected"{/case}{case}{/case}{/switch}>{'User Name'|i18n('design/admin/shop/orderlist' )}</option>
     <option value="order_nr" {switch match=$sort_field}{case match="order_nr"} selected="selected"{/case}{case}{/case}{/switch}>{'Order ID'|i18n('design/admin/shop/orderlist' )}</option>
</select>
<img src={"asc-transp.gif"|ezimage} alt="{'Ascending'|i18n('design/admin/shop/orderlist' )}" title="{'Sort ascending'|i18n('design/admin/shop/orderlist' )}" /><input type="radio" name="SortOrder" value="asc" {section show=eq($sort_order,"asc")}checked="checked"{/section} />
<img src={"desc-transp.gif"|ezimage} alt="{'Descending'|i18n('design/admin/shop/orderlist' )}" title="{'Sort descending'|i18n('design/admin/shop/orderlist' )}" /><input type="radio" name="SortOrder" value="desc" {section show=eq($sort_order,"desc")}checked="checked"{/section} />
{include uri="design:gui/button.tpl" name=Sort id_name=SortButton value="Sort"|i18n( 'design/admin/shop/orderlist' )}

{section show=$order_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
	<th>{'ID'|i18n( 'design/admin/shop/orderlist' )}</th>
	<th>{'Customer'|i18n( 'design/admin/shop/orderlist' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderlist' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderlist' )}</th>
	<th>{'Date'|i18n( 'design/admin/shop/orderlist' )}</th>
</tr>
{section var=Orders loop=$order_list sequence=array( bglight, bgdark )}
<tr class="{$Orders.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Orders.item.id}" /></td>
	<td><a href={concat("/shop/orderview/",$Orders.item.id,"/")|ezurl}>{$Orders.item.order_nr}</a></td>
	<td><a href={concat("/shop/customerorderview/",$Orders.item.user_id,"/",$Orders.item.account_email)|ezurl}>{$Orders.item.account_name}</a></td>
	<td>{$Orders.item.total_ex_vat|l10n(currency)}</td>
	<td>{$Orders.item.total_inc_vat|l10n(currency)}</td>
	<td>{$Orders.item.created|l10n(shortdatetime)}</td>
</tr>
{/section}
</table>
{section-else}
<p>{"The order list is empty"|i18n( 'design/admin/shop/orderlist' )}</p>
{/section}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/shop/orderlist'
         item_count=$order_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/admin/shop/orderlist' )}" />
</div>
</div>


</div>
</form>