<form action={concat( $module.functions.password.uri, '/', $userID )|ezurl} method="post" name="Password">
<input type="hidden" name="RedirectOnCancel" value="/content/draft" />

{section show=$message}
{section show=or($oldPasswordNotValid,$newPasswordNotMatch)}
    {section show=$oldPasswordNotValid}
        <div class="message-warning">
            <h2>{'Please retype your old password.'|i18n( 'design/admin/user/password' )}<span class="time">{currentdate()|l10n(shortdatetime)}</span></h2>
        </div>
    {/section}
    {section show=$newPasswordNotMatch}
        <div class="message-warning">
            <h2>{"Password didn't match, please retype your new password."|i18n( 'design/admin/user/password' )}<span class="time">{currentdate()|l10n(shortdatetime)}</span></h2>
        </div>
    {/section}
{section-else}
    <div class="message-feedback">
        <h2>{'Password successfully updated.'|i18n( 'design/admin/user/password' )}<span class="time">{currentdate()|l10n(shortdatetime)}</span></h2>
    </div>
{/section}
{/section}

<div class="context-block">
<h2 class="context-title">{'Change password'|i18n( 'design/admin/user/password' )}</h2>

<div class="context-attributes">

<div class="block">
<label>{'Username'|i18n( 'design/admin/user/password' )}</label>
{$userAccount.login}
</div>

<div class="block">
<label>{'Old password'|i18n( 'design/admin/user/password' )}</label>
<input class="halfbox" type="password" name="oldPassword" value="{$oldPassword}" />
</div>

<div class="block">
<label>{'New password'|i18n( 'design/admin/user/password' )}</label>
<input class="halfbox" type="password" name="newPassword" value="{$newPassword}" />
</div>

<div class="block">
<label>{'New password (retype)'|i18n( 'design/admin/user/password' )}</label>
<input class="halfbox" type="password" name="confirmPassword" value="{$confirmPassword}" />
</div>

</div>

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="OKButton" value="{'OK'|i18n( 'design/admin/user/password' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/user/password' )}" />
</div>
</div>

</div>

</form>
