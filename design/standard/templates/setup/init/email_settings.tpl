{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<div align="center">
  <h1>{"E-mail settings"|i18n("design/standard/setup/init")}</h1>
</div>

<p>
  {"Specify email settings for eZ publish"|i18n("design/standard/setup/init")}
</p>

{section show=eq($system.type,"unix")}
<p>
 {"You can choose from either"|i18n("design/standard/setup/init")} <i>{"sendmail"|i18n("design/standard/setup/init")}</i> {"which must be available on the server or"|i18n("design/standard/setup/init")} <i>{"SMTP"|i18n("design/standard/setup/init")}</i> {"which will relay the emails. If unsure what to use ask your webhost, some webhosts do not support"|i18n("design/standard/setup/init")} <i>{"sendmail"|i18n("design/standard/setup/init")}</i>.
</p>
{section-else}
<p>
{"SMTP is recommended for MS Windows users"|i18n("design/standard/setup/init")}
</p>
{/section}

<p>

<form method="post" action="{$script}">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <th class="normal" colspan="3">{"Mail system"|i18n("design/standard/setup/init")}:</th>
</tr>
{section show=eq($system.type,"unix")}
<tr>
  <td class="normal">
    <input type="radio" name="eZSetupEmailTransport" value="1" checked="checked" />
    {"sendmail"|i18n("design/standard/setup/init")}
  </td>
</tr>
{/section}
<tr>
  <td class="normal">
    {section show=eq($system.type,"unix")}
      <input type="radio" name="eZSetupEmailTransport" value="2" />
    {section-else}
      <input type="hidden" name="eZSetupEmailTransport" value="2" />
    {/section}
    {"SMTP"|i18n("design/standard/setup/init")}
  </td>
</tr>
<tr>
  <td class="normal">&nbsp;{"Server name"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" size="16" name="eZSetupSMTPServer" value="{$email_info.server}"></td>
</tr>
<tr>
  <td class="normal">&nbsp;{"User name"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="text" size="16" name="eZSetupSMTPUser" value="{$email_info.user}"></td>
</tr>
<tr>
  <td class="normal">&nbsp;{"Password"|i18n("design/standard/setup/init")}</td>
  <td class="normal"><input type="password" size="16" name="eZSetupSMTPPassword" value="{$email_info.password}"></td>
</tr>
</table>

  <div class="buttonblock">
    {include uri="design:setup/init/steps.tpl"}
    <input class="defaultbutton" type="submit" name="StepButton" value="{"Next"|i18n("design/standard/setup/init", "next button in installation")} &gt;&gt;" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>

</p>
