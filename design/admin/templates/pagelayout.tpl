<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
{include uri='design:page_head.tpl'}

{* cache-block keys=array('navigation_tabs',$navigation_part.identifier,$current_user.contentobject_id) *}
{* Cache header for each navigation part *}

    <link rel="stylesheet" type="text/css" href={'stylesheets/core.css'|ezdesign} />
    <link rel="stylesheet" type="text/css" href={'stylesheets/site.css'|ezdesign} />
    <link rel="stylesheet" type="text/css" href={'stylesheets/debug.css'|ezdesign} />

{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent div#maincontent-design { width: 100%; } /* Avoid width bug in IE 5.5 */
div#maincontent div.context-block { width: 100%; } /* Avoid width bug in IE 5.5 */
</style>
<![endif]-->
{/literal}

    {section show=and( or( eq( $navigation_part.identifier, 'ezcontentnavigationpart' ),
                           eq( $navigation_part.identifier, 'ezmedianavigationpart' ),
                           eq( $navigation_part.identifier, 'ezusernavigationpart' ) ),
                       ezpreference( 'admin_left_menu_width' )|gt( 0 ) )}
<style>
div#leftmenu {ldelim} width: {ezpreference( 'admin_left_menu_width' )}em; {rdelim}
div#maincontent {ldelim} margin-left: {sum( ezpreference( 'admin_left_menu_width' ), 0.5 )}em; {rdelim}
</style>
{/section}

</head>

<body>

<div id="allcontent">
<div id="header">
<div id="header-design">

<div id="logo">
<a href="/"><img src={'ezpublish-logo-200x40.gif'|ezimage} width="200" height="40" alt="" border="0" /></a>
</div>

{* --- Search ---*}
<div id="search">
<form action={'/content/search/'|ezurl} method="get">
    <input id="searchtext" type="text" size="20" name="SearchText" id="Search" value="" />
    <input id="searchbutton" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
    <p><label><input type="radio" checked="checked" />{'All content'|i18n( 'design/admin/pagelayout' )}</label> <label><input type="radio" />{'Current location'|i18n( 'design/admin/pagelayout' )}</label> <a href="/content/advancedsearch/">{'Advanced'|i18n( 'design/admin/pagelayout' )}</a></p>
</form>
</div>

<div class="break"></div>

</div>
</div>

<hr class="hide" />

<div id="topmenu">
<div id="topmenu-design">

<h3 class="hide">Top menu</h3>

{* Browse mode... *}
{section show=eq( $ui_context, 'browse' )}
<ul>
    <li><div>
    {* Content menu *}
    {section show=$browse.top_level_nodes|contains( ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {include uri='design:page_menuheadselected.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/browse/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {section-else}
    {include uri='design:page_menuheadgray.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' )}
    {/section}
    </div></li>
    <li><div>
    {* Media menu *}
    {section show=$browse.top_level_nodes|contains( ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
    {include uri='design:page_menuheadselected.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/browse/', ezini('NodeSettings','MediaRootNode','content.ini' ) )}
    {section-else}
    {include uri='design:page_menuheadgray.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' )}
    {/section}
    </div></li>
    <li><div>
    {* Users menu *}
    {section show=$browse.top_level_nodes|contains( ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
    {include uri='design:page_menuheadselected.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/browse/', ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
    {section-else}
    {include uri='design:page_menuheadgray.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' )}
    {/section}
    </div></li>
    <li><div>
    {* Shop menu *}
    {include uri='design:page_menuheadgray.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' )}
    </div></li>
    <li><div>
    {* Set up menu *}
    {include uri='design:page_menuheadgray.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' )}
    </div></li>

    <li><div>
    {* Design menu *}
    {include uri='design:page_menuheadgray.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' )}
    </div></li>


    <li><div>
    {* Personal *}
    {include uri='design:page_menuheadgray.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' )}
    </div></li>
</ul>

{* NOT Browse mode... *}
{section-else}
<ul>
    <li><div>
    {* Content menu *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat('/content/view/full/',ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {/section}
    </div></li>
    <li><div>
    {* Media menu *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
    {/section}
    </div></li>
    <li><div>
    {* Users menu *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url='/content/view/full/5/'}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url='/content/view/full/5/'}
    {/section}
    </div></li>
    <li><div>
    {* Shop menu *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' ) menu_url='/shop/orderlist/'}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' ) menu_url='/shop/orderlist/'}
    {/section}
    </div></li>
    <li><div>
    {* Design menu *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' ) menu_url='/design/menuconfig'}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' ) menu_url='/design/menuconfig'}
    {/section}
    </div></li>
    <li><div>
    {* Set up menu *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' ) menu_url='/setup/menu/'}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' ) menu_url='/setup/menu/'}
    {/section}
    </div></li>
    <li><div>
    {* Personal *}
    {section show=ne( $ui_context, 'edit' )}
        {include uri='design:page_menuheadselected.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' ) menu_url='/content/draft/'}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' ) menu_url='/content/draft/'}
    {/section}
    </div></li>
</ul>
{/section}


</div>
</div>

{* /cache-block *}

<hr class="hide" />


<div id="path">
<div id="path-design">

{include uri='design:page_toppath.tpl'}

</div>
</div>


<hr class="hide" />

<div id="columns">

<div id="leftmenu">
<div id="leftmenu-design">

{section show=and( $ui_context|eq( 'edit' ), $ui_component|eq( 'content' ) )}
    {include uri='design:edit_menu.tpl'}
{section-else}

{section show=eq( $navigation_part.identifier, 'ezcontentnavigationpart' )}
    {include uri='design:parts/content/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezmedianavigationpart' )}
    {include uri='design:parts/media/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezshopnavigationpart' )}
    {include uri='design:parts/shop/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezusernavigationpart' )}
    {include uri='design:parts/user/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezdesignnavigationpart' )}
    {include uri='design:parts/design/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezsetupnavigationpart' )}
    {include uri='design:parts/setup/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezmynavigationpart' )}
    {include uri='design:parts/my/menu.tpl'}
{/section}

{/section}

</div>
</div>

<hr class="hide" />

<div id="rightmenu">
<div id="rightmenu-design">

<h3 class="hide">Right</h3>
{* --- Current user ---*}
<h4>{'Current user'|i18n( 'design/admin/pagelayout' )}</h4>
<p>{$current_user.contentobject.name|wash}</p>
<ul>
{let basket=fetch( shop, basket )}
{section show=ne( $ui_context, 'edit' )}
    <li><a href={concat( '/content/edit/',  $current_user.contentobject_id, '/' )|ezurl}>{'Change information'|i18n( 'design/admin/pagelayout' )}</a></li>
    <li><a href={concat( '/user/password/', $current_user.contentobject_id )|ezurl}>{'Change password'|i18n( 'design/admin/pagelayout' )}</a></li>

{section show=$basket.is_empty|not}
<li><a href={'shop/basket'|ezurl}>{'Webshop basket (%basket_count)'|i18n( 'design/admin/pagelayout',, hash( %basket_count, $basket.items|count ) )}</a></li>
{/section}

        <li><a href={'/user/logout'|ezurl}>{'Logout'|i18n( 'design/admin/pagelayout' )}</a></li>
{section-else}
    <li><span class="disabled">{'Change user info'|i18n( 'design/admin/pagelayout' )}</span></li>
    <li><span class="disabled">{'Logout'|i18n( 'design/admin/pagelayout' )}</span></li>
{/section}
{/let}
</ul>

{* --- Bookmarks --- *}
<div id="bookmarks">
{section show=ezpreference( 'admin_bookmark_menu' )}
    {section show=ne( $ui_context, 'edit' )}
     <h4><a href={'/content/bookmark/'|ezurl} title="{'Manage your personal bookmarks.'|i18n( '/design/admin/pagelayout' )}">{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</a> <a class="showhide" href={'/user/preferences/set/admin_bookmark_menu/0'|ezurl}>[-]</a></h4> 
    {section-else}
     <h4><span class="disabled">{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</span> <span class="disabled openclose">[-]</span></h4> 
    {/section}

<ul>
{let bookmark_list=fetch( content, bookmarks )}
{section var=Bookmarks loop=$bookmark_list}
    {section show=ne( $ui_context, 'edit' )}
    <li>

<a href="#" onclick="ezpopmnu_showTopLevel( 'BookmarkMenu', '{$Bookmarks.item.node_id}' , '{$Bookmarks.item.contentobject_id}' , '{$Bookmarks.item.name|shorten(18)}'); return false;">{$Bookmarks.item.node.object.content_class.identifier|class_icon( small, $Bookmarks.item.node.object.content_class.name )}</a>&nbsp;<a href={$Bookmarks.item.node.url_alias|ezurl}>{$Bookmarks.item.node.name|wash}</a></li>
{section-else}
    <li>{$Bookmarks.item.node.object.content_class.identifier|class_icon( small, $Bookmarks.item.node.object.content_class.name )}&nbsp;<span class="disabled">{$Bookmarks.item.node.name|wash}</span></li>
    {/section}
{/section}
{/let}
</ul>
{section-else}
    {section show=ne( $ui_context,'edit' )}
    <h4><a href={'/content/bookmark/'|ezurl}>{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</a> <a class="showhide" href={'/user/preferences/set/admin_bookmark_menu/1'|ezurl}>[+]</a></h4>
    {section-else}
     <h4><span class="disabled">{'Bookmarks'|i18n( 'design/admin/pagelayout' )}</span> <span class="disabled openclose">[+]</span></h4> 
    {/section}
{/section}
</div>

{* Show "Add to bookmarks" button if we're viewing an actual node. *}
{section show=$node.node_id|is_set}
<form method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="ActionAddToBookmarks" value="{'Bookmark item'|i18n( 'design/admin/pagelayout' )}" title="{'Add the current item to your bookmarks.'|i18n( '/design/admin/pagelayout' )}" />
</form>
{/section}

{* --- Notifications --- *}
{section show=ne( $ui_context, 'edit' )}
<h4><a href={'/notification/settings'|ezurl} title="{'Manage your personal notification settings.'|i18n( '/design/admin/pagelayout' )}">{'Notifications'|i18n( 'design/admin/pagelayout' )}</a></h4> 
{section-else}
<h4><span class="disabled">{'Notifications'|i18n( 'design/admin/pagelayout' )}</span></h4> 
{/section}

{* Show "Add to notification" button if we're viewing an actual node. *}
{section show=$node.node_id|is_set}
<form method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="ActionAddToNotification" value="{'Add notification'|i18n( 'design/admin/pagelayout' )}" title="{'Add the current item to your personal notification list.'|i18n( 'design/admin/pagelayout' )}" />
</form>
{/section}

</div>
</div>


<hr class="hide" />

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->
{* Main area START *}

{include uri='design:page_mainarea.tpl'}

{* Main area END *}

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

<div class="break"></div>
</div>

<hr class="hide" />

<div id="footer">
<div id="footer-design">

{include uri='design:page_copyright.tpl'}

</div>
</div>

<div class="break"></div>
</div>

{* __FIX_ME__ Temporary debug stuff - to be removed later. *}
<h2>Temporary debug stuff (from pagelayout.tpl):</h2>
$navigation_part.identifier: {$navigation_part.identifier}<br />
$ui_context:   {$ui_context}<br />
$ui_component: {$ui_component}<br />

{* The popup menu include must be outside all divs. It is hidden by default. *}
{include uri='design:popupmenu/popup_menu.tpl'}

</body>
</html>
