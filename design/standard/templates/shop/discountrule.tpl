<form action={"shop/discountrule"|ezurl} method="post" name="DiscountRule">

<div class="maincontentheader">
<h1>{"Defined discount rules"|i18n('content/class')}</h1>
</div>
<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"Name"|i18n('content/class')}:</th>
</tr>
{section name=Rules loop=$discountrule_array sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Rules:sequence}"><a href={concat($module.functions.discountrulemembershipview.uri,"/",$Rules:item.id)|ezurl}>{$Rules:item.name}</a></td>
    <td class="{$Rules:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.discountruleedit.uri,"/",$Rules:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a></div></td>
    <td class="{$Rules:sequence}" width="1%"><input type="checkbox" name="discountRuleIDList[]" value="{$Rules:item.id}"></td>
</tr>
{/section}
</table>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=newrule id_name=AddDiscountRuleButton value="New"|i18n('shop')}
{include uri="design:gui/button.tpl" name=removerule id_name=RemoveDiscountRuleButton value="Remove"|i18n('shop')}
</div>

</form>
