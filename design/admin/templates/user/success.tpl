<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"User registered"|i18n("design/admin/user")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

{section show=$verify_user_email}

<div class="feedback">
<p>
{'Your account was successfully created. An e-mail will be sent to the specified
e-mail address. You need to follow the instructions in that mail to activate
your account.'|i18n('design/admin/user')}
</p>
</div>
{section-else}
<div class="feedback">
<h2>{"Your account was successfully created."|i18n("design/admin/user")}</h2>
</div>
{/section}

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>