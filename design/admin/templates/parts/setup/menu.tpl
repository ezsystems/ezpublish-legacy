<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/class/grouplist/"|ezurl}>{"Classes"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/section/list/"|ezurl}>{"Sections"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/setup/templatelist/"|ezurl}>{"Templates"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/setup/rad/"|ezurl}>{"RAD"|i18n("design/admin/layout",'Rapid Application Development')}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem"  href={"/setup/extensions/"|ezurl}>{"Extension setup"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/workflow/grouplist/"|ezurl}>{"Workflows"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/trigger/list/"|ezurl}>{"Triggers"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/content/translations/"|ezurl}>{"Translations"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/setup/cache/"|ezurl}>{"Cache"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem" href={"/notification/runfilter/"|ezurl}>{"Notification"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem"  href={"/search/stats/"|ezurl}>{"Search stats"|i18n("design/admin/layout")}</a>
</div>

<div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
<a class="leftmenuitem"  href={"/setup/info/"|ezurl}>{"System information"|i18n("design/admin/layout")}</a>
</div>



{section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
   {'Site:'|i18n('design/standard/layout')} {ezini('SiteSettings','SiteURL')}
   {'Version:'|i18n('design/standard/layout')} {$ezinfo.version}
   {'Revision:'|i18n('design/standard/layout')} {$ezinfo.revision}
 {/section}

