<form action={concat($module.functions.password.uri,"/",$userID)|ezurl} method="post" name="Password">
<h1>Change password for user {$userAccount.login}</h1>
<hr noshade size="4">

{section show=$message}
<div class="warning">
<h2 class="warning">{$message}</h2>
</div>
{/section}

<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<td width="250">
<h4>{section show=$oldPasswordNotValid}<font color=red>*</font>{/section}{"Old Password"|i18n}</h4>
</td>
</tr>
<tr>
<td>
<input type="password" name="oldPassword" size="11" value="{$oldPassword}">
</td>
</tr>
<tr>
<td>
<h4>{section show=$newPasswordNotMatch}<font color=red>*</font>{/section}{"New Password"|i18n}</h4>
</td>
<td>
<h4>{section show=$newPasswordNotMatch}<font color=red>*</font>{/section}{"Retype Password"|i18n}</h4>
</td>
</tr>
<tr>
<td>
<input type="password" name="newPassword" size="11" value="{$newPassword}">
</td>
<td>
<input type="password" name="confirmPassword" size="11" value="{$confirmPassword}">
</td>
</tr>
</table>
<hr noshade size="4">
<input type="submit" name="OKButton" value="OK" />
<input type="submit" name="CancelButton" value="Cancel" />
</form>