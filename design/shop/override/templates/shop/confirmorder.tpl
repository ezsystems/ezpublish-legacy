<form method="post" action={"/shop/confirmorder/"|ezurl}>

<h1>Confirm order</h1>

{shop_account_view_gui view=html order=$order}

<label>Comment:</label>
<p>
{$order.account_information.comment|wash(xhtml)}
</p>

<div class="basket">
<table class="list" cellspacing="0">
<tr>
	<th>
	{"Product"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Price inc. VAT"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Count"|i18n("design/standard/shop")}:
	</th>
	<th>
	{"Total Price inc. VAT"|i18n("design/standard/shop")}:
	</th>
</tr>
{let name=ProductItem product_price_ex_vat_ex_discount=0}
{section loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
{set product_price_ex_vat_ex_discount=sum($:product_price_ex_vat_ex_discount,$:item.price_ex_vat)}
<tr class="{$ProductItem:sequence}">
	<td class="product">
	<input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />
	<a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a>
	</td>
	<td class="price">
	{$ProductItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="amount">
	{$ProductItem:item.item_count}
	</td>
	<td class="totalprice">
	{$ProductItem:item.total_price_inc_vat|l10n(currency)}
	</td>
</tr>
{section show=$ProductItem:item.item_object.option_list}
<tr>
  <td class="{$ProductItem:sequence}" colspan="3">
     <table width="100%"  border="0">
<tr>
<td colspan="3">
Selected options
</td> 
</tr>
     {section name=Options loop=$ProductItem:item.item_object.option_list}
      <tr> 
        <td width="33%">{$ProductItem:Options:item.name}</td>
        <td width="33%">{$ProductItem:Options:item.value}</td>
        <td width="33%">{$ProductItem:Options:item.price|l10n(currency)}</td>
      </tr>
    {/section}
     </table>
   </td>
  <td class="{$ProductItem:sequence}" colspan="3">
  </td>
</tr>
{/section}

{/section}

<tr>
     <td colspan="3">
     </td>
     <td>
     <b>{"Subtotal Inc. VAT:"|i18n("design/standard/shop")}</b>
     </td>
     <td>
     </td>
</tr>
<tr>
<td colspan="3">
</td>
<td>
{$order.total_inc_vat|l10n(currency)}
</td>
<td>
</td>
</tr>
</table>
</div>

{/let}


<b>{"Order summary:"|i18n("design/standard/shop")}</b><br />
<table class="list" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="bgdark">
    {"Subtotal of items:"|i18n("design/standard/shop")}
    </td>
    <td class="bgdark">
    {$order.product_total_inc_vat|l10n(currency)}
    </td>
    <td class="bgdark">
    &nbsp;
    </td>
</tr>

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$OrderItem:sequence}">
	{$OrderItem:item.description}:
	</td>
	<td class="{$OrderItem:sequence}">
	{$OrderItem:item.price_inc_vat|l10n(currency)}
	</td>
	<td class="{$OrderItem:sequence}">
	( {$OrderItem:item.vat_value}% VAT )
	</td>
</tr>
{/section}
<tr>
    <td class="bgdark">
    <b>{"Order total:"|i18n("design/standard/shop")}</b>
    </td>
    <td class="bgdark">
    <b>{$order.total_inc_vat|l10n(currency)}</b>
    </td>
    <td class="bgdark">
    &nbsp;
    </td>
</tr>
</table>

<p>
Should you have any questions, feel free to send an e-mail to <a href="mailto:{ezini("MailSettings","AdminEmail")}">{ezini("MailSettings","AdminEmail")}</a>.
</p>

<div class="buttonblock">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/shop')}" /> &nbsp;
<input class="defaultbutton" type="submit" name="ConfirmOrderButton" value="{'Confirm'|i18n('design/standard/shop')}" /> &nbsp;
</div>

</form>
