<form method="post" action={"/setup/menu/"|ezurl}>
<h1>{"Menu setup"|i18n("design/standard/setup")}</h1>

<select name="CurrentSiteAccess">
{section name=SiteAccess loop=ezini('SiteAccessSettings','AvailableSiteAccessList')}
    {section show=eq($current_siteaccess,$:item)}
        <option value="{$SiteAccess:item}" selected="selected">{$:item}</option>
    {section-else}
        <option value="{$SiteAccess:item}">{$:item}</option>
    {/section}
{/section}
</select>
<input type="submit" value="{"Set"|i18n("design/standard/setup")}" name="SelectCurrentSiteAccessButton" />


<table border="0">
<tr>
{section var=menu loop=$available_menu_array}
    <td>
    <h2>{$menu.item.settings.TitleText}</h2>

    <img src={$menu.item.settings.MenuThumbnail|ezimage} alt="{$menu.item.settings.TitleText}" />

    <input type="radio" name="MenuType" {$current_menu|eq($menu.item.type)|choose('','checked="checked"')}  value="{$menu.item.type}" />
    </td>
    {delimiter modulo=2}
     </tr>
     <tr>
    {/delimiter}
{/section}
</tr>
</table>

<input type="submit" name="StoreButton" value="{"Store"|i18n("design/standard/setup")}" />

</form>

