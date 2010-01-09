<form action={concat( $module.functions.discountruleedit.uri, '/', $discountgroup_id, '/', $discountrule.id )|ezurl} method="post" name="DiscountRuleEdit">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Edit <%rule_name> [Discount rule]'|i18n( 'design/admin/shop/discountruleedit',, hash( '%rule_name', $discountrule.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{* Name *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/shop/discountruleedit' )}:</label>
<input class="box" id="discountruleName" type="text" name="discountrule_name" value="{$discountrule.name|wash}" size="40" />
</div>

{* Discount precent *}
<div class="block">
<label>{'Discount percent'|i18n( 'design/admin/shop/discountruleedit' )}:</label>
<input type="text" name="discountrule_percent" value="{$discountrule.discount_percent|l10n( number )}" size="5" />&nbsp;%
</div>

<div class="block">

{* Classes *}
<div class="element">
<label>{'Product types'|i18n( 'design/admin/shop/discountruleedit' )}:</label>
{section show=$product_list}
    {section var=class loop=$class_limitation_list}
    <input type="hidden" name="Contentclasses[]" value="{$class}" />
    {/section}
    <select name="ContentclassesDisabled[]" size="5" multiple="multiple" disabled="disabled">
{section-else}
    <select name="Contentclasses[]" size="5" multiple="multiple">
{/section}
<option value="-1" {if $class_any_selected}selected="selected"{/if} >{'Any'|i18n( 'design/admin/shop/discountruleedit' )}</option>
{section name=Classes loop=$product_class_list}
<option value="{$Classes:item.id}" {switch match=$Classes:item.id}{case in=$class_limitation_list} selected="selected"{/case}{case/}{/switch}>
{$Classes:item.name|wash}
</option>
{/section}
</select>
</div>

{* Sections *}
<div class="element">
<label>{'in sections'|i18n( 'design/admin/shop/discountruleedit' )}:</label>
{section show=$product_list}
    {section var=section loop=$section_limitation_list}
    <input type="hidden" name="Sections[]" value="{$section}" />
    {/section}
    <select name="SectionsDisabled[]" size="5" multiple="multiple" disabled="disabled">
{section-else}
    <select name="Sections[]" size="5" multiple="multiple">
{/section}
<option value="-1" {if $section_any_selected}selected="selected"{/if}>{'Any'|i18n( 'design/admin/shop/discountruleedit' )}</option>
{section name=Sections loop=$section_list}
<option value="{$Sections:item.id}" {switch match=$Sections:item.id}{case in=$section_limitation_list} selected="selected"{/case}{case/}{/switch}>
{$Sections:item.name|wash}
</option>
{/section}
</select>
</div>

</div>

{* Objects *}
<div class="block">
<fieldset>
<legend>{'Individual products'|i18n( 'design/admin/shop/discountruleedit' )}</legend>

{section show=$product_list}
<table class="list" cellspacing="0">
<tr>
<th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/discountruleedit' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/discountruleedit' )}" onclick="ezjs_toggleCheckboxes( document.DiscountRuleEdit, 'DeleteProductIDArray[]' ); return false;" /></th>
<th>{'Name'|i18n( 'design/admin/shop/discountruleedit' )}</th>
</tr>
{section var=Product show=$product_list loop=$product_list sequence=array( bglight, bgdark )}
<tr class="{$Product.sequence}">
<td><input type="hidden" name="Products[]" value="{$Product.id}" /><input type="checkbox" name="DeleteProductIDArray[]" value="{$Product.id}" /></td>
<td>{$Product.name|wash}</td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'The individual product list is empty.'|i18n( 'design/admin/shop/discountruleedit' )}</p>
</div>
{/section}

{if $product_list}
<input class="button" type="submit" name="DeleteProductButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountruleedit' )}" />
{else}
<input class="button-disabled" type="submit" name="DeleteProductButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountruleedit' )}" disabled="disabled" />
{/if}

<input class="button" type="submit" name="BrowseProductButton" value="{'Add products'|i18n( 'design/admin/shop/discountruleedit' )}" />
</fieldset>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/shop/discountruleedit' )}" />
<input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/shop/discountruleedit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>
</form>

{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('discountruleName').select();
        document.getElementById('discountruleName').focus();
    }
-->
</script>
{/literal}
