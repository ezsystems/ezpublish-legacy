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

<body>

<!-- Top box START -->

<img src={"images/whiteboxlogo.png"|ezdesign} alt="White box - contemporary art gallery" />

<!-- Top box END -->

<table class="layout" width="700" cellpadding="1" cellspacing="0" border="0">
<tr>
    <td bgcolor="#cccccc">
    &nbsp;&nbsp;:: about<br /> 
    &nbsp;&nbsp;:: about<br /> 
    &nbsp;&nbsp;:: about<br /> 
    </td>
    <td bgcolor="#e8e8e8">
    </td>
</tr>

<tr>
    <td class="pathline" colspan="3">

    <p class="path">
    &nbsp;&nbsp;
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a href="{$Path:item.url}">{$Path:item.text}</a>
        {section-else}
        {$Path:item.text}
        {/section}

        {delimiter}
	/
        {/delimiter}
    {/section}
    </p>

    </td>
</tr>
<tr>
    <td class="mainarea" width="99%" valign="top">

<!-- Main area START -->

{$content}

<!-- Main area END -->

    </td>
    <td bgcolor="#cccccc" valign="top" width="150">
    Gallery #1

    <form action="/content/search/" method="get">
    <label class="topline" for="Search">Search:</label><a class="topline" href="/content/advancedsearch/"><span class="small">Advanced search</span></a><div class="labelbreak"></div>
    <input type="text" size="10" name="SearchText" id="Search" value="" />
    <input class="button" name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />
    </form>

    </td>
</tr>
<tr>
    <td bgcolor="#e2e2e2" colspan="2">
    <div align="left" style="padding: 0.5em;">
    <p class="small"><a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a></p>
    </div>
    </td>
</tr>
</table>


</body>
</html>
