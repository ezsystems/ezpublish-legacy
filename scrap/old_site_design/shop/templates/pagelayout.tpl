<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{cache-block keys=$uri_string}
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,shop_package))}
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

{*<link rel="stylesheet" type="text/css" href={$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot} />*}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
    @import url({$pagedesign.data_map.sitestyle.content|ezpackage(filepath,"cssfile")|ezroot});
   {* @import url("/design/shop/stylesheets/shop.css"); *}
</style>

{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent div.design { width: 100%; }
</style>
<![endif]-->
{/literal}

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
{/let}

    <div id="topmenubackground">

    <div id="mainmenu">
        <div class="design">

            <h3 class="invisible">{"Main menu"|i18n("design/shop/layout")}</h3>
            <ul>
            {let folder_list=fetch( content, list, hash( parent_node_id, 2, sort_by, array( array( priority ) ) ) )}
            {section name=Folder loop=$folder_list}<li><span class="corner"><a href={concat( "/content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a></span></li>{/section}
            {/let}
            </ul>
        
        </div>
    </div>
        
    <div id="subheader">
        <div class="design">
       
        <div id="searchbox">
            <div class="design">
                <form action={"/content/search/"|ezurl} method="get">
                     <input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />
                     <input class="searchbutton" name="SearchButton" type="submit" value="{"Search"|i18n("design/shop/layout")}" />
                </form>
            </div>
        </div>
{/cache-block}

        <div id="shoppingmenu">
            <div class="design">


            <ul>
                {section show=$current_user.is_logged_in}
                    <li><a href={"/notification/settings"|ezurl}>{"Notifications"|i18n("design/shop/layout")}</a></li>
                    <li><a href={concat( '/content/edit/', $current_user.contentobject_id )|ezurl}>{"Edit account"|i18n("design/shop/layout")}</a></li>
                    <li><a href={"/shop/basket/"|ezurl}>{"View basket"|i18n("design/shop/layout")}</a></li>
                    <li><a href={"/user/logout"|ezurl}>{"Logout"|i18n("design/shop/layout")}</a></li>
                {section-else}
                    <li><a href={"/user/register/"|ezurl}>{"Register new customer"|i18n("design/shop/layout")}</a></li>
                    <li><a href={"/user/login"|ezurl}>{"Login"|i18n("design/shop/layout")}</a></li>
                {/section}
            </ul>

            </div>
        </div>

        <div class="break"></div>

        </div>
    </div>

    </div>
    
    <div id="maincolumns">
    <div id="leftmenu">

    {cache-block}
    <div id="productmenu">
        <div class="design">
        <h3>{"Products"|i18n("design/shop/layout")}</h3>
            {let path=$module_result.path
                 node_id=$module_result.node_id}

                {section show=$module_result.path[1].node_id|ne(154)}
                    {set path=array(hash('node_id',2,'url','/content/view/full/2'),hash('node_id',154,'url','/content/view/full/154'))}
                    {set node_id=154}
                {/section}

                <ul>
                    {let mainMenu=treemenu($path,$node_id,array('folder','info_page'), 1, 10 )}
                        {section var=menu loop=$mainMenu}
                            {section show=$menu.item.is_selected}
                                <li class="level_{$menu.item.level}">
                                    <div class="selected"> 
                                    <a href={$menu.item.url_alias|ezurl}>{$menu.item.text}</a>
                                    </div>  
                                </li>
                            {section-else}
                                <li class="level_{$menu.item.level}">
                                    <a href={$menu.item.url_alias|ezurl}>{$menu.item.text}</a>
                                </li>
                            {/section}
                        {/section}
                    {/let}
                </ul>
            {/let}
        </div>
    </div>

    <div id="latestproducts">
        <div class="design">
            <h3>{"Latest products"|i18n("design/shop/layout")}</h3>  
            {let new_product_list=fetch( content, tree, hash( parent_node_id, 2,
                                                                    limit, 6, 
                                                                    sort_by, array( published, false() ),
                                                                    class_filter_type, include, 
                                                                    class_filter_array, array( 'product' ) ) )}
            <ul>
                {section name=Product loop=$new_product_list}
                    <li>
                    <a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a>
                    <div class="date">
                        ({$:item.object.published|l10n( shortdate )})
                    </div>  
                    </li>
                {/section}
            </ul>

            {/let}
        </div>
    </div>

    </div>

    {/cache-block}

    <div id="rightmenu">

    <div id="cart">
        <div class="design">

            {let basket=fetch( shop, basket )
                 use_urlalias=ezini( 'URLTranslator', 'Translation' )|eq( 'enabled' )
                 basket_items=$basket.items}
            <h3>{"Shopping basket"|i18n("design/shop/layout")}</h3>
            {section show=$basket_items}
            <ul>
                {section var=product loop=$basket_items sequence=array( odd, even )}
                    <li>
                    {$product.item.item_count} x <a href={cond( $use_urlalias, $product.item.item_object.contentobject.main_node.url_alias,
                                                                concat( "content/view/full/", $product.item.node_id ) )|ezurl}>{$product.item.object_name}</a>
                    </li>
                {/section}
            </ul>
            <div class="price"><p>{$basket.total_inc_vat|l10n(currency)}</p></div>
            <p><a href={"/shop/basket"|ezurl}>{"View all details"|i18n("design/shop/layout")}</a></p>
            {section-else}
                <p>{"Your basket is empty"|i18n("design/shop/layout")}</p>
            {/section}
            {/let}

        </div>
    </div>
    {cache-block}
    <div id="infobox">
        <div class="design">
            {let bestseller_list=false()}
            {switch match=$module_result.content_info.class_id}
                {case match=23}
                    {set bestseller_list=fetch( shop, best_sell_list, hash( top_parent_node_id, $DesignKeys:used.parent_node,
                                                          limit, 5 ) )}
                {/case}
	        {case match=1}
                    {switch match=$module_result.path[1].node_id}
		    {case match=154}
                        {set bestseller_list=fetch( shop, best_sell_list, hash( top_parent_node_id, $DesignKeys:used.node,
                                                          limit, 5 ) )}
                    {/case}
                    {case}
                        {set bestseller_list=fetch( shop, best_sell_list, hash( top_parent_node_id, 2,
                                                          limit, 5 ) )}
                    {/case}
		    {/switch}
                {/case}
                {case}
                    {set bestseller_list=fetch( shop, best_sell_list, hash( top_parent_node_id, 2,
                                                          limit, 5 ) )}
                {/case}
            {/switch}
            <h3>{"Best sellers"|i18n("design/shop/layout")}</h3>
            <ul>
                   {section name=Products loop=$bestseller_list}
                       <li>
                       <a href={concat( 'content/view/full/', $Products:item.main_node_id )|ezurl}>{$Products:item.name|wash}</a> 
                       </li>
                    {/section}
            </ul>
            {/let}
        

        </div>
    </div>

    <div id="latestnews">
        <div class="design">

       {let news_list=fetch( content, tree, hash( parent_node_id, 2,
                                                  limit, 5,
                                                  sort_by, array( published, false() ),
                                                  class_filter_type, include, 
                                                  class_filter_array, array( 2 ) ) )}
        <h3>{"Latest news"|i18n("design/shop/layout")}</h3>
        <ul>
               {section name=News loop=$news_list}
                   <li>
                   <a href={concat( 'content/view/full/', $News:item.node_id )|ezurl}>{$News:item.name|wash}</a>
                   <div class="date">
                   ({$News:item.object.published|l10n( shortdate )})
                   </div>  
                   </li>
                {/section}
        </ul>
           {/let}

        </div>
    </div>

    </div>
    {/cache-block}    
    <div id="maincontent">
      <div class="design">
        
        <div id="path">

           <p>
           &gt; 
           {section name=Path loop=$module_result.path }
               {section show=$Path:item.url}
                   {section show=$:item.node_id|eq(152)|not()}
                      <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a> /
                   {/section}
               {section-else}
    	      {$Path:item.text|wash}
               {/section}
    
            {/section}
           </p>

        </div>

        <div id="innercontent">
           {$module_result.content}
        </div>        
            
        <div class="break"></div>
      </div>
    </div>
            
    <div class="break"></div>
    </div>

{cache-block}
    <div id="footer">
        <div class="design">
            <address>
		 {ezini('SiteSettings','MetaDataArray','site.ini').copyright}
		 <br /><a href="http://ez.no/">Powered by eZ publish E-Commerce Engine</a>
            </address>   
        </div>
    </div>
{/cache-block}

</div>

</body>
</html>
