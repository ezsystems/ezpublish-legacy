<div class="maincontentheader">
<h1>{"Activate account"|i18n("design/standard/user")}</h1>
</div>

<p>
{section show=$account_activated}
{'Your account is now activated.'|i18n('design/standard/shop')}
{section-else}
{'Sorry, the key submitted was not a valid key. Account was not activated.'|i18n('design/standard/shop')}
{/section}

</p>