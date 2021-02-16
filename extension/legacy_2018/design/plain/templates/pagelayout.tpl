{*?template charset=latin1?*}
<!DOCTYPE html>
<html lang="{$site.http_equiv.Content-language|wash}">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

</head>

<body>

{* Top box START *}

{include uri="design:page_topbox.tpl"}

{* Top box END *}

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="pathline" colspan="2">

{* Main path START *}

{cache-block keys=array('path',$uri_string)}
{include uri="design:page_toppath.tpl"}
{/cache-block}

{* Main path END *}

    </td>
</tr>
<tr>
    <td width="120" valign="top" style="padding-right: 4px;">

{* Left menu START *}
{cache-block keys=array('left_menu',$uri_string)}
{include uri="design:left_menu.tpl"}
{/cache-block}

{* Left menu END *}

    </td>
    <td class="mainarea" width="99%" valign="top">

{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

<!--DEBUG_REPORT-->

</body>
</html>
