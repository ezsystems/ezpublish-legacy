{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />

{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}

<!-- Meta information START -->

{section name=meta loop=$site.meta}
<meta name="{$meta:key}" content="{$meta:item}" />
{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="generator" content="eZ publish" />

<!-- Meta information END -->

</head>

<body style="background: url(/design/standard/images/grid-background.gif);">

<!-- Top box START -->

<form action="/content/search/" method="get">

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="topline" width="40%">
    <img src={"ezpublish-logo.gif"|ezimage} width="210" height="60" alt="" />
    </td>
    <td class="topbox" width="20%" valign="bottom">
	<label class="topline" for="Search">Search:</label><a class="topline" href="/content/advancedsearch/"><span class="small">Advanced search</span></a><div class="labelbreak"></div>
	<input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="" />
    </td>
    <td class="topbox" width="20%" valign="bottom">
	<input class="button" name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />
    </td>
    <td class="topbox" valign="bottom" width="20%">
{*    <p class="menuitem"><a class="menuitem" href="/user/logout/">Log out</a></p> *}

    <label class="topline">Name: {content_view_gui view=text_linked content_object=$current_user.contentobject}</label><div class="labelbreak"></div>
    <p style="padding: 0;"><img src={"bullet.gif"|ezimage} width="12" height="12" alt="" align="middle" hspace="2" /><a class="topline" href="/user/password/{$current_user.contentobject_id}">Change Password</a></p>
    <p style="padding: 0;">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" align="middle" hspace="2" />
    {section show=eq($current_user.contentobject_id,$anonymous_user_id)}
    <a class="topline" href="/user/login/">Login</a>
    {section-else}
    <a class="topline" href="/user/logout/">Logout</a>
    {/section}
    </p>
   </td>
</tr>
</table>

</form>

<!-- Top box END -->

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="pathline" colspan="2">

<!-- Main path START -->

<table class="path" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" align="middle" hspace="2" /><br />
    </td>
    <td width="99%">
    <p class="path">
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a class="path" href="{$Path:item.url}">{$Path:item.text}</a>
        {section-else}
        {$Path:item.text}
        {/section}

        {delimiter}
        <span class="slash">/</span>
        {/delimiter}
    {/section}
    &nbsp;</p>
    </td>
</tr>
</table>

<!-- Main path END -->

    </td>
</tr>
<tr>
    <td width="120" valign="top" style="padding-right: 4px;">

<!-- Left menu START -->

{include uri="design:left_menu.tpl"}

<!-- Left menu END -->

    </td>
    <td class="mainarea" width="99%" valign="top">

<!-- Main area START -->

{$content}

<!-- Main area END -->

    </td>
</tr>
</table>

<div align="center" style="padding-top: 0.5em;">
<p class="small"><a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a></p>
</div>

</body>
</html>
