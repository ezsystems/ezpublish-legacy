<ul>
    <li><a href={concat("/content/view/full/2/",ezini('NodeSettings','RootNode','content.ini'))|ezurl}>{"Frontpage"|i18n("design/admin/layout")}</a></li>
    <li><a href={"/content/view/sitemap/2/"|ezurl}>{"Sitemap"|i18n("design/admin/layout")}</a></li>
    <li><a href={concat("/content/trash/",ezini('NodeSettings','RootNode','content.ini'))|ezurl}>{"Trash"|i18n("design/admin/layout")}</a></li>
    {section show=eq(ezpreference('bookmark_menu'),'on')}
    <li><a href={"/content/bookmark/"|ezurl}>{"Bookmarks"|i18n("design/admin/layout")}</a>
    <a href={"/user/preferences/set/bookmark_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a>
        <ul>
        {let bookmark_list=fetch(content,bookmarks)}
        {section name=BookMark loop=$bookmark_list}
        <li><a href={$:item.node.url_alias|ezurl}>{$:item.node.object.content_class.identifier|class_icon( small, $:item.node.object.content_class.name )}&nbsp;{$:item.node.name|wash}</a></li>
        {/section}
        {/let}
        </ul>
    </li>
    {section-else}
    <li><a href={"/content/bookmark/"|ezurl}>{"Bookmarks"|i18n("design/admin/layout")}</a>
    <a href={"/user/preferences/set/bookmark_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a></li>
    {/section}
    {section show=eq(ezpreference('history_menu'),'on')}
    <li>{"History"|i18n("design/admin/layout")}
    <a href={"/user/preferences/set/history_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a>
        <ul>
        {let history_list=fetch(content,recent)}
        {section name=History loop=$history_list}
        <li><a href={$:item.node.url_alias|ezurl}>{$:item.node.object.content_class.identifier|class_icon( small, $:item.node.object.content_class.name )}&nbsp;{$:item.node.name|wash}</a></li>
        {/section}
        {/let}
        </ul>
    </li>
    {section-else}
    <li>{"History"|i18n("design/admin/layout")}
    <a href={"/user/preferences/set/history_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a></li>
    {/section}
</ul>
