{section show=eq(ezpreference('content_structure_menu'),'on')}
<h4>{"Content structure"|i18n("design/admin/layout")} <a href={"/user/preferences/set/content_structure_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a></h4>
<div id="contentstructure">
    {include uri="design:menu/content_structure_menu.tpl"}
</div>
{section-else}
<h4>{"Content structure"|i18n("design/admin/layout")} <a href={"/user/preferences/set/content_structure_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a></h4>
{/section}

<ul>
{*    <li><a href={"/content/view/sitemap/2/"|ezurl}>{"Sitemap"|i18n("design/admin/layout")}</a></li>
    <li><a href={concat("/content/trash/",ezini('NodeSettings','RootNode','content.ini'))|ezurl}>{"Trash"|i18n("design/admin/layout")}</a></li>
</ul>