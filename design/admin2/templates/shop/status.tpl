<form action={'shop/status'|ezurl} method="post" name="OrderStatus">

<div class="context-block">

{section show=$messages|count|gt( 0 )}
<div class="message-feedback">
    {section var=message loop=$messages}
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$message.description|wash}</h2>
    {/section}
</div>
{/section}

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Order status (%order_status)'|i18n( 'design/admin/shop/status',, hash( '%order_status', $orderstatus_array|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$orderstatus_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/status' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/status' )}" onclick="ezjs_toggleCheckboxes( document.OrderStatus, 'orderStatusIDList[]' ); return false;" /></th>
    <th class="tight">{'Status ID'|i18n( 'design/admin/shop/status' )}</th>
    <th>{'Name'|i18n( 'design/admin/shop/status' )}</th>
    <th class="tight">{'Active'|i18n( 'design/admin/shop/status' )}</th>
</tr>

{section var=Orderstatus loop=$orderstatus_array sequence=array( bglight, bgdark )}
<tr class="{$Orderstatus.sequence}">
    <td><input type="checkbox" name="orderStatusIDList[]" value="{$Orderstatus.item.id}" title="{'Select order status for removal.'|i18n( 'design/admin/shop/status' )}" {if $Orderstatus.item.is_internal}disabled="disabled"{/if} /></td>
    <td class="number" align="right">{if $Orderstatus.item.is_internal}<i>{/if}{$Orderstatus.item.status_id}{if $Orderstatus.item.is_internal}</i>{/if}</td>
    <td><input type="text" name="orderstatus_name_{$Orderstatus.item.id}" value="{$Orderstatus.item.name|wash}" size="24" /></td>
    <td>
        {* The hidden variable is required because a checkbox will only set the value if it is checked *}
        <input type="hidden" name="orderstatus_active_has_input_{$Orderstatus.item.id}" value="1" />
        <input type="checkbox" name="orderstatus_active_{$Orderstatus.item.id}" value="{$Orderstatus.item.id}" title="{'Check this if you want the status to be usable in the shopping system.'|i18n( 'design/admin/shop/status' )}" {if $Orderstatus.item.is_active}checked="checked"{/if} />
    </td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no order statuses.'|i18n( 'design/admin/shop/status' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<div class="button-left">
    {if $orderstatus_array}
    <input class="button" type="submit" name="RemoveOrderStatusButton" value="{'Remove selected'|i18n( 'design/admin/shop/status' )}" title="{'Remove selected order statuses.'|i18n( 'design/admin/shop/status' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveOrderStatusButton" value="{'Remove selected'|i18n( 'design/admin/shop/status' )}" disabled="disabled" />
    {/if}
    <input class="button" type="submit" name="AddOrderStatusButton" value="{'New order status'|i18n( 'design/admin/shop/status' )}" title="{'Create a new order status.'|i18n( 'design/admin/shop/status' )}" />
</div>
<div class="button-right">
    {if $orderstatus_array}
    <input class="button" type="submit" name="SaveOrderStatusButton" value="{'Apply changes'|i18n( 'design/admin/shop/status' )}" title="{'Click this button to store changes if you have modified any of the fields above.'|i18n( 'design/admin/shop/status' )}" />
    {else}
    <input class="button-disabled" type="submit" name="SaveOrderStatusButton" value="{'Apply changes'|i18n( 'design/admin/shop/status' )}" disabled="disabled" />
    {/if}
</div>
<div class="break"></div>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
