{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

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

<body style="background: url(/design/standard/images/grid-background.gif);">

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="topline" width="40%" colspan="2">
    <img src={"ezpublish-logo.gif"|ezimage} width="210" height="60" alt="" />
   </td>
</tr>
<tr>
    <td class="pathline" colspan="2">
<table class="path" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" align="middle" hspace="2" /><br />
    </td>
    <td width="99%">
    <p class="path">
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a class="path" href={$Path:item.url|ezurl}>{$Path:item.text}</a>
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
   </td>
</tr>
<tr>
    <td colspan="2">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

{* Header *}
<tr>
	<td>
<table width="100%"  cellspacing="0" cellpadding="4">
{section show=$warning_list}
<tr>
  <td colspan="3">
    <table width="100%" cellspacing="0" cellpadding="0">
  {section name=Warning loop=$warning_list}
    <tr>
      <td>
        <div class="error">
        <h3 class="error">{$Warning:item.error.type} ({$Warning:item.error.number})</h3>
        <ul class="error">
          <li>{$Warning:item.text}</li>
        </ul>
        </div>
      </td>
    </tr>
  {/section}
    </table>
  </td>
</tr>
{/section}
<tr>
    {* This is the main content *}
    <td width="20%" bgcolor="#ffffff" valign="top">
    </td>
    <td width="30%" bgcolor="#ffffff">
    {$module_result.content}
    </td>
    <td width="50%" bgcolor="#ffffff" valign="top">
    <h2>{"Welcome to eZ publish administration"|i18n('login')}</h2>
    <p>{"To log in enter a valid login and password."|i18n('login')}</p>
    </td>
</tr>
</table>

    </td>
</tr>
</table>

<div align="center" style="padding-top: 0.5em;">
<p class="small"><a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a></p>
</div>

<!--DEBUG_REPORT-->

</body>
</html>


