<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm removal of VAT types'|i18n( 'design/admin/shop/removevattypes' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr">

<form action={'shop/vattype'|ezurl} method="post" name="RemoveVatType">

{* if user is trying to remove all VAT types with no replacement *}
{if $vat_types_for_replacement|not}
<div class="message-error">
    <h2>{'Unable to remove all VAT types'|i18n( 'design/admin/shop/removevattypes' )}</h2>
    <ul>
        <li>{'Some of the VAT types you have chosen to remove are referenced from products and/or VAT rules and there is no replacement for them. If you do not want to charge any VAT for your products then just leave one VAT type and set set its percentage to zero.'|i18n( 'design/admin/shop/removevattypes' )}</li>
    </ul>
</div>
{/if}


<div class="box-content">

{if $vat_types_for_replacement}
<div class="message-confirmation">
{if $dependencies|count|gt(1)}
    <h2>{'Are you sure you want to remove the VAT types?'|i18n( 'design/admin/shop/removevattypes' )}</h2>
{else}
    <h2>{'Are you sure you want to remove this VAT type?'|i18n( 'design/admin/shop/removevattypes' )}</h2>
{/if}
</div>
{/if}

<ul>
{def $vat_name = '' $rules_count = 0 $products_count = 0}
{foreach $dependencies as $vat_id => $vat_deps}
    {set $vat_name       = $vat_deps.name
         $rules_count    = $vat_deps.affected_rules_count
         $products_count = $vat_deps.affected_products_count}
    {if $rules_count|gt( 0 )}
        {if $rules_count|eq( 1 )}
            <li>{"Removing VAT type <%1> will result in changing VAT type for 1 VAT charging rule."|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash ) )|wash}</li>
        {else}
            <li>{'Removing VAT type <%1> will result in changing VAT type for %2 VAT charging rules.'|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash, $rules_count ) )|wash}</li>
        {/if}
    {/if}
    {if $products_count|gt( 0 )}
        {if $products_count|eq( 1 )}
            <li>{"Removing VAT type <%1> will result in changing VAT type for 1 product."|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash ) )|wash}</li>
        {else}
            <li>{'Removing VAT type <%1> will result in changing VAT type for %2 products.'|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash, $products_count ) )|wash}</li>
        {/if}
    {/if}
{/foreach}
{undef $rules_count $products_count}
</ul>

{if $vat_types_for_replacement}
<div class="block">
<label>{'Choose a VAT type replacement'|i18n( 'design/standard/class/datatype' )}:</label>
<select name="VatReplacement">
{foreach $vat_types_for_replacement as $vat}
<option value="{$vat.id}">{$vat.name|wash}, {$vat.percentage}%</option>
    {$vat.name|wash}, {$vat.percentage}%
{/foreach}
</select>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
{if $vat_types_for_replacement}
    <input type="hidden" name="vatTypeIDList" value="{$vat_type_ids}" />
    <input class="button" type="submit" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/shop/removevattypes' )}" />
    <input class="button" type="submit" name="CancelRemovalButton" value="{'Cancel'|i18n( 'design/admin/shop/removevattypes' )}" />
{else}
    <input class="button" type="submit" name="CancelRemovalButton" value="{'OK'|i18n( 'design/admin/shop/removevattypes' )}" />
{/if}
</div>

</form>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

