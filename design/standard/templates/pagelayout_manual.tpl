{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/manual.css"|ezdesign} />

{include uri="design:page_head.tpl"}

</head>

<body style="background: url(/design/standard/images/grid-background.gif);">

{* Top box START *}
<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="topline" width="40%" colspan="2">
    <img src={"ezpublish_manuals.gif"|ezimage} alt="{'eZ publish manuals'|i18n('design/standard/layout')}" />
   </td>
</tr>
{* Top box END *}
<tr>
    <td class="pathline" colspan="2">

{* Main path START *}

{include uri="design:page_toppath.tpl"}

{* Main path END *}

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
    <td width="160" bgcolor="#ffffff" valign="top">
    {* Menu *}


<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{"About eZ publish"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/about/about"|ezurl}>{"About eZ publish 3"|i18n("design/standard/layout")}</a></p>
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
    <p class="menuhead">{"Installation & configuration"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/installer"|ezurl}>{"Install using installers"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/setup_guide"|ezurl}>{"Install using the setup guide"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/install_manually"|ezurl}>{"Install manually"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/siteaccess"|ezurl}>{"Site access"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/multisite"|ezurl}>{"Multi site configuration"|i18n("design/standard/layout")}</a></p>
    </td>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href={"/manual/install/uninstall"|ezurl}>{"Uninstall"|i18n("design/standard/layout")}</a></p>
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
    <p class="menuitem"><a class="menuitem" href={"/manual/user/e-commerce"|ezurl}>E-commerce functions and settings</a></p>
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
    <p class="menuhead">{"SDK & Technical references"|i18n("design/standard/layout")}</p>
    </th>
</tr>
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class="menuitem"><a class="menuitem" href="http://sdk.ez.no"}>{"eZ publish SDK"|i18n("design/standard/layout")}</a></p>
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
