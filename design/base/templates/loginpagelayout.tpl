{cache-block keys=$uri_string}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>

<style type="text/css">
    @import url({"stylesheets/core.css"|ezdesign});
{*    @import url({ezini('StylesheetSettings','MainCSS','design.ini')|ezdesign}); *}
    @import url({"stylesheets/site.css"|ezdesign});
{*    @import url({"stylesheets/t1/site-colors.css"|ezdesign});   todo -> read from design settings *}
    @import url({ezini('StylesheetSettings','SiteCSS','design.ini')|ezroot});
    @import url({"stylesheets/classes.css"|ezdesign});
 {*    @import url({"stylesheets/t1/classes-colors.css"|ezdesign}); todo -> read from design settings  *}
    @import url({ezini('StylesheetSettings','ClassesCSS','design.ini')|ezroot});
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

    <div id="topcontent">

        {let pagedesign=fetch_alias( by_identifier, hash( attr_id, sitestyle_identifier ) )}
        <div id="header">
            <div id="header-design">
                {*<img src={"/images/t1/t1-logo-placeholder.gif"|ezdesign} height="70" width="211" alt="Company logo" />*}
                {if $pagedesign.data_map.image.content.is_valid|not()}
                    <h1>{ezini( 'SiteSettings', 'SiteName' )}</h1>
                {else}
                    <a href={"/"|ezurl}><img src={$pagedesign.data_map.image.content[logo].full_path|ezroot} alt="Company logo" /></a>
                {/if}
            </div>{* id="header-design" *}
        </div>{* id="header" *}
        {/let}

{/cache-block}

        <div class="break"></div>

        <div id="topmenu">
            <div id="topmenu-design">

                <h3 class="hide">Top menu</h3>
                &nbsp;
                <div class="break"></div>
            </div>{* id="topmenu-design" *}
        </div>{* id="topmenu" *}

        <div class="break"></div>
    </div>{* id="topcontent" *}

    <hr class="hide" />

    <div id="path">
        <div id="path-design">
            {include uri="design:parts/path.tpl"}
        </div>{* id="path-design" *}
    </div>{* id="path" *}

    <hr class="hide" />

    <div id="columns">

        <hr class="hide" />

        {cache-block}
            {let maincontentstyle='maincontent-bothmenus'}

            {if eq(ezini('SelectedMenu','LeftMenu','menu.ini'),'')}
                {set maincontentstyle='maincontent-noleftmenu'}
            {/if}

            {if ezini('Toolbar_right','Tool','toolbar.ini')|count|eq(0)}
                {if $maincontentstyle|eq('noleftmenu')}
                    {set maincontentstyle='maincontent-nomenus'}
                    {else}
                    {set maincontentstyle='maincontent-norightmenu'}
                {/if}
            {/if}

            <div id="maincontent" class="{$maincontentstyle}">
                <div id="fix">
                    <div id="maincontent-design">
            {/let}

        {/cache-block}

                        {$module_result.content}
                    </div>{* id="maincontent-design" *}

                </div>{* id="fix" *}
                <div class="break"></div>
            </div>{* id="maincontent" *}
    </div>{* id="columns" *}

    <div id="toolbar-bottom">
        <div class="toolbar-design">
            &nbsp;
            <div class="break"></div>
        </div>{* id="toolbar-design" *}


        <div id="footer">
            <div id="footer-design">

                <address>{"Powered by %linkStartTag eZ Publish&reg;&trade; open source content management system %linkEndTag and development framework."|i18n("design/base",,hash('%linkStartTag',"<a href='http://ez.no'>",'%linkEndTag',"</a>" ))}<br />{ezini('SiteSettings','MetaDataArray','site.ini').copyright}
                </address>

            </div>{* id="footer-design" *}
        </div>{* id="footer" *}

        <div class="break"></div>
    </div>{* id="toolbar-bottom" *}
</div>{* id="allcontent" *}

<!--DEBUG_REPORT-->

</body>
</html>

