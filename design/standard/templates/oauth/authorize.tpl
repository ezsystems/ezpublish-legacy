
<div class="attribute-header">
    <h1>{"Application authorization"|i18n( 'extension/oauth' )}</h1>
</div>

<p>{"The application %application_name% has requested access to this website on your behalf."|i18n(
    'extension/oauth', null, hash( '%application_name%', $application.name|wash ) )}</p>

<form method="post" action={$module.functions.authorize.uri|ezurl}>
    {foreach $httpParameters as $name => $value}
        <input type="hidden" name="{$name}" value="{$value|wash}" />
    {/foreach}

    <div>
        <p>{"Click on \"Authorize\" to grant the requested access"}</p>
        <p>{"Click on \"Deny\" to refuse the requested access"}</p>
    </div>
    <div class="buttonblock">
        <input type="submit" id="AuthorizeButton" class="defaultbutton authorizebutton" name="AuthorizeButton" value="{"Authorize"|i18n( 'extension/oauth/authorize' )}" />
        <input type="submit" id="DenyButton" class="button" name="DenyButton" value="{"Deny"|i18n( 'extension/oauth/authorize' )}" />
    </div>
</form>