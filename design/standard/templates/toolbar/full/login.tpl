{section show=eq($current_user.is_logged_in)}
<form method="post" action={"/user/login/"|ezurl}>
<div class="block">
<label for="id1">{"Login"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login}" />
</div>
<div class="block">
<label for="id2">{"Password"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="password" size="10" name="Password" id="id2" value="" />
</div>

<div class="buttonblock">
<input class="defaultbutton" type="submit" name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" />
</div>
</form> <br />
{section-else}
<div>
Login User: {$current_user.contentobject.name}
</div>
<div>
<a href={"/user/logout"|ezurl}>Logout</a></span>
</div>
{/section}
