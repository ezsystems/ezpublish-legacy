<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{cache-block keys=$uri_string}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,forum_package))}
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
{*    @import url("/design/forum/stylesheets/forum_blue.css");*}
    @import url({$pagedesign.data_map.sitestyle.content|ezpackage(filepath,"cssfile")|ezroot});
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
                {let folder_list=fetch( content, list, hash( parent_node_id, 2, sort_by, array( array( priority ) ) ) )}
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

{/let}

{/cache-block}
    <div id="usermenu">
        <div class="design">

        <h3 class="invisible">User menu</h3>
        <ul>
        {section show=$current_user.is_logged_in}
            <li><a href={"/notification/settings"|ezurl}>{"Notifications"|i18n("design/forum/layout")}</a></li>
            <li><a href={concat('/content/edit/',$current_user.contentobject_id)|ezurl}>{"Edit account"|i18n("design/forum/layout")}</a></li>
        {/section}
        {section show=eq($current_user.is_logged_in)}
            <li><a href={"/user/login"|ezurl}>{"Login"|i18n("design/forum/layout")}</a></li>
        {section-else}
            <li><a href={"/user/logout"|ezurl}>{"Logout"|i18n("design/forum/layout")}</a></li>
        {/section}
        
        </ul>

        </div>
    </div>
{cache-block keys=$uri_string}

    <div id="maincontent">
        <div class="design">
        
    <div id="path">
        <div class="design">

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
    </div>
{/cache-block}

            {$module_result.content}
        
        </div>
    </div>
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

