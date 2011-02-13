<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Preferred currency'|i18n( 'design/admin/shop/currencylist' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{def $currency_list = fetch( 'shop', 'currency_list', hash( 'status', 'active' ) )
     $preferred_currency_code = fetch( 'shop', 'preferred_currency_code' )}
    {if gt( count($currency_list),0 )}
        <div class="block">
        <form action={'shop/setpreferredcurrency'|ezurl} method="post">
            {default currency_names = hash()}
                {include uri='design:shop/currencynames.tpl'}
                <select name="Currency" title="Select currency">
                    {foreach $currency_list as $Currency}
                        <option value="{$Currency.code}" {if eq( $Currency.code, $preferred_currency_code )}selected="selected"{/if}>{$Currency.code} - {if is_set($currency_names[$Currency.code])}{$currency_names[$Currency.code]}{else}{'Unknown currency name'|i18n( 'design/standard/shop/preferredcurrency' )}{/if}</option>
                    {/foreach}
                </select>
                {* Set button *}
                <input class="button" type="submit" name="SetButton" value="{'Set'|i18n( 'design/standard/shop/preferredcurrency' )}" title="{'Set the selected currency as preferred.'|i18n( 'design/standard/shop/preferredcurrency' )}" />
            {/default}
        </form>
        </div>
    {else}
        <div class="block">
        <p>{'The available currency list is empty'|i18n( 'design/admin/shop/preferredcurrency' )}</p>
        </div>
    {/if}
{undef}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
