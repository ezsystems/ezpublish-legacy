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

<img src={"toppmeny.gif"|ezimage} alt="" border="" USEMAP="#map" />

<map name="map">
<area SHAPE="RECT" COORDS="2,1,103,27" href={"content/view/full/159/"|ezurl} />
<AREA SHAPE="RECT" COORDS="104,0,175,24" href={"content/view/full/32/"|ezurl} />
<AREA SHAPE="RECT" COORDS="177,2,245,23" href={"content/view/full/26/"|ezurl} />
<AREA SHAPE="RECT" COORDS="248,3,317,24" href={"content/view/full/82/"|ezurl} />
<AREA SHAPE="RECT" COORDS="320,3,392,23" href={"content/view/full/62/"|ezurl} />
</map>
<br />

{let folder_list=fetch(content,list,hash(parent_node_id,158,sort_by,array(array(priority))))
     news_list=fetch(content,list,hash(parent_node_id,159,limit,5,sort_by,array(published),class_filter_type,include,class_filter_array,array(2)))}


<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td colspan="2">
    <img src={"mycompanylogo.jpg"|ezimage} alt="My company - business" /></td>
</tr>
<tr>
    <td colspan="2" bgcolor="#e4eaf3">
    <table width="700" border="0" cellspacing="2" cellpadding="2">
    <tr>
{section name=Folder loop=$folder_list}
        <td align="center">
        &nbsp;<a class="small" href={concat("/content/view/full/",$Folder:item.node_id,"/")|ezurl}>{$Folder:item.name}</a>  <font size="2">&nbsp;</font>
        </td>
{/section}
        <td align="right">
        <form action={"/content/search/"|ezurl} method="get">
        <input type="hidden" name="SectionID" value="6">
        <input  type="text" size="10" name="SearchText" id="Search" value="" />
        <input class="button" name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />
	</form>
        </td>
    </tr>
    </table>    
    </td>
</tr>
<tr>
    <td valign="top">
    &nbsp;
     {section name=Path loop=$module_result.path  offset=2}
        {section show=$Path:item.url}
        <a class="small" href="{$Path:item.url}">{$Path:item.text}</a>
        {section-else}
	<span class="small">{$Path:item.text}</span>
        {/section}

        {delimiter}
        <span class="small">/</span>
        {/delimiter}
    {/section}
    <table width="100%" border="0" alt="" cellpadding="0" cellspacing="10">
    <tr>
        <td valign="top" width="100">
	Item 1<br />
	Item 2
        </td>

        <td valign="top">
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
	&nbsp;<b>News</b>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff">
        {section name=News loop=$news_list}
        {node_view_gui view=menu content_node=$News:item}
	{delimiter}
	<br />
	{/delimiter}
	{/section}
        </td>
    </tr>
    </td>
</tr>
</table>

</body>
</html>
{/let}
{/let}