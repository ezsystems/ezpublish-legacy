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
    {section var=css_file loop=ezini( 'StylesheetSettings', 'CSSFileList', 'design.ini' )}
        @import url({concat( 'stylesheets/', $css_file )|ezdesign});
    {/section}
    {section var=css_file loop=ezini( 'StylesheetSettings', 'FrontendCSSFileList', 'design.ini' )}
        @import url({concat( 'stylesheets/', $css_file )|ezdesign});
    {/section}
</style>

{section name=JavaScript loop=ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ) }
    <script type="text/javascript" src={concat( 'javascript/',$:item )|ezdesign}></script>
{/section}
{section name=JavaScript loop=ezini( 'JavaScriptSettings', 'FrontendJavaScriptList', 'design.ini' ) }
    <script type="text/javascript" src={concat( 'javascript/',$:item )|ezdesign}></script>
{/section}

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

{cache-block keys=$uri_string}
    <div id="topcontent">

        {let pagedesign=fetch_alias(by_identifier,hash(attr_id,sitestyle_identifier))}
        <div id="header">
            <div id="header-design">
                {*<img src={"/images/t1/t1-logo-placeholder.gif"|ezdesign} height="70" width="211" alt="Company logo" />*}
                {if $pagedesign.data_map.image.content.is_valid|not()}
                    <h1><a href={"/"|ezurl}>{ezini('SiteSettings','SiteName')}</a></h1>
                {else}
                    <a href={"/"|ezurl}><img src={$pagedesign.data_map.image.content[logo].full_path|ezroot} alt="{$pagedesign.data_map.image.content[logo].text}" /></a>
                {/if}
            </div>{* id="header-design" *}
        </div>{* id="header" *}
        {/let}

{/cache-block}

    {if ezini('Toolbar_top','Tool','toolbar.ini')|count}
        <div id="toolbar-top">
            <div class="toolbar-design">
                {tool_bar name=top view=line}
            </div>{* id="toolbar-design" *}
             <div class="break"></div>
        </div>{* id="toolbar-top" *}
    {/if}

    {default current_user=fetch('user','current_user')}
    {cache-block keys=array($uri_string, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}
        <div class="break"></div>
    </div>{* id="topcontent" *}

    <hr class="hide" />

    {menu name=TopMenu}

    <hr class="hide" />
    {/cache-block}

    <div id="path">
        <div id="path-design">
            {include uri="design:parts/path.tpl"}
        </div>{* id="path-design" *}
    </div>{* id="path" *}

    {cache-block keys=array($uri_string, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}
    <hr class="hide" />

    <div id="columns">

        {menu name=LeftMenu}

        <hr class="hide" />
    {/cache-block}
    {/default}

        {if ezini( 'Toolbar_right', 'Tool', 'toolbar.ini' )|count}
            <div id="rightmenu">
                <div id="rightmenu-design">
                    <h3 class="hide">Right menu</h3>
                    <div id="toolbar-right">
                        <div class="toolbar-design">
                            {tool_bar name=right view=full}
                        </div>{* id="toolbar-design" *}
                    </div>{* id="toolbar-right" *}
                </div>{* id="rightmenu-design" *}
            </div>{* id="rightmenu" *}
        {/if}

        <hr class="hide" />

        {cache-block}
            {let maincontentstyle='maincontent-bothmenus'}

            {if eq(ezini('SelectedMenu','LeftMenu','menu.ini'),'')}
                {set maincontentstyle='maincontent-noleftmenu'}
            {/if}

            {if ezini('Toolbar_right','Tool','toolbar.ini')|count|eq(0)}
                {if $maincontentstyle|eq('maincontent-noleftmenu')}
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
                    <div class="break"></div>
                </div>{* id="fix" *}
            </div>{* id="maincontent" *}

            <div class="break"></div>
    </div>{* id="columns" *}

    <hr class="hide" />

    {if ezini('Toolbar_bottom','Tool','toolbar.ini')|count}
        <div id="toolbar-bottom">
            <div class="toolbar-design">
                {tool_bar name=bottom view=line}
            </div>{* id="toolbar-design" *}
            <div class="break"></div>
        </div>{* id="toolbar-bottom" *}
    {/if}

    <div id="footer">
        <div id="footer-design">

            <address>{"Powered by %linkStartTag eZ Publish&reg; open source content management system %linkEndTag and development framework."|i18n("design/base",,hash('%linkStartTag',"<a href='http://ez.no'>",'%linkEndTag',"</a>" ))}<br />{ezini('SiteSettings','MetaDataArray','site.ini').copyright}
            </address>

        </div>{* id="footer-design" *}
    </div>{* id="footer" *}

    <div class="break"></div>
</div>{* id="allcontent" *}

<!--DEBUG_REPORT-->

</body>
</html>
