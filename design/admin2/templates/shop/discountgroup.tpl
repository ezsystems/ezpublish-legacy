<form action={'shop/discountgroup'|ezurl} method="post" name="DiscountGroup">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Discount groups (%discount_groups)'|i18n( 'design/admin/shop/discountgroup',, hash( '%discount_groups', $discountgroup_array|count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$discountgroup_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/discountgroup' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/discountgroup' )}" onclick="ezjs_toggleCheckboxes( document.DiscountGroup, 'discountGroupIDList[]' ); return false;" /></th>
    <th class="wide">{'Name'|i18n( 'design/admin/shop/discountgroup' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Groups loop=$discountgroup_array sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td><input type="checkbox" name="discountGroupIDList[]" value="{$Groups.item.id}" title="{'Select discount group for removal.'|i18n( 'design/admin/shop/discountgroup' )}" /></td>
    <td><a href={concat( $module.functions.discountgroupview.uri, '/', $Groups.item.id )|ezurl}>{$Groups.item.name|wash}</a></td>
    <td><a href={concat( $module.functions.discountgroupedit.uri, '/', $Groups.item.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/shop/discountgroup' )}" title="{'Edit the <%discountgroup_name> discount group.'|i18n( 'design/admin/shop/discountgroup',, hash( '%discountgroup_name', $Groups.item.name ) )|wash}" /></a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no discount groups.'|i18n( 'design/admin/shop/discountgroup' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    {if $discountgroup_array}
    <input class="button" type="submit" name="RemoveDiscountGroupButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroup' )}" title="{'Remove selected discount groups.'|i18n( 'design/admin/shop/discountgroup' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveDiscountGroupButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroup' )}" disabled="disabled" />
    {/if}
    <input class="button" type="submit" name="AddDiscountGroupButton" value="{'New discount group'|i18n( 'design/admin/shop/discountgroup' )}" title="{'Create a new discount group.'|i18n( 'design/admin/shop/discountgroup' )}" />
</div>
</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</form>
