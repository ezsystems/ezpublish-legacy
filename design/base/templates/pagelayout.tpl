<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>

<style type="text/css">
    @import url({"stylesheets/core.css"|ezdesign});
    @import url({"stylesheets/classes.css"|ezdesign});
{*    @import url({"stylesheets/site.css"|ezdesign}); *}
    @import url({ezini('StylesheetSettings','MainCSS','design.ini')|ezdesign});
    @import url({"stylesheets/debug.css"|ezdesign});
{*  @import url({"stylesheets/t1.css"|ezdesign}); *}

    {section show=eq(ezini('SelectedMenu','LeftMenu','menu.ini'),'')}
    @import url({ezini('StylesheetSettings','NoLeftMenuCSS','design.ini')|ezdesign});
    {/section}

    {section show=ezini('Toolbar_right','Tool','toolbar.ini')|count|eq(0)}
    @import url({ezini('StylesheetSettings','NoRightMenuCSS','design.ini')|ezdesign});
    {/section}

</style>

{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent div.design { width: 100%; } /* This is needed to avoid width bug in IE 5.5 */
</style>
<![endif]-->
{/literal}

</head>
<body>

<div id="allcontent">

<div id="header">
    <div id="header-design">
    <img src={"/images/t1/t1-logo-placeholder.gif"|ezdesign} height="70" width="211" alt="Company logo" />
    </div>
</div>

{section show=ezini('Toolbar_top','Tool','toolbar.ini')|count}
<div id="toolbar-top">
    <div id="toolbar-design">
        {tool_bar name=top view=line}
    </div>
</div>
{/section}

<div class="break"></div>

<hr class="hide" />

{menu name=TopMenu}

<hr class="hide" />

<div id="path">
    <div id="path-design">
    {include uri="design:parts/path.tpl"}
    </div>
</div>

<hr class="hide" />

<div id="columns">

{menu name=LeftMenu}

<hr class="hide" />

{section show=ezini('Toolbar_right','Tool','toolbar.ini')|count}
<div id="rightmenu">
<div id="rightmenu-design">

<h3 class="hide">Right menu</h3>

<div id="toolbar-right">
    <div id="toolbar-design">
       {tool_bar name=right view=full}
    </div>
</div>

</div>
</div>
{/section}

<hr class="hide" />

<div id="maincontent"><div id="fix">
<div id="maincontent-design">

{$module_result.content}

</div>
<div class="break"></div>
</div></div>

<div class="break"></div>
</div>

<hr class="hide" />

{section show=ezini('Toolbar_right','Tool','toolbar.ini')|count}
<div id="toolbar-bottom">
    <div id="toolbar-design">
       {tool_bar name=bottom view=line}
    </div>
</div>
{/section}

<div id="footer">
<div id="footer-design">

<address>Powered by <a href="http://ez.no">eZ publish&reg;&trade; open source content management system</a> and development framework.<br />
{ezini('SiteSettings','MetaDataArray','site.ini').copyright}
</address>

</div>
</div>

<div class="break"></div>
</div>

</body>
</html>
