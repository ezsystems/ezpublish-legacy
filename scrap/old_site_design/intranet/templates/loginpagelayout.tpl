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
    @import url({"stylesheets/intranet.css"|ezdesign});
{*    @import url({$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot}); *}
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
        &nbsp;
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
