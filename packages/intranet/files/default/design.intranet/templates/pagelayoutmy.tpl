{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

{let pagedesign=fetch_alias(by_identifier,hash(attr_id,intranet888))}

<head>
 <link rel="stylesheet" type="text/css" href={"stylesheets/intranet_leftmenu.css"|ezdesign} /> 
{* <link rel="stylesheet" type="text/css" href={"stylesheets/intranet_rightmenu.css"|ezdesign} />  *}
{*<link rel="stylesheet" type="text/css" href={$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot} />*}

{* page header start *}
{default enable_help=true() enable_link=true()}

{let name=Path
     path=$module_result.path
     reverse_path=array()}
{section show=is_set($module_result.title_path)}
    {set path=$module_result.title_path}
{/section}
{section loop=$:path}
    {set reverse_path=$:reverse_path|array_prepend($:item)}
{/section}

{set-block scope=root variable=site_title}
{section loop=$Path:reverse_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section} - {$site.title|wash}
{/set-block}

{/let}

<title>{$site_title}</title>

{section show=and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
    {section name=ExtraData loop=$#Header:extra_data}
        {$:item}
    {/section}
{/section}

{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}

{section name=HTTP loop=$site.http_equiv}
    <meta http-equiv="{$HTTP:key|wash}" content="{$HTTP:item|wash}" />
{/section}

{section name=meta loop=$site.meta}
    <meta name="{$meta:key|wash}" content="{$meta:item|wash}" />
{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta name="generator" content="eZ publish" />

{/default}
{* page header finished *}
</head>

<body>
{let color="#77ff77"}
<div id="container" style="background-color: {$color}; font-family: Verdana; font-style: normal;">
    {* Top box START *}
    <div id="topbox" style="background-color: #444444;">
        <form action={"/content/advancedsearch/"|ezurl} method="get">
	<div id="logo">
        {let content=$pagedesign.data_map.image.content}
	    <a href={"/"|ezurl}><img src={$content[logo].full_path|ezroot} /></a> 
        {/let}
	</div>
	<div id="searchbox">
                <select name="SearchContentClassID">
		<option value="-1"{section show=eq($search_contentclass_id,-1)}selected{/section} />Alle site</option>
		<option value="2" {section show=eq($search_contentclass_id,2)}selected{/section} />Article</option>
		<option value="16" {section show=eq($search_contentclass_id,16)}selected{/section} />Company</option>
		<option value="17" {section show=eq($search_contentclass_id,17)}selected{/section} />Person</option>	
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
    <div id="topmenu">
	{* Menubox start *}
	{let  top_menu=fetch( content, list, hash( parent_node_id, 2, 
				     sort_by, array( priority, true() ),
				     class_filter_type, include,
				     class_filter_array, array( 'folder' ) ) ) }

	{section name=item loop=$top_menu}
	    <div class="item">
	        <a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a>
	    </div>
            {delimiter}
	    <div class="delimiter">
            |
            </div> 
            {/delimiter}
	{/section}
	{/let}
	{* Menubox stop *}    
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
    <a href="/user/login">login</a>
    {section-else}
    <a href="/user/logout">logout</a> ( {$current_user.contentobject.name} )
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

    {* Main menu START *}
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

    {* Main menu END *}

    {* Main area START *}

    <div id="maincontent">
    {$module_result.content}    
    </div>
    
    </div>
    
    {* Main area END *}

{* Main part END *}

{* Footer START *}
<div id="footer">
    <a href="http://ez.no">eZ publish&trade;</a> copyright &copy; 1999-2004 <a href="http://ez.no">eZ systems as</a>
</div>
{* Copyright END *}


</div>
<!--DEBUG_REPORT-->

</body>

{/let}
</html>
