{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/news24.css"|ezdesign} />

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

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<img src={"toppmeny.gif"|ezimage} alt="" border="" USEMAP="#map" />

<map name="map">
<area SHAPE="RECT" COORDS="2,1,103,27" href={"content/view/full/159/"|ezurl} />
<AREA SHAPE="RECT" COORDS="104,0,175,24" href={"content/view/full/32/"|ezurl} />
<AREA SHAPE="RECT" COORDS="177,2,245,23" href={"content/view/full/26/"|ezurl} />
<AREA SHAPE="RECT" COORDS="248,3,317,24" href={"content/view/full/82/"|ezurl} />
<AREA SHAPE="RECT" COORDS="320,3,392,23" href={"content/view/full/62/"|ezurl} />
</map>

{let folder_list=fetch(content,list,hash(parent_node_id,24,sort_by,array(array(priority))))
     news_list=fetch(content,tree,hash(parent_node_id,24,limit,5,sort_by,array(published,false()),class_filter_type,include,class_filter_array,array(2)))}

<table width="700" border="0" cellpadding="0" cellspacing="0">
<tr> 
    <td width="700" valign="bottom" bgcolor="#FFFFFF">
    <a href={"/content/view/full/26/"|ezurl}><img src={"news_top.gif"|ezimage} width="700" height="67" border="0" /></a></td>
</tr>
<tr> 
<td  valign="top" bgcolor="#ffffff">

<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">
<tr> 
    <td width="100" valign="top" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" bgcolor="#FF9900"> 
    <table width="100" border="0" align="center" cellpadding="3" cellspacing="1">

{section name=Folder loop=$folder_list}
    <tr> 
        <td bgcolor="#FFD2A6" class="links"> 
        &nbsp;<a class="small" href={concat("/content/view/full/",$Folder:item.node_id,"/")|ezurl}>{$Folder:item.name}</a>  <font size="2">&nbsp;</font></td>
    </tr>
{/section}
    <tr> 
        <td valign="top" bgcolor="#FF9900"> 
	<br />
        <form action={"/content/search/"|ezurl} method="get">
        <input type="hidden" name="SectionID" value="3">
        <input name="SearchButton" type="image" src={"search.gif"|ezimage} value="{"Search"|i18n('pagelayout')}" style="font-size: 7px; margin: 0px; padding: 0px;" /><br />
        <input  type="text" size="10" name="SearchText" id="Search" value="" style="font-family: verdana; width: 80px; font-size: 9px; margin: 0px; background: #ffffff;" />
	</form>
        <br /><br />
	<a href="http://developer.ez.no"><img src={"powered-by-ezpublish-100x35-orange.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a>
        </td>
    </tr>
    </table>
    </td>
    <td width="100%" align="left" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr> 
            <td width="100%" bgcolor="#000000" class="links"> 
             <span class="small"><font color="#fffff">&gt;{currentdate()|l10n(datetime)}</font></span>
            </td>
        </tr>
        <tr>
            <td class="links">
            <p class="path">
            &nbsp;&nbsp;
                 {section name=Path loop=$module_result.path offset=2}
                    {section show=$Path:item.url}
                        <a href="{$Path:item.url}">{$Path:item.text}</a>
                    {section-else}
                         {$Path:item.text}
                    {/section}
                   {delimiter}
	              /
                   {/delimiter}
                 {/section}
            </p>
                </td>
                </tr>
               </table>
            </td>
         </tr>
       <tr>
    <td width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0"> 
    <tr>
        <td width="20">
        &nbsp;&nbsp;
        </td>
        <td width="100%" valign="top">
        {$module_result.content}
        </td>
        <td width="145" bgcolor="#FFF4EA" valign="top" class="links">
        <table width="145" border="0" cellspacing="0" cellpadding="8" align="left">
        <tr>             
             <td valign="top" bgcolor="#663366">
	     <strong class="small"><font color="#FFFFFF">Latest update....</font> 
             </strong></font>
             </td>
         </tr>
         {section name=News loop=$news_list max=1}
         <tr>
         <td width="100%" valign="top">
         {node_view_gui view=menu content_node=$News:item}
	 <br /><br />
         </td>
         </tr>
         {/section} 
         {section name=News loop=$news_list offset=1}
         <tr> 
             <td width="125" bgcolor="#FFF4EA" class="links"> 
             <a class="small" href="/content/view/full/{$News:item.node_id}">{$News:item.name}</a>
             </td>
         </tr>
         {/section}   
         </table>
         </td>
       </tr>
   </table>
  </td>
</tr>
<tr>
     <td>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
          <td bgcolor="#000000">&nbsp;</td>
     </tr>
    </table>
    </td>
</tr>
</table>

<div align="center" class="small">
Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2002.
</div>

{/let}
</body>
</html>
