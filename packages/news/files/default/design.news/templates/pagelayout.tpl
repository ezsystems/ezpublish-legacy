<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{cache-block keys=$uri_string}
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,news_package))}
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
    @import url({$pagedesign.data_map.sitestyle.content|ezpackage(filepath,"cssfile")|ezroot});

 {*  @import url({"stylesheets/news_blue.css"|ezdesign});*}
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
							     class_filter_array, array( 'folder' ) ) )}
                {section var=Folder loop=$folder_list}
                        <li><a href={$Folder.item.url_alias|ezurl}>{$Folder.item.name|wash}</a></li>
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
                                                          
            <div id="categorylist">
            <h3>{"News"|i18n("design/news/layout")}</h3>
            <ul>
                   {section var=category loop=$category_list sequence=array(bglight,bgdark)}
                       <li class="{$category.sequence}">
		       <a href={$category.item.url_alias|ezurl}>{$category.item.name|wash}</a>
                       </li>
                   {/section}
            </ul>
            </div>
               {/let}

               {let news_list=fetch( content, tree, hash( parent_node_id, 2,
							   limit, 5,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'article' ) ) )}
                                                          
            <div id="latestnews">
            <h3>{"Latest news"|i18n("design/news/layout")}</h3>
            <ul>
                   {section var=news loop=$news_list sequence=array(bglight,bgdark)}
                       <li class="{$news.sequence}">
                       <a href={$news.item.url_alias|ezurl}>{$news.item.name|wash}</a>
                       <div class="date">
                        ({$news.item.object.published|l10n( shortdate )})
                       </div>  
                       </li>
                    {/section}
            </ul>
            </div>
               {/let}

	       {let tipsend_list=fetch('content','tipafriend_top_list',hash(limit,5,offset,0))}
	    
            <div id="mostpopular">
	    <h3>{"Most popular"|i18n("design/news/layout")}</h3>
            <ul>
                   {section var=tipped loop=$tipsend_list sequence=array(bglight,bgdark)}
                       <li class="{$tipped.sequence}">
                       <a href={$tipped.item.url_alias|ezurl}>{$tipped.item.name|wash}</a>
                       <div class="date">
                        ({$tipped.item.object.published|l10n( shortdate )})
                       </div>
                       </li>
                   {/section}
            </ul>
            </div>
               {/let}

	       {let poll_list=fetch( content, list, hash(  parent_node_id, 173, sort_by, array( array( published, false() ) ), limit, 1 ) ) }
            <div id="pollbox">
            <h3>{"Poll"|i18n("design/news/layout")}</h3>
                <div class="poll">
                   <form method="post" action={"content/action"|ezurl}>

                   <input type="hidden" name="ContentNodeID" value="{$poll_list[0].node_id}" />
                   <input type="hidden" name="ContentObjectID" value="{$poll_list[0].object.id}" />

                   <p>{$poll_list[0].name}</p>
                   {attribute_view_gui attribute=$poll_list[0].object.data_map.option}
                   {section name=ContentAction loop=$poll_list[0].object.content_action_list show=$poll_list[0].object.content_action_list}
                      <input class="button" type="submit" name="{$ContentAction:item.action}" value="Vote" />
                   {/section}

                   <a href={concat( "/content/collectedinfo/", $poll_list[0].node_id, "/" )|ezurl}>Result</a>
                   </form>

                   <a href={concat( "/content/view/full/", $poll_list[0].parent_node_id, "/" )|ezurl}><h4>View all polls</h4></a>
                </div>
            </div>
               {/let}
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
							   limit, 8,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'folder' ) ) )}
            <table>
            <tr>
                {section var=category loop=$category_list}
                <td>
                    <h3>{$category.item.name}</h3>
                    {let news_list=fetch( content, list, hash( parent_node_id, $category.item.node_id,
							   limit, 10,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'article' ) ) )}

                    {section var=newsitem loop=$news_list sequence=array(bglight,bgdark)}
                    <ul>
                       <li class="{$newsitem.sequence}">
                       <a href={$newsitem.item.url_alias|ezurl}>{$newsitem.item.name|wash}</a>
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

{cache-block}
    <div id="footer">
        <div class="design">
            <address>
		 {ezini('SiteSettings','MetaDataArray','site.ini').copyright}
		 <br /><a href="http://ez.no/">Powered by eZ publish Content Management System</a>
            </address>
        </div>
    </div>
{/cache-block}

</div>

</body>

</html>
