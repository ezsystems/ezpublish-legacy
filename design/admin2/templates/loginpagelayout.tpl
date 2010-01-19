<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">
{def $admin_theme = ezpreference( 'admin_theme' )}
<head>
{cache-block keys=array( $navigation_part.identifier, $module_result.navigation_part, $ui_context, $ui_component, $admin_theme )}{* Pr tab cache *}

{include uri='design:page_head.tpl'}


{include uri='design:page_head_style.tpl'}
{include uri='design:page_head_script.tpl'}

</head>

<body class="loginpage">

<div id="page" class="{$navigation_part.identifier} section_id_{first_set( $module_result.section_id, 0 )}">
<div id="header">
<div id="header-design" class="float-break">

</div>
</div>

<hr class="hide" />

<div id="subheader">
<div id="subheader-design">
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

{/cache-block}
<hr class="hide" />

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">

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

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->
</div><!-- div id="page" -->

</body>
</html>
