<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Confirm removal of product categories'|i18n( 'design/admin/shop/removeproductcategories' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{if $dependencies|count|gt(1)}
    <h2>{'Are you sure you want to remove the categories?'|i18n( 'design/admin/shop/removeproductcategories' )}</h2>
{else}
    <h2>{'Are you sure you want to remove this category?'|i18n( 'design/admin/shop/removeproductcategories' )}</h2>
{/if}

<ul>
{def $cat_name = '' $rules_count = 0 $products_count = 0 $possible_conflicts=false()}
{foreach $dependencies as $cat_id => $cat_deps}
    {set $cat_name       = $cat_deps.name
         $rules_count    = $cat_deps.affected_rules_count
         $products_count = $cat_deps.affected_products_count}
    {if $rules_count|gt( 0 )}
        {if $rules_count|eq( 1 )}
            <li>{"Removing category <%1> will result in modifying 1 VAT charging rule."|i18n( 'design/admin/shop/removeproductcategories',, array( $cat_name|wash ) )|wash}</li>
        {else}
            <li>{'Removing category <%1> will result in modifying %2 VAT charging rules.'|i18n( 'design/admin/shop/removeproductcategories',, array( $cat_name|wash, $rules_count ) )|wash}</li>
        {/if}
        {set $possible_conflicts=true()}
    {/if}
    {if $products_count|gt( 0 )}
        {if $products_count|eq( 1 )}
            <li>{"Removing category <%1> will result in resetting category for 1 product."|i18n( 'design/admin/shop/removeproductcategories',, array( $cat_name|wash ) )|wash}</li>
        {else}
            <li>{'Removing category <%1> will result in resetting category for %2 products.'|i18n( 'design/admin/shop/removeproductcategories',, array( $cat_name|wash, $products_count ) )|wash}</li>
        {/if}
    {/if}
{/foreach}
{undef $rules_count $products_count}
</ul>
{if $possible_conflicts}
{'Note that the removal may cause conflicts in VAT charging rules.'|i18n( 'design/admin/shop/removeproductcategories' )|wash}
{/if}
{undef $possible_conflicts}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">

<form action={'shop/productcategories'|ezurl} method="post" name="RemoveCategory">
    <input class="button" type="submit" name="ConfirmRemovalButton" value="{'OK'|i18n( 'design/admin/shop/removeproductcategories' )}" />
    <input class="button" type="submit" name="CancelRemovalButton" value="{'Cancel'|i18n( 'design/admin/shop/removeproductcategories' )}" />
    <input type="hidden" name="CategoryIDList" value="{$category_ids}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>

