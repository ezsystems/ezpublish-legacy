{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">


{let pagedesign=fetch_alias(by_identifier,hash(attr_id,intranet_package))}

<head>
{* Fetch the template for metadata. Normally located in design/standard/templates/ *}
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}
{*<link rel="stylesheet" type="text/css" href={$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot} /> *}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
 {*    @import url({"stylesheets/intranet.css"|ezdesign});*}
    @import url({$pagedesign.data_map.sitestyle.content|ezpackage(filepath,"cssfile")|ezroot}); 
</style>
</head>


<body>
<div id="container">
    {* Top box START *}
    <div id="topbox">
        <form action={"/content/advancedsearch/"|ezurl} method="get">
	<div id="logo">
        {let content=$pagedesign.data_map.image.content}
	    <a href={"/"|ezurl}><img src={$content[logo].full_path|ezroot} /></a> 
        {/let}
	</div>
	<div id="searchbox">
                <select name="SearchContentClassID">
		<option value="-1"{section show=eq($search_contentclass_id,-1)}selected{/section} />Whole site</option>
		<option value="2" {section show=eq($search_contentclass_id,2)}selected{/section} />News</option>
		<option value="16" {section show=eq($search_contentclass_id,16)}selected{/section} />Companies</option>
		<option value="17" {section show=eq($search_contentclass_id,17)}selected{/section} />Persons</option>	
		</select>
	        <input type="text" size="15" name="SearchText" id="Search" value="" />
	        <input class="searchbutton" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
	</div>
	<div id="sitelogo">
	&nbsp;
	</div>
	</form>
    </div>
    {* Top box END *}
{/let}
    {* Top menu START *}
    <div id="mainmenu">
       <div class="design">

	{* Menubox start *}
	{let  top_menu=fetch( content, list, hash( parent_node_id, 2, 
				     sort_by, array( priority, true() ),
				     class_filter_type, include,
				     class_filter_array, array( 'folder' ) ) ) }

        <ul>
	{section name=item loop=$top_menu}
	    <li>
	        <a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a>
	    </li>
	{/section}
        </ul>
	{/let}
	{* Menubox stop *}    
       </div>
    </div>
    {* Top menu END *}

    <div id="pathline">
    {* Main path START *}
    <div id="mainpath">
	{section name=Path loop=$module_result.path}
            <div class="item">  
	    {section show=$Path:item.url}
	    <a href={$Path:item.url|ezurl}>{$Path:item.text|shorten(18)|wash}</a>
	    {section-else}
	    {$Path:item.text|wash}
	    {/section}
            </div>   
	    {delimiter}
            <div class="delimiter">  
	    /
            </div>   
	    {/delimiter}
	{/section}
    </div>
    {* Main path END *}
    
    {* Login box START *}
    <div id="login">
    {section show=eq($current_user.is_logged_in)}
    <a href={"/user/login"|ezurl}>{"login"|i18n("design/shop/layout")}</a>
    {section-else}
    <a href={"/user/logout"|ezurl}>{"logout"|i18n("design/shop/layout")}</a> ( {$current_user.contentobject.name} )
    {/section}
    </div>
    {* Login box END *}

    </div>
    
    {* Current Date START *}
    <div id="date">
    {currentdate()|l10n( date )}
    </div>
    {* Current Date END *}
    
   
    {* Main part START *}
    <div id="mainframe">

    {* Main area START *}

    <div id="maincontent">
       <div class="design">
       {$module_result.content}    
       </div>
    </div>


    {* Main menu START *}
    <div id="submenu">
       <div class="design">
          <div id="navigation">
           <h3>Navigation</h3>
           {let sub_menu=treemenu($module_result.path,$module_result.node_id,array('folder','info_page'), 1, 3)}
           <ul>
               {section name=Menu loop=$sub_menu}
                <li class="level_{$:item.level}"><a href={$:item.url_alias|ezurl}>{$:item.text}</a></li>
               {/section}
           </ul>
           {/let}
           </div>

            {let news_list=fetch( content, tree, hash( parent_node_id, 2,
  					               limit, 5,
						       sort_by, array( published, false() ),
					               class_filter_type, include, 
						       class_filter_array, array( 'article' ) ) )}
                                                          
            <div id="latestnews">
            <h3>{"Latest news"|i18n("design/intranet/layout")}</h3>
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
       </div>
    </div>

    {* Main menu END *}
    
    </div>
    
    {* Main area END *}

{* Main part END *}

{* Footer START *}
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
{* Copyright END *}


</div>
<!--DEBUG_REPORT-->

</body>

{/let}
</html>
