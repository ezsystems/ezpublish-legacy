<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

{def $title=''}
{if $can_remove}
    {if $dependencies|count|gt(1)}
        {set $title='Confirm removal of the VAT types'|i18n( 'design/admin/shop/removevattypes' )}
    {else}
        {set $title='Confirm removal of this VAT type'|i18n( 'design/admin/shop/removevattypes' )}
    {/if}
{else}
    {if $dependencies|count|gt(1)}
        {set $title='Removing VAT types'|i18n( 'design/admin/shop/removevattypes' )}
    {else}
        {set $title='Removing VAT type'|i18n( 'design/admin/shop/removevattypes' )}
    {/if}
{/if}
<h1 class="context-title">{$title}</h1>
{undef $title}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr">

<form action={'shop/vattype'|ezurl} method="post" name="RemoveVatType">

{if $can_remove|not}
<div class="message-error">
{if $dependencies|count|gt(1)}
    <h2>{'Unable to remove the VAT types'|i18n( 'design/admin/shop/removevattypes' )}</h2>
{else}
    <h2>{'Unable to remove this VAT type'|i18n( 'design/admin/shop/removevattypes' )}</h2>
{/if}
<ul>
{foreach $errors as $error}
<li>{$error}</li>
{/foreach}
</ul>
</div>
{/if}


<div class="box-content">

{if $can_remove}
<div class="message-confirmation">
{if $dependencies|count|gt(1)}
    <h2>{'Are you sure you want to remove the VAT types?'|i18n( 'design/admin/shop/removevattypes' )}</h2>
{else}
    <h2>{'Are you sure you want to remove this VAT type?'|i18n( 'design/admin/shop/removevattypes' )}</h2>
{/if}
</div>
{/if}

{*---------- Dependencies START ----------*}
{if $show_dependencies}
<ul>
{def $vat_name = '' $rules_count = 0 $products_count = 0 $classes_count = 0}
{foreach $dependencies as $vat_id => $vat_deps}
    {set $vat_name       = $vat_deps.name
         $rules_count    = $vat_deps.affected_rules_count
         $products_count = $vat_deps.affected_products_count
         $classes_count  = $vat_deps.affected_classes_count}
    {if $can_remove|not}
        {if $classes_count|gt( 0 )}
            {if $classes_count|eq( 1 )}
                <li>{"VAT type <%1> is set as default for 1 product class."|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash ) )|wash}</li>
            {else}
                <li>{'VAT type <%1> is set as default for %2 product classes.'|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash, $classes_count ) )|wash}</li>
            {/if}
        {/if}
    {else}
        {if $rules_count|gt( 0 )}
            {if $rules_count|eq( 1 )}
                <li>{"Removing VAT type <%1> will result in removal of 1 VAT charging rule."|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash ) )|wash}</li>
            {else}
                <li>{'Removing VAT type <%1> will result in removal of %2 VAT charging rules.'|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash, $rules_count ) )|wash}</li>
            {/if}
        {/if}
        {if $products_count|gt( 0 )}
            {if $products_count|eq( 1 )}
                <li>{"Removing VAT type <%1> will result in resetting VAT type for 1 product to its default value."|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash ) )|wash}</li>
            {else}
                <li>{'Removing VAT type <%1> will result in resetting VAT type for %2 products to their default value.'|i18n( 'design/admin/shop/removevattypes',, array( $vat_name|wash, $products_count ) )|wash}</li>
            {/if}
        {/if}
    {/if}
{/foreach}
{undef $rules_count $products_count}
</ul>
{/if}
{*----------  Dependencies END -----------*}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
{if $can_remove}
    <input type="hidden" name="vatTypeIDList" value="{$vat_type_ids}" />
    <input class="button" type="submit" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/shop/removevattypes' )}" />
    <input class="button" type="submit" name="CancelRemovalButton" value="{'Cancel'|i18n( 'design/admin/shop/removevattypes' )}" />
{else}
    <input class="button" type="submit" name="CancelRemovalButton" value="{'Back'|i18n( 'design/admin/shop/removevattypes' )}" />
{/if}
</div>

</form>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>

