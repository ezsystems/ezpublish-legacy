<form name="wishlistform" method="post" action={'/shop/wishlist/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'My wish list [%item_count]'|i18n( 'design/admin/shop/wishlist',, hash( '%item_count', $wish_list.items|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$wish_list.items}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/wishlist' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/wishlist' )}" onclick="ezjs_toggleCheckboxes( document.wishlistform, 'RemoveProductItemDeleteList[]' ); return false;" /></th>
    <th>{'Name'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Quantity'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'VAT'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Price (ex. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Price (inc. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Discount'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Total price (ex. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Total price (inc. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
</tr>
{section var=WishedItems loop=$wish_list.items sequence=array( bglight, bgdark )}
<tr class="{$WishedItems.sequence}">

    {* Remove. *}
    <td><input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$WishedItems.item.id}" /></td>

    {* Name. *}
    <td>
        {$WishedItems.item.item_object.contentobject.class_identifier|class_icon( small, $WishedItems.item.item_object.contentobject.class_name )}&nbsp;<a href={concat( '/content/view/full/', $WishedItems.item.node_id, '/' )|ezurl}>{$WishedItems.item.object_name}</a>
        <input type="hidden" name="ProductItemIDList[]" value="{$WishedItems.item.id}" />
	</td>

    {* Quantity. *}
    <td><input type="text" name="ProductItemCountList[]" value="{$WishedItems.item.item_count}" size="3" />	</td>

    {* VAT. *}
    <td>{$WishedItems.item.vat_value}%</td>

    {* Item price (ex. VAT.) *}
    <td>{$WishedItems.item.price_ex_vat|l10n(currency)}</td>

    {* Item price (inc. VAT.). *}
    <td>{$WishedItems.item.price_inc_vat|l10n(currency)}</td>

    {* Discount. *}
    <td>{$WishedItems.item.discount_percent}%</td>

    {* Total price (ex. VAT). *}
    <td>{$WishedItems.item.total_price_ex_vat|l10n(currency)}</td>

    {* Total price (inc. VAT). *}
    <td>{$WishedItems.item.total_price_inc_vat|l10n(currency)}</td>

</tr>

{section show=$WishedItems.item.item_object.option_list}
<tr>
    <td colspan='4'>
    <table class="list" cellspacing="0">
    <tr>
        <td colspan='3'>
        {'Selected options'|i18n( 'design/admin/shop/wishlist')}
        </td>
    </tr>
    {section var=ItemOptions loop=$WishedItems.item.item_object.option_list}
        <tr>
            <td>{$ItemOptions.item.name|wash}</td>
            <td>{$ItemOptions.item.value}</td>
            <td>{section show=$ItemOptions.item.price|ne( 0 )}{$ItemOptions.item.price|l10n( currency )}{/section}</td>
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

{section-else}
<div class="block">
<p>{'The wish list is empty.'|i18n( 'design/admin/shop/wishlist')}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{section show=$wish_list.items}
<input class="button" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/wishlist' )}" />
<input class="button" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/wishlist' )}" />
{section-else}
<input class="button-disabled" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/wishlist' )}" disabled="disabled" />
<input class="button-disabled" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/wishlist' )}" disabled="disabled" />
{/section}
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
