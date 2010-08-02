<h1>{"Application authorization request"|i18n( 'extension/oauth' )}</h1>

<p>{"The application %application_name% has requested access to this website on your behalf."|i18n(
    'extension/oauth', null, hash( '%application_name%', $application.name|wash ) )}</p>

<form method="post" action={$module.functions.authorize.uri|ezurl}>
    {foreach $httpParameters as $name => $value}
        <input type="hidden" name="{$name}" value="{$value|wash}" />
    {/foreach}

    <div>
        <label for="AuthorizeButton">{"Click on \"Authorize\" to grant the requested access"}</label>
        <input type="submit" id="AuthorizeButton" name="AuthorizeButton" value="{"Authorize"|i18n( 'extension/oauth/authorize' )}" />
    </div>
    <div>
        <label for="DenyButton">{"Click on \"Deny\" to refuse the requested access"}</label>
        <input type="submit" id="DenyButton" name="DenyButton" value="{"Deny"|i18n( 'extension/oauth/authorize' )}" />
    </div>
</form>