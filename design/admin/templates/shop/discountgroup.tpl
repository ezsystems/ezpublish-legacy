<form action={'shop/discountgroup'|ezurl} method="post" name="DiscountGroup">

<div class="context-block">
<h2 class="context-title">{'Discount groups [%discount_groups]'|i18n( 'design/admin/shop/discountgroup',, hash( '%discount_groups', $discountgroup_array|count ) )}</h2>

{section show=$discountgroup_array}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th class="wide">{'Name'|i18n( 'design/admin/shop/discountgroup' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Groups loop=$discountgroup_array sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td><input type="checkbox" name="discountGroupIDList[]" value="{$Groups.item.id}"></td>
    <td><a href={concat( $module.functions.discountgroupview.uri, '/', $Groups.item.id )|ezurl}>{$Groups.item.name}</a></td>
    <td><a href={concat( $module.functions.discountgroupedit.uri, '/', $Groups.item.id )|ezurl}><img class="button" src={'edit.png'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/shop/discountgroup' )}" /></a></td>
</tr>
{/section}
</table>
{section-else}
<p>There are no discount groups.</p>
{/section}

{* Buttons. *}
<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="RemoveDiscountGroupButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroup' )}" {section show=$discountgroup_array|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="AddDiscountGroupButton" value="{'New discount group'|i18n( 'design/admin/shop/discountgroup' )}" />
</div>
</div>

</div>

</form>
