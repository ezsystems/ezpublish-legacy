<table width="100%" cellpadding="2" cellspacing="0">
<tr>
	<td class="bglight">
	<b>{"Login"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"e-mail"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Password"|i18n}</b>:<br/>
	</td>
	<td class="bglight">
	<b>{"Password confirm"|i18n}</b>:<br/>
	</td>
</tr>
<tr>
	<td class="bglight">

	<input type="text" name="ContentObjectAttribute_data_user_login_{$attribute.id}" size="11" value="{$attribute.content.login}">
	</td>
	<td class="bglight">
	<input type="text" name="ContentObjectAttribute_data_user_email_{$attribute.id}" size="11" value="{$attribute.content.email}">
	</td>
	<td class="bglight">
	<input type="password" name="ContentObjectAttribute_data_user_password_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}">
	</td>
	<td class="bglight">
	{* {$attribute.content.password_hash} *}
	<input type="password" name="ContentObjectAttribute_data_user_password_confirm_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}">
	</td>
</tr>
</table>

