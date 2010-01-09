{* Feedbacks. *}
{if $message}

{if or( $oldPasswordNotValid, $newPasswordNotMatch, $newPasswordTooShort )}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The password could not be changed.'|i18n( 'design/admin/user/password' )}</h2>
    {if $oldPasswordNotValid}
        <ul>
            <li>{'The old password was either missing or incorrect.'|i18n( 'design/admin/user/password' )}</li>
            <li>{'Please retype the old password and try again.'|i18n( 'design/admin/user/password' )}</li>
        <ul>
    {/if}
    {if $newPasswordNotMatch}
        <ul>
            <li>{'The new passwords did not match.'|i18n( 'design/admin/user/password' )}</li>
            <li>{'Please retype the new passwords and try again.'|i18n( 'design/admin/user/password' )}</li>
        </ul>
    {/if}
    {if $newPasswordTooShort}
        <ul>
            <li>{'The password must be at least %1 characters long.'|i18n( 'design/admin/user/password','',array( ezini('UserSettings','MinPasswordLength') ) )}</li>
            <li>{'Please retype the new passwords and try again.'|i18n( 'design/admin/user/password' )}</li>
        </ul>
    {/if}
    </div>
{else}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The password was successfully changed.'|i18n( 'design/admin/user/password' )}</h2>
    </div>
{/if}
{/if}




<form name="Password" method="post" action={concat( $module.functions.password.uri, '/', $userID )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Change password for <%username>'|i18n( 'design/admin/user/password',, hash( '%username', $userAccount.login ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{* Username. *}
<div class="block">
<label>{'Username'|i18n( 'design/admin/user/password' )}:</label>
{$userAccount.login}
</div>

{* Old password. *}
<div class="block">
<label>{'Old password'|i18n( 'design/admin/user/password' )}:</label>
<input class="halfbox" id="pass" type="password" name="oldPassword" value="{$oldPassword}" />
</div>

{* New password. *}
<div class="block">
<label>{'New password'|i18n( 'design/admin/user/password' )}:</label>
<input class="halfbox" type="password" name="newPassword" value="{$newPassword}" />
</div>

{* Confirm new password. *}
<div class="block">
<label>{'Confirm new password'|i18n( 'design/admin/user/password' )}:</label>
<input class="halfbox" type="password" name="confirmPassword" value="{$confirmPassword}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="OKButton" value="{'OK'|i18n( 'design/admin/user/password' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/user/password' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>




{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('pass').focus();
    }
-->
</script>
{/literal}
