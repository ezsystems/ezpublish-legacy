{section show=$User:warning.bad_login}
<div class="message-warning">
<h2>{"Could not login"|i18n("design/standard/user")}</h2>
<ul>
    <li>{"A valid username and password is required to login."|i18n("design/standard/user")}</li>
</ul>
</div>
{section-else}

{section show=$site_access.allowed|not}
<div class="message-warning">
<h2>{"Access not allowed"|i18n("design/standard/user")}</h2>
<ul>
    <li>{"You are not allowed to access %1."|i18n("design/standard/user",,array($site_access.name))}</li>
</ul>
</div>
{/section}

{/section}

<div class="context-block">

<form method="post" action={"/user/login/"|ezurl}>

<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr">
<div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"Welcome to eZ publish administration"|i18n("design/standard/layout")}</h1>

<div class="header-mainline"></div>

</div></div>
</div></div></div>
</div>

<div class="box-ml"><div class="box-mr">
<div class="box-content">

<div class="context-attributes">

<div class="block">
    <p>{"To log in enter a valid login and password."|i18n("design/standard/layout")}</p>
</div>

<div class="block">
    <label for="id1">{"Login"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
    <input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login}" />
</div>

<div class="block">
    <label for="id2">{"Password"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
    <input class="halfbox" type="password" size="10" name="Password" id="id2" value="" />
</div>

</div>

</div>
</div></div>

<div class="controlbar">

<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-tc">
<div class="box-bl"><div class="box-br">

<div class="block">
    <input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" />
    <input class="button" type="submit" name="RegisterButton" value="{'Sign Up'|i18n('design/standard/user','Button')}" />
</div>

</div></div>
</div>
</div></div></div>

</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri}" />

</form>

</div>