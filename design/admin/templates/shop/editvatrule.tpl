{if $errors}
    <div class="message-error">
        <h2>{$error_header}</h2>
        <ul>
        {foreach $errors as $error}
            <li>{$error|wash}</li>
        {/foreach}
        </ul>
   </div>
{/if}

<form name="editvatrule" action={'shop/editvatrule'|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">
{if is_set( $rule )}
    {'Edit VAT charging rule'|i18n( 'design/admin/shop/editvatrule' )}
{else}
    {'Create new VAT charging rule'|i18n( 'design/admin/shop/editvatrule' )}
{/if}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<table class="list" cellspacing="0">
<tr>
    <td>
    <label>{'Country/region'|i18n( 'design/admin/shop/editvatrule' )}:</label>
    </td>
    <td>
    {include uri='design:shop/country/edit.tpl' select_name='Country' select_size=1
             default_val='*' default_desc='Any'|i18n( 'design/admin/shop/editvatrule' )
             current_val=$country use_country_code=1}
    </td>
</tr>

<tr>
    <td>
    <label>{'Product categories'|i18n( 'design/admin/shop/editvatrule' )}:</label>
    </td>
    <td>
    {def $categories_list_size = cond( $all_product_categories|count|le( 100 ), $all_product_categories|count, true, 100 )}
    <select name="Categories[]" size="{$categories_list_size}" multiple="multiple">
        <option {if $category_ids|count|eq(0)}selected="selected"{/if} value="*">{'Any'|i18n( 'design/admin/shop/editvatrule' )}</option>
    {foreach $all_product_categories as $current_cat}
        <option {if $category_ids|contains( $current_cat.id )}selected="selected"{/if} value="{$current_cat.id}">{$current_cat.name}</option>
        {/foreach}
    </select>
    </td>
</tr>

<tr>
    <td>
    <label>{'VAT type'|i18n( 'design/admin/shop/editvatrule' )}:</label>
    </td>
    <td>
    <select name="VatType" size="1">
    {foreach $all_vat_types as $current_vat_type}
        <option {if eq( $vat_type_id, $current_vat_type.id )}selected="selected"{/if} value="{$current_vat_type.id}">{$current_vat_type.name} ({$current_vat_type.percentage}%)</option>
        {/foreach}
    </select>
    </td>
</tr>

</table>
</div>


{* DESIGN: Content END *}</div></div></div>

{* Button bar for remove and add currency. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    <div class="left">
        {if is_set( $rule )}
            {* 'Store changes' button *}
            <input class="button" type="submit" name="StoreChangesButton" value="{'Store changes'|i18n( 'design/admin/shop/editvatrule' )}" title="{'Store changes.'|i18n( 'design/admin/shop/editvatrule' )}" />
        {else}
            {* Create button *}
            <input class="button" type="submit" name="CreateButton" value="{'Create'|i18n( 'design/admin/shop/editvatrule' )}" title="{'Finish creating currency.'|i18n( 'design/admin/shop/editvatrule' )}" />
        {/if}
        {* Cancel button *}
        <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/shop/editvatrule' )}" title="{'Cancel creating new currency.'|i18n( 'design/admin/shop/editvatrule' )}" />
    </div>

    <div class="break"></div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

{if is_set( $rule )}
<input type="hidden" name="RuleID" value="{$rule.id}" />
{/if}

</form>
