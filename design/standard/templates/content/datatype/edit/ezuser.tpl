<table width="100%" cellpadding="2" cellspacing="0">
<tr>
	<td>
	<b>{"Login"|i18n('content/object')}</b>:<br/>
	</td>
	<td>
	<b>{"e-mail"|i18n('content/object')}</b>:<br/>
	</td>
	<td>
	<b>{"Password"|i18n('content/object')}</b>:<br/>
	</td>
	<td>
	<b>{"Password confirm"|i18n('content/object')}</b>:<br/>
	</td>
</tr>
<tr>
	<td>
	{switch name=Sw match=$attribute.content.login}
	{case match=""}
	<input type="text" name="ContentObjectAttribute_data_user_login_{$attribute.id}" size="11" value="">
	{/case}
	{case}
	<input type="hidden" name="ContentObjectAttribute_data_user_login_{$attribute.id}" value="{$attribute.content.login}">
	{$attribute.content.login}
	{$attribute.content.email}
        {/case}
	{/switch}
	</td>
	<td>
	<input type="text" name="ContentObjectAttribute_data_user_email_{$attribute.id}" size="11" value="{$attribute.content.email}">
	</td>
	<td>
	<input type="password" name="ContentObjectAttribute_data_user_password_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}">
	</td>
	<td>
	{* {$attribute.content.password_hash} *}
	<input type="password" name="ContentObjectAttribute_data_user_password_confirm_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}">
	</td>
</tr>
</table>
