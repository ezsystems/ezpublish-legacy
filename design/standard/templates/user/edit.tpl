<form action={concat($module.functions.edit.uri,"/",$userID)|ezurl} method="post" name="Edit">

<div class="maincontentheader">
<h1>{"Registed user profile"|i18n("design/standard/user")}</h1>
</div>

<div class="block">
<label>{"Login:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<p class="box">{$userAccount.login}</p>
</div>

{section name=ClassAttribute loop=$userProfile}
<div class="block">
<label>{$ClassAttribute:item.name}:</label><div class="labelbreak"></div>
<input class="box" type="text" name="ContentclassAttribute_{$ClassAttribute:item.classAttribute_id}" size="20" value="{$ClassAttribute:item.value}" />
</div>
{/section}

<div class="block">
<label>{"e-mail:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="box" type="text" name="email" size="20" value="{$userAccount.email}" />
</div>

<div class="buttonblock">
<input class="button" type="submit" name="UpdateProfileButton" value="{'Update Profile'|i18n('design/standard/user')}" />
<input class="button" type="submit" name="ChangePasswordButton" value="{'Change Password'|i18n('design/standard/user')}" />
<input class="button" type="submit" name="ChangeSettingButton" value="{'Change Setting'|i18n('design/standard/user')}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/user')}" />
</div>

</form>
