<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{"Creating"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="menu">
{switch match=fetch('content', 'can_instantiate_classes')}
{case match=1}
<form method="post" action={"content/action"|ezurl}>

         <select name="ClassID">
	      {section name=Classes loop=fetch('content', 'can_instantiate_class_list')}
	      <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	      {/section}
         </select>
         <input class="button" type="submit" name="NewButton" value="{'New'|i18n('design/standard/node/view')}" />
</form>
{/case}
{case match=0}

{/case}
{/switch}

    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{"Content"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/content/view/full/2/"|ezurl}>{"List"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/content/view/sitemap/2/"|ezurl}>{"Sitemap"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"content/draft/"|ezurl}>{"My drafts"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{"Set up"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/class/grouplist/"|ezurl}>{"Classes"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/section/list/"|ezurl}>{"Sections"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/workflow/grouplist/"|ezurl}>{"Workflows"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/trigger/list/"|ezurl}>{"Triggers"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem"  href={"/search/stats/"|ezurl}>{"Search stats"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{"Shop"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/shop/orderlist/"|ezurl}>{"Order list"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/shop/vattype/"|ezurl}>{"VAT types"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/shop/discountgroup/"|ezurl}>{"Discount"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{"Users"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/content/view/sitemap/5/"|ezurl}>{"Users"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/role/list/"|ezurl}>{"Roles"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/notification/list/"|ezurl}>{"My Notifications"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/task/view/"|ezurl}>{"My Tasks"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
</table>

{*
<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheadlight" colspan="2">
    <p class="menuhead">{"Content"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/">{"New article"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/">{"New link"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/">{"New product"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
</table>

*}
