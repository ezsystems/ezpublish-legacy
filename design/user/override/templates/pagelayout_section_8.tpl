{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/mysite.css"|ezdesign} />
{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}
{section name=HTTP loop=$site.http_equiv}
<meta http-equiv="{$HTTP:key}" content="{$HTTP:item}" />

{/section}

<!-- Meta information START -->

{section name=meta loop=$site.meta}
<meta name="{$meta:key}" content="{$meta:item}" />

{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="generator" content="eZ publish" />

<!-- Meta information END -->

</head>

<body>

<img src={"toppmeny.gif"|ezimage} alt="" border="" USEMAP="#map" />

<MAP NAME="map">
<AREA SHAPE="RECT" COORDS="1,1,71,25" href={"content/view/full/26/"|ezurl}>
<AREA SHAPE="RECT" COORDS="73,0,156,24" href={"content/view/full/159/"|ezurl}>
<AREA SHAPE="RECT" COORDS="157,0,228,23" href={"content/view/full/62/"|ezurl}>
<AREA SHAPE="RECT" COORDS="229,0,299,24" href={"content/view/full/200/"|ezurl}>
<AREA SHAPE="RECT" COORDS="300,0,372,24" href={"content/view/full/32/"|ezurl}>
<AREA SHAPE="RECT" COORDS="374,1,448,24" href={"content/view/full/210/"|ezurl}>
<AREA SHAPE="RECT" COORDS="450,1,523,24" href={"content/view/full/82/"|ezurl}>
</MAP>

{let page_list=fetch(content,list,hash(parent_node_id,210,sort_by,array(array(published,false())),limit,10))}

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
     {section name=Path loop=$module_result.path offset=2 show=eq($DesignKeys:used.viewmode,'full')}
        {section show=$Path:item.url}
        <a href="{$Path:item.url}">{$Path:item.text}</a>
        {section-else}
	    {$Path:item.text}
        {/section}

        {delimiter}
        /
        {/delimiter}
    {section-else}
     {section name=Path loop=$module_result.path}
        {section show=$Path:Path:item.url}
        <a href="{$Path:item.url}">{$Path:Path:item.text}</a>
        {section-else}
	    {$Path:Path:item.text}
        {/section}

        {delimiter}
        /
        {/delimiter}
     {/section}
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
    {section name=Page loop=$page_list}
    <tr>
        <td>
        <a class="menuitem" href={concat("/content/view/full/",$Page:item.node_id,"/")|ezurl}>{$Page:item.name}</a>
        </td>
    </tr>    
    {/section}
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

{* Main area end *}

    </td>
    <td class="spacer" width="1%">
    <img src={"1x1.gif"|ezimage} width="16" height="1" alt="" border="0" /><br />
    </td>
</tr>
<tr>
    <td class="spacer" colspan="5">
    <img src={"1x1.gif"|ezimage} width="1" height="16" alt="" border="0" /><br />
    </td>
</tr>
</table>

{/let}
</body>
</html>
