{*?template charset=utf8?*}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/forum.css"|ezdesign} />

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

<MAP NAME="map">
<AREA SHAPE="RECT" COORDS="1,1,71,25" href={"content/view/full/26/"|ezurl}>
<AREA SHAPE="RECT" COORDS="73,0,156,24" href={"content/view/full/159/"|ezurl}>
<AREA SHAPE="RECT" COORDS="157,0,228,23" href={"content/view/full/62/"|ezurl}>
<AREA SHAPE="RECT" COORDS="229,0,299,24" href={"content/view/full/200/"|ezurl}>
<AREA SHAPE="RECT" COORDS="300,0,372,24" href={"content/view/full/32/"|ezurl}>
<AREA SHAPE="RECT" COORDS="374,1,448,24" href={"content/view/full/211/"|ezurl}>
<AREA SHAPE="RECT" COORDS="450,1,523,24" href={"content/view/full/82/"|ezurl}>
</MAP>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<br />

<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td><a href={"content/view/full/32/"|ezurl}><img src={"images/crossroads_logo.gif"|ezdesign} alt="Forum fora" border="0" /></a></td>
</tr>
<tr>
    <td>
    <table width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#FF9900;">
    <tr>
        <td align="center" bgcolor="#990000">
	<a href={"content/view/full/32/"|ezurl}><font color="white"><b class="small">home</b></font></a>
	</td>
        <td align="center" bgcolor="#990000">
	<a href={"content/view/full/192/"|ezurl}><font color="white"><b class="small">links</b></font></a>
	</td>
        <td align="center" bgcolor="#990000">
	<a href={"content/view/full/198/"|ezurl}><font color="white"><b class="small">about</b></font></a>
	</td>
        <td align="center" bgcolor="#990000">
	<a href={"user/login/"|ezurl}><font color="white"><b class="small">login</b></font></a>
	</td>
    </tr> 	
    </table>
    </td>    
</tr>
</table>

<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td>

    <table width="100%" border="0" cellspacing="20" cellpadding="0">
    <tr>
       <td>
       <span class="small">&gt;</span>
       {section name=Path loop=$module_result.path offset=2}
        {section show=$Path:item.url}
        <a class="small" href={$Path:item.url|ezurl}>{$Path:item.text}</a>
        {section-else}
        <span class="small">{$Path:item.text}</span>
      
        {/section}

        {delimiter}
        <span class="small">/</span>
        {/delimiter}
        {/section}
	</td>
    </tr> 	
    <tr>
        <td>
        {$module_result.content}
	</td>
    </tr>
    </table>
    <div align="center" class="small">
    <a href="http://developer.ez.no"><img src={"powered-by-ezpublish-100x35-trans-lgrey.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a><br />
    Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2002.
    </div>
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
