{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />

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

<map name="map">
<area SHAPE="RECT" COORDS="2,1,103,27" href={"content/view/full/159/"|ezurl} />
<AREA SHAPE="RECT" COORDS="104,0,175,24" href={"content/view/full/32/"|ezurl} />
<AREA SHAPE="RECT" COORDS="177,2,245,23" href={"content/view/full/26/"|ezurl} />
<AREA SHAPE="RECT" COORDS="248,3,317,24" href={"content/view/full/82/"|ezurl} />
<AREA SHAPE="RECT" COORDS="320,3,392,23" href={"content/view/full/62/"|ezurl} />
</map>

{let folder_list=fetch(content,list,hash(parent_node_id,60))
     product_list=fetch(content,tree,hash(parent_node_id,60,limit,20,sort_by,array(array(published)),class_filter_type,include,class_filter_array,array(22)))}

<table width="700" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top" bgcolor="#333333"><a href={concat("content/view/full/",62)|ezurl}><img src={"booklogo-green.gif"|ezimage} width="700" border="0"></a></td>
</tr>
<tr>
    <td valign="top" bgcolor="#ffffff"><img src={"1x1.gif"|ezimage} width="1" height="1" border="0"></td>
</tr>
<tr>
    <td bgcolor="#96dc50">
    <table width="700" border="0" cellpadding="0" cellspacing="4">
    <tr>
       <td>
       <div class="small" align="center"><font color="#000000">&quot;Wear the old coat and buy the new book&quot; (Austin Phelps)</font></div>
       </td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td valign="top">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
        <td width="120" valign="top" bgcolor="#ababab">

        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
        {section name=Folder loop=$folder_list}
        <tr> 
            <td bgcolor="#dddddd">
            &nbsp;<a class="small" href={concat("/content/view/full/",$Folder:item.node_id,"/")|ezurl}>{$Folder:item.name}</a>
            </td>
        </tr>
        {/section}
        <tr> 
            <td bgcolor="#ababab">
            <form action={"/content/search/"|ezurl} method="get">
            <input type="hidden" name="SectionID" value="5" />
	    &nbsp;<input  type="text" size="10" name="SearchText" id="Search" value="" />
            <input class="button" name="SearchButton" type="submit" value="{"Search"|i18n('pagelayout')}" />
	    </form>
	    <br />
	    <br />
	    <center>
	    <a href="http://developer.ez.no"><img src={"powered-by-ezpublish-100x35-trans-lgrey.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a>
	    </center>

            </td>
        </tr>
        </table>

        </td>
        <td width="430" valign="top" bgcolor="#FFFFFF">
	<table width="100%" cellspacing="0" cellpadding="20" border="0">
        <tr> 
        <td>
        {$module_result.content}
	<br />
	<br />
	</td>
	</tr>
	</table>
        </td>
        <td width="20" valign="top" bgcolor="#ffffff">&nbsp;</td><td>
        <td width="130" valign="top" bgcolor="#ffffff">
	<br />
	<br />

        <h2><font color="#000000">New books</font></h2>
	<br />
        {section name=Product loop=$product_list}
        <a class="small" href="/content/view/full/{$Product:item.node_id}"><font color="#000000"><b>{$Product:item.name}</b></font></a><br /><br />
        {/section}
        </td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td bgcolor="#333333">
    &nbsp;
    </td>
</tr>
</table>

<div align="center" class="small">
Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2002.
</div>

{/let}
</body>
</html>
