<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{"User registered"|i18n("design/admin/user")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
{if $verify_user_email}
<div class="feedback">
<p>
{'Your account was successfully created. An email will be sent to the specified
email address. Follow the instructions in that mail to activate
your account.'|i18n('design/admin/user')}
</p>
</div>
{else}
<div class="feedback">
<h2>{"Your account was successfully created."|i18n("design/admin/user")}</h2>
</div>
{/if}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<form action={"/"|ezurl} method="post">
    <input class="defaultbutton" type="submit" value="{'OK'|i18n( 'design/admin/user' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>