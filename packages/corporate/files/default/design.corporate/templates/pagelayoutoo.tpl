<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<head>
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,corporate_package))}
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}
{*<link rel="stylesheet" type="text/css" href={"stylesheets/corporate.css"|ezdesign} />*}
<link rel="stylesheet" type="text/css" href={$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot} />
</head>

<body>

{* Top box START *}

<div id="mainlayout">

   <div id="header">
        <a href={"/"|ezurl}><img src={"mycompanylogo.jpg"|ezimage} width="700" height="68" alt="My company - business" border="0" /></a>
   </div>

   <div id="navigation_bar">
       {let folder_list=fetch( content, list, hash( parent_node_id, 2,
                                                    sort_by, array( array( priority ) ) ) )}
           {section name=Folder loop=$folder_list}
               <div class="navigation_item">
               <a href={concat( "/content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a> 
               </div>
           {/section}
       {/let}
   </div>

   <div id="path">
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

   <div id="mainmenu">
    {let MainMenu=treemenu($module_result.path,$module_result.node_id,1,array('folder','info_page'))}
        {section name=Menu loop=$MainMenu}
            <div class="item">
	    {section show=$:item.is_selected}
               <div class="selected">
            {/section}

            <div class="level_{$:item.level}">
               <a href={$:item.url_alias|ezurl}>{$Menu:item.text}</a>
            </div>

	    {section show=$:item.is_selected}
               </div>
            {/section}
            </div>
        {/section}
    {/let}
    
   </div>

   <div id="info_bar">
   {let news_list=fetch( content, tree, hash( parent_node_id, 2,
                                              limit, 5,
                                              sort_by, array( published, false() ),
                                              class_filter_type, include, 
                                              class_filter_array, array( 2 ) ) )}

       {section name=News loop=$news_list}
           <h3><a href={concat('content/view/full/',$News:item.node_id)|ezurl}>{$News:item.name|wash}</a></h3>
           <p>
            ({$News:item.object.published|l10n( shortdate )})
           </p>  
        {/section}
   {/let}

      <div id="search_box">
         <form action={"/content/search/"|ezurl} method="get">
           <input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />&nbsp;
           <input class="searchbutton" name="SearchButton" type="submit" value="Search" />
         </form>
      </div>
   </div>

   <div id="main_content">
      {$module_result.content}
   </div>

   <div id="footer">
       <p>
            Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2004
            <a href="http://ez.no/">Powered by eZ publish Content Management System</a>
       </p>
   </div>
</div>

</body>
{/let}
</html>

