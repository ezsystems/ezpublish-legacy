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

</body>
</html>
