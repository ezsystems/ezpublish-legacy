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

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
   <td width="20%" bgcolor="#4987bc">
   <h1><font color="white">eZ publish 3<sup>TM</sup></font></h1>
   </td>
   <td width="70%" bgcolor="#4987bc">
   {section show=$access_type}<h1><font color="white">({$access_type.name})</font></h1>{/section}
   </td>

</tr>
</table>
</td>
</tr>
<tr>

	<td>
<table width="100%"  cellspacing="0" cellpadding="4">
<tr>

{* This is the main content *}
<td width="20%" bgcolor="#ffffff">
&nbsp;&nbsp;&nbsp;<img src={"user.png"|ezimage} border="0" />
</td>
<td width="30%" bgcolor="#ffffff">
<form method="post" action="/user/login/">

<b>{"Login"|i18n('logon')}:</b><br />
<input type="text" name="Login" /><br/>
<b>{"Password"|i18n('logon')}:</b><br />
<input type="password" name="Password" /><br/>


<input type="submit" value="{'Login'|i18n('logon','Button')}" />

</form>

</td>
<td width="50%" bgcolor="#ffffff" valign="top">
<h2>{"Welcome to eZ publish administration"|i18n('logon')}</h2>
<p>{"To log in enter a valid login and password."|i18n('logon')}</p>

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


