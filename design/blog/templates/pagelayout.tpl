<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,forum_package))}
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}
{*<link rel="stylesheet" type="text/css" href={$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot} /> *}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
    @import url({"stylesheets/blog.css"|ezdesign});
</style>
</head>

<body>

<div id="background">

    <div id="header">
        <div class="design">
        
           {let content=$pagedesign.data_map.image.content}
            <div id="logo">
                <a href={"/"|ezurl}><img src={$content[logo].full_path|ezroot} /></a>
            </div>
           {/let}
            <h1>My Personal Blog</h1>  
                  
            <div class="break"></div> {* This is needed for proper flow of floating objects *}

        </div>
    </div>

        
    <div id="subheader">
        <div class="design">
        
            <div id="searchbox">
                <form action={"/content/search/"|ezurl} method="get">
                    <input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />
                    <input class="searchbutton" name="SearchButton" type="submit" value="Search" />
                </form>
            </div>
        
        <div id="mainmenu">
            <div class="design">

                <h3 class="invisible">Main menu</h3>
                <ul>
                {let folder_list=fetch( content, list, hash( parent_node_id, 2, sort_by, array( array( priority ) ) ) )}
                {section name=Folder loop=$folder_list}
		    {section show=ne($Folder:item.node_id,173)}
                        <li><a href={concat( "/content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a></li>
		    {/section}
                {/section}
                {/let}
                </ul>
            
            </div>
        </div>
        
        <div class="break"></div> {* This is needed for proper flow of floating objects *}
        
        </div>
    </div>

    <div id="navigationbar">
        <div class="design">
            {include uri="design:navigationbar.tpl"}
         <h3>Poll</h3>
         <p>
	 {let poll_list=fetch( content, list, hash(  parent_node_id, 173, sort_by, array( array( priority ) ), limit, 1 ) ) }
	 {section name=poll loop=$poll_list}
	     {node_view_gui view=full content_node=$poll:item}
	 {/let}
	 </p>   
        </div>
    </div>

    <div id="maincontent">
        <div class="design">
        
    <div id="path">
        <div class="design">

           <p>
           &gt;
           {section name=Path loop=$module_result.path }
               {section show=$Path:item.url}
                        {let pathtest=fetch('content','node',hash('node_id',$Path:item.node_id))}
                            {section show=and($Path:pathtest.object.main_node.parent_node_id|eq(111),$Path:pathtest.object.main_node.depth|eq(3))|eq(false())} {* Hide a path level on if specific parent and depth *}
                                <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a> /
                            {/section}
                        {/let}
               {section-else}
    	      {$Path:item.text|wash}
               {/section}
    
            {/section}
           </p>

        </div>
    </div>

            {$module_result.content}
        
        </div>
    </div>

    <div id="footer">
        <div class="design">
        
            <address>
            Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2003
            <a href="http://ez.no/">Powered by eZ publish Content Management System</a>
            </address>
        
        </div>
    </div>

</div>

</body>
{/let}
</html>

