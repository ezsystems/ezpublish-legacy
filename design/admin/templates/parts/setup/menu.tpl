<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/setup/menu/"|ezurl}>{"Menu management"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/content/urltranslator/"|ezurl}>{"URL translator"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/url/list/"|ezurl}>{"URL management"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/rss/list/"|ezurl}>{"RSS"|i18n("design/admin/layout",'Really Simple Syndication')}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/setup/cache/"|ezurl}>{"Cache"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/settings/view/"|ezurl}>{"Ini settings"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/setup/toolbarlist/"|ezurl}>{"Toolbar settings"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem"  href={"/search/stats/"|ezurl}>{"Search stats"|i18n("design/admin/layout")}</a>
</div>

<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem"  href={"/setup/info/"|ezurl}>{"System information"|i18n("design/admin/layout")}</a>
</div>


<div style="width: 100%; padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
{section show=eq(ezpreference('advanced_menu'),'on')}
 <a class="leftmenuitem" href={"/user/preferences/set/advanced_menu/off"|ezurl}>{"Advanced"|i18n("design/admin/layout")}</a>
 <a href={"/user/preferences/set/advanced_menu/off"|ezurl}><img src={"down.gif"|ezimage} alt="" width="11" height="6" /></a>
</div>
<ul class="leftmenu">
<li>&#187; <a href={"/class/grouplist/"|ezurl}>{"Classes"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/setup/templatelist/"|ezurl}>{"Templates"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/section/list/"|ezurl}>{"Sections"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/pdf/list/"|ezurl}>{"PDF export"|i18n("design/admin/layout",'PDF export')}</a></li>
<li>&#187; <a href={"/setup/rad/"|ezurl}>{"RAD"|i18n("design/admin/layout",'Rapid Application Development')}</a></li>
<li>&#187; <a href={"/setup/extensions/"|ezurl}>{"Extension setup"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/workflow/grouplist/"|ezurl}>{"Workflows"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/trigger/list/"|ezurl}>{"Triggers"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/content/translations/"|ezurl}>{"Translations"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/package/list/"|ezurl}>{"Packages"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/notification/runfilter/"|ezurl}>{"Notification"|i18n("design/admin/layout")}</a></li>
<li>&#187; <a href={"/setup/systemupgrade"|ezurl}>{"System upgrade"|i18n("design/admin/layout")}</a></li>
</ul>
{section-else}
 <a class="leftmenuitem" href={"/user/preferences/set/advanced_menu/on"|ezurl}>{"Advanced"|i18n("design/admin/layout")}</a>
 <a href={"/user/preferences/set/advanced_menu/on"|ezurl}><img src={"up.gif"|ezimage} alt="" width="11" height="6" /></a>
</div>
{/section}
