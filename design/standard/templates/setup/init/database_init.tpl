{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 We're now ready to initialize the database, the database will be created and the basic structure initialized.
 To start the initialization enter the password you want on the database and click the <i>Create Database</i> button.
</p>

{section show=$error}
<div class="error">
<p>
{switch match=$error}
 {case match=1}
  <h2>Empty password</h2>
  <ul>
    <li>You must supply a password for the database.</li>
  </ul>
 {/case}
 {case match=2}
  <h2>Password does not match</h2>
  <ul>
    <li>The password and confirmation password must match.</li>
  </ul>
 {/case}
 {case}
  <h2>Unknown error</h2>
 {/case}
{/switch}
</p>
</div>
{/section}

<div class="highlight">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <th class="normal" colspan="3">Database:</th>
</tr>
<tr>
  <td class="normal">Type:</td>
  <td rowspan="6" class="normal">&nbsp;&nbsp;</td>
  <td class="normal">
  {$database_info.info.name}
  </td>
</tr>
<tr>
  <td class="normal">Server:</td>
  <td class="normal">{$database_info.server}</td>
</tr>
<tr>
  <td class="normal">Name:</td>
  <td class="normal">{$database_info.name}</td>
</tr>
<tr>
  <td class="normal">Username:</td>
  <td class="normal">{$database_info.user}</td>
</tr>
<tr>
  <td class="normal">Password:</td>
  <td class="normal"><input type="password" name="eZSetupDatabasePassword" size="16" value="" /></td>
</tr>
<tr>
  <td class="normal">Confirm password:</td>
  <td class="normal"><input type="password" name="eZSetupDatabasePasswordConfirm" size="16" value="" /></td>
</tr>
</table>
</div>

<blockquote class="note">
<p>
 <b>Note:</b>
 It can take some time creating the database so please be patient and wait until the new page is finished.
</p>
</blockquote>


  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_8" value="Create Database" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
