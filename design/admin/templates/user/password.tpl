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
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Change password for <%username>'|i18n( 'design/admin/user/password',, hash( '%username', $userAccount.login ) )|wash}</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Login'|i18n( 'design/admin/user/password' )}</label>
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

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="OKButton" value="{'OK'|i18n( 'design/admin/user/password' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/user/password' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
