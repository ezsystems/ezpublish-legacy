<form action={concat($module.functions.activate.uri,"/",$LoginID,"/",$UserIDHash)|ezurl} method="post" name="Register">

<div class="maincontentheader">
<h1>{"Activate account"|i18n("design/standard/user")}</h1>
</div>

<h2>{$message}</h2>

<div class="block">
<div class="element">
<label>{"Login ID:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="text" name="Login_id" size="20" value="" />
</div>
<div class="element">
<label>{"Password:"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="Password" size="20" value="" />
</div>
<div class="break"></div>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="ActivateButton" value="{'Activate'|i18n('design/standard/user')}" />
</div>

</form>
