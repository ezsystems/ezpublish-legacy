<form method="post" action={"/user/login/"|ezurl}>

<div class="maincontentheader">
<h1>{"Login"|i18n("design/standard/user")}</h1>
</div>

<p>
{"You need to log in to get access to the intranet."|i18n("design/standard/user")}
</p>
{section show=$User:warning.bad_login}
<div class="warning">
<h2>{"Could not login"|i18n("design/standard/user")}</h2>
<ul>
    <li>{"A valid username and password is required to login."|i18n("design/standard/user")}</li>
</ul>
</div>
{section-else}

{section show=$site_access.allowed|not}
<div class="warning">
<h2>{"Access not allowed"|i18n("design/standard/user")}</h2>
<ul>
    <li>{"You are not allowed to access %1."|i18n("design/standard/user",,array($site_access.name))}</li>
</ul>
</div>
{/section}

{/section}

<div class="block">
<label for="id1">{"Login"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login}" />
</div>
<div class="block">
<label for="id2">{"Password"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="password" size="10" name="Password" id="id2" value="" />
</div>

<div class="buttonblock">
<input class="defaultbutton" type="submit" name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" />
<input class="button" type="submit" name="RegisterButton" value="{'Sign Up'|i18n('design/standard/user','Button')}" />
</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri}" />

</form>
