{cache-block keys=$uri_string}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>

<style type="text/css">
    @import url({"stylesheets/core.css"|ezdesign});
{*    @import url({ezini('StylesheetSettings','MainCSS','design.ini')|ezdesign}); *}
    @import url({"stylesheets/site.css"|ezdesign});
    @import url({"stylesheets/t1/site-colors.css"|ezdesign}); {* todo -> read from design settings  *}
    @import url({"stylesheets/classes.css"|ezdesign});
    @import url({"stylesheets/t1/classes-colors.css"|ezdesign}); {* todo -> read from design settings  *}
    @import url({"stylesheets/debug.css"|ezdesign});
</style>

{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent-design { width: 100%; } /* This is needed to avoid width bug in IE 5.5 */
</style>
<![endif]-->
{/literal}

{include uri="design:page_head.tpl"}

</head>
<body>

<div id="allcontent">

<div id="top-content">

{let pagedesign=fetch_alias(by_identifier,hash(attr_id,sitestyle_identifier))}
<div id="header">
    <div id="header-design">
    {*<img src={"/images/t1/t1-logo-placeholder.gif"|ezdesign} height="70" width="211" alt="Company logo" />*}
    {section show=$pagedesign.data_map.image.content.is_valid|not()}
        <h1>{ezini('SiteSettings','SiteName')}</h1>
    {section-else}
        <a href={"/"|ezurl}><img src={$pagedesign.data_map.image.content[logo].full_path|ezroot} alt="Company logo" /></a>
    {/section}
    </div>
</div>
{/let}

{/cache-block}

<div class="break"></div>

<hr class="hide" />


<div id="path">
    <div id="path-design">
    {include uri="design:parts/path.tpl"}
    </div>
</div>

<hr class="hide" />

{cache-block}
    {let maincontentstyle='bothmenus'}

    {section show=eq(ezini('SelectedMenu','LeftMenu','menu.ini'),'')}
       {set maincontentstyle='noleftmenu'}
    {/section}

    {section show=ezini('Toolbar_right','Tool','toolbar.ini')|count|eq(0)}
       {section show=$maincontentstyle|eq('noleftmenu')}
           {set maincontentstyle='nomenus'}
       {section-else}
           {set maincontentstyle='norightmenu'}
       {/section}
    {/section}

<div id="maincontent" class="{$maincontentstyle}"><div id="fix">
<div id="maincontent-design">
    {/let}

{/cache-block}

{$module_result.content}

</div>
<div class="break"></div>
</div></div>

<div class="break"></div>
</div>


<div id="footer">
<div id="footer-design">

<address>{"Powered by %linkStartTag eZ publish&reg;&trade; open source content management system %linkEndTag and development framework."|i18n("design/base",,hash('%linkStartTag',"<a href='http://ez.no'>",'%linkEndTag',"</a>" ))}<br />
{ezini('SiteSettings','MetaDataArray','site.ini').copyright}
</address>

</div>
</div>

<div class="break"></div>
</div>

</body>
</html>

