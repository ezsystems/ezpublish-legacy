{section show=$verify_user_email}
<div class="maincontentheader">
<h1>{"User registered"|i18n("design/standard/user")}</h1>
</div>

<div class="feedback">
<p>
{'Your account was successfully created. An e-mail will be sent to the specified
e-mail address. You need to follow the instructions in that mail to activate
your account.'|i18n('design/standard/user')}
</p>
</div>
{section-else}
<div class="maincontentheader">
<h1>{"User registered"|i18n("design/standard/user")}</h1>
</div>

<div class="feedback">
<h2>{"Your account was successfully created."|i18n("design/standard/user")}</h2>
</div>
{/section}