<form method="post" action={"/shop/confirmorder/"|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Order confirmation'|i18n( 'design/admin/shop/confirmorder' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<p>{'Please confirm that the information below is correct. Click "Confirm order" to confirm the order.'|i18n( 'design/admin/shop/confirmorder' )}</p>

<div class="block">
<label>{'Customer'|i18n( 'design/admin/shop/confirmorder' )}:</label>
{shop_account_view_gui view=html order=$order}
</div>

{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

<div class="block">
<label>{'Items to be purchased'|i18n( 'design/admin/shop/confirmorder' )}:</label>
<table class="list" cellspacing="0">
<tr>
    <th>{'Product'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'Quantity'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'VAT'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'Price (ex. VAT)'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'Price (inc. VAT)'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'Discount'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'Total price (ex. VAT)'|i18n( 'design/admin/shop/confirmorder' )}</th>
    <th>{'Total price (inc. VAT)'|i18n( 'design/admin/shop/confirmorder' )}</th>
</tr>
{section var=Items loop=$order.product_items show=$order.product_items sequence=array( bglight, bgdark )}
<tr class="{$Items.sequence}">
    <td>
    <input type="hidden" name="ProductItemIDList[]" value="{$Items.item.id}" />
    <a href={concat( '/content/view/full/', $Items.item.node_id, '/' )|ezurl}>{$Items.item.object_name}</a>
    </td>
    <td class="number" align="right">{$Items.item.item_count}</td>
    <td class="number" align="right">{$Items.item.vat_value}%</td>
    <td class="number" align="right">{$Items.item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$Items.item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$Items.item.discount_percent}%</td>
    <td class="number" align="right">{$Items.item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$Items.item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{section show=$Items.item.item_object.option_list}
<tr>
    <td colspan='3'>
    <table border="0">
    <tr>
        <td colspan='3'>{'Selected options'|i18n( 'design/admin/shop/confirmorder' )}</td>
    </tr>
    {section var=Options loop=$Items.item.item_object.option_list}
    <tr>
        <td>{$Options.item.name|wash}</td>
        <td>{$Options.item.value}</td>
        <td class="number" align="right">{$Options.item.price|l10n( 'currency', $locale, $symbol )}</td>
    </tr>
    {/section}
    </table>
    </td>
    <td colspan='5'>
  </td>
</tr>
{/section}
{/section}
</table>
</div>

<div class="block">
<label>{'Order summary'|i18n( 'design/admin/shop/confirmorder' )}:</label>
<table class="list" cellspacing="0">
<tr>
    <td>{'Subtotal of items'|i18n( 'design/admin/shop/confirmorder' )}:</td>
    <td class="number" align="right">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>

{section var=OrderItems loop=$order.order_items show=$order.order_items}
<tr>
    <td>{$OrderItems.item.description}:</td>
    <td class="number" align="right">{$OrderItems.item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$OrderItems.item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{/section}
<tr>
    <td><b>{'Order total'|i18n( 'design/admin/shop/confirmorder' )}:</b> </td>
    <td class="number" align="right"><b>{$order.total_ex_vat|l10n( 'currency', $locale, $symbol )}</b></td>
    <td class="number" align="right"><b>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
</tr>
</table>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="ConfirmOrderButton" value="{'Confirm order'|i18n( 'design/admin/shop/confirmorder' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/shop/confirmorder' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
