<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{cache-block keys=array( $uri_string, $current_user.is_logged_in ) }
{* fetch object by attribute_id, see definition in settings/fetchalias.ini *}
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,blog_package))}
<head>
{* Fetch the template for metadata. Normally located in design/standard/templates/ *}
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}
{*<link rel="stylesheet" type="text/css" href={$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot} /> *}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
    @import url({$pagedesign.data_map.sitestyle.content|ezpackage(filepath,"cssfile")|ezroot});
{*    @import url("/design/blog/stylesheets/blog_blue.css"); *}
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
           {* <h1>My Personal Blog</h1>  *}
                  
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
                        <li><a href={$Folder:item.url_alias|ezurl}>{$Folder:item.name|wash}</a></li>
		    {/section}
                {/section}
                {/let}
		{section show=$current_user.is_logged_in}
		  <li><a href={"/user/logout"|ezurl}>{"Logout"|i18n("design/standard/templates/")}</a></li>
		{section-else}
		  <li><a href={"/user/login"|ezurl}>{"Login"|i18n("design/standard/templates/")}</a></li>
		{/section}
                </ul>
            
            </div>
        </div>
        
        <div class="break"></div> {* This is needed for proper flow of floating objects *}
        
        </div>
    </div>

    <div id="maincontent">
        <div class="design">
        
    <div id="subsubheader">
        <div class="design">
	&nbsp;
        </div>
    </div>

    <div id="navigationcol">
        <div class="design">
            {include uri="design:navigationbar.tpl"}

         <div id="poll">
             <h2>{"Poll"|i18n("design/blog/layout")}</h2>
             <p>
            {let poll_list=fetch( content, list, hash(  parent_node_id, 173, sort_by, array( array( published, false() ) ), limit, 1 ) ) }
            {section name=poll loop=$poll_list}
                {node_view_gui view=full content_node=$poll:item}
	    {/section}
            {/let}
            </p>   
         </div>

         <div id="links">
             <h2>{"Recent links"|i18n("design/blog/layout")}</h2>
             {let link_limit=10
                  link_list=fetch( content, tree, hash( parent_node_id, 2,
                                                        limit, $link_limit,
                                                        sort_by, array( published, false() ),
                                                        class_filter_type, include, 
                                                        class_filter_array, array( 'link' ) ) )}
                 <ul>
                 {section var=link loop=$link_list}
                 <li>
                     <a href={$link.item.data_map.url.content|ezurl}>{$link.item.name}</a>
                 </li>
                 {/section}
                 </ul> 
             {/let}
         </div>

        </div>
    </div>

    <div id="maincol">
        <div id="path">

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
{/let}

        <div class="design">
        <div id="innercontent">
{/cache-block}
            {$module_result.content}
        </div>
        </div>
    </div>

        
        </div>
    </div>

    <div id="footer">
        <div class="design">
            <address>
		 {ezini('SiteSettings','MetaDataArray','site.ini').copyright}
		 <br /><a href="http://ez.no/">Powered by eZ publish Content Management System</a>
            </address>
        </div>
    </div>
</div>
</body>
</html>

