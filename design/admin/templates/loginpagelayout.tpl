<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
{include uri="design:page_head.tpl"}

    <link rel="stylesheet" type="text/css" href={'stylesheets/core.css'|ezdesign} />
    <link rel="stylesheet" type="text/css" href={'stylesheets/site.css'|ezdesign} />
    <link rel="stylesheet" type="text/css" href={'stylesheets/debug.css'|ezdesign} />

    <script language="JavaScript" src={"javascript/tools/ezjsselection.js"|ezdesign}></script>
{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent div#maincontent-design { width: 99%; } /* Avoid width bug in IE 5.5 */
div#maincontent div.context-block { width: 100%; } /* Avoid width bug in IE 5.5 */
</style>
<![endif]-->
<!--
<style>
div#path { margin-top: -1px; }
</style>
-->
{/literal}

</head>

<body>

<div id="allcontent">

<div id="header">
<div id="header-design">
</div>
</div>

<div id="topmenu">
<div id="topmenu-design">

<h3 class="hide">Top menu</h3>

{* Browse mode... *}
{section show=eq( $ui_context, 'browse' )}
<ul>
    {* Content menu *}
    {section show=$browse.top_level_nodes|contains( ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {include uri='design:page_menuheadenabled.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/browse/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {section-else}
    {include uri='design:page_menuheadgray.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' )}
    {/section}

    {* Media menu *}
    {section show=$browse.top_level_nodes|contains( ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
    {include uri='design:page_menuheadenabled.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/browse/', ezini('NodeSettings','MediaRootNode','content.ini' ) )}
    {section-else}
    {include uri='design:page_menuheadgray.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' )}
    {/section}

    {* Users menu *}
    {section show=$browse.top_level_nodes|contains( ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
    {include uri='design:page_menuheadenabled.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/browse/', ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
    {section-else}
    {include uri='design:page_menuheadgray.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' )}
    {/section}

    {* Shop menu *}
    {include uri='design:page_menuheadgray.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' )}

    {* Set up menu *}
    {include uri='design:page_menuheadgray.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' )}

    {* Design menu *}
    {include uri='design:page_menuheadgray.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' )}

    {* Personal *}
    {include uri='design:page_menuheadgray.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' )}
</ul>

{* NOT Browse mode... *}
{section-else}
<ul>
    {* Content menu *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Content structure'|i18n( 'design/admin/pagelayout' ) menu_url=concat('/content/view/full/',ezini( 'NodeSettings', 'RootNode', 'content.ini' ) )}
    {/section}

    {* Media menu *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezmedianavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Media library'|i18n( 'design/admin/pagelayout' ) menu_url=concat( '/content/view/full/', ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
    {/section}

    {* Users menu *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezusernavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url='/content/view/full/5/'}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url='/content/view/full/5/'}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='User accounts'|i18n( 'design/admin/pagelayout' ) menu_url='/content/view/full/5/'}
    {/section}

    {* Shop menu *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' ) menu_url='/shop/orderlist/'}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' ) menu_url='/shop/orderlist/'}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Webshop'|i18n( 'design/admin/pagelayout' ) menu_url='/shop/orderlist/'}
    {/section}

    {* Design menu *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezdesignnavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' ) menu_url='/design/menuconfig'}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' ) menu_url='/design/menuconfig'}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Design'|i18n( 'design/admin/pagelayout' ) menu_url='/design/menuconfig'}
    {/section}

    {* Set up menu *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' ) menu_url='/setup/menu/'}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' ) menu_url='/setup/menu/'}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='Setup'|i18n( 'design/admin/pagelayout' ) menu_url='/setup/menu/'}
    {/section}

    {* Personal *}
    {section show=ne( $ui_context, 'edit' )}
        {section show=eq($navigation_part.identifier,'ezmynavigationpart')}
        {include uri='design:page_menuheadselected.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' ) menu_url='/content/draft/'}
        {section-else}
        {include uri='design:page_menuheadenabled.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' ) menu_url='/content/draft/'}
        {/section}
    {section-else}
        {include uri='design:page_menuheadgray.tpl' menu_text='My account'|i18n( 'design/admin/pagelayout' ) menu_url='/content/draft/'}
    {/section}

</ul>
{/section}
<div class="break"></div>
</div>
</div>

<div id="path">
<div id="path-design">

{include uri="design:page_toppath.tpl"}

</div>
</div>


<hr class="hide" />

<div id="columns">

<div id="leftmenu">
<div id="leftmenu-design">

</div>
</div>

<hr class="hide" />

<div id="rightmenu">
<div id="rightmenu-design">

</div>
</div>

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

</body>
</html>
