<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{"Activate account"|i18n("design/admin/user")}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<p>
{section show=$account_activated}
{'Your account is now activated.'|i18n('design/admin/user')}
{section-else}
{'Sorry, the key submitted was not a valid key. Account was not activated.'|i18n('design/admin/user')}
{/section}
</p>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>