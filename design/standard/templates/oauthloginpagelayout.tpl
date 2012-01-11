<!DOCTYPE html>
<html lang="{$site.http_equiv.Content-language|wash}">
<head>
{cache-block keys=array( $navigation_part.identifier, $ui_context, $ui_component, $access_type )}

{include uri='design:page_head.tpl'}

<link rel="stylesheet" type="text/css" href="{'stylesheets/loginpagelayout.css'|ezdesign( 'no' )}" />

</head>

<body class="loginpage">

<div id="floater"></div>
<div id="page">

{/cache-block}

{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

</div>

<div id="footer">
    
{include uri="design:page_copyright.tpl"}

</div>

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->

</body>
</html>
