<form action={concat($module.functions.register.uri)|ezurl} method="post" name="Register">
<h1>Sign up form:</h1>
{section show=$message}
<div class="warning">
<h2 class="warning">{$message}</h2>
</div>
{/section}
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<td>
<h2>User Profile:</h2>
</td>
</tr>
{section name=UserAttributes loop=$userAttributes}
<tr>
<td width="180">
<b>{$UserAttributes:item.name}</b>
</td>
<td>
<input type="text" name="ContentObjectAttribute_{$UserAttributes:item.classAttribute_id}" size="20" value="{$UserAttributes:item.value}">
</td>
{/section}
<tr>
<td>
<br>
<h2>User Account:</h2>
</td>
</tr>
<tr>
<td width="180">
{section show=$userIDNotValid}<font color=red>*</font>{/section}<b>Login ID</b>:
</td>
<td>
<input type="text" name="Login_id" size="20" value="{$login}">
</td>
</tr>
<tr>
<td>
{section show=$emailNotValid}<font color=red>*</font>{/section}<b>e-mail</b>
</td>
<td>
<input type="text" name="Email" size="20" value="{$email}">
</td>
<tr>
<td>
{section show=$passwordNotValid}<font color=red>*</font>{/section}<b>Password</b>
</td>
<td>
<input type="password" name="Password" size="20" value="{$password}">
</td>
</tr>
<tr>
<td>
{section show=$passwordNotValid}<font color=red>*</font>{/section}<b>Password confirm</b>
</td>
<td>
<input type="password" name="Password_confirm" size="20" value="{$passwordConfirm}">
</td>
</tr>
<tr>
</table>
<br>
<input type="submit" name="StoreButton" value="Register" />
<input type="submit" name="CancelButton" value="Cancel" />
</form>
