<form method="post" action={"/user/login/"|ezurl}>

<div class="maincontentheader">
<h1>Login</h1>
</div>

{section show=$User:warning.bad_login}
<div class="warning">
<h2>Could not login</h2>
<ul>
    <li>A valid username and password is required to login.</li>
</ul>
</div>
{/section}

<div class="block">
<label for="id1">{"Login"|i18n("design/standard/user")}:</label><div class="labelbreak"></div>
<input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login}" />
</div>
<div class="block">
<label for="id2">{"Password"|i18n("design/standard/user")}:</label><div class="labelbreak"></div>
<input class="halfbox" type="password" size="10" name="Password" id="id2" value="" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" />
<input class="button" type="submit" name="RegisterButton" value="{'Sign Up'|i18n('design/standard/user','Button')}" />
</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri}" />

</form>
