{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 The database is ready for initialization, click the <i>Create Database</i> when ready.
</p>
{section show=$database_info.info.has_demo_data}
<p>
 If you wish the setup can add some demo data to your database, the demo data will
 give a good demonstration of the capabilites of eZ publish {$#version.text}.
 First time users are adviced to install the demo data.
</p>
<div class="highlight">
<p>
 Install demo data?
 <input type="checkbox" name="eZSetupDemoData" value="1" {section show=$demo_data.use}checked="checked"{/section} />
<p>
</div>
{section-else}
 <input type="hidden" name="eZSetupDemoData" value="0" />
{/section}

{section show=$database_status.error}
<div class="error">
<p>
{section show=$demo_status|not}
  <h2>Demo data failure</h2>
  <ul>
    <li>Could not unpack the demo data.</li>
    <li>You should try to install without demo data.</li>
  </ul>
{section-else}
  <h2>Initialization failed</h2>
  <ul>
    <li>The database could not be properly initialized.</li>
    <li>{$database_status.error.text}</li>
    <li>{$database_info.info.name} Error #{$database_status.error.number}</li>
  </ul>
{/section}
</p>
</div>
{/section}

{section show=$database_info.table.is_empty|not}
<h1>Warning</h1>
<p>
 Your database already contains data.
 The setup can continue with the initialization but may damage the present data.
</p>
<p>
 What do you want the setup to do?
</p>

<div class="highlight">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
 <td class="normal">
  <p>Continue but leave the data as it is.</p>
 </td>
 <td rowspan="4" class="normal">
  &nbsp;&nbsp;
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="1" />
 </td>
</tr>
<tr>
 <td class="normal">
  <p>Continue and remove the data.</p>
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="2"  checked="checked" />
 </td>
</tr>
<tr>
 <td class="normal">
  <p>Continue and skip database initialization.</p>
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="3" />
 </td>
</tr>
<tr>
 <td class="normal">
  <p>Let me choose a new database.</p>
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="4" />
 </td>
</tr>
</table>
</div>

{/section}


<blockquote class="note">
<p>
 <b>Note:</b>
 It can take some time creating the database so please be patient and wait until the new page is finished.
</p>
</blockquote>


  <div class="buttonblock">
    <input type="hidden" name="eZSetupDatabaseReady" value="" />
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_8" value="Create Database" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
