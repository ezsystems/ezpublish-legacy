{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

{let pagedesign=fetch_alias(by_identifier,hash(attr_id,intranet888))}

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    {*<link rel="stylesheet" type="text/css" href={"stylesheets/intranet_red.css"|ezdesign} />*}
    <link rel="stylesheet" type="text/css" href="/{$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")}" />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

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

{* Top box START *}
<form action={"/content/search/"|ezurl} method="get">

<table class="layout" width="100%" cellpadding="3" cellspacing="0" border="0">
<tr>
    <td class="logo" background={"06_intranet_background_repeat.png"|ezimage}>
	<a href="/"><img src={$pagedesign.data_map.image.content[original].full_path|ezroot} /></a>
    </td>
    <td class="topbox" align="left" width="15%" valign="bottom" background={"06_intranet_background_repeat.png"|ezimage}>
       &nbsp;<br />
	<input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="" />  <br />
	<a class="topline" href={"/content/advancedsearch/"|ezurl}><span class="small">{"Advanced search"|i18n("design/standard/layout")}</span></a><div class="labelbreak"></div>
    </td>
    <td class="topbox" valign="top" width="10%" background={"06_intranet_background_repeat.png"|ezimage}>
       &nbsp;<br />
	<input class="button" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
    </td>
    <td class="topline" align="right" width="40%" background={"06_intranet_background_repeat.png"|ezimage} >
    <img src={"06_intranet_logo.png"|ezimage} />
    </td>
</tr>
</table>

</form>

{* Top box END *}

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="pathline" colspan="2">

{* Top menu START *}

{include uri="design:top_menu.tpl"}

{* Top menu END *}

{* Main path START *}

{cache-block keys=array('path',$uri_string)}
 <p class="path">
    &nbsp;
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a class="path" href={$Path:item.url|ezurl}>{$Path:item.text|shorten(18)|wash}</a>
        {section-else}
        {$Path:item.text|wash}
        {/section}

        {delimiter}
        <span class="slash">/</span>
        {/delimiter}
    {/section}
    &nbsp;</p>
{/cache-block}

{* Main path END *}

    </td>
</tr>
<tr>
    <td width="120" valign="top" style="padding-right: 4px;">

{* Left menu START *}
{cache-block keys=array('left_menu',$uri_string)}
{include uri="design:left_menu.tpl"}
{/cache-block}

{* Left menu END *}

    </td>
    <td class="mainarea" width="99%" valign="top">

{* Main area START *}

{include uri="design:page_mainarea.tpl"}

{* Main area END *}

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

<!--DEBUG_REPORT-->

</body>

{/let}
</html>
