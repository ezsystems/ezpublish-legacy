{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"/design/admin/stylesheets/admin.css"|root} />
    <link rel="stylesheet" type="text/css" href={"/design/admin/stylesheets/debug.css"|ezdesign} />

{include uri="design:page_head.tpl"}

</head>

<body>

{* Top box START *}
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="headlogo" width="360">
    {* Admin logo area *}
    <img src={"/design/admin/images/logo.gif"|ezroot} alt="" />&nbsp;</td>
    <td class="headlogo" width="100%">
    &nbsp;
    </td>
   <td class="headlogo" width="50%">
   &nbsp;</td>
</tr>
<tr>
    <td colspan="11" class="menuheadtoolbar">
    &nbsp;
    </td>
</tr>
</table>

{* Top box END *}

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="pathline" colspan="2">

{* Main path START *}

{include uri="design:page_toppath.tpl"}

{* Main path END *}

    </td>
</tr>
<tr>
    <td width="120" valign="top" style="padding-right: 4px;">

{* Left menu START *}

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
        <input class="menubutton" type="submit" name="StepButton_1" value="{'Restart'|i18n('design/standard/layout')}" />
      </div>
    </form>
  </td>
</tr>
</table>
{/let}

{* Left menu END *}

    </td>
    <td class="mainarea" width="99%" valign="top">

{* Main area START *}

{$module_result.content}

{* Main area END *}

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

<!--DEBUG_REPORT-->

</body>
</html>
