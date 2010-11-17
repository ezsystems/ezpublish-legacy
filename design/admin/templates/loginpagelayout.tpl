<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>
{include uri="design:page_head.tpl"}

<script type="text/javascript" src={"javascript/tools/ezjsselection.js"|ezdesign}></script>
{section name=JavaScript loop=ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ) }
<script type="text/javascript" src={concat( 'javascript/',$:item )|ezdesign}></script>
{/section}

<style type="text/css">
    @import url({'stylesheets/core.css'|ezdesign});
    @import url({'stylesheets/site.css'|ezdesign});
    @import url({'stylesheets/debug.css'|ezdesign});
{section var=css_file loop=ezini( 'StylesheetSettings', 'CSSFileList', 'design.ini' )}
    @import url({concat( 'stylesheets/',$css_file )|ezdesign});
{/section}
</style>

{literal}
<!--[if IE]>
<style type="text/css">
div#leftmenu div.box-bc, div#rightmenu div.box-bc { border-bottom: 1px solid #bfbeb6; /* Strange IE bug fix */ }
div#contentstructure { overflow-x: auto; overflow-y: hidden; } /* hide vertical scrollbar in IE */
div.menu-block li { width: 19%; } /* Avoid width bug in IE */
div.notranslations li { width: 24%; } /* Avoid width bug in IE */
div.context-user div.menu-block li { width: 14%; } /* Avoid width bug in IE */
input.button, input.button-disabled { padding: 0 0.5em 0 0.5em; overflow: visible; }
input.box, textarea.box { width: 98%; }
td input.box, td textarea.box { width: 97%; }
div#search p.select { margin-top: 0; }
div#search p.advanced { margin-top: 0.3em; }
div.content-navigation div.mainobject-window div.fixedsize { float: none; width: auto; }
div.fixedsize input.box, div.fixedsize textarea.box, div.fixedsize table.list { width: 95%; }
</style>
<![endif]-->
<!--[if lt IE 6.0]>
<style type="text/css">
div#maincontent div.context-block { width: 100%; } /* Avoid width bug in IE 5.5 */
div#maincontent div#maincontent-design { width: 98%; } /* Avoid width bug in IE 5.5 */
</style>
<![endif]-->
<!--[if IE 6.0]>
<style type="text/css">
div#maincontent div.box-bc { border-bottom: 1px solid #bfbfb7; /* Strange IE bug fix */ }
div#leftmenu-design { margin: 0.5em 4px 0.5em 0.5em; }
</style>
<![endif]-->
{/literal}

</head>

<body>

<div id="allcontent">

<div id="header">
<div id="header-design">

<div id="logo">
<img src={'ezpublish-logo-4-symbol.gif'|ezimage} width="256" height="40" alt="eZ Publish" border="0" />
<p>version {fetch(setup,version)}</p>
</div>

</div>
</div>

<div id="topmenu">
<div id="topmenu-design">

<div class="loginpagemenu"></div>

</div>
</div>

<div id="path">
<div id="path-design">

&nbsp;

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

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->

</body>
</html>
