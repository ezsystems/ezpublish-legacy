<form method="post" action={"/user/login/"|ezurl}>

<h1 class="top">Login</h1>

{section show=$User:warning.bad_login}
<div class="warning">
<h3 class="warning">Could not login</h3>
<ul class="warning">
    <li>A valid username and password is required to login.</li>
</ul>
</div>
{/section}

<div class="block">
  <label for="id1">{"Login"|i18n('logon')}:</label><div class="labelbreak"></div>
  <input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login}" />
</div>
<div class="block">
  <label for="id2">{"Password"|i18n('logon')}:</label><div class="labelbreak"></div>
  <input class="halfbox" type="password" size="10" name="Password" id="id2" value="" />
</div>
<div class="buttonblock">
  <input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('logon','Button')}" />
</div>
<div class="buttonblock">
  <input class="button" type="submit" name="RegisterButton" value="{'Sign Up'|i18n('signup','Button')}" />
</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri}" />

</form>
