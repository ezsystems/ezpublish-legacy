{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>We're now ready to setup the database, please enter the relevant information in the boxes below.</p>
<p>If you have an already existing eZ publish database enter the information and the setup will use that as database.</p>
<blockquote class="note">
<p><b>Note:</b> If unsure of what information to enter just leave the fields empty and the setup will give som sensible default data.</p>
</blockquote>

<div class="highlight">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <th class="normal" colspan="3">Database:</th>
</tr>
<tr>
  <td class="normal">Type:</td>
  <td rowspan="7" class="normal">&nbsp;&nbsp;</td>
  <td class="normal">
  {section show=$database_list|gt(1)}
    <select name="eZSetupDatabaseType">
    {section name=DB loop=$database_list}
      <option value="{$:item.type}">{$:item.name}</option>
    {/section}
    </select>
  {section-else}
    <b>{$database_list[0].name}</b>
    <input type="hidden" name="eZSetupDatabaseType" value="{$database_list[0].type}" />
  {/section}
  </td>
</tr>
<tr>
  <td class="normal">Server:</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseServer" size="25" value="{$database_info.server}" /></td>
</tr>
<tr>
  <td class="normal">Name:</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseName" size="25" value="{$database_info.name}" maxlength="60" /></td>
</tr>
<tr>
  <td class="normal">Username:</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseUser" size="25" value="{$database_info.user}" /></td>
</tr>
</table>
</div>


    <div class="buttonblock">
      <input type="hidden" name="ChangeStepAction" value="" />
      <input class="button" type="submit" name="StepButton_5" value="Language Options" />
    </div>
    {include uri='design:setup/persistence.tpl'}
  </form>
