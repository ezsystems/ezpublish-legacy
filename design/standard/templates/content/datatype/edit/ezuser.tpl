<div class="block">
<div class="element">
<label>{"Login:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
{section show=eq($attribute.content.login,'')}
<input type="text" name="ContentObjectAttribute_data_user_login_{$attribute.id}" size="11" value="" />
{section-else}
<input type="hidden" name="ContentObjectAttribute_data_user_login_{$attribute.id}" value="{$attribute.content.login}" />
{$attribute.content.login}
{$attribute.content.email}
{/section}
</div>
<div class="element">
<label>{"E-Mail:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_user_email_{$attribute.id}" size="11" value="{$attribute.content.email}">
</div>
<div class="element">
<label>{"Password:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="password" name="ContentObjectAttribute_data_user_password_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}" />
</div>
<div class="element">
<label>{"Confirm password:"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
{* {$attribute.content.password_hash} *}
<input type="password" name="ContentObjectAttribute_data_user_password_confirm_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}" />
</div>
<div class="break"></div>
</div>
