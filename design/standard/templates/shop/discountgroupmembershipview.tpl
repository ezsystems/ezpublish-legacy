<h1>{"Group view"|i18n('shop/discountgroupview')}</h1>

<form action={concat($module.functions.discountgroupview.uri,"/",$discountgroup.id)|ezurl} method="post"  name="DiscountGroupView">

<h3>Group Name:</h3>
{$discountgroup.name}<a href={concat("/shop/discountgroupedit/",$discountgroup.id,"/")|ezurl}>[edit]</a><br/>
<h3>Defined rules:</h3> 
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	Name:
	</th>
	<th>
        Percent
	</th>
	<th>
	Apply to:
	</th>
	<th>
	</th>
	<th>
	</th>
</tr>
{section name=Rule loop=$rule_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Rule:sequence}">
	{$Rule:item.name}
	</td>
	<td class="{$Rule:sequence}">
	{$Rule:item.discount_percent}%
	</td>
	<td class="{$Rule:sequence}">
	{$Rule:item.limitation}
	</td>
	<td class="{$Rule:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.discountruleedit.uri,"/",$discountgroup.id,"/",$Rule:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
	<td class="{$Rule:sequence}">
	<input type="checkbox" name="removeRuleList[]" value="{$Rule:item.id}" />
	</td>
</tr>
{/section}
</table>
<div class="buttonblock">
<input type="submit" name="AddRuleButton" value="Add Rule" /> &nbsp;
<input type="submit" name="RemoveRuleButton" value="Remove Rule" />
</div>
<table width="100%" cellspacing="0">
<tr>
	<th>Customers</th>
</tr>
{section name=Customer loop=$customers sequence=array(bglight,bgdark)}
<tr>
	<td  class="{$Customer:sequence}">
	{$Customer:item.name}
	</td>
	<td  class="{$Customer:sequence}">
	<input type="checkbox" value="{$Customer:item.id}" name="CustomerIDArray[]" />
	</td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input type="submit" name="AddCustomerButton" value="Add customer" /> &nbsp;
<input type="submit" name="RemoveCustomerButton" value="Remove customer" />
</div>
</form>
