{let can_apply=false()}
<form name="archivelist" method="post" action={concat( '/shop/archivelist' )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Archived orders (%count)'|i18n( 'design/admin/shop/archivelist',, hash( '%count', $archive_list|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$archive_list}
<div class="context-toolbar">
<div class="button-left">
<p class="table-preferences">
{if eq( ezpreference( 'admin_archivelist_sortfield' ), 'user_name' )}
    <a href={'/user/preferences/set/admin_archivelist_sortfield/time/shop/archivelist/'|ezurl}>{'Time'|i18n( 'design/admin/shop/archivelist' )}</a>
    <span class="current">{'Customer'|i18n( 'design/admin/shop/archivelist' )}</span>
{else}
    <span class="current">{'Time'|i18n( 'design/admin/shop/archivelist' )}</span>
    <a href={'/user/preferences/set/admin_archivelist_sortfield/user_name/shop/archivelist/'|ezurl}>{'Customer'|i18n( 'design/admin/shop/archivelist' )}</a>
{/if}
</p>
</div>
<div class="button-right">
<p class="table-preferences">
{if eq( ezpreference( 'admin_archivelist_sortorder' ), 'desc' )}
    <a href={'/user/preferences/set/admin_archivelist_sortorder/asc/shop/archivelist/'|ezurl}>{'Ascending'|i18n( 'design/admin/shop/archivelist' )}</a>
    <span class="current">{'Descending'|i18n( 'design/admin/shop/archivelist' )}</span>
{else}
    <span class="current">{'Ascending'|i18n( 'design/admin/shop/archivelist' )}</span>
    <a href={'/user/preferences/set/admin_archivelist_sortorder/desc/shop/archivelist/'|ezurl}>{'Descending'|i18n( 'design/admin/shop/archivelist' )}</a>
{/if}
</p>
</div>

<div class="float-break"></div>
</div>

{def $currency = false()
     $symbol = false()
     $locale = false()}

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/archivelist' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/archivelist' )}" onclick="ezjs_toggleCheckboxes( document.archivelist, 'OrderIDArray[]' ); return false;" /></th>
    <th class="tight">{'ID'|i18n( 'design/admin/shop/archivelist' )}</th>
    <th class="wide">{'Customer'|i18n( 'design/admin/shop/archivelist' )}</th>
    <th class="tight">{'Total (ex. VAT)'|i18n( 'design/admin/shop/archivelist' )}</th>
    <th class="tight">{'Total (inc. VAT)'|i18n( 'design/admin/shop/archivelist' )}</th>
    <th class="wide">{'Time'|i18n( 'design/admin/shop/archivelist' )}</th>
    <th class="wide">{'Status'|i18n( 'design/admin/shop/archivelist' )}</th>
</tr>

{section var=Orders loop=$archive_list sequence=array( bglight, bgdark )}

{set $currency = fetch( 'shop', 'currency', hash( 'code', $Orders.item.productcollection.currency_code ) )}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{else}
    {set locale = false()
         symbol = false()}
{/if}

<tr class="{$Orders.sequence}">
    <td><input type="checkbox" name="OrderIDArray[]" value="{$Orders.item.id}" title="{'Select order for removal.'|i18n( 'design/admin/shop/archivelist' )}" /></td>
    <td><a href={concat( '/shop/orderview/', $Orders.item.id, '/' )|ezurl}>{$Orders.item.order_nr}</a></td>
    <td><a href={concat( '/shop/customerorderview/', $Orders.item.user_id, '/', $Orders.item.account_email )|ezurl}>{$Orders.item.account_name}</a></td>

    {* NOTE: These two attribute calls are slow, they cause the system to generate lots of SQLs.
             The reason is that their values are not cached in the order tables *}
    <td class="number" align="right">{$Orders.item.total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td class="number" align="right">{$Orders.item.total_inc_vat|l10n( 'currency', $locale, $symbol )}</td>

    <td>{$Orders.item.created|l10n( shortdatetime )}</td>
    <td>
    {$Orders.status_name|wash}
    </td>
</tr>
{/section}
</table>
{undef $currency $symbol $locale}
{section-else}
<div class="block">
<p>{'The order list is empty.'|i18n( 'design/admin/shop/archivelist' )}</p>
</div>
{/section}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/shop/archivelist'
         item_count=$archive_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
<div class="button-left">
{if $archive_list}
    <input class="button" type="submit" name="UnarchiveButton" value="{'Unarchive selected'|i18n( 'design/admin/shop/archivelist' )}" title="{'Unarchive selected orders.'|i18n( 'design/admin/shop/archivelist' )}" />
{else}
    <input class="button-disabled" type="submit" name="UnarchiveButton" value="{'Unarchive selected'|i18n( 'design/admin/shop/archivelist' )}" disabled="disabled" />
{/if}
</div>
<div class="break"></div>

</div>

{* DESIGN: Control bar END *}</div></div>
</div>

</form>
{/let}
