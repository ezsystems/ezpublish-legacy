<form action={concat($module.functions.edit.uri,"/",$userID)|ezurl} method="post" name="Edit">
<h1>Registed user profile:

<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<td width="180">
<b>{"Login"|i18n}</b>:<br/>
</td>
<td>
{$userAccount.login}
</td>
</tr>
{section name=ClassAttribute loop=$userProfile}
<tr>
<td width="180">
<b>{$ClassAttribute:item.name}</b>
</td>
<td>
<input type="text" name="ContentclassAttribute_{$ClassAttribute:item.classAttribute_id}" size="20" value="{$ClassAttribute:item.value}">
</td>
{/section}
<tr>
<td>
<b>{"e-mail"|i18n}</b>:<br/>	
</td>
<td>
<input type="text" name="email" size="20" value="{$userAccount.email}">
</td>
</tr>
</table>
<br>
<input type="submit" name="UpdateProfileButton" value="Update Profile" />
<input type="submit" name="ChangePasswordButton" value="Change Password" />
<input type="submit" name="ChangeSettingButton" value="Change Setting" />
<input type="submit" name="CancelButton" value="Cancel" />
</form>