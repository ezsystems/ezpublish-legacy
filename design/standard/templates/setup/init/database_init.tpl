{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

{section show=$database_status}
<div class="error">
<p>
{section show=$demo_status|not}
  <h2>{"Demo data failure"|i18n("design/standard/setup/init")}</h2>
  <ul>
    <li>{"Could not unpack the demo data."|i18n("design/standard/setup/init")}</li>
  </ul>
{section-else}

{section show=$database_status.connected|not}
  <h2>{"No database connection"|i18n("design/standard/setup/init")}</h2>
  <ul>
    <li>{"Could not connect to database."|i18n("design/standard/setup/init")}</li>
    <li>{$database_status.error.text}</li>
    <li>{$database_info.info.name} Error #{$database_status.error.number}</li>
  </ul>
{/section}

{/section}

</p>
</div>

<p>
 {"The database would not accept the connection , please review your settings and try again."|i18n("design/standard/setup/init")}
</p>
{include uri=concat('design:setup/db/',$database_info.info.type,'_connection_error.tpl')}

{section-else}

<p>
 {"We're now ready to initialize the database. The basic structure will be initialized. To start the initialization, please enter the relevant information in the boxes below, and the password you want on the database and click the"|i18n("design/standard/setup/init")} <i>{"Connect To Database"|i18n("design/standard/setup/init")}</i> {"button."|i18n("design/standard/setup/init")}
</p>
<p>{"If you have an already existing eZ publish database enter the information and the setup will use that as database."|i18n("design/standard/setup/init")}</p>

<blockquote class="note">
<p>
 <b>Note:</b>This step requires that a database has been created with a valid user.
 Please consult the manual for your database to figure out how to create a database and user.
</p>
</blockquote>

{section show=$error}
<div class="error">
<p>
{switch match=$error}
 {case match=1}
  <h2>{"Empty password"|i18n("design/standard/setup/init")}</h2>
  <ul>
    <li>{"You must supply a password for the database."|i18n("design/standard/setup/init")}</li>
  </ul>
 {/case}
 {case match=2}
  <h2>{"Password does not match"|i18n("design/standard/setup/init")}</h2>
  <ul>
    <li>{"The password and confirmation password must match."|i18n("design/standard/setup/init")}</li>
  </ul>
 {/case}
 {case}
  <h2>{"Unknown error"|i18n("design/standard/setup/init")}</h2>
 {/case}
{/switch}
</p>
</div>
{/section}

{/section}

<div class="highlight">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <th class="normal" colspan="3">Database</th>
</tr>
<tr>
  <td class="normal">{"Type"|i18n("design/standard/setup/init")}</td>
  <td rowspan="{eq($database_info.info.driver,'ezmysql')|choose(8,9)}" class="normal">&nbsp;&nbsp;</td>
  <td class="normal">
  {$database_info.info.name}
  </td>
</tr>
<tr>
  <td class="normal">{"Driver"|i18n("design/standard/setup/init")}</td>
  <td class="normal">
  {$database_info.info.driver}
  </td>
</tr>
<tr>
  <td class="normal">{"Unicode support"|i18n("design/standard/setup/init")}</td>
  <td class="normal">
  {$database_info.info.supports_unicode|choose("no","yes")}
  </td>
</tr>

<tr>
  <td class="normal">{"Servername"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseServer" size="16" value="{$database_info.server}" /></td>
</tr>
{section show=eq($database_info.info.driver,'ezmysql')}
<tr>
  <td class="normal">{"Socket"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseSocket" size="16" value="{$database_info.socket}" /></td>
</tr>
{/section}
<tr>
  <td class="normal">{"Databasename"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseName" size="16" value="{$database_info.name}" maxlength="60" /></td>
</tr>
<tr>
  <td class="normal">{"Username"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseUser" size="16" value="{$database_info.user}" /></td>
</tr>


<tr>
  <td class="normal">{"Password"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="password" name="eZSetupDatabasePassword" size="16" value="{$database_info.password}" /></td>
</tr>
<tr>
  <td class="normal">{"Confirm password"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="password" name="eZSetupDatabasePasswordConfirm" size="16" value="{$database_info.password}" /></td>
</tr>
</table>
</div>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="defaultbutton" type="submit" name="StepButton_8" value="{'Connect To Database'|i18n('design/standard/setup/init')} >>" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
