{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<div align="center">
  <h1>{"Database initialization"|i18n("design/standard/setup/init")}</h1>
</div>

{section show=$db_error}
  <blockquote class="error">
  <p>
      {$db_error.text|wash}
  </p>
  </blockquote>
{/section}

{section show=eq( $db_not_empty, 1 )}
<h2>{"Warning"|i18n("design/standard/setup/init")}</h2>
<p>
 {"Your database already contains data."|i18n("design/standard/setup/init")}
 {"The setup can continue with the initialization but may damage the present data."|i18n("design/standard/setup/init")}
</p>
<p>
 {"What do you want the setup to do?"|i18n("design/standard/setup/init")}
</p>

<blockquote class="note">
<p>
 <b>{"Note:"|i18n("design/standard/setup/init")}</b>
 {"The setup will not do an upgrade from older eZ publish versions (such as 2.2.7) if you leave the data as it is. This is only meant for people who have existing data that they don't want to lose. If you have existing eZ publish 3.0 data (such as from an RC release) you should skip DB initialization, however you will then need to do a manual upgrade."|i18n("design/standard/setup/init")}
</p>
</blockquote>

<div class="input_highlight">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
 <td class="normal">
  <p>{"Continue but leave the data as it is."|i18n("design/standard/setup/init")}</p>
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
  <p>{"Continue but remove the data first."|i18n("design/standard/setup/init")}</p>
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="2"  checked="checked" />
 </td>
</tr>
<tr>
 <td class="normal">
  <p>{"Keep data and skip database initialization."|i18n("design/standard/setup/init")}</p>
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="3" />
 </td>
</tr>
<tr>
 <td class="normal">
  <p>{"Let me choose a new database."|i18n("design/standard/setup/init")}</p>
 </td>
 <td class="normal">
  <input type="radio" name="eZSetupDatabaseDataChoice" value="4" />
 </td>
</tr>
</table>
</div>
{/section}



<p>
 {"We're now ready to initialize the database. The basic structure will be initialized. To start the initialization, please enter the relevant information in the boxes below, and the password you want on the database and click the"|i18n("design/standard/setup/init")} <i>&gt;&gt;</i> {"button."|i18n("design/standard/setup/init")}
</p>
<p>{"If you have an already existing eZ publish database enter the information and the setup will use that as database."|i18n("design/standard/setup/init")}</p>

<blockquote class="note">
<p>
 <b>{"Note"|i18n("design/standard/setup/init")}:</b>{"This step requires that a database has been created with a valid user.
 Please consult the manual for your database to figure out how to create a database and user."|i18n("design/standard/setup/init")}
</p>
</blockquote>

<div class="input_highlight">
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
  <td class="normal">{"Username"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" name="eZSetupDatabaseUser" size="16" value="{$database_info.user}" /></td>
</tr>


<tr>
  <td class="normal">{"Password"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" name="eZSetupDatabasePassword" size="16" value="{$database_info.password}" /></td>
</tr>
</table>
</div>

  <div class="buttonblock">
    <input class="defaultbutton" type="submit" name="StepButton" value="{'Connect To Database'|i18n('design/standard/setup/init')} &gt;&gt;" />
  </div>
  {include uri="design:setup/init/steps.tpl"}
  {include uri="design:setup/persistence.tpl"}
</form>
