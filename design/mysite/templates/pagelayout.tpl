{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>
<link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
<link rel="stylesheet" type="text/css" href={"stylesheets/mysite.css"|ezdesign} />

{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

</head>

<body>

{include uri="design:top_menu.tpl"}


<table class="mainlayout" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="maintop" width="1%" background={"mysite-top_repeat.gif"|ezimage}>
    <img src={"mysite-top_left.gif"|ezimage} width="180" height="69" alt="MySite" border="0" /><br />
    </td>
    <td class="maintop" width="98%" background={"mysite-top_repeat.gif"|ezimage}>
    &nbsp;
    </td>
    <td class="maintop" width="1%" background={"mysite-top_repeat-frontpage.gif"|ezimage}>
    <img src={"mysite-top_right.gif"|ezimage} width="80" height="69" alt="" border="0" /><br />
    </td>
</tr>
</table>

<table class="mainlayout" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="topedge">
    <img src={"1x1.gif"|ezimage} width="1" height="8" alt="" border="0" /><br />
    </td>
</tr>
<tr>
    <td class="image" align="center">
    <img src={"mysite-illustration.jpg"|ezimage} width="700" height="55" alt="" border="0" /><br />
    </td>
</tr>
<tr>
    <td class="path">
    &gt;
     {section name=Path loop=$module_result.path}
        {if $:item.url}
        <a href={$:item.url|ezurl}>{$:item.text|wash}</a>
        {else}
	    {$Path:item.text|wash}
        {/if}

        {delimiter}
        /
        {/delimiter}
    {/section}
    </td>
</tr>
</table>

<table class="mainlayout" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="edge" rowspan="3" width="1%">
    <img src={"1x1.gif"|ezimage} width="16" height="1" alt="" border="0" /><br />
    </td>
    <td class="spacer" colspan="3">
    <img src={"1x1.gif"|ezimage} width="1" height="16" alt="" border="0" /><br />
    </td>
    <td class="rightcolumn" valign="top" rowspan="3" width="1%">
    
    <table class="menu" width="100%" cellspacing="0" cellpadding="0" border="0">
    {let page_list=fetch( content, list, hash( 
                          parent_node_id, 2,
                          sort_by,array( array( published, false() ) ),
			  limit, 10 ) )}

    {section name=Page loop=$page_list}
    <tr>
        <td>
        <a class="menuitem" href={concat("/content/view/full/",$Page:item.node_id,"/")|ezurl}>{$Page:item.name|wash}</a>
        </td>
    </tr>    
    {/section}

    {/let}
    </table>    
    
    <img src={"1x1.gif"|ezimage} width="100" height="1" alt="" border="0" /><br />
    </td>
</tr>
<tr>
    <td class="spacer" width="1%">
    <img src={"1x1.gif"|ezimage} width="16" height="1" alt="" border="0" /><br />
    </td>
    <td class="maincontent" valign="top" width="96%">

{* Main area start *}

{$module_result.content}
{cache-block}
{* Main area end *}

    </td>
    <td class="spacer" width="1%">
    <img src={"1x1.gif"|ezimage} width="16" height="1" alt="" border="0" /><br />
    </td>
</tr>
<tr>
    <td class="spacer" colspan="5">
    <img src={"1x1.gif"|ezimage} width="1" height="3" alt="" border="0" /><br />
   <center><a href="http://ez.no/developer"><img src={"powered-by-ezpublish-100x35-grey.gif"|ezimage} alt="eZ Publish" border="0" width="100" height="35" /></a></center>
    <img src={"1x1.gif"|ezimage} width="1" height="3" alt="" border="0" /><br />
    </td>
</tr>
</table>
{/cache-block}

</body>
</html>
