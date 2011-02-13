{let item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     wish_list_count=fetch( 'shop', 'wish_list_count', hash( 'production_id', $wish_list.productcollection_id ) )}
<form name="wishlistform" method="post" action={'/shop/wishlist/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'My wish list [%item_count]'|i18n( 'design/admin/shop/wishlist',, hash( '%item_count', $wish_list_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $wish_list_count}
{* Items per page *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_list_limit/1/shop/wishlist'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_list_limit/3/shop/wishlist'|ezurl}>50</a>
    {/case}

    {case match=50}
        <a href={'/user/preferences/set/admin_list_limit/1/shop/wishlist'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_list_limit/2/shop/wishlist'|ezurl}>25</a>
        <span class="current">50</span>
    {/case}

    {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_list_limit/2/shop/wishlist'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_list_limit/3/shop/wishlist'|ezurl}>50</a>
    {/case}
    {/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/wishlist' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/wishlist' )}" onclick="ezjs_toggleCheckboxes( document.wishlistform, 'RemoveProductItemDeleteList[]' ); return false;" /></th>
    <th>{'Name'|i18n( 'design/admin/shop/wishlist')}</th>
{*
    <th>{'Quantity'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'VAT'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Price (ex. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Price (inc. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Discount'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Total price (ex. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
    <th>{'Total price (inc. VAT)'|i18n( 'design/admin/shop/wishlist')}</th>
*}
</tr>
{section var=WishedItems loop=fetch( 'shop', 'wish_list', hash( 'production_id', $wish_list.productcollection_id, 'offset', $view_parameters.offset, 'limit', number_of_items ) ) sequence=array( bglight, bgdark )}
<tr class="{$WishedItems.sequence}">

    {* Remove. *}
    <td><input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$WishedItems.item.id}" title="{'Select item for removal.'|i18n( 'design/admin/shop/wishlist' )}" /></td>

    {* Name. *}
    <td>
        {$WishedItems.item.item_object.contentobject.class_identifier|class_icon( small, $WishedItems.item.item_object.contentobject.class_name )}&nbsp;<a href={concat( '/content/view/full/', $WishedItems.item.node_id, '/' )|ezurl}>{$WishedItems.item.object_name}</a>
        {section show=$WishedItems.item.item_object.option_list}
            ({*'Selected options'|i18n( 'design/admin/shop/wishlist' ): *}
{section var=Options loop=$WishedItems.item.item_object.option_list}
{$Options.item.name|wash}: {$Options.item.value}
{delimiter}, {/delimiter}
{/section})
        {/section}
        <input type="hidden" name="ProductItemIDList[]" value="{$WishedItems.item.id}" />
	</td>

    {* Quantity. *}
    {* <td><input type="text" name="ProductItemCountList[]" value="{$WishedItems.item.item_count}" size="3" />	</td> *}

    {* VAT. *}
    {* <td>{$WishedItems.item.vat_value}%</td> *}

    {* Item price (ex. VAT.) *}
    {* <td>{$WishedItems.item.price_ex_vat|l10n(currency)}</td> *}

    {* Item price (inc. VAT.). *}
    {* <td>{$WishedItems.item.price_inc_vat|l10n(currency)}</td> *}

    {* Discount. *}
    {* <td>{$WishedItems.item.discount_percent}%</td> *}

    {* Total price (ex. VAT). *}
    {* <td>{$WishedItems.item.total_price_ex_vat|l10n(currency)}</td> *}

    {* Total price (inc. VAT). *}
    {* <td>{$WishedItems.item.total_price_inc_vat|l10n(currency)}</td> *}

</tr>

{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/shop/wishlist'
         item_count=$wish_list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{else}
<div class="block">
<p>{'The wish list is empty.'|i18n( 'design/admin/shop/wishlist')}</p>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{if $wish_list.items}
<input class="button" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/wishlist' )}" title="{'Remove selected items.'|i18n( 'design/admin/shop/wishlist' )}" />
{* <input class="button" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/wishlist' )}" title="{'Click this button to store changes if you have modified quantity and/or option values.'|i18n( 'design/admin/shop/wishlist' )}" /> *}
{else}
<input class="button-disabled" type="submit" name="RemoveProductItemButton" value="{'Remove selected'|i18n( 'design/admin/shop/wishlist' )}" disabled="disabled" />
{* <input class="button-disabled" type="submit" name="StoreChangesButton" value="{'Apply changes'|i18n( 'design/admin/shop/wishlist' )}" disabled="disabled" /> *}
{/if}
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
{/let}
