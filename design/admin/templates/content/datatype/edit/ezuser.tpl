{default attribute_base=ContentObjectAttribute}

<table class="list" cellspacing="0">
<tr>
    <th>{'User ID'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
    <th>{'Username'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
    <th>{'Password'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
    <th>{'Confirm password'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
    <th>{'E-mail'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
</tr>

<tr>
    {* User ID. *}
    <td>{$attribute.content.contentobject_id}</td>

    {* Username. *}
    <td>
    {section show=$attribute.content.has_stored_login}
        <input type="hidden" name="{$attribute_base}_data_user_login_{$attribute.id}" value="{$attribute.content.login}" />
        {$attribute.content.login}
        {section-else}
        <input type="text" name="{$attribute_base}_data_user_login_{$attribute.id}" size="11" value="{$attribute.content.login}" />
    {/section}
    </td>

    {* Password. *}
    <td><input type="password" name="{$attribute_base}_data_user_password_{$attribute.id}" size="11" value="{section show=$attribute.content.original_password}{$attribute.content.original_password}{section-else}{section show=$attribute.content.has_stored_login}password{/section}{/section}" /></td>

    {* Confirm password. *}
    <td><input type="password" name="{$attribute_base}_data_user_password_confirm_{$attribute.id}" size="11" value="{section show=$attribute.content.original_password_confirm}{$attribute.content.original_password_confirm}{section-else}{section show=$attribute.content.has_stored_login}password{/section}{/section}" /></td>

    {* Email. *}
    <td><input type="text" name="{$attribute_base}_data_user_email_{$attribute.id}" size="17" value="{$attribute.content.email|wash(xhtml)}"></td>

</tr>

</table>

{/default}
