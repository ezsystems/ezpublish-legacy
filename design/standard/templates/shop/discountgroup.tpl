<form action={"shop/discountgroup"|ezurl} method="post" name="DiscountGroup">

<div class="maincontentheader">
<h1>{"Defined discount groups"|i18n("design/standard/shop")}</h1>
</div>
<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th>{"Name:"|i18n("design/standard/shop")}</th>
</tr>
{section name=Groups loop=$discountgroup_array sequence=array(bglight,bgdark)}
<tr>
    <td class="{$Groups:sequence}"><a href={concat($module.functions.discountgroupview.uri,"/",$Groups:item.id)|ezurl}>{$Groups:item.name}</a></td>
    <td class="{$Groups:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.discountgroupedit.uri,"/",$Groups:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="{'Edit'|i18n('design/standard/shop')}" /></a></div></td>
    <td class="{$Groups:sequence}" width="1%"><input type="checkbox" name="discountGroupIDList[]" value="{$Groups:item.id}"></td>
</tr>
{/section}
</table>

<div class="buttonblock">
{include uri="design:gui/button.tpl" name=newgroup id_name=AddDiscountGroupButton value="New"|i18n("design/standard/shop")}
{include uri="design:gui/button.tpl" name=removegroup id_name=RemoveDiscountGroupButton value="Remove"|i18n("design/standard/shop")}
</div>

</form>
