<form action="{$module.functions.setting.uri}/{$userID}" method="post" name="Setting">
<h1>User setting:
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
<td width="250">
<b>{"Maximum login"|i18n}</b>:
</td>
<td>
<input type="text" name="max_login" size="11" value="{$userSetting.max_login}">
</td>
</tr>
<tr>
<td>
<b>{"Is enabled"|i18n}</b>
</td>
<td>
<input type="checkbox" name="is_enabled" {section show=$userSetting.is_enabled}checked{/section} >
</td>
</tr>
</table>
<br>
<input type="submit" name="UpdateSettingButton" value="Update" />
<input type="submit" name="CancelSettingButton" value="Cancel" />
</form>