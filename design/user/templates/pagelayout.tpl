{*?template charset=utf8?*}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />

{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
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

<map name="map">
<area SHAPE="RECT" COORDS="2,1,103,27" href={"content/view/full/159/"|ezurl} />
<AREA SHAPE="RECT" COORDS="104,0,175,24" href={"content/view/full/32/"|ezurl} />
<AREA SHAPE="RECT" COORDS="177,2,245,23" href={"content/view/full/26/"|ezurl} />
<AREA SHAPE="RECT" COORDS="248,3,317,24" href={"content/view/full/82/"|ezurl} />
<AREA SHAPE="RECT" COORDS="320,3,392,23" href={"content/view/full/62/"|ezurl} />
<AREA SHAPE="RECT" COORDS="393,3,472,23" href={"content/view/full/200/"|ezurl} />
</map>

<h2>Unfinished design</h2>
<p>eZ publish will default to this design when loosing section information
this will not happen in the following beta releases. So, don't panic.
</p>

{$module_result.content}

</body>
</html>

