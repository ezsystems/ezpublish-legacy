{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>{$site.title} - {section name=Path loop=$module_result.path}{$Path:item.text}{delimiter} / {/delimiter}{/section}</title>

    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/setup.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />
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

<body style="background: url(/design/standard/images/grid-background.gif);">

<!-- Top box START -->

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="topline" width="40%">
    <img src={"ezpublish-logo.gif"|ezimage} width="210" height="60" alt="" />
    </td>
</tr>
</table>

<!-- Top box END -->

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="pathline" colspan="2">

<!-- Main path START -->

<table class="path" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="bullet" width="1">
    <img src={"bullet.gif"|ezimage} width="12" height="12" alt="" align="middle" hspace="2" /><br />
    </td>
    <td width="99%">
    <p class="path">
    {section name=Path loop=$module_result.path}
        {section show=$Path:item.url}
        <a class="path" href={$Path:item.url|ezurl}>{$Path:item.text}</a>
        {section-else}
        {$Path:item.text}
        {/section}

        {delimiter}
        <span class="slash">/</span>
        {/delimiter}
    {/section}
    &nbsp;</p>
    </td>
</tr>
</table>

<!-- Main path END -->

    </td>
</tr>
<tr>
    <td width="120" valign="top" style="padding-right: 4px;">

<!-- Left menu START -->

{* {include uri="design:left_menu.tpl"} *}

{let setup_info=$module_result.setup_info
     setup_steps=$setup_info.steps}
<table class="menuboxleft" width="120" cellpadding="1" cellspacing="0" border="0">
<tr>
    <th class="menuheaddark" colspan="2">
    <p class="menuhead">{$setup_info.part.description} steps</p>
    </th>
</tr>
{section name=Step loop=$setup_info.main_steps}
<tr>
    <td class="bullet" width="1">
    <img src={$:item.type|choose("bullet_grey.gif","bullet_mark.gif","bullet.gif")|ezimage} width="12" height="12" alt="" /><br />
    </td>
    <td class="menu" width="99%">
    <p class={$:item.type|choose("disabled","current","enabled")}>{section show=$:item.name_id}{$setup_steps[$:item.name_id].description}{section-else}{$:item.name}{/section}</p>
    </td>
</tr>
{/section}
<tr>
  <td class="menu" colspan="2">
    <form method="post" action="{$script}">
      <div class="buttonblock">
        <input type="hidden" name="ChangeStepAction" value="" />
        <input class="menubutton" type="submit" name="StepButton_1" value="{'Restart'|i18n('design/standard/setup')}" />
      </div>
    </form>
  </td>
</tr>
</table>
{/let}

<!-- Left menu END -->

    </td>
    <td class="mainarea" width="99%" valign="top">

<!-- Main area START -->

{$module_result.content}

<!-- Main area END -->

    </td>
</tr>
</table>

<div align="center" style="padding-top: 0.5em;">
<p class="small"><a href="http://developer.ez.no">eZ publish&trade;</a> copyright &copy; 1999-2002 <a href="http://ez.no">eZ systems as</a></p>
</div>

<!--DEBUG_REPORT-->

</body>
</html>
