{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>{$site.title}{section show=$site.page_title} - {$site.page_title}{/section}</title>

{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<META HTTP-EQUIV=Refresh CONTENT="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}

<link rel="stylesheet" type="text/css" href={"stylesheets/style.css"|ezdesign} />

<!-- set the content meta information -->

{section name=meta loop=$site.meta}
<meta name="{$meta:key}" content="{$meta:item}" />
{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE">

<meta name="generator" content="eZ publish" />

</head>

<body bgcolor="#4987bc" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

{* Header *}
<tr><td>
<form action="/search/search/" method="get">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
   <td width="25%" bgcolor="#4987bc">
   <h1><font  color="white">eZ publish<sup>TM</sup></font></h1>
   </td>
   <td width="50%" bgcolor="#4987bc">
   {section show=$access_type}<h1><font color="white">({$access_type.name})</font></h1>{/section}
   </td>

   <td width="25%" bgcolor="#04396c">
<nobr>
<input type="text" name="SearchText" value=""/><input type="image" src={"search.png"|ezimage} name="SearchButton" value="Search"/>
</nobr>
<br />
<a href="/search/advancedsearch/"><font color="white">advanced search</font></a>
   </td>
</tr>
</table>
</form>
</td>
</tr>
<tr>

	<td>
<table width="100%"  cellspacing="0" cellpadding="4">
<tr>
{* Left side *}
<td valign="top" width="10%" class="leftmenu">

<table>
<tr><td><a href="/class/grouplist/"><nobr><img src={"configure.png"|ezimage} border="0" alt=""/>Classes</nobr></a></td></tr>
<tr><td><a href="/content/view/full/1/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Contents</nobr></a></td></tr>
<tr><td><a href="/content/sitemap/1/"><nobr><img src={"view_tree.png"|ezimage} border="0" alt=""/> Sitemap</nobr></a></td></tr>
<tr><td><a href="/content/sitemap/4/"><nobr><img src={"view_tree.png"|ezimage} border="0" alt=""/> Users</nobr></a></td></tr>
<tr><td><a href="/workflow/list/"><nobr><img src={"uml.png"|ezimage} border="0" alt=""/> Workflows</nobr></a></td></tr>
<tr><td><a href="/shop/orderlist/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Order list</nobr></a></td></tr>
<tr><td><a href="/shop/wishlist/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Wish list</nobr></a></td></tr>
<tr><td><a href="/shop/cart/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Shopping Cart</nobr></a></td></tr>
<tr><td><a href="/search/stats/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Search stats</nobr></a></td></tr>
<tr><td><a href="/role/list/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Roles </nobr></a></td></tr>
<tr><td><a href="/section/list/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Sections </nobr></a></td></tr>
<tr><td><a href="/task/list/"><nobr><img src={"folder_blue_open.png"|ezimage} border="0" alt=""/> Tasks </nobr></a></td></tr>
<tr><td><b>Current user:</b>{$current_user.login}</td></tr>
<tr><td><b>Member of groups</b> 
{section name=groups loop=$current_user.groups}
{delimiter},{/delimiter}
{$groups:item}
{/section}
</td></tr>
<tr><td><b>Member of roles:</b> <br/>
{section name=Roles loop=$current_user.roles}
<a href="/role/view/{$Roles:item.id}/">{$Roles:item.name}</a><br/>
{/section}
</td></tr>
<tr><td><a class="path" href="/user/logout/"><nobr><img src={"exit.png"|ezimage} border="0" alt=""/> log out</a></nobr></td></tr>
</table>

<br />
<br />
<br />

<table border="0">
<tr><td><a href="http://developer.ez.no"><img src={"powered-by-ezpublish-100x35-trans-lgrey.gif"|ezimage} border="0" alt=""/></a> </td></tr>
</table>

<table>
{section name=Execution loop=$execution_entries}
<tr><td>{$Execution:item.module}/{$Execution:item.function}</td></tr>
{/section}
</table>

</td>

{* This is the main content *}
<td width="90%" class="contents" valign="top">
{$content}
</td>

</tr>
</table>

</td>
</tr>

{* Bottom *}
<tr><td></td></tr>

</table>

<br />
<font color="white">
<center>
<a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a>
</center>
</font>
<br />

</body>
</html>