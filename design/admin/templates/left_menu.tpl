<p></p>
{default menu_enable_content=true()
         menu_enable_setup=true()
         menu_enable_shop=true()
         menu_enable_users=true()}

{section show=$:menu_enable_content}
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
{/section}

{section show=$:menu_enable_shop}
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
{/section}

{section show=$:menu_enable_users}
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
    <p class="menuitem"><a class="menuitem" href={"/content/view/full/5/"|ezurl}>{"Users"|i18n("design/standard/layout")}</a></p>
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
{/section}

{section show=$:menu_enable_setup}
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
{/section}

{/default}
