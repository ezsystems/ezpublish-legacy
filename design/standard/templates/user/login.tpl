<h1>Login</h1>


<form method="post" action="/user/login/">

<b>{"Login"|i18n('logon')}:</b><br />
<input type="text" name="Login" value="{$User:login}"/><br/>
<b>{"Password"|i18n('logon')}:</b><br />
<input type="password" name="Password"  value="{$User:password}"/><br/>

<input type="submit" name="LoginButton" value="{'Login'|i18n('logon','Button')}" />

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri}" />

</form>
