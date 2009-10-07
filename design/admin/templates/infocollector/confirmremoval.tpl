<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm information collection removal'|i18n( 'design/admin/infocollector/confirmremoval' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

<h2>{'Are you sure you want to remove the collected information?'|i18n( 'design/admin/infocollector/confirmremoval' )}</h2>

{if $collections|lt( 2 )}
<p>{'%collections collection will be removed.'|i18n( 'design/admin/infocollector/confirmremoval',, hash( '%collections', $collections ) )}</p>
{else}
<p>{'%collections collections will be removed.'|i18n( 'design/admin/infocollector/confirmremoval',, hash( '%collections', $collections ) )}</p>
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

{if $remove_type|eq( 'objects' )}
<form action={$module.functions.overview.uri|ezurl} method="post" name="CollectionRemove">
{else}
<form action={concat( $module.functions.collectionlist.uri, '/', $object_id )|ezurl} method="post" name="CollectionRemove">
{/if}
    <input class="button" type="submit" name="ConfirmRemoveButton" value="{'OK'|i18n( 'design/admin/infocollector/confirmremoval' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/infocollector/confirmremoval' )}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>
