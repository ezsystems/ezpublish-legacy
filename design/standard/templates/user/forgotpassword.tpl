{section show=$link}
<p>
{"A mail has been send to the following e-mail address: %1. This e-mail contains a link you need to click so that we can confirm that the correct user is getting the new password."|i18n('design/standard/user/forgotpassword',,array($email))}
</p>
{section-else}
   {section show=$wrong_email}
   <div class="warning">
   <h2>{"There is no registered user with that e-mail address."|i18n('design/standard/user/forgotpassword')}</h2>
   </div>
   {/section}
   {section show=$generated}
   <p>
   {"Password was successfully generated and sent to: %1"|i18n('design/standard/user/forgotpassword',,array($email))}
   </p>
   {section-else}
      {section show=$wrong_key}
      <div class="warning">
      <h2>{"The key is invalid or has been used. "|i18n('design/standard/user/forgotpassword')}</h2>
      </div>
      {section-else}
      <form method="post" name="forgotpassword" action={"/user/forgotpassword/"|ezurl}>
      <div class="maincontentheader">
      <h1>{"Have you forgotten your password?"|i18n('design/standard/user/forgotpassword')}</h1>
      </div>

      <p>
      {"If you have forgotten your password we can generate a new one for you. All you need to do is to enter your e-mail address and we will create a new password for you."|i18n('design/standard/user/forgotpassword')}
      </p>
    
      <div class="block">
      <label for="email">{"E-mail"|i18n('design/standard/user/forgotpassword')}:</label>
      <div class="labelbreak"></div>
      <input class="halfbox" type="text" name="UserEmail" size="40" value="{$wrong_email}" />
      </div>

      <div class="buttonblock">
      <input class="button" type="submit" name="GenerateButton" value="{'Generate new password'|i18n('design/standard/user/forgotpassword')}" />
      </div>
      </form>
      {/section}
   {/section}
{/section}
