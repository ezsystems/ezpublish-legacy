{* Feedback if some items were removed by the system. *}
{section show=$removed_items}
<div class="message-warning">
<h2>{'The following items were removed from your basket because the products have changed:'|i18n( 'design/admin/shop/basket' )}</h2>
<ul>
{section var=RemovedItems loop=$removed_items}
<li><a href={concat( '/content/view/full/', $RemovedItems.item.contentobject.main_node_id, '/' )|ezurl}>{$RemovedItems.item.contentobject.name}</a></li>
{/section}
</ul>
</div>
{/section}

<form method="post" action={'/shop/basket/'|ezurl}>

<div class="context-block">
<h2 class="context-title">{'Shopping basket'|i18n( 'design/admin/shop/basket' )}</h2>

{section show=$basket.items}
<table class="list" cellspacing="0">
<tr>
	<th class="tight">&nbsp;</th>
	<th>{'Product'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'Quantity'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'VAT'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'Price (ex. VAT)'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'Price (inc. VAT)'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'Discount'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'Total (ex. VAT)'|i18n( 'design/admin/shop/basket' )}</th>
	<th>{'Total (inc. VAT)'|i18n( 'design/admin/shop/basket' )}</th>
</tr>
{section var=Products loop=$basket.items sequence=array( bglight, bgdark )}
<tr class="{$Products.sequence}">
	<td><input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Products.item.id}" /></td>
	<td><input type="hidden" name="ProductItemIDList[]" value="{$Products.item.id}" /><a href={concat( '/content/view/full/', $Products.item.node_id, '/' )|ezurl}>{$Products.item.object_name}</a></td>
	<td><input type="text" name="ProductItemCountList[]" value="{$Products.item.item_count}" size="3" /></td>
	<td>{$Products.item.vat_value} %</td>
	<td>{$Products.item.price_ex_vat|l10n( currency )}</td>
	<td>{$Products.item.price_inc_vat|l10n( currency )}</td>
	<td>{$Products.item.discount_percent}%</td>
	<td>{$Products.item.total_price_ex_vat|l10n( currency )}</td>
	<td>{$Products.item.total_price_inc_vat|l10n( currency )}</td>
</tr>
{section show=$Products.item.item_object.option_list}
<tr>
  <td colspan='4'>
  <table class="shop-option_list">
  <tr>
<td colspan='3'>
{'Selected options'|i18n( 'design/admin/shop/basket' )}
</td>
</tr>
     {section var=Options loop=$Products.item.item_object.option_list}
      <tr>
        <td width="33%">{$Options.item.name}</td>
        <td width="33%">{$Options.item.value}</td>
        <td width="33%">{$Options.item.price|l10n( currency )}</td>
      </tr>
    {/section}
     </table>
   </td>
  <td class="{$Products.sequence}" colspan='5'>
  </td>
</tr>
{/section}
{/section}
<tr>
<td colspan="9"><hr /></td>
</tr>
<tr>
<td colspan="7">&nbsp;</td>
<td><b>{'Subtotal (ex. VAT)'|i18n( 'design/admin/shop/basket' )}</b></td>
<td><b>{'Subtotal (inc. VAT)'|i18n( 'design/admin/shop/basket' )}</b></td>
</tr>
<tr>
<td colspan="7">
</td>
<td>{$basket.total_ex_vat|l10n( currency )}</td>
<td>{$basket.total_inc_vat|l10n( currency )}</td>
</tr>
</table>


{* Buttons. *}
<div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/basket' )}" />
        <input class="button" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/basket' )}" />
    <div class="right">
        <input class="button" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n( 'design/admin/shop/basket' )}" />
        <input class="button" type="submit" name="CheckoutButton" value="{'Checkout'|i18n( 'design/admin/shop/basket' )}" />
    </div>
    </div>
</div>

{section-else}
    <p>{'There are no products in your shopping basket.'|i18n( 'design/admin/shop/basket' )}</p>
{/section}

</div>
</form>
