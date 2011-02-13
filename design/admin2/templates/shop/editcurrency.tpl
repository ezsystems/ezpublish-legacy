{if $error}
    <div class="message-error">
        <h2>{$error}</h2>
    </div>
{/if}

<form name="editcurrency" action={'shop/editcurrency'|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">
{if eq( $original_currency_code, '' )}
    {'Create currency'|i18n( 'design/admin/shop/editcurrency' )}
{else}
    {'Edit \'%currency_code\' currency'|i18n( 'design/admin/shop/editcurrency',, hash( '%currency_code', $original_currency_code ) )}
{/if}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{def $locale_list = fetch( 'content', 'locale_list' )}

<div class="block">
{if $can_edit}
    <table class="list" cellspacing="0">
        <tr>
            <td class="nowrap">{'Currency code'|i18n( 'design/admin/shop/editcurrency' )}</td>
            <td><input type="text" name="CurrencyData[code]" value="{$currency_data['code']}" />{'(Use three capital letters)'|i18n( 'design/admin/shop/editcurrency' )}</td>
        </tr>
        <tr>
            <td class="nowrap">{'Currency symbol'|i18n( 'design/admin/shop/editcurrency' )}</td>
            <td><input type="text" name="CurrencyData[symbol]" value="{$currency_data['symbol']}" /></td>
        </tr>
        <tr>
            <td class="nowrap">{'Formatting locale'|i18n( 'design/admin/shop/editcurrency' )}</td>
            <td><select name="CurrencyData[locale]" title="{'Select locale for formatting price values.'|i18n( 'design/admin/shop/editcurrency' )}">
                {foreach $locale_list as $locale}
                    <option value="{$locale.locale_full_code}" {if $locale.locale_full_code|compare( $currency_data['locale'] )}selected="selected"{/if}>{$locale.locale_full_code}</option>
                {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td class="nowrap">{'Custom rate'|i18n( 'design/admin/shop/editcurrency' )}</td>
            <td><input type="text" name="CurrencyData[custom_rate_value]" value="{$currency_data['custom_rate_value']}" /></td>
        </tr>
        <tr>
            <td class="nowrap">{'Rate factor'|i18n( 'design/admin/shop/editcurrency' )}</td>
            <td><input type="text" name="CurrencyData[rate_factor]" value="{$currency_data['rate_factor']}" /></td>
        </tr>
    </table>
{else}
    {'Unable to edit'|i18n( 'design/admin/shop/editcurrency' )}
{/if}
</div>


{* DESIGN: Content END *}</div></div></div>

{* Button bar for remove and add currency. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
    <div class="left">
        {if $can_edit}
            {if eq( $original_currency_code, '' )}
                {* Create button *}
                <input class="button" type="submit" name="CreateButton" value="{'Create'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Finish creating currency.'|i18n( 'design/admin/shop/editcurrency' )}" />
            {else}
                {* 'Store changes' button *}
                <input class="button" type="submit" name="StoreChangesButton" value="{'Store changes'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Store changes.'|i18n( 'design/admin/shop/editcurrency' )}" />
            {/if}
            {* Remove button *}
            <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Cancel creating new currency.'|i18n( 'design/admin/shop/editcurrency' )}" />
        {else}
            {* Back button *}
            <input class="button" type="submit" name="CancelButton" value="{'Back'|i18n( 'design/admin/shop/editcurrency' )}" title="{'Back to the currency list'|i18n( 'design/admin/shop/editcurrency' )}" />
        {/if}

    </div>

    <div class="break"></div>
</div>
{* DESIGN: Control bar END *}</div></div>

</div>

</div>

<input type="hidden" name="OriginalCurrencyCode" value="{$original_currency_code}" />
</form>
