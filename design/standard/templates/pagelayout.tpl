{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title}{section show=$site.page_title} - {$site.page_title}{/section}</title>
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
    <td class="topline" width="60%">
    <img src={"ezpublish-logo.gif"|ezimage} width="210" height="60" alt="" />
    </td>
    <td class="topbox" width="1%" valign="bottom">
	<label class="topline" for="Search">Search:</label><a class="topline" href="/content/advancedsearch/"><span class="small">Advanced search</span></a><div class="labelbreak"></div>
	<input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="" />
    </td>
    <td class="topbox" width="34%" valign="bottom">
	<input class="button" name="SearchButton" type="submit" value="S" />
    </td>
    <td class="topbox" valign="bottom" width="1%">
	<label class="topline" for="ID">ID:</label><br />
	<input type="text" size="4" name="ObjectID" id="ID" value="" />
    </td>
    <td class="topbox" valign="bottom" width="1%">
	<label class="topline" for="Type">Class:</label><br />
	<select size="1" name="ObjectType" id="Type">
		<option>Folder</option>
		<option>Article</option>
		<option>Product</option>
		<option>Link</option>
		<option>Image</option>
	</select>
    </td>
    <td class="topbox" valign="bottom" width="1%">
	<input class="button" type="submit" value="N" />
    </td>
    <td class="topbox" valign="bottom" width="1%">
	<input class="button" type="submit" value="E" />
    </td>
    <td class="topbox" valign="bottom" width="1%">
	<input class="button" type="submit" value="P" />
    </td>
</tr>
</table>

</form>

<!-- Top box END -->

<table class="layout" width="100%" cellpadding="1" cellspacing="0" border="0">
<tr>
    <td class="pathline" colspan="3">

<!-- Main path START -->

<table class="path" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" align="middle" hspace="2" /><br />
    </td>
    <td width="99%">
    <p class="path"><a class="path" href="/">Browse</a> <span class="slash">/</span> <a class="path" href="/">Toplevel</a> <span class="slash">/</span> <a class="path" href="/">Subcategory</a> <span class="slash">/</span> <a class="path" href="/">Subsubcategory</a></p>
    </td>
</tr>
</table>

<!-- Main path END -->

    </td>
</tr>
<tr>
    <td width="120" valign="top" style="padding-right: 4px;">

<!-- Left menu START -->

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Content</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/content/view/full/2/">Contents</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/content/sitemap/2/">Sitemap</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem"  href="/search/stats/">Search stats</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Set up</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/class/grouplist/">Classes</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/section/list/">Sections</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/workflow/grouplist/">Workflows</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Shop</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/shop/orderlist/">Order lists</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/shop/wishlist/">Wish list</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/shop/cart/">Shopping cart</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Users</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/content/sitemap/5/">Users</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/role/list/">Roles</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/notification/list/">Notification rule list</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/task/view/">Tasks</a></p>
    </td>
</tr>
</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheadlight" colspan="2">
    <p class="menuhead">Content</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/">New article</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/">New link</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/">New product blah blah</a></p>
    </td>
</tr>
</table>

<!-- Left menu END -->

    </td>
    <td class="mainarea" width="99%" valign="top">

<!-- Main area START -->

{$content}

<!-- Main area END -->

    </td>
    <td width="120" valign="top" style="padding-left: 4px;">

<!-- Right menu START -->

<table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Current user</p>
    </th>
</tr>
<tr>
    <td class="menu" colspan="2">
    <p class="menufieldlabel">Name:</p>
    <p class="menufield">
    {$current_user.login}
    </p>
    <p class="menufieldlabel">Groups:</p>
    <p class="menufield">
    {section name=groups loop=$current_user.groups}
    {delimiter},{/delimiter}
    {$groups:item}
    {/section}
    </p>
    <p class="menufieldlabel">Roles:</p>
    <p class="menufield">
        {section name=Roles loop=$current_user.roles}
        <a href="/role/view/{$Roles:item.id}/">{$Roles:item.name}</a><br/>
        {/section}
    </p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="/user/logout/">Log out</a></p>
    </td>
</tr>
</table>

<table>
{section name=Execution loop=$execution_entries}
<tr><td>{$Execution:item.module}/{$Execution:item.function}</td></tr>
{/section}
</table>

<table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheadlight">
    <p class="menuhead">Connected items</p>
    </th>
</tr>
<tr>
    <td class="menu">
    <p class="menutext">
    Tekst, tekst, tekst, tekst, tekst, tekst, tekst, tekst, tekst!
    </p>
    </td>
</tr>
</table>

<!-- Right menu END -->

    </td>
</tr>
</table>

<div align="center" style="padding-top: 0.5em;">
<p class="small"><a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a></p>
</div>

</body>
</html>

