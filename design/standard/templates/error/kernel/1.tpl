<div class="warning">
<h2>{"Access denied"|i18n("design/standard/error/kernel")}</h2>
<ul>
	<li>{"You don't have permission to access this area."|i18n("design/standard/error/kernel")}</li>
{section show=eq($current_user.contentobject_id,$anonymous_user_id)}
	<li>{"Login with a user to get proper permissions."|i18n("design/standard/error/kernel")}</li>
{/section}
</ul>
</div>

{section show=eq($current_user.contentobject_id,$anonymous_user_id)}

<form method="post" action={"/user/login/"|ezurl}>

<p>{"Click the Login button to login as a user."|i18n("design/standard/error/kernel")}</p>
<div class="buttonblock">
<input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" />
</div>

<input type="hidden" name="Login" value="" />
<input type="hidden" name="Password" value="" />
<input type="hidden" name="RedirectURI" value="{$redirect_uri}" />
</form>

{/section}
