<div class="context-block">

<form action={concat("/shop/orderlist")|ezurl} method="post" name="Orderlist">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Order #%order_id [%order_status]'|i18n( 'design/admin/shop/orderview',,
                            hash( '%order_id', $order.order_nr,
                                  '%order_status', $order.status_name ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Conten START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{shop_account_view_gui view=html order=$order}
{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

<b>{'Product items'|i18n( 'design/admin/shop/orderview' )}</b>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{'Product'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Count'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'VAT'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Price ex. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Price inc. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Discount'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Total price ex. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Total price inc. VAT'|i18n( 'design/admin/shop/orderview' )}</th>
</tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items}
<tr>
    {if and( $ProductItem:item.item_object.contentobject, $ProductItem:item.item_object.contentobject.main_node )}
    {let node_url=$ProductItem:item.item_object.contentobject.main_node.url_alias}
    <td>{$ProductItem:item.item_object.contentobject.class_identifier|class_icon( small,$ProductItem:item.item_object.contentobject.class_name )}&nbsp;{if $:node_url}<a href={$:node_url|ezurl}>{/if}{$ProductItem:item.item_object.contentobject.name|wash}{if $:node_url}</a>{/if}</td>
    {/let}
    {else}
    <td>{false()|class_icon( small )}&nbsp;{$ProductItem:item.item_object.name|wash}</td>
    {/if}
    <td class="number" align="right">{$ProductItem:item.item_count}</td>
    <td class="number" align="right">{$ProductItem:item.vat_value}&nbsp;%</td>
    <td class="number" align="right">{$ProductItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$ProductItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$ProductItem:item.discount_percent}&nbsp;%</td>
    <td class="number" align="right">{$ProductItem:item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$ProductItem:item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{section show=$ProductItem:item.item_object.option_list}
<tr>
    <td colspan='3'>
    <table border="0">
    <tr>
        <td colspan='3'>{'Selected options'|i18n( 'design/admin/shop/orderview' )}</td>
    </tr>
    {section var=Options loop=$ProductItem:item.item_object.option_list}
    <tr>
        <td>{$:Options.item.name|wash}</td>
        <td>{$:Options.item.value}</td>
        <td class="number" align="right">{$:Options.item.price|l10n( 'currency', $locale, $symbol )}</td>
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

<b>{'Order summary'|i18n( 'design/admin/shop/orderview' )}:</b><br />
<table class="list" cellspacing="0">
<tr>
    <td>{'Subtotal of items'|i18n( 'design/admin/shop/orderview' )}:</td>
    <td class="number" align="right">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>

{section name=OrderItem loop=$order.order_items show=$order.order_items}
<tr>
    <td>{$OrderItem:item.description}:</td>
    <td class="number" align="right">{$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$OrderItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{/section}
<tr>
    <td><b>{'Order total'|i18n( 'design/admin/shop/orderview' )}</b></td>
    <td class="number" align="right"><b>{$order.total_ex_vat|l10n( 'currency', $locale, $symbol )}</b></td>
    <td class="number" align="right"><b>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
</tr>
</table>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="hidden" name="OrderIDArray[]" value="{$order.id}" />
<input class="button" type="submit" name="RemoveButton" value="{'Remove'|i18n( 'design/admin/shop/orderview' )}" title="{'Remove this order.'|i18n( 'design/admin/shop/orderview' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</form>

</div>

{* Status history *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Status history [%status_count]'|i18n( 'design/admin/shop/orderview',,
                            hash( '%status_count', fetch( shop, order_status_history_count, hash( 'order_id', $order.order_nr ) ) ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{let order_status_history=fetch( shop, order_status_history, hash( 'order_id', $order.order_nr ) )}
{section show=$order_status_history|count|gt( 0 )}
<table class="list" cellspacing="0">
<tr>
    <th>{'Date'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Status'|i18n( 'design/admin/shop/orderview' )}</th>
    <th>{'Person'|i18n( 'design/admin/shop/orderview' )}</th>
</tr>

{section var=history loop=$order_status_history sequence=array( bglight, bgdark )}

<tr class="{$history.sequence}">
    {if eq( $order.status_modified, $history.modified )}
    {* The current history element should be highlighted *}

    <td class="date"><strong>{$history.modified|l10n( shortdatetime )}</strong></td>
    <td><strong>{$history.status_name|wash}</strong></td>

    {let modifier=$history.modifier}
    <td><a href={$modifier.main_node.url|ezurl} title="{'This is the person who modified the status of the order. Click to view the user information.'|i18n( 'design/admin/shop/orderview' )}"><strong>{$modifier.name|wash}</strong></a></td>
    {/let}

    {else}

    <td class="date">{$history.modified|l10n( shortdatetime )}</td>
    <td>{$history.status_name|wash}</td>

    {let modifier=$history.modifier}
    <td><a href={$modifier.main_node.url|ezurl} title="{'This is the person who modified the status of the order. Click to view the user information.'|i18n( 'design/admin/shop/orderview' )}">{$modifier.name|wash}</a></td>
    {/let}

    {/if}
</tr>

{/section}

</table>
{/section}
{/let}

{* DESIGN: Content END *}</div></div></div>

</div>
