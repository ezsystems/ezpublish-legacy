<form action={concat($module.functions.activate.uri,"/",$LoginID,"/",$UserIDHash)|ezurl} method="post" name="Register">
<h1>Activite account:</h1>

<h2>{$message}</h2>
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<td width="180">
<b>Login ID</b>:
</td>
<td>
<input type="text" name="Login_id" size="20" value="">
</td>
</tr>
<tr>
<td>
<b>Password</b>
</td>
<td>
<input type="password" name="Password" size="20" value="">
</td>
</tr>
</table>
<br>
<input type="submit" name="ActivateButton" value="Activate" />
</form>
