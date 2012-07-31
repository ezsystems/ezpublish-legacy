<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"Activate account"|i18n("design/admin/user")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>
{if $account_activated}
    {if $is_pending}
        {'Your email address has been confirmed. An administrator needs to approve your sign up request, before your login becomes valid.'|i18n('design/standard/user')}
    {else}
        {'Your account is now activated.'|i18n('design/standard/user')}
    {/if}
{elseif $already_active}
    {'Your account is already active.'|i18n('design/standard/user')}
{else}
    {'Sorry, the key submitted was not a valid key. Account was not activated.'|i18n('design/standard/user')}
{/if}
</p>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <form action={"/user/login"|ezurl} method="post">
        <input class="button" type="submit" value="{'OK'|i18n( 'design/admin/user' )}" />
    </form>
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>