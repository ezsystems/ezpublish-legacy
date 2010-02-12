<form action={'shop/discountgroup'|ezurl} method="post">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'%discount_group [Discount group]'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%discount_group', $discountgroup.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/shop/discountgroupmembershipview' )}:</label>
{$discountgroup.name|wash}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input type="hidden" name="discountGroupIDList[]" value="{$discountgroup.id}" />
<input type="hidden" name="EditGroupID" value="{$discountgroup.id}" />
<input class="button" type="submit" name="EditGroupButton" value="{'Edit'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Edit this discount group.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
<input class="button" type="submit" name="RemoveDiscountGroupButton" value="{'Remove'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Remove this discount group.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>
</form>



<form action={concat( $module.functions.discountgroupview.uri, '/', $discountgroup.id )|ezurl} method="post" name="DiscountGroupView">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Discount rules (%rule_count)'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%rule_count', $rule_list|count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$rule_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" onclick="ezjs_toggleCheckboxes( document.DiscountGroupView, 'removeRuleList[]' ); return false;" /></th>
    <th>{'Name'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th>{'Percent'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th>{'Apply to'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th class="tight">&nbsp;</th>
</tr>
{section var=Rules loop=$rule_list sequence=array( bglight, bgdark )}
<tr class="{$Rules.sequence}">
    <td><input type="checkbox" name="removeRuleList[]" value="{$Rules.item.id}" title="{'Select discount rule for removal.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" /></td>
    <td>{$Rules.item.name|wash}</td>
    <td class="number" align="right">{$Rules.item.discount_percent|l10n( number )}%</td>
    <td>{$Rules.item.limitation}</td>
    <td><a href={concat( $module.functions.discountruleedit.uri, '/', $discountgroup.id, '/', $Rules.item.id)|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Edit the <%discount_rule_name> discount rule.'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%discount_rule_name', $Rules.item.name ) )|wash}" /></a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no discount rules in this group.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">
    {if $rule_list}
    <input class="button" type="submit" name="RemoveRuleButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Remove selected discount rules.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveRuleButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" disabled="disabled" />
    {/if}
    <input class="button" type="submit" name="AddRuleButton" value="{'New discount rule'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Create a new discount rule and add it to the <%discount_group_name> discount group.'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%discount_group_name', $discountgroup.name ) )|wash}" />
</div>

{* DESIGN: Control bar END *}</div></div>

</div>
</div>




<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Customers (users and user groups) (%customer_count)'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%customer_count', $customers|count) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$customers}
<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" onclick="ezjs_toggleCheckboxes( document.DiscountGroupView, 'CustomerIDArray[]' ); return false;" /></th>
    <th>{'Name'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th>{'Type'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
</tr>
{section var=Customers loop=$customers sequence=array( bglight, bgdark )}
<tr class="{$Customers.sequence}">
    <td><input type="checkbox" value="{$Customers.item.id}" name="CustomerIDArray[]" title="{'Select user or user group for removal.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" /></td>
    <td>{$Customers.item.class_identifier|class_icon(small, $Customers.item.class_name )}&nbsp;<a href={$Customers.item.main_node.url_alias|ezurl}>{$Customers.item.name|wash}</a></td>
    <td>{$Customers.item.class_name}</td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no customers in this discount group.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    {if $customers}
    <input class="button" type="submit" name="RemoveCustomerButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Remove selected users and/or user groups.'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveCustomerButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" disabled="disabled" />
    {/if}
    <input class="button" type="submit" name="AddCustomerButton" value="{'Add customers'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" title="{'Add users and/or user groups to the <%discount_group_name> discount group.'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%discount_group_name', $discountgroup.name ) )|wash}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
