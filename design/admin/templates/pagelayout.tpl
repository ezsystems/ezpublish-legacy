<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
{include uri="design:page_head.tpl"}

{* cache-block keys=array('navigation_tabs',$navigation_part.identifier,$current_user.contentobject_id) *}
{* Cache header for each navigation part *}

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/site.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent div#maincontent-design { width: 100%; } /* Avoid width bug in IE 5.5 */
</style>
<![endif]-->
{/literal}
</head>

<body>
<div id="allcontent">

<div id="header">
<div id="header-design">

<div id="logo">
<a href="/"><img src={"ezpublish-logo-200x40.gif"|ezimage} width="200" height="40" alt="" border="0" /></a>
</div>

{* --- Search ---*}
<div id="search">
<form action={"/content/search/"|ezurl} method="get">
    <input id="searchtext" type="text" size="20" name="SearchText" id="Search" value="" />
    <input id="searchbutton" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
    <p><label><input type="radio" checked="checked" />All content</label> <label><input type="radio" />Current location</label> <a href="/content/advancedsearch/">Advanced</a></p>
</form>
</div>

<div class="break"></div>

</div>
</div>

<hr class="hide" />

<div id="topmenu">
<div id="topmenu-design">

<h3 class="hide">Top menu</h3>

<ul>
    <li><div>
    {* Content menu *}
    {section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Content structure'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','RootNode','content.ini'))}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Content structure'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','RootNode','content.ini'))}
    {/section}
    </div></li>
    <li><div>
    {* Media menu *}
    {section show=eq($navigation_part.identifier,'ezmedianavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Media library'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','MediaRootNode','content.ini'))}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Media library'|i18n('design/admin/layout') menu_url=concat("/content/view/full/",ezini('NodeSettings','MediaRootNode','content.ini'))}
    {/section}
    </div></li>
    <li><div>
    {* Users menu *}
    {section show=eq($navigation_part.identifier,'ezusernavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='User accounts'|i18n('design/admin/layout') menu_url="/content/view/full/5/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='User accounts'|i18n('design/admin/layout') menu_url="/content/view/full/5/"}
    {/section}
    </div></li>
{section show=eq(ezpreference( 'interface_mode'),'advanced')}
    <li><div>
    {* Shop menu *}
    {section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Webshop'|i18n('design/admin/layout') menu_url="/shop/orderlist/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Webshop'|i18n('design/admin/layout') menu_url="/shop/orderlist/"}
    {/section}
    </div></li>
    <li><div>
    {* Set up menu *}
    {section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Setup'|i18n('design/admin/layout') menu_url="/setup/menu/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Setup'|i18n('design/admin/layout') menu_url="/setup/menu/"}
    {/section}
    </div></li>
{/section}
    <li><div>
    {* Personal *}
    {section show=eq($navigation_part.identifier,'ezmynavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='My account'|i18n('design/admin/layout') menu_url="/content/draft/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='My account'|i18n('design/admin/layout') menu_url="/content/draft/"}
    {/section}
    </div></li>
</ul>

</div>
</div>

{* /cache-block *}

<hr class="hide" />


<div id="path">
<div id="path-design">

{include uri="design:page_toppath.tpl"}

</div>
</div>


<hr class="hide" />

<div id="columns">

<div id="leftmenu">
<div id="leftmenu-design">

{section show=and( $ui_context|contains( 'edit' ), $ui_component|contains( 'content' ) )}
{include uri="design:edit_menu.tpl"}
{section-else}

{section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
{include uri="design:parts/content/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezmedianavigationpart')}
{include uri="design:parts/media/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
{include uri="design:parts/shop/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezusernavigationpart')}
{include uri="design:parts/user/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
{include uri="design:parts/setup/menu.tpl"}
{/section}

{section show=eq($navigation_part.identifier,'ezmynavigationpart')}
{include uri="design:parts/my/menu.tpl"}
{/section}
{/section}

</div>
</div>

<hr class="hide" />

<div id="rightmenu">
<div id="rightmenu-design">

<h3 class="hide">Right</h3>
{let quoted_node=concat( '"', $node.name, '"' )|wash()}

{* --- Current user ---*}
<h4>Current user</h4>
<p>{$current_user.contentobject.name|wash}</p>
<ul>
    <li><a href={concat("/content/edit/",$current_user.contentobject_id,"/")|ezurl}>Change user info</a></li>
    <li><a href={"/user/logout"|ezurl}>Logout</a></li>
</ul>

{* --- Bookmarks --- *}
<div id="bookmarks">
{section show=eq(ezpreference('bookmark_menu'),'on')}
 <h4><a href={"/content/bookmark/"|ezurl} title="{'Click here to manage your personal bookmarks.'|i18n( '/design/admin/layout' )}">{"Bookmarks"|i18n("design/admin/layout")}</a> <a class="showhide" href={"/user/preferences/set/bookmark_menu/off"|ezurl}>[-]</a></h4> 
<ul>
{let bookmark_list=fetch(content,bookmarks)}
{section name=BookMark loop=$bookmark_list}
<li>{$:item.node.object.content_class.identifier|class_icon( small, $:item.node.object.content_class.name )}&nbsp;<a href={$:item.node.url_alias|ezurl}>{$:item.node.name|wash}</a></li>
{/section}
{/let}
</ul>
{section-else}
 <h4><a href={"/content/bookmark/"|ezurl}>{"Bookmarks"|i18n("design/admin/layout")}</a> <a class="showhide" href={"/user/preferences/set/bookmark_menu/on"|ezurl}>[+]</a></h4> 
{/section}
</div>

{* Show "Add to bookmarks" button if we're viewing an actual node. *}
{section show=$node.node_id|is_set()}
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="ActionAddToBookmarks" value="{'Add bookmark'|i18n('design/standard/node/view')}" title="{'Click here to add %quoted_node to your personal bookmarks.'|i18n( '/design/admin/layout',, hash( '%quoted_node', $quoted_node ) )}" />
</form>
{/section}

{* --- History --- *}
<div id="history">
{section show=eq(ezpreference('history_menu'),'on')}
<h4>{"History"|i18n("design/admin/layout")} <a class="showhide" href={"/user/preferences/set/history_menu/off"|ezurl}>[-]</a></h4> 
<ul>
{let history_list=fetch(content,recent)}
{section name=History loop=$history_list}
<li>{$:item.node.object.content_class.identifier|class_icon( small, $:item.node.object.content_class.name )}&nbsp;<a href={$:item.node.url_alias|ezurl}>{$:item.node.name|wash}</a></li>
{/section}
{/let}
</ul>
{section-else}
 <h4>{"History"|i18n("design/admin/layout")} <a class="showhide" href={"/user/preferences/set/history_menu/on"|ezurl}>[+]</a></h4>
{/section}
</div>

{* --- Notifications --- *}
<h4><a href={"/notification/settings"|ezurl} title="{'Click here to manage your personal notification settings.'|i18n( '/design/admin/layout' )}">{"Notifications"|i18n("design/admin/layout")}</a></h4> 
{* Show "Add to notification" button if we're viewing an actual node. *}
{section show=$node.node_id|is_set()}
<form method="post" action={"content/action"|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="ActionAddToNotification" value="{'Add notification'|i18n('design/standard/node/view')}" title="{'Click here to add %quoted_node to your personal notification list.'|i18n( 'design/admin/layout',, hash( '%quoted_node', $quoted_node ) )}" />
</form>
{/section}

{* --- Interface mode --- *}
<h4>Interface mode</h4>
{section show=eq(ezpreference('interface_mode'),'simple')}
<p>{"Current mode"|i18n("design/admin/layout")}: {"Simple"|i18n("design/admin/layout")}</p>
<form method="post" action={"/user/preferences/set/interface_mode/advanced"|ezurl}>
<input class="button" type="submit" name="SetInterfaceModeAdvanced" value="{'Switch to advanced'|i18n('/design/admin/layout')}" title="{'Click here to put the user interface into advanced mode. Advanced mode provides a full-blown interface for power users and administrators.'|i18n( 'design/admin/layout' )}" />
</form>
{section-else}
<p>{"Current mode"|i18n("design/admin/layout")}: {"Advanced"|i18n("design/admin/layout")}</p>
<form method="post" action={"/user/preferences/set/interface_mode/simple"|ezurl}>
<input class="button" type="submit" name="SetInterfaceModeSimple" value="{'Switch to simple'|i18n('/design/admin/layout')}" title="{'Click here to put the user interface into simple mode. Simple mode provides a lightweight interface for content authors.'|i18n( 'design/admin/layout' )}" />
</form>
{/section}


</div>
</div>
{/let}

<hr class="hide" />

<div id="maincontent"><div id="fix">
<div id="maincontent-design">

{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

</div>
<div class="break"></div>
</div></div>

<div class="break"></div>
</div>

<hr class="hide" />

<div id="footer">
<div id="footer-design">

{include uri="design:page_copyright.tpl"}

</div>
</div>

<div class="break"></div>
</div>

{* __FIX_ME__ Temporary debug stuff - to be removed later. *}
<h2>Temporary debug stuff (from pagelayout.tpl):</h2>
$navigation_part.identifier: {$navigation_part.identifier}<br />
$ui_context:   {$ui_context}<br />
$ui_component: {$ui_component}<br />

<!--DEBUG_REPORT-->

</body>
</html>
