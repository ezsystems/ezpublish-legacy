<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{cache-block keys=$uri_string}
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,gallery_package))}
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
    {* @import url({$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot});*}
    @import url({"stylesheets/news.css"|ezdesign});
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
                {let folder_list=fetch( content, list, hash( parent_node_id, 2, 
                                                             sort_by, array( array( priority ) ),
  							     class_filter_type, exclude, 
							     class_filter_array, array( 'gallery' ) ) )}
                {section name=Folder loop=$folder_list}
                    <li><a href={concat( "/content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a></li>
                {/section}
                {/let}
                </ul>
            
            </div>
        </div>
        
        <div class="break"></div> {* This is needed for proper flow of floating objects *}
        
        </div>
    </div>

{cache-block keys="infobox"}

    <div id="infobox">
        <div class="design">

               {let category_list=fetch( content, tree, hash( parent_node_id, 181,
							   limit, 5,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'folder' ) ) )}
                                                          
            <h3>News</h3>
            <ul>
                   {section name=News loop=$category_list sequence=array(bglight,bgdark)}
                       <li class="{$:sequence}">
                       <a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a>
                       </li>
                    {/section}
            </ul>
               {/let}

               {let news_list=fetch( content, tree, hash( parent_node_id, 2,
							   limit, 5,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'article' ) ) )}
                                                          
            <h3>Latest news</h3>
            <ul>
                   {section name=News loop=$news_list sequence=array(bglight,bgdark)}
                       <li class="{$:sequence}">
                       <a href={$News:item.url_alias|ezurl}>{$News:item.name|wash}</a>
                       <div class="date">
                        ({$News:item.object.published|l10n( shortdate )})
                       </div>  
                       </li>
                    {/section}
            </ul>
               {/let}

	       {let tipsend_list=fetch('content','tipafriend_top_list',hash(limit,5,offset,0))}
	    
	    <h3>Most popular tipsend</h3>
            <ul>
                   {section var=TipsendList loop=$tipsend_list sequence=array(bglight,bgdark)}
                       <li class="{$:sequence}">
                       <a href={$TipsendList.item.url_alias|ezurl}>{$TipsendList.item.name|wash}</a>
                       <div class="date">
                        ({$TipsendList.item.object.published|l10n( shortdate )})
                       </div>
                       </li>
                   {/section}
            </ul>
        </div>
    </div>
{/cache-block}

    <div id="maincontent">
        <div class="design">
        
    <div id="path">
        <div class="design">

           <p>
           &gt;
           {section name=Path loop=$module_result.path }
               {section show=$Path:item.url}
                  <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a>
               {section-else}
    	      {$Path:item.text|wash}
               {/section}
    
               {delimiter}
                 /
               {/delimiter}
            {/section}
           </p>

        </div>
    </div>
{/let}
{/cache-block}

            {$module_result.content}
       
        </div>
    </div>
{cache-block keys="newsblock"}
    <div id="newsblock">
        <div class="design">
            {let category_list=fetch( content, tree, hash( parent_node_id, 181,
							   limit, 5,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'folder' ) ) )}
            <table>
            <tr>
                {section name=Category loop=$category_list}
                <td>
                    <h3>{$:item.name}</h3>
                    {let news_list=fetch( content, list, hash( parent_node_id, $:item.node_id,
							   limit, 10,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'article' ) ) )}

                    {section loop=$:news_list sequence=array(bglight,bgdark)}
                    <ul>
                       <li class="{$:sequence}">
                       <a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a>
                       </li>
                    </ul>
                    {/section}
                    {/let}
                </td>
                   {delimiter modulo=4}
                    </tr>
                    <tr>
                   {/delimiter}
                {/section}
            </tr>  
            </table>
               {/let}
        </div>
    </div>
{/cache-block}
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

</html>