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

{* Top box START *}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="headlogo" width="360">
    {* Admin logo area *}
    <img src={"logo.gif"|ezimage} alt="" />&nbsp;&nbsp;<img src={"admin.gif"|ezimage} alt="" /></td>
    <td class="headlink" width="66">
    {* Content menu *}
    {section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}</td>
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}</td>
    {/section}

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>
    <td class="headlink" width="66">
    {* Shop menu *}
    {section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Shop'|i18n('design/admin/layout') menu_url="/shop/orderlist/"}</td>
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Shop'|i18n('design/admin/layout') menu_url="/shop/orderlist/"}</td>
    {/section}
    
    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>

    <td class="headlink" width="66">

    {* Users menu *}
    {section show=eq($navigation_part.identifier,'ezusernavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Users'|i18n('design/admin/layout') menu_url="/content/view/full/5/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Users'|i18n('design/admin/layout') menu_url="/content/view/full/5/"}
    {/section}
    
    </td>

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>

    <td class="headlink" width="66">

    {* Set up menu *}
    {section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Set up'|i18n('design/admin/layout') menu_url="/class/grouplist/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Set up'|i18n('design/admin/layout') menu_url="/class/grouplist/"}
    {/section}

    </td>

    <td class="menuheadspacer" width="3">
    <img src={"1x1.gif"|ezimage} alt="" width="3" height="1" /></td>

    <td class="headlink" width="66">

    {* Personal *}
    {section show=eq($navigation_part.identifier,'ezmynavigationpart')}
    {include uri="design:page_menuheadselected.tpl" menu_text='Personal'|i18n('design/admin/layout') menu_url="/content/draft/"}
    {section-else}
    {include uri="design:page_menuheadgray.tpl" menu_text='Personal'|i18n('design/admin/layout') menu_url="/content/draft/"}
    {/section}

    </td>
   <td class="headlogo" width="50%">
   &nbsp;</td>
</tr>
<tr>
    <td colspan="11" class="menuheadtoolbar">

<form action={"/content/search/"|ezurl} method="get" style="margin-top: 0px; margin-bottom: 0px; padding: 0px;">

<table width="750" cellpadding="0" cellspacing="2" border="0">
<tr>
    <td align="left" valign="middle" width="50">
	<input class="searchbox" type="text" size="20" name="SearchText" id="Search" value="" />
    </td>
    <td align="left" valign="middle">
	<input class="searchbutton" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />

    </td>
    <td valign="middle">
      <p class="menuitem">
      {section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
        {'Site:'|i18n('design/standard/layout')} {ezini('SiteSettings','SiteURL')}
        {'Version:'|i18n('design/standard/layout')} {$site.version}
      {/section}
      </p>
    </td>
    <td align="right" valign="middle">
      <p class="menuitem">
      {section show=eq($current_user.contentobject_id,$anonymous_user_id)}
      <a class="menuheadlink" href={"/user/login/"|ezurl}>{'Login'|i18n('design/standard/layout')}</a>
      {section-else}
      <a class="menuheadlink" href={"/user/logout/"|ezurl}>{'Logout'|i18n('design/standard/layout')}</a> ({$current_user.contentobject.name|wash})
      {/section}
      </p>
    </td>
</tr>
</table>

</form>
    </td>
</tr>
<tr>
    <td class="pathline" colspan="11">
    {include uri="design:page_toppath.tpl"}
    </td>
</tr>
</table>

{* Top box END *}

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="120" valign="top" style="padding-right: 4px; padding-left: 15px; padding-top: 15px;">

{* Left menu START *}

{section show=eq($navigation_part.identifier,'ezcontentnavigationpart')}
{include uri="design:parts/content/menu.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}
{/section}

{section show=eq($navigation_part.identifier,'ezshopnavigationpart')}
{include uri="design:parts/shop/menu.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}
{/section}

{section show=eq($navigation_part.identifier,'ezusernavigationpart')}
{include uri="design:parts/user/menu.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}
{/section}

{section show=eq($navigation_part.identifier,'ezsetupnavigationpart')}
{include uri="design:parts/setup/menu.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}
{/section}

{section show=eq($navigation_part.identifier,'ezmynavigationpart')}
{include uri="design:parts/my/menu.tpl" menu_text='Content'|i18n('design/admin/layout') menu_url="/content/view/full/2/"}
{/section}

{* {include uri="design:left_menu.tpl"} *}

{* Left menu END *}

    </td>
    <td class="mainarea" width="99%" valign="top">

{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

<!--DEBUG_REPORT-->

</body>
</html>
