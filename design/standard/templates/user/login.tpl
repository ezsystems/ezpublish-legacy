<form method="post" action="/user/login/">

<h1>Login</h1>

<div class="block">
<label for="id1">{"Login"|i18n('logon')}:</label><div class="labelbreak"></div>
<input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login}" />
</div>
<div class="block">
<label for="id2">{"Password"|i18n('logon')}:</label><div class="labelbreak"></div>
<input class="halfbox" type="password" size="10" name="Password" id="id2" value="{$User:password}" />
</div>
<div class="buttonblock">
<input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('logon','Button')}" />
</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri}" />

</form>
