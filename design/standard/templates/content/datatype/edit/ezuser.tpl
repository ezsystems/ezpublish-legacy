<div class="block">
<div class="element">
<label>{"Login"|i18n('content/object')}:</label><div class="labelbreak"></div>
{switch name=Sw match=$attribute.content.login}
{case match=""}
<input type="text" name="ContentObjectAttribute_data_user_login_{$attribute.id}" size="11" value="" />
{/case}
{case}
<input type="hidden" name="ContentObjectAttribute_data_user_login_{$attribute.id}" value="{$attribute.content.login}" />
{$attribute.content.login}
{$attribute.content.email}
    {/case}
{/switch}
</div>
<div class="element">
<label>{"e-mail"|i18n('content/object')}:</label><div class="labelbreak"></div>
<input type="text" name="ContentObjectAttribute_data_user_email_{$attribute.id}" size="11" value="{$attribute.content.email}">
</div>
<div class="element">
<label>{"Password"|i18n('content/object')}:</label><div class="labelbreak"></div>
<input type="password" name="ContentObjectAttribute_data_user_password_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}" />
</div>
<div class="element">
<label>{"Password confirm"|i18n('content/object')}:</label><div class="labelbreak"></div>
{* {$attribute.content.password_hash} *}
<input type="password" name="ContentObjectAttribute_data_user_password_confirm_{$attribute.id}" size="11" value="{section show=$attribute.content.login}password{/section}" />
</div>
<div class="break"></div>
</div>
