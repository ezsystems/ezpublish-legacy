{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/manual.css"|ezdesign} />

{include uri="design:page_head.tpl"}

</head>

<body>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="headlogo" width="560">
    <img src={"ezpublish_logo_blue.gif"|ezimage} alt="" /></td>
    <td class="headlink" width="66">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="menuheadselectedgfx">
    <img src={"light-left-corner.gif"|ezimage} alt=""/></td>
    <td class="menuheadselectedtopline">
    <img src={"1x1.gif"|ezimage} alt="" width="60" height="1" /></td>
    <td class="menuheadselectedgfx">
    <img src={"light-right-corner.gif"|ezimage} alt=""/></td>
</tr>
<tr>
    <td class="menuheadselectedleftline">
    <img src={"1x1.gif"|ezimage} alt="" width="1" height="19" /></td>
    <td class="menuheadselected">
    <p class="menuheadselected">
    <a class="menuheadlink" href={"/manual/"|ezurl}>Manual</a>
    </p>
    </td>
    <td class="menuheadselectedrightline">
    <img src={"1x1.gif"|ezimage} alt="" width="1" height="19" /></td>
</tr>
</table>

</td>

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>
    <td class="headlink" width="66">

<table width="66" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="menuheadgraygfx" width="3">
    <img src={"dark-left-corner.gif"|ezimage} alt=""/></td>
    <td class="menuheadgraytopline" width="60">
    <img src={"1x1.gif"|ezimage} alt="" width="60" height="1" /></td>
    <td class="menuheadgraygfx" width="3">
    <img src={"dark-right-corner.gif"|ezimage} alt=""/></td>
</tr>
<tr>
    <td class="menuheadgrayleftline" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="1" height="15" /></td>
    <td class="menuheadgray">
    <p class="menuheadgray">
    <a class="menuheadlink" href={"/sdk/"|ezurl}>SDK</a>
    </p>
    </td>
    <td class="menuheadgrayrightline" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="1" height="15" /></td>
</tr>
</table>

    </td>
   <td class="headlogo" width="50%">
   &nbsp;</td>
</tr>
    <td colspan="11" class="menuheadtoolbar">
    &nbsp;
    </td>
</tr>
<tr>
    <td class="pathline" colspan="11">
    {include uri="design:page_toppath.tpl"}
    </td>
</tr>
</table>

{* Top box END *}


{* Top box START *}
<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
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
    <td width="160" bgcolor="#ffffff" valign="top">
    {* Menu *}


<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">About eZ publish</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/about/about"|ezurl}>About eZ publish 3</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/about/definitions"|ezurl}>eZ publish 3 definitions</a></p>
    </td>
</tr>
</table>


<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Installation & configuration</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/installer"|ezurl}>Install using installers</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/setup_guide"|ezurl}>Install using the setup guide</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/install_manually"|ezurl}>Install manually</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/siteaccess"|ezurl}>Site access</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/multisite"|ezurl}>Multi site configuration</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/uninstall"|ezurl}>Uninstall</a></p>
    </td>
</tr>
</table>



<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Security</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/security/securing"|ezurl}>Securing the site</a></p>
    </td>
</tr>

</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">Setup & design</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/setup/pagesetup"|ezurl}>Page setup</a></p>
    </td>
</tr>

</table>

<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">User Manual</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/user/everyday"|ezurl}>Everyday functions</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/user/permissions"|ezurl}>Permissions</a></p>
    </td>
</tr>

<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/user/e-commerce"|ezurl}>e-commerce functions and settings</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/user/workflows"|ezurl}>Workflows</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/user/informationcollectors"|ezurl}>Information collector</a></p>
    </td>
</tr>
</table>


<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">SDK & Technical references</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="http://ez.no/sdk"}>eZ publish SDK</a></p>
    </td>
</tr>
</table>

    {* end menu *}
    </td>
    <td width="450" bgcolor="#ffffff" valign="top">
    {* This is the main content *}

    {$module_result.content}
    </td>
    <td width="1%" bgcolor="#ffffff" valign="top">
    </td>
</tr>
</table>

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

 </body>
</html>
