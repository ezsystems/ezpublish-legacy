{*?template charset=utf8?*}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />
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


<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px; border-color: black;" bgcolor="#cccccc">
<tr>
   <th>
   Quick links
   </th>
</tr>
<tr>
    <td >
    <a href="/content/view/full/31/">Crossroads Forum</a>
    </td>
    <td >
    <a href="/content/view/news/26">News 24</a>
    </td>
    <td >
    <a href="/content/view/thumbnail/18/">Whitebox art gallery</a>
    </td>
    <td >
    <a href="/content/view/full/65/">the Book corner</a>
    </td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="10">
<tr>
<td>

<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td><img src={"images/crossroads_logo.gif"|ezdesign} alt="Crossroads forum" /></td>    
</tr>
<tr>
    <td style="border-style: solid; border-width: 1px; border-color: black;" bgcolor="#cccccc">
    <table width="100%" border="0">
    <tr>
        <td align="center">
	<a href={"content/view/full/31/"|ezurl}><b>HOME</b></a>
	</td>
        <td align="center">
	<b>FAQ</b>
	</td>
        <td align="center">
	<b>LINKS</b>
	</td>
    </tr> 	
    </table>
    </td>    
</tr>
</table>

<br />

<table width="700" border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px; border-color: black;" bgcolor="#E6E6E6">
<tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td>
        {$module_result.content}
	</td>
    </tr>
    </table>
    </td>    
</tr>
<tr>
    <td>
    <img src={"images/crossroads_bottom.gif"|ezdesign} alt="" /></td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
