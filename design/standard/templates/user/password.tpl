<form action={concat($module.functions.password.uri,"/",$userID)|ezurl} method="post" name="Password">
<h1>Change password for user {$userAccount.login}</h1>
<hr noshade size="4">

<h2>{$message}</h2>
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<td width="250">
<h4>{"Old Password"|i18n}</h4>
</td>
</tr>
<tr>
<td>
<input type="password" name="old_password" size="11" value="">
</td>
</tr>
<tr>
<td>
<h4>{"New Password"|i18n}</h4>
</td>
<td>
<h4>{"Retype Password"|i18n}</h4>
</td>
</tr>
<tr>
<td>
<input type="password" name="new_password" size="11" value="">
</td>
<td>
<input type="password" name="confirm_password" size="11" value="">
</td>
</tr>
</table>
<hr noshade size="4">
<input type="submit" name="OKButton" value="OK" />
<input type="submit" name="CancelButton" value="Cancel" />
</form>