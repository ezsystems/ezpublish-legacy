<ul>
    <li><a href={"/setup/cache/"|ezurl}>{"Cache management"|i18n("design/admin/layout")}</a></li>
    <li><a href={"/search/stats/"|ezurl}>{"Search statistics"|i18n("design/admin/layout")}</a></li>
    <li><a href={"/setup/info/"|ezurl}>{"System information"|i18n("design/admin/layout")}</a></li>
    <li><a href={"/url/list/"|ezurl}>{"URL management"|i18n("design/admin/layout")}</a></li>
    <li><a href={"/content/urltranslator/"|ezurl}>{"URL translator"|i18n("design/admin/layout")}</a></li>
    {section show=eq(ezpreference('advanced_menu'),'on')}
    <li><a href={"/user/preferences/set/advanced_menu/off"|ezurl}>{"Advanced"|i18n("design/admin/layout")}</a>
    <a href={"/user/preferences/set/advanced_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a>
        <ul>
        <li><a href={"/class/grouplist/"|ezurl}>{"Classes"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/setup/extensions/"|ezurl}>{"Extensions"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/settings/view"|ezurl}>{"Ini settings"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/notification/runfilter/"|ezurl}>{"Notification"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/pdf/list/"|ezurl}>{"PDF export"|i18n("design/admin/layout",'PDF export')}</a></li>
        <li><a href={"/package/list/"|ezurl}>{"Packages"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/setup/rad/"|ezurl}>{"RAD"|i18n("design/admin/layout",'Rapid Application Development')}</a></li>
        <li><a href={"/rss/list"|ezurl}>{"RSS"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/section/list/"|ezurl}>{"Sections"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/setup/session"|ezurl}>{"Sessions"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/setup/systemupgrade"|ezurl}>{"System upgrade"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/setup/templatelist/"|ezurl}>{"Templates"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/content/translations/"|ezurl}>{"Translations"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/trigger/list/"|ezurl}>{"Triggers"|i18n("design/admin/layout")}</a></li>
        <li><a href={"/workflow/grouplist/"|ezurl}>{"Workflows"|i18n("design/admin/layout")}</a></li>
        </ul>
    </li>
    {section-else}
    <li><a href={"/user/preferences/set/advanced_menu/on"|ezurl}>{"Advanced"|i18n("design/admin/layout")}</a>
    <a href={"/user/preferences/set/advanced_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a></li>
    {/section}
</ul>
