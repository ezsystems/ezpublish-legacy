<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={concat("/content/view/full/2/",ezini('NodeSettings','RootNode','content.ini'))|ezurl}>{"Frontpage"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/content/view/sitemap/2/"|ezurl}>{"Sitemap"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={concat("/content/trash/",ezini('NodeSettings','RootNode','content.ini'))|ezurl}>{"Trash"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
 <a class="leftmenuitem" href={"/content/bookmark/"|ezurl}>{"Bookmarks"|i18n("design/admin/layout")}</a>
{section show=eq(ezpreference('bookmark_menu'),'on')}
 <a href={"/user/preferences/set/bookmark_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a>
</div>
<ul class="leftmenu">
{let bookmark_list=fetch(content,bookmarks)}
{section name=BookMark loop=$bookmark_list}
<li>&#187; <a href={$:item.node.url_alias|ezurl}>{$:item.node.object.content_class.identifier|class_icon( small, $:item.node.object.content_class.name )}&nbsp;{$:item.node.name|wash}</a></li>
{/section}
{/let}
</ul>
{section-else}
 <a href={"/user/preferences/set/bookmark_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a>
</div>
{/section}

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
 <a class="leftmenuitem">{"History"|i18n("design/admin/layout")}</a>
{section show=eq(ezpreference('history_menu'),'on')}
 <a href={"/user/preferences/set/history_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a>
</div>
<ul class="leftmenu">
{let history_list=fetch(content,recent)}
{section name=History loop=$history_list}
<li>&#187; <a href={$:item.node.url_alias|ezurl}>{$:item.node.object.content_class.identifier|class_icon( small, $:item.node.object.content_class.name )}&nbsp;{$:item.node.name|wash}</a></li>
{/section}
{/let}
</ul>
{section-else}
 <a href={"/user/preferences/set/history_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a>
</div>
{/section}
