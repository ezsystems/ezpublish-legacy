{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

<p>
 Email is used for sending out important notices such as user registration, content approval
 and used to send the site registration.
</p>
{section show=eq($system.type,"unix")}
<p>
 You can choose from either <i>sendmail</i> which must available on the server or <i>SMTP</i> which
 will relay the emails. If unsure what to use ask your webhost, some webhosts does not
 support <i>sendmail</i>.
</p>
{/section}


<div class="highlight">
<table cellspacing="0" cellpadding="0" border="0">
{section show=eq($system.type,"unix")}
<tr>
  <td class="normal">
   sendmail
  </td>
  <td align="right" class="normal">
    <input type="radio" name="eZSetupEmailTransport" value="1" checked="checked" />
  </td>
</tr>
<tr>
  <td colspan="2" class="normal">
   <p>Configuration of sendmail is done on the server, consult your webhost.</p>
  </td>
</tr>
<tr>
  <td colspan="2" class="normal">&nbsp;</d>
</tr>
{/section}
<tr>
  <td class="normal">
   SMTP
  </td>
  <td align="right" class="normal">
{section show=eq($system.type,"unix")}
    <input type="radio" name="eZSetupEmailTransport" value="2" />
{section-else}
    <input type="hidden" name="eZSetupEmailTransport" value="2" />
    &nbsp;
{/section}
  </td>
</tr>
<tr>
  <td colspan="2" class="normal">
   <p>Email transport by SMTP requires a server name. If the server requires authentication
   you must enter a username and password as well.</p>
  </td>
</tr>
<tr>
  <td class="normal">Server name</td>
  <td class="normal"><input type="text" size="16" name="eZSetupSMTPServer" value="{$email_info.server}"></td>
</tr>
<tr>
  <td class="normal">User name</td>
  <td class="normal"><input type="text" size="16" name="eZSetupSMTPUser" value="{$email_info.user}"></td>
</tr>
<tr>
  <td class="normal">Password</td>
  <td class="normal"><input type="password" size="16" name="eZSetupSMTPPassword" value="{$email_info.password}"></td>
</tr>
</table>
</div>

  <div class="buttonblock">
    <input type="hidden" name="ChangeStepAction" value="" />
    <input class="button" type="submit" name="StepButton_10" value="Site Details" />
  </div>
  {include uri='design:setup/persistence.tpl'}
</form>
