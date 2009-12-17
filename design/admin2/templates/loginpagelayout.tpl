<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>
{cache-block keys=array( $navigation_part.identifier, $module_result.navigation_part, $ui_context, $ui_component )}{* Pr tab cache *}

{include uri='design:page_head.tpl'}


{include uri='design:page_head_style.tpl'}
{include uri='design:page_head_script.tpl'}

</head>

<body>

<div id="page">
<div id="header">
<div id="header-design" class="float-break">

    <div id="header-search">
    <form action={'/content/search/'|ezurl} method="get">
            <select name="SubTreeArray" title="{'Search location, to be able to narrow down the search results!'|i18n('design/admin/pagelayout')}" disabled="disabled">
                <option value="1" title="{'Search everthing!'|i18n( 'design/admin/pagelayout' )}">{'Everything'|i18n( 'design/admin/pagelayout' )}</option>
            </select>
            <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" disabled="disabled" title="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
            <input id="searchbutton" class="button-disabled" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
            <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
    </form>
    </div>

    <div id="header-logo">
        <a href="http://ez.no" title="eZ Publish {fetch( 'setup', 'version' )}" target="_blank">&nbsp;</a>
    </div>


    <div id="header-usermenu">
        <span title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}" id="header-usermenu-logout" class="disabled">{'Logout'|i18n( 'design/admin/pagelayout' )}</span>
    </div>

    <div id="header-topmenu">
        &nbsp;
    </div>

</div>
</div>

<hr class="hide" />

<div id="path">
<div id="path-design">
    &nbsp;
</div>
</div>


<hr class="hide" />

<div id="columns">

<div id="leftmenu">
<div id="leftmenu-design">

</div>
</div>

<hr class="hide" />

<div id="rightmenu">
<div id="rightmenu-design">

</div>
</div>

{/cache-block}
<hr class="hide" />

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">

{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

</div>
<div class="break"></div>
</div></div>

<div class="break"></div>
</div>

<hr class="hide" />

<div id="footer">
<div id="footer-design">

{include uri="design:page_copyright.tpl"}

</div>
</div>

<div class="break"></div>
</div>

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->

</body>
</html>
