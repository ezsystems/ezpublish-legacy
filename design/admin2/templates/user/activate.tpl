<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{"Activate account"|i18n("design/admin/user")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>
{if $account_activated}
{'Your account is now activated.'|i18n('design/admin/user')}
{else}
{'Sorry, the key submitted was not a valid key. Account was not activated.'|i18n('design/admin/user')}
{/if}
</p>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
    <div class="block">
    <form action={"/user/login"|ezurl} method="post">
        <input class="button" type="submit" value="{'OK'|i18n( 'design/admin/user' )}" />
    </form>
    </div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>