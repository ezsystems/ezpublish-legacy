{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

{include uri="design:page_head.tpl"}

</head>

<body>

<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #4272b4; background-image:url('{"bgimage.gif"|ezimage(no)}'); background-position: right top; background-repeat: no-repeat;">
<tr>
    <td style="padding: 4px" colspan="13">
    <table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="5" style="background-image:url('{"tbox-top-left.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
        <td style="border-top: solid 1px #789dce;" width="99%">
        <img src={"1x1.gif"|ezimage} alt="" width="1" height="1" /></td>
        <td width="5" style="background-image:url('{"tbox-top-right.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
    </tr>
    <tr>
        <td style="border-left: solid 1px #789dce;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
        <td>
	    <img src={"logo.gif"|ezimage} alt="" />
        </td>
        <td style="border-right: solid 1px #789dce;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
    </tr>
    <tr>
        <td style="background-image:url('{"tbox-bottom-left.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
        <td style="border-bottom: solid 1px #789dce;">
        <img src={"1x1.gif"|ezimage} alt="" width="1" height="1" /></td>
        <td style="background-image:url('{"tbox-bottom-right.gif"|ezimage(no)}'); background-repeat: no-repeat;">
        <img src={"1x1.gif"|ezimage} alt="" width="5" height="6" /></td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td>
       <img src={"1x1.gif"|ezimage} alt="" width="5" height="26" /></td>
</tr>
<tr>
    <td colspan="13">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="5" /></td>
</tr>
<tr>
    <td colspan="13" style="background-image:url('{"bgtilelight.gif"|ezimage(no)}'); background-repeat: repeat;">
    <img src={"1x1.gif"|ezimage} alt="" width="1" height="8" /></td>
<tr>
</table>


<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
 <td>
  

<table width="100%" border="0" cellspacing="0" cellpadding="0">

{* Header *}
<tr>
	<td>

<table width="750"  cellspacing="0" cellpadding="4">
{section show=$warning_list}
<tr>
  <td colspan="3">
   {include uri="design:page_warning.tpl"}
  </td>
</tr>
{/section}
<tr>
    {* This is the main content *}
    <td rowspan="2" width="120" valign="top" style="padding-right: 0px; padding-left: 0px; padding-top: 0px; background-image:url('{"bgtilelight.gif"|ezimage(no)}'); background-repeat: repeat;">
    <div style="padding-right: 4px; padding-left: 15px; padding-top: 4px; padding-bottom: 4px; margin-bottom:1px; background-image:url('{"bgtiledark.gif"|ezimage(no)}'); background-repeat: repeat;">
    <a class="leftmenuitem" href={"/class/grouplist/"|ezurl}>Login</a>
    </div>

    </td>
    <td rowspan="2" valign="top"  style="background-color: #ffffff; background-image:url('{"corner.gif"|ezimage(no)}'); background-repeat: no-repeat;">
    <img src={"1x1.gif"|ezimage} alt="" width="23" height="1" /></td>
    <td width="30%" bgcolor="#ffffff">
    {$module_result.content}
    </td>
    <td width="50%" bgcolor="#ffffff" valign="top">
    <h2>{"Welcome to eZ publish administration"|i18n("design/standard/layout")}</h2>
    <p>{"To log in enter a valid login and password."|i18n("design/standard/layout")}</p>
    </td>
</tr>
</table>

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

<!--DEBUG_REPORT-->

</body>
</html>