<form action={concat($module.functions.password.uri,"/",$userID)|ezurl} method="post" name="Password">

<div class="maincontentheader">
<h1>{"Change password for user"|i18n("design/standard/user")} {$userAccount.login}</h1>
</div>

{section show=$message}
<div class="warning">
<h2>{$message}</h2>
</div>
{/section}

<div class="block">
{section show=$oldPasswordNotValid}*{/section}
<label>{"Old Password:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="oldPassword" size="11" value="{$oldPassword}" />
</div>

<div class="block">
<div class="element">
{section show=$newPasswordNotMatch}*{/section}
<label>{"New Password:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="newPassword" size="11" value="{$newPassword}" />
</div>
<div class="element">
{section show=$newPasswordNotMatch}*{/section}
<label>{"Retype Password:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="confirmPassword" size="11" value="{$confirmPassword}" />
</div>
<div class="break"></div>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="OKButton" value="{'OK'|i18n('design/standard/user')}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/user')}" />
</div>

</form>
