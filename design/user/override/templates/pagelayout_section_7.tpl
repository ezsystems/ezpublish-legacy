{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/intranet.css"|ezdesign} />
{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}
{section name=HTTP loop=$site.http_equiv}
<meta http-equiv="{$HTTP:key}" content="{$HTTP:item}" />

{/section}

<!-- Meta information START -->

{section name=meta loop=$site.meta}
<meta name="{$meta:key}" content="{$meta:item}" />

{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="generator" content="eZ publish" />

<!-- Meta information END -->

</head>

<body>

<form action={"/content/search/"|ezurl} method="get">

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="tight" colspan="2"><img class="toplogo" src={"intranet-top-logo.gif"|ezimage} width="142" height="32" alt="Intranet" /></td>
</tr>
<tr>
    <td class="topline" width="1%">
    <input type="hidden" name="SectionID" value="7">
    <input class="searchbox" type="text" size="10" name="SearchText" id="Search" value="" />
    </td>
    <td class="topline" width="1%">
    <input name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />
    </td>
    <td class="topline" width="98%" align="right"><img src={"intranet-top-background-image.gif"|ezimage} width="160" height="48" alt="" /></td>
</tr>
</table>
</form>

<div class="path">
<p class="path">&gt; <a href="/">Top</a> / <a href="/">submenu</a> / <a href="/">subsubmenu</a> / current page</p>
</div>

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="leftmenu">

<!-- Menubox START -->

    <div class="menubox">
    <table class="menubox" width="120" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <th>menutitle</th>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 1</a></td>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 2</a></td>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 3</a></td>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 4</a></td>
    </tr>
    </table>
    </div>
    
<!-- Menubox END -->

    </td>

    <td class="divider"><img src={"images/1x1.gif"|ezimage} width="16" height="1" alt="" border="0" /></td>
    <td class="maincontent">

<!-- Main area START -->

{$module_result.content}

<!-- Main area END -->

    </td>
    <td class="divider"><img {"images/1x1.gif"|ezimage} width="16" height="1" alt="" border="0" /></td>
    <td class="rightmenu">

<!-- Menubox START -->

    <div class="menubox">
    <table class="menubox" width="120" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <th>menutitle</th>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 1</a></td>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 2</a></td>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 3</a></td>
    </tr>
    <tr>
        <td class="menuchoice" colspan="2"><a href="/">Menu choice 4</a></td>
    </tr>
    </table>
    </div>
    
<!-- Menubox END -->

    </td>
</tr>    
</table>

</body>
</html>
