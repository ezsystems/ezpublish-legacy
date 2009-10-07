<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm order removal'|i18n( 'design/admin/shop/removeorder' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{if $delete_result|count|eq(1)}
<h2>{'Are you sure you want to remove order #%order_number?'|i18n( 'design/admin/shop/removeorder',, hash( '%order_number', $delete_result ) )}</h2>
{else}
<h2>{'Are you sure you want to remove the following orders?'|i18n( 'design/admin/shop/removeorder' )}</h2>
{$delete_result}
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<form action={concat( $module.functions.removeorder.uri )|ezurl} method="post" name="OrderRemove">
    <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/shop/removeorder' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/shop/removeorder' )}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>
