{*?template charset=utf8?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
</head>

<body>

{include uri="design:top_menu.tpl"}

{let folder_list=fetch( content, list, hash( parent_node_id, 19 ) )
     product_list=fetch( content, tree, hash( 
                                        parent_node_id, 19, 
                                        limit, 20, 
                                        sort_by, array( array( published ) ),
                                        class_filter_type, include,
                                        class_filter_array, array( 8 ) ) ) }

<br clear="all" />
<br />

<table width="700" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top" bgcolor="#333333">
        <a href={concat( "content/view/full/", 23 )|ezurl}><img src={"booklogo-green.gif"|ezimage} width="700" border="0"></a>
    </td>
</tr>
<tr>
    <td valign="top" bgcolor="#ffffff">
        <img src={"1x1.gif"|ezimage} width="1" height="1" border="0">
    </td>
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
                            &nbsp;<a class="small" href={concat( "content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a>
                        </td>
                    </tr>
                {/section}
                <tr> 
                    <td bgcolor="#dddddd">
                        &nbsp;<a class="small" href={"user/login/"|ezurl}>Login</a>
                    </td>
                </tr>
                <tr> 
                    <td bgcolor="#dddddd">
                        &nbsp;<a class="small" href={"user/logout/"|ezurl}>Logout</a>
                    </td>
                </tr>
                <tr> 
                    <td bgcolor="#dddddd">
                        &nbsp;<a class="small" href={"shop/basket/"|ezurl}>Basket</a>
                    </td>
                </tr>
                <tr> 
                    <td bgcolor="#dddddd">
                        &nbsp;<a class="small" href={"shop/wishlist/"|ezurl}>Wish list</a>
                    </td>
                </tr>
                <tr> 
                    <td bgcolor="#ababab">
                        <form action={"/content/search/"|ezurl} method="get">
                        <input type="hidden" name="SectionID" value="5" />
                	&nbsp;<input  type="text" size="10" name="SearchText" id="Search" value="" />
                        <input class="button" name="SearchButton" type="submit" value="Search" />
                	</form>
                	<br />
                	<br />
            	        <center>
        	        <a href="http://ez.no/developer"><img src={"powered-by-ezpublish-100x35-grey.gif"|ezimage} alt="eZ publish" border="0" width="100" height="35" /></a>
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
            <td width="20" valign="top" bgcolor="#ffffff">
                &nbsp;
            </td>
            <td width="130" valign="top" bgcolor="#ffffff">
	        <br />
	        <br />
                <h2><font color="#000000">New books</font></h2>
	        <br />
                {section name=Product loop=$product_list}
                    <a class="small" href={concat( "/content/view/full/", $Product:item.node_id, "/" )|ezurl}><font color="#000000"><b>{$Product:item.name|wash}</b></font></a><br /><br />
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
    Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2004.
</div>

{/let}
</body>
</html>