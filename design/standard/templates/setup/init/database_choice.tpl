{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<h1>{"Choose database"|i18n("design/standard/setup/init")}</h1>
<p>
 {"We detected both MySQL and PostgreSQL support on your system. Which database system would you like to use?"|i18n("design/standard/setup/init")}
</p>

<div class="input_highlight">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <th class="normal" colspan="3">Database:</th>
</tr>
{section name=DB loop=$database_list}
<tr>
  <td class="normal">{"Type"|i18n("design/standard/setup/init")}</td>
  <td rowspan="3" class="normal">&nbsp;&nbsp;</td>
  <td class="normal">{$:item.name}</td>
  <td rowspan="3" class="normal">&nbsp;&nbsp;</td>
  <td class="normal" rowspan="3" valign="top">
  {section show=$database_list|gt(1)}
   <input type="radio" name="eZSetupDatabaseType" value="{$:item.type}" {section show=eq($:number,1)}checked="checked" {/section}/>
  {section-else}
   <input type="hidden" name="eZSetupDatabaseType" value="{$:item.type}" />
  {/section}
  {include uri="design:setup/init/steps.tpl"}
  </td>
</tr>
<tr>
  <td class="normal">{"Driver"|i18n("design/standard/setup/init")}</td>
  <td class="normal">{$:item.driver}</td>
</tr>
<tr>
  <td class="normal">{"Unicode support"|i18n("design/standard/setup/init")}</td>
  <td class="normal">{$:item.supports_unicode|choose("no"|i18n("design/standard/setup/init"),"yes"|i18n("design/standard/setup/init"))}</td>
</tr>
{/section}
</table>
</div>


    <div class="buttonblock">
      <input class="defaultbutton" type="submit" name="StepButton" value="{'Language Options'|i18n('design/standard/setup/init')} &gt;&gt;" />
    </div>
    {include uri='design:setup/persistence.tpl'}
  </form>
