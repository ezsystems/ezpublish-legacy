{if $error}
    <div class="message-error">
        <h2>{$error}</h2>
    </div>
{/if}

<form name="editcurrency" action={'shop/editcurrency'|ezurl} method="post">
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">
{if eq( $original_currency_code, '' )}
    {'Create currency'|i18n( 'design/admin/shop/editcurrency' )}
{else}
    {'Edit currency'|i18n( 'design/admin/shop/editcurrency' )}
{/if}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<table class="list" cellspacing="0">
    <tr>
        <td class="class">{'Currency code'|i18n( 'design/admin/shop/editcurrency' )}</td>
        <td><input type="text" name="CurrencyCode" value="{$currency_code}" /></td>
    </tr>
    <tr>
        <td class="class">{'Currency symbol'|i18n( 'design/admin/shop/editcurrency' )}</td>
        <td><input type="text" name="CurrencySymbol" value="{$currency_symbol}" /></td>
    </tr>
    <tr>
        <td class="class">{'Custom rate'|i18n( 'design/admin/shop/editcurrency' )}</td>
        <td><input type="text" name="CustomRate" value="{$custom_rate}" /></td>
    </tr>
    <tr>
        <td class="class">{'Rate factor'|i18n( 'design/admin/shop/editcurrency' )}</td>
        <td><input type="text" name="RateFactor" value="{$rate_factor}" /></td>
    </tr>
</table>
</div>


{* DESIGN: Content END *}</div></div></div>

{* Button bar for remove and add currency. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    <div class="left">
        {* Remove button *}
        <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Cancel creating new currency.'|i18n( 'design/admin/shop/editcurrency' )}" />
        {if eq( $original_currency_code, '' )}
            {* Create button *}
            <input class="button" type="submit" name="CreateButton" value="{'Create'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Finish creating currnecy.'|i18n( 'design/admin/shop/editcurrency' )}" />
        {else}
            {* 'Store changes' button *}
            <input class="button" type="submit" name="StoreChangesButton" value="{'Store changes'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Store changes.'|i18n( 'design/admin/shop/editcurrency' )}" />
        {/if}
    </div>

    <div class="break"></div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

<input type="hidden" name="OriginalCurrencyCode" value="{$original_currency_code}" />
</form>
