<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}
{cache-block}
<link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
<link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
<link rel="stylesheet" type="text/css" href={"stylesheets/mycompany.css"|ezdesign} />

{* Javascript START *}
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
{* Javascript END *}

</head>

<body>

{* Top box START *}

<table class="mainlayout" width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td colspan="2">
        <a href={"/"|ezurl}><img src={"mycompanylogo.jpg"|ezimage} width="700" height="68" alt="My company - business" border="0" /></a>
    </td>
</tr>
<tr>
    <td class="topmenuline" colspan="2">
        <form action={"/content/search/"|ezurl} method="get">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
	    {let folder_list=fetch( content, list, hash(
	                            parent_node_id, 2,
                                    sort_by, array( array( priority ) ) ) )}

            {section name=Folder loop=$folder_list}
                <td class="topmenu">
                    <a href={concat( "/content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a> 
                </td>
            {/section}
	    {/let}
            <td align="right" width="1%">
                <input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />&nbsp;
        	</td>
            <td align="right" width="1%">
                <input class="searchbutton" name="SearchButton" type="image" src={"search.gif"|ezimage} value="Search" />
            </td>
        </tr>
        </table>    
        </form>
    </td>
</tr>
<tr>
    <td valign="top">
    <p class="path">
    &gt;
{/cache-block}
{cache-block keys=$uri_string}
    {section name=Path loop=$module_result.path offset=1 show=eq( $DesignKeys:used.viewmode, 'full' )}
        {section show=$Path:item.url}
            <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a>
        {section-else}
	    {$Path:item.text|wash}
        {/section}

        {delimiter}
            /
        {/delimiter}
    {section-else}
        {section name=Path loop=$module_result.path}
            {section show=$Path:Path:item.url}
                <a href={$Path:item.url|ezurl}>{$Path:Path:item.text|wash}</a>
            {section-else}
	        {$Path:Path:item.text|wash}
            {/section}

            {delimiter}
                /
            {/delimiter}
         {/section}
    {/section}
    </p>

    <table class="layout" width="500" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="leftedge" valign="top" width="20%">
	  &nbsp;
	    {section name=Menu show=eq( $DesignKeys:used.viewmode, 'full' ) loop=fetch( content, list, hash(
                                                                                 parent_node_id, $module_result.path[1].node_id,
		    							         class_filter_type, include,
									         class_filter_array, array( 'folder', 'info_page' ) ) )}
	        <p class="leftmenuitem"><a href={concat('content/view/full/', $Menu:item.node_id)|ezurl}>{$Menu:item.name|wash}</a></p>
	    {/section}
       </td>
{/cache-block}
       <td class="maincontent" valign="top" width="80%">
           {$module_result.content}
       </td>
{cache-block}
    </tr>
    </table>

    </td>
    <td width="204" valign="top">

        <table class="menu" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th class="menuhead">
	        News
            </th>
        </tr>

        {let news_list=fetch( content, tree, hash(
                                parent_node_id, 2,
                                limit, 5,
                                sort_by, array( published, false() ),
                                class_filter_type, include, 
                                class_filter_array, array( 2 ) ) )}

        {section name=News loop=$news_list}
	    <tr>
                <td class="menuitem">
                    <div class="menuname">
                        <a href={concat('content/view/full/',$News:item.node_id)|ezurl}>{$News:item.name|wash}</a>
                    </div>
	            <div class="menudate">
                        ({$News:item.object.published|l10n( shortdate )})
                    </div>
                </td>
            </tr>
	    {delimiter}
	    {/delimiter}
        {/section}

        {/let}
        </table>

    </td>
</tr>
<tr>
    <td colspan="2">
        <div class="credits" align="center">
            <p>Copyright &copy; <a href="http://ez.no">eZ systems as</a><br />1999-2004</p>
            <a href="http://ez.no/"><img src={"powered-by-ezpublish-100x35-trans-lgrey.gif"|ezimage} alt="Powered eZ publish" border="0" width="100" height="35" /></a>
        </div>
    </td>
</tr>
</table>

</body>
</html>

{/cache-block}