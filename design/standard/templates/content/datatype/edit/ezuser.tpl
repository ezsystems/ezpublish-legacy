{default attribute_base=ContentObjectAttribute}
<div class="block">
<div class="element">
<label>{"User ID"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<p class="box">{$attribute.content.contentobject_id}</p>
</div>
<div class="element">
<label>{"Login"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
{section show=$attribute.content.has_stored_login}
<input type="hidden" name="{$attribute_base}_data_user_login_{$attribute.id}" value="{$attribute.content.login}" />
{$attribute.content.login}
{section-else}
<input type="text" name="{$attribute_base}_data_user_login_{$attribute.id}" size="11" value="{$attribute.content.login}" />
{/section}
</div>
<div class="element">
<label>{"E-Mail"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="{$attribute_base}_data_user_email_{$attribute.id}" size="11" value="{$attribute.content.email|wash(xhtml)}">
</div>
<div class="element">
<label>{"Password"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
<input type="password" name="{$attribute_base}_data_user_password_{$attribute.id}" size="11" value="{section show=$attribute.content.original_password}{$attribute.content.original_password}{section-else}{section show=$attribute.content.has_stored_login}password{/section}{/section}" />
</div>
<div class="element">
<label>{"Confirm password"|i18n("design/standard/content/datatype")}</label><div class="labelbreak"></div>
{* {$attribute.content.password_hash} *}
<input type="password" name="{$attribute_base}_data_user_password_confirm_{$attribute.id}" size="11" value="{section show=$attribute.content.original_password_confirm}{$attribute.content.original_password_confirm}{section-else}{section show=$attribute.content.has_stored_login}password{/section}{/section}" />
</div>
<div class="break"></div>
</div>
{/default}
