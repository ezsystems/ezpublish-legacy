<form action={'shop/discountgroup'|ezurl} method="post">
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'%discount_group [Discount group]'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%discount_group', $discountgroup.name ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</label>
{$discountgroup.name}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input type="hidden" name="discountGroupIDList[]" value="{$discountgroup.id}" />
<input type="hidden" name="EditGroupID" value="{$discountgroup.id}" />
<input class="button" type="submit" name="EditGroupButton" value="{'Edit'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
<input class="button" type="submit" name="RemoveDiscountGroupButton" value="{'Remove'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>
</form>



<form action={concat( $module.functions.discountgroupview.uri, '/', $discountgroup.id )|ezurl} method="post" name="DiscountGroupView">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Discount rules [%rule_count]'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%rule_count', $rule_list|count ) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$rule_list}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th>{'Percent'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th>{'Apply to'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
    <th>&nbsp;</th>
</tr>
{section var=Rules loop=$rule_list sequence=array( bglight, bgdark )}
<tr class="{$Rules.sequence}">
    <td><input type="checkbox" name="removeRuleList[]" value="{$Rules.item.id}" /></td>
    <td>{$Rules.item.name|wash}</td>
    <td>{$Rules.item.discount_percent|l10n( number )}%</td>
    <td>{$Rules.item.limitation}</td>
    <td><a href={concat( $module.functions.discountruleedit.uri, '/', $discountgroup.id, '/', $Rules.item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></td>
</tr>
{/section}
</table>
{section-else}
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    <input class="button" type="submit" name="RemoveRuleButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" {section show=$rule_list|not}disabled="disabled"{/section}/>
    <input class="button" type="submit" name="AddRuleButton" value="{'New discount rule'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>




<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Customers [%customer_count]'|i18n( 'design/admin/shop/discountgroupmembershipview',, hash( '%customer_count', $customers|count) )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$customers}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
    <th>{'Name'|i18n( 'design/admin/shop/discountgroupmembershipview' )}</th>
</tr>
{section var=Customers loop=$customers sequence=array( bglight, bgdark )}
<tr class="{$Customers.sequence}">
    <td><input type="checkbox" value="{$Customers.item.id}" name="CustomerIDArray[]" /></td>
    <td>{content_view_gui view=text_linked content_object=$Customers.item}</td>
</tr>
{/section}
</table>
{section-else}
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="RemoveCustomerButton" value="{'Remove selected'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" {section show=$customers|not}disabled="disabled"{/section} />
    <input class="button" type="submit" name="AddCustomerButton" value="{'Add customer'|i18n( 'design/admin/shop/discountgroupmembershipview' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
