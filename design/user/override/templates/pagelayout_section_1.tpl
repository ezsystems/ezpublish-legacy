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

<h2>Unfinished design</h2>
<p>eZ publish will default to this design when loosing section information
this will not happen in the following beta releases. So, don't panic.
</p>

{$module_result.content}

</body>
</html>

