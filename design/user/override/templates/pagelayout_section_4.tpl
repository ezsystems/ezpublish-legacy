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

<img src={"toppmeny.gif"|ezimage} alt="" border="" USEMAP="#map" />

<map name="map">
<area SHAPE="RECT" COORDS="2,1,103,27" href={"content/view/full/159/"|ezurl} />
<AREA SHAPE="RECT" COORDS="104,0,175,24" href={"content/view/full/32/"|ezurl} />
<AREA SHAPE="RECT" COORDS="177,2,245,23" href={"content/view/full/26/"|ezurl} />
<AREA SHAPE="RECT" COORDS="248,3,317,24" href={"content/view/full/82/"|ezurl} />
<AREA SHAPE="RECT" COORDS="320,3,392,23" href={"content/view/full/62/"|ezurl} />
</map>

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
	<a href={"content/view/full/32/"|ezurl}><b>HOME</b></a>
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

<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td>
    <table width="100%" border="0" cellspacing="20" cellpadding="0">
    <tr>
        <td>
        {$module_result.content}
	</td>
    </tr>
    </table>

    <center><a href="http://developer.ez.no"><img src={"powered-by-ezpublish-100x35-trans-lgrey.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a></center>

    </td>    
</tr>
<tr>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
