{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/news24.css"|ezdesign} />
</head>

<body>

{include uri="design:top_menu.tpl"}

{let folder_list=fetch( content, list, hash(
                                       parent_node_id, 17,
                                       sort_by, array( array( priority ))
				       )
                      )
     news_list=fetch( content, tree, hash(
                                     parent_node_id, 17, 
				     limit, 5, 
				     sort_by, array( published, false() ),
				     class_filter_type, include,
				     class_filter_array, array( 2 )) ) }

<table class="mainlayout" width="700" border="0" cellpadding="0" cellspacing="0">
<tr> 
    <td width="100%">
        <a href={"/content/view/full/24/"|ezurl}><img src={"news_top.gif"|ezimage} width="700" height="67" border="0" /></a>
    </td>
</tr>
<tr> 
    <td valign="top">
    <table class="layout" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
        <td class="leftcolumn" width="1%" rowspan="3" valign="top" cellspacing="0" cellpadding="0"> 
            <table class="leftmenu" width="100%" border="0" cellpadding="0" cellspacing="0">

            {section name=Folder loop=$folder_list}
                <tr> 
                <td class="menuitem"> 
                    <a class="small" href={concat( "/content/view/full/", $Folder:item.node_id,"/" )|ezurl}>{$Folder:item.name|wash}</a>  
                </td>
                </tr>
            {/section}
            </table>

	    <div class="searchblock">
	        <form action={"/content/search/"|ezurl} method="get">
	        <input type="hidden" name="SectionID" value="3">
		<input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />
		<input class="searchbutton" name="SearchButton" type="image" src={"search.gif"|ezimage} value="Search" />
		</form>
	    </div>
	    <a href="http://ez.no/developer"><img src={"powered-by-ezpublish-100x35-orange.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a>
        </td>
        <td class="date" colspan="2" width="99%">
	    {currentdate()|l10n( datetime )}
        </td>
    </tr>

    <tr>
        <td class="path" colspan="2">&gt;
            {section name=Path loop=$module_result.path offset=2}
	        {section show=$Path:item.url}
                    <a href="{concat($Path:item.url, "/")|ezurl}">{$Path:item.text|wash}</a>
                {section-else}
                     {$Path:item.text|wash}
                {/section}
                {delimiter}
       	            /
                {/delimiter}
            {/section}
        </td>
    </tr>
               
    <tr>
        <td class="maincontent" valign="top" width="99%">
            {$module_result.content}
        </td>
        <td class="rightcolumn" width="1%" valign="top">
            <table class="rightmenu" width="145" border="0" cellspacing="0" cellpadding="8">
            <tr>             
                <th>
                    Latest update.... 
                </th>
            </tr>
            {section name=News loop=$news_list max=1}
                <tr>
                    <td class="menuitem">
                        {node_view_gui view=menu content_node=$News:item}
                    </td>
                </tr>
             {/section} 
             {section name=News loop=$news_list offset=1}
                 <tr> 
                     <td class="menuitem"> 
                         <p class="readmore"><a href={concat("/content/view/full/", $News:item.node_id, "/")|ezurl}>{$News:item.name|wash}</a></p>
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
    <td style="background-color: #000000;">
        <img src={"1x1.gif"|ezimage} alt="" border="0" width="1" height="16" /><br />
    </td>
</tr>
<tr>
    <td>
        <div class="credits">
            <p>Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2004.</p>
        </div>
    </td>
</tr>
</table>

{/let}
</body>
</html>
