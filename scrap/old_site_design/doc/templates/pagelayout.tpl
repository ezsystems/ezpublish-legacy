{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/doc.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />


</head>

<body>


{let root_folders=fetch('content', 'list', hash(
					parent_node_id, 2, 
					sort_by, array(array(priority,true())), 
					class_filter_type,include,
					class_filter_array,array(1)
					)
)
}

{* Top box START *}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="headlogo" width="100%" colspan="4">
    {* Admin logo area *}
    <img src={"logo.gif"|ezimage} alt="" />&nbsp;&nbsp;<img src={"admin.gif"|ezimage} alt="" /></td>
</tr>
<tr>
    <td colspan="4" class="menuheadtoolbar">
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
	<a class="menuheadlink" href={"/content/view/sitemap/2/"|ezurl}>sitemap</a>
	</p>
	</td>
    </tr>
    </table>
    </form>
    </td>
</tr>
<tr>
    <td class="pathline" colspan="4">
    {include uri="design:page_toppath.tpl"}
    </td>
</tr>
</table>

{* Top box END *}

{* Left menu START *}

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="180" valign="top" style="padding-right: 4px; padding-left: 15px; padding-top: 15px;">
   
    <table width="180" cellpadding="1" cellspacing="0" border="0">
    
    {section name=Child loop=$root_folders}
    <tr>
        <td class="bullet" width="1">
	<img src={"arrow.gif"|ezimage} width="8" height="11" alt="" /><br />
	</td>
	<td class="menu" width="99%">
	<p class="menuitem"><a class="menuitem" href={$Child:item.url_alias|ezurl}>{$Child:item.name}</a></p>
	</td>
    </tr>
    {/section}
    </table>   

    </td>

{* Left menu END *}
    <td class="mainarea" width="99%" valign="top">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td colspan="2" valign="top">
	{$module_result.content}
	</td>
    </tr>
    </table>   

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

</body>
</html>
