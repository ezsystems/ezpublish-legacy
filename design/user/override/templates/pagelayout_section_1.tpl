{*?template charset=utf8?*}
{let gallery_limit=8
     gallery_pre_items=2
     gallery_post_items=2}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/mycompany.css"|ezdesign} />

<!-- Javascript START -->

<script language="JavaScript">
<!--
{literal}
function OpenWindow ( URL, WinName, Features ) {
	popup = window.open ( URL, WinName, Features );
	if ( popup.opener == null ) {
		remote.opener = window;
	}
	popup.focus();
}
{/literal}

// -->
</script>

<!-- Javascript END -->

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

<img src={"toppmeny.gif"|ezimage} alt="" border="0" usemap="#map" />
<br />


<MAP NAME="map">
<AREA SHAPE="RECT" COORDS="1,1,71,25" href={"content/view/full/26/"|ezurl}>
<AREA SHAPE="RECT" COORDS="73,0,156,24" href={"content/view/full/159/"|ezurl}>
<AREA SHAPE="RECT" COORDS="157,0,228,23" href={"content/view/full/62/"|ezurl}>
<AREA SHAPE="RECT" COORDS="229,0,299,24" href={"content/view/full/200/"|ezurl}>
<AREA SHAPE="RECT" COORDS="300,0,372,24" href={"content/view/full/32/"|ezurl}>
<AREA SHAPE="RECT" COORDS="374,1,448,24" href={"content/view/full/211/"|ezurl}>
<AREA SHAPE="RECT" COORDS="450,1,523,24" href={"content/view/full/82/"|ezurl}>
</MAP>


{let folder_list=fetch(content,list,hash(parent_node_id,158,sort_by,array(array(priority))))
     news_list=fetch(content,list,hash(parent_node_id,159,limit,5,sort_by,array(published,false()),class_filter_type,include,class_filter_array,array(2)))}

<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td colspan="2">
    <a href={"content/view/full/159/"|ezurl}><img src={"mycompanylogo.jpg"|ezimage} alt="My company - business" border="0" /></a>
    </td>
</tr>
<tr>
    <td colspan="2" bgcolor="#e4eaf3">
    <form action={"/content/search/"|ezurl} method="get" style="margin-top: 0px; margin-bottom: 0px; padding: 0px;">

    <table width="700" border="0" cellspacing="0" cellpadding="0">
    <tr>
        {section name=Folder loop=$folder_list}
        <td align="left">
        &nbsp;<a class="small" href={concat("/content/view/full/",$Folder:item.node_id,"/")|ezurl}>{$Folder:item.name}</a>  <font size="2">&nbsp;</font>
        </td>
        {/section}
        <td align="right" width="30">
        <input type="hidden" name="SectionID" value="6">
        <input  type="text" size="10" name="SearchText" id="Search" value="" style="font-family: verdana; width: 80px; font-size: 9px; margin: 0px; background: #ffffff;"/>&nbsp;
	</td>
	<td align="right" width="30">
        <input name="SearchButton" type="image" src={"search.gif"|ezimage} value="{"Search"|i18n('pagelayout')}" style="font-size: 7px; margin: 0px; padding: 0px;" />
        </td>
    </tr>
    </table>    
    </form>

    </td>
</tr>
<tr>
    <td valign="top">

    &nbsp;<span class="small">&gt;</span>
     {section name=Path loop=$module_result.path offset=2 show=eq($DesignKeys:used.viewmode,'full')}
        {section show=$Path:item.url}
        <a class="small" href="{$Path:item.url}">{$Path:item.text}</a>
        {section-else}
	<span class="small">{$Path:item.text}</span>
        {/section}

        {delimiter}
        <span class="small">/</span>
        {/delimiter}
    {section-else}
     {section name=Path loop=$module_result.path}
        {section show=$Path:Path:item.url}
        <a class="small" href="{$Path:item.url}">{$Path:Path:item.text}</a>
        {section-else}
	<span class="small">{$Path:Path:item.text}</span>
        {/section}

        {delimiter}
        <span class="small">/</span>
        {/delimiter}
     {/section}
    {/section}
    <table width="100%" border="0" alt="" cellpadding="0" cellspacing="10">
    <tr>
        <td valign="top" width="100">
	{section name=Menu loop=fetch(content,list,hash(parent_node_id,$DesignKeys:used.node,class_filter_type,include,class_filter_array,array(1,24))) show=eq($DesignKeys:used.viewmode,'full')}
	<a class="small" href={concat('content/view/full/',$Menu:item.node_id)|ezurl}>{$Menu:item.name}</a>
	{delimiter}
	<br />
	{/delimiter}
	{/section}
	
	<br />
        </td>

        <td valign="top" width="396">
	{$module_result.content}
        </td>
    </tr>
    </table>

    </td>
    <td width="204" valign="top">

    <table width="100%"  bgcolor="#e4eaf3" border="0" alt="" cellpadding="0" cellspacing="1">
    <tr>
        <td>
        <img width="204" height="116" src={"speed.jpg"|ezimage} alt="" /><br />
        </td>
    </tr>
    <tr>
        <td bgcolor="#e4eaf3">

	<table width="100%" border="0" alt="" cellpadding="0" cellspacing="2">
	<tr>
	   <td>
	   &nbsp;&nbsp;<b>News</b>
	   </td>
	</tr>
	</table> 	

        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff">

	<table width="100%" border="0" alt="" cellpadding="0" cellspacing="10">
	<tr>
	    <td>
        {section name=News loop=$news_list}
	<a class="small" href={concat('content/view/full/',$News:item.node_id)|ezurl}>{$News:item.name}</a><br />
	<span class="small">({$News:item.object.published|l10n(shortdate)})</span>
	{delimiter}
	<br clear="all" /><br />
	{/delimiter}
	{/section}
        </td>
	</tr>
	</table> 	

        </td>
    </tr>
    </table>

    </td>
</tr>
</table>

<br />
<div align="center" class="small">
Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2002.<br />
<a href="http://developer.ez.no"><img src={"powered-by-ezpublish-100x35-trans-lgrey.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a>
</div>

</body>
</html>
{/let}
{/let}