<h1>{"Rule view"|i18n('shop/discountrulemembershipview')}</h1>

<form action={concat($module.functions.discountrulemembershipview.uri,"/",$discountrule.id)|ezurl} method="post"  name="DiscountRuleMembershipView">

<h3>Rule Name:</h3>
{$discountrule.name}<a href={concat("/shop/discountruleedit/",$discountrule.id,"/")|ezurl}>[edit]</a><br/>
<h3>Defined sub rules:</h3> 
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	Name:
	</th>
	<th>
        Percent
	</th>
	<th>
	Limitation List:
	</th>
	<th>
	</th>
	<th>
	</th>
</tr>
{section name=SubRule loop=$subrule_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$SubRule:sequence}">
	{$SubRule:item.name}
	</td>
	<td class="{$SubRule:sequence}">
	{$SubRule:item.discount_percent}%
	</td>
	<td class="{$SubRule:sequence}">
	{$SubRule:item.limitation}
	</td>
	<td class="{$SubRule:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.discountsubruleedit.uri,"/",$discountrule.id,"/",$SubRule:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
	<td class="{$SubRule:sequence}">
	<input type="checkbox" name="removeSubRuleList[]" value="{$SubRule:item.id}" />
	</td>
</tr>
{/section}
</table>
<div class="buttonblock">
<input type="submit" name="AddSubRuleButton" value="Add Sub Rule" /> &nbsp;
<input type="submit" name="RemoveSubRuleButton" value="Remove Sub Rule" />
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
