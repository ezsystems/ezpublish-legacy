<h1>Login</h1>


<form method="post" action="/user/login/">

<b>{"Login"|i18n('logon')}:</b><br />
<input type="text" name="Login" /><br/>
<b>{"Password"|i18n('logon')}:</b><br />
<input type="password" name="Password" /><br/>


<input type="submit" value="{'Login'|i18n('logon','Button')}" />

</form>
