{* Feedback if some items were removed by the system. *}
{section show=$removed_items}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Items removed'|i18n( 'design/admin/shop/basket' )}</h2>
<p>{'The following items were removed from your basket because the products have changed'|i18n( 'design/admin/shop/basket' )}:</p>
<ul>
{section var=RemovedItems loop=$removed_items}
<li><a href={concat( '/content/view/full/', $RemovedItems.item.contentobject.main_node_id, '/' )|ezurl}>{$RemovedItems.item.contentobject.name|wash}</a></li>
{/section}
</ul>
</div>
{/section}

{if not( $vat_is_known )}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'VAT is unknown'|i18n( 'design/admin/shop/basket' )}</h2>
{'VAT percentage is not yet known for some of the items being purchased.'|i18n( 'design/admin/shop/basket' )}<br/>
{'This probably means that some information about you is not yet available and will be obtained during checkout.'|i18n( 'design/admin/shop/basket' )}
</div>
{/if}

{section show=$error}
<div class="message-warning">
{if eq( $error, "invaliditemcount" )}
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span>
    {"Incorrect quantity! The quantity of the product(s) must be numeric and not less than 1."|i18n("design/standard/shop",,)}</h2>
{/if}
    {section show=eq( $error, "options")}
       <h2>{"You have chosen invalid combination of options"|i18n("design/standard/shop",,)}</h2>
        <ul>
          {section var=UnvalidatedOptions loop=$error_data}
            <li>{$UnvalidatedOptions.item.name}</li>
                  <ul>
                      {section var=Errors loop=$UnvalidatedOptions.item.description}
                          <li>{$Errors.item}</li>
                      {/section}
                  </ul>
          {/section}
        </ul>
    {/section}
</div>
{/section}

<form name="basket" method="post" action={'/shop/basket/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Shopping basket'|i18n( 'design/admin/shop/basket' )}</h1>

{* DESIGN: Subline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$basket.items}

{def $currency = fetch( 'shop', 'currency', hash( 'code', $basket.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/basket' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/basket' )}" onclick="ezjs_toggleCheckboxes( document.basket, 'RemoveProductItemDeleteList[]' ); return false;" /></th>
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
	<td><input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Products.item.id}" title="{'Select item for removal.'|i18n( 'design/admin/shop/basket' )}" /></td>
	<td><input type="hidden" name="ProductItemIDList[]" value="{$Products.item.id}" /><a href={concat( '/content/view/full/', $Products.item.node_id, '/' )|ezurl}>{$Products.item.object_name}</a></td>
	<td><input type="text" name="ProductItemCountList[]" value="{$Products.item.item_count}" size="3" /></td>
	<td class="number" align="right">
    {if ne( $Products.item.vat_value, -1 )}
        {$Products.item.vat_value} %
    {else}
        {'unknown'|i18n( 'design/admin/shop/basket' )}
    {/if}
    </td>
	<td class="number" align="right">{$Products.item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="right">{$Products.item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="right">{$Products.item.discount_percent}%</td>
	<td class="number" align="right">{$Products.item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="right">{$Products.item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{section show=$Products.item.item_object.option_list}
<tr class="{$Products.sequence}">
    <td></td>
    <td colspan="8">
    {*'Selected options'|i18n( 'design/admin/shop/basket' ): *}
{section var=Options loop=$Products.item.item_object.option_list}
{$Options.item.name|wash}: {$Options.item.value}
{delimiter}, {/delimiter}
{/section}
    </td>
</tr>
{/section}
{/section}
<tr>
<td colspan="9"><hr /></td>
</tr>
<td colspan="7">
</td>
<tr>
<td colspan="1">&nbsp;</td>
<td colspan="6">{'Subtotal of items'|i18n( 'design/admin/shop/basket' )}:</td>
<td class="number" align="right">{$basket.total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
<td class="number" align="right">{$basket.total_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{if is_set( $shipping_info )}
{* Show shipping type/cost. *}
<tr>
<td colspan="1">&nbsp;</td>
<td colspan="6"><a href={$shipping_info.management_link|ezurl}>{'Shipping'|i18n( 'design/admin/shop/basket' )}{if $shipping_info.description} ({$shipping_info.description}){/if}</a>:</td>
<td class="number" align="right">{$shipping_info.cost|l10n( 'currency', $locale, $symbol )}</td>
<td class="number" align="right">{$shipping_info.cost|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{* Show order total *}
<tr>
<td colspan="7"><b>{'Order total'|i18n( 'design/admin/shop/basket' )}</b>:</td>
<td class="number" align="right">{$total_inc_shipping_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
<td class="number" align="right">{$total_inc_shipping_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>

<tr>
<td colspan="9"><hr /></td>
</tr>

{/if}
</table>

{section-else}
<div class="block">
    <p>{'There are no items in your shopping basket.'|i18n( 'design/admin/shop/basket' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    {if $basket.items}
    <div class="button-left">
        <input class="button" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/basket' )}" title="{'Remove selected items from the basket.'|i18n( 'design/admin/shop/basket' )}" />
        <input class="button" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/basket' )}" title="{'Click this button to update the basket if you have modified any quantity and/or option fields.'|i18n( 'design/admin/shop/basket' )}" />
    </div>
    <div class="button-right">
        <input class="button" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n( 'design/admin/shop/basket' )}" title="{'Leave the basket and continue shopping.'|i18n( 'design/admin/shop/basket' )}" />
        <input class="button" type="submit" name="CheckoutButton" value="{'Checkout'|i18n( 'design/admin/shop/basket' )}" title="{'Proceed to checkout and purchase the items that are in the basket.'|i18n( 'design/admin/shop/basket' )}" />
    </div>
    {else}
    <div class="button-left">
        <input class="button-disabled" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/basket' )}" disabled="disabled" title="{'You cannot remove any items because there are no items in the basket.'|i18n( 'design/admin/shop/basket' )}" />
        <input class="button-disabled" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/basket' )}" disabled="disabled" title="{'You cannot store any changes because the basket is empty.'|i18n( 'design/admin/shop/basket' )}" />
    </div>
    <div class="button-right">
        <input class="button-disabled" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n( 'design/admin/shop/basket' )}" disabled="disabled" title="{'Leave the basket and continue shopping.'|i18n( 'design/admin/shop/basket' )}" />
        <input class="button-disabled" type="submit" name="CheckoutButton" value="{'Checkout'|i18n( 'design/admin/shop/basket' )}" disabled="disabled" title="{'You cannot check out because the basket is empty.'|i18n( 'design/admin/shop/basket' )}" />
    </div>
    {/if}
    <div class="break"></div>
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>
</form>

