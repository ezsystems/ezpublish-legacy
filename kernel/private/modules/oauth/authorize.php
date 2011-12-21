<?php
/**
 * File containing the oauth/authorize view definition
 *
 * @param string $_GET[client_id] the client application identifier, as in ezpRestClient
 * @param string $_GET[redirect_uri] the URI the view should redirect to in case of success
 * @param string $_GET[response_type] the requested response type. Can be code_and_token, code, or token
 * @param string $_GET[scope] the permissions scope the client requests (optional)
 * @param string $_GET[state] Not implemented yet (optional)
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package oauth
 */
$module = $Params['Module'];

// First check for mandatory parameters
$http = eZHTTPTool::instance();
$restINI = eZINI::instance( 'rest.ini' );

// Redirect URI, required to handle other errors, checked first
if ( ( $pRedirectUri = getHTTPVariable( 'redirect_uri' ) ) === false )
{
    response( '400 Bad Request' );
}

// Client ID
if ( ( $pClientId = getHTTPVariable( 'client_id' ) ) === false )
{
    error( $pRedirectUri, 'invalid_request', "Missing client ID parameter" );
    // redirect to the redirect_uri with GET parameter error=invalid_request
    // @todo mismatch between chapters 3.2's example (error=access-denied) and error codes list at 3.2.1 (error=access_denied)
}

// Requested response type
if ( ( $pResponseType = getHTTPVariable( 'response_type' ) ) === false )
{
    error( $pRedirectUri, 'invalid_request', "Missing response_type parameter" );
    // redirect to the redirect_uri with GET parameter error=invalid_request
    // @todo mismatch between chapters 3.2's example (error=access-denied) and error codes list at 3.2.1 (error=access_denied)
}

// Scope
$pScope = getHTTPVariable( 'scope' );

// @todo The spec mentions (FIXME:chapter) throwing an error if the request has extra parameters. Check this.

// Try loading the REST client based on the ID
$application = ezpRestClient::fetchByClientId( $pClientId );
if ( !$application instanceof ezpRestClient )
{
    error( $pRedirectUri, 'invalid_client' );
}

// The client is found, validate the redirect_uri
if ( !$application->isEndPointValid( $pRedirectUri ) )
{
    error( $pRedirectUri, 'redirect_uri_mismatch' );
}

// authentication
$user = eZUser::currentUser();

// login is like REALLY required here. But we can't use the standard policy check, as it won't redirect w/ GET parameters
if ( !$user->isLoggedIn() )
{
    $redirectUri = str_replace( eZSys::indexDir(), '', $_SERVER['REQUEST_URI'] );

    $tpl = eZTemplate::factory();

    $tpl->setVariable( 'redirect_uri', $redirectUri, 'User' );
    $tpl->setVariable( 'site_access', array( 'allowed' => true ) );

    $result = array();
    $result['content'] = $tpl->fetch( 'design:user/login.tpl' );
    $result['pagelayout'] = false;
    $result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/user', 'User' ),
                                    'url' => false ),
                             array( 'text' => ezpI18n::tr( 'kernel/user', 'Login' ),
                                    'url' => false ) );
    $result['pagelayout'] = 'oauthloginpagelayout.tpl';

    return $result;
}
// logged in, but no access to oauth/authorize
$access = $user->hasAccessTo( 'oauth', 'authorize' );
if ( !$access[ 'accessWord' ] == 'yes' )
{
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}
unset( $access );

// Everything is valid. Process to app authorization.
if ( !$application->isAuthorizedByUser( $pScope, eZUser::currentUser() ) )
{
    $http = eZHTTPTool::instance();

    // authorization denied: redirect
    if ( $http->hasPostVariable( 'DenyButton' ) )
    {
        error( $pRedirectUri, 'access_denied' );
    }

    elseif ( $http->hasPostVariable( 'AuthorizeButton' ) )
    {
        $application->authorizeFor( $user );
    }

    // show authorization form
    if ( !$application->isAuthorizedByUser( $pScope, eZUser::currentUser() ) )
    {
        $tpl = eZTemplate::factory();

        $tpl->setVariable( 'application', $application );
        $tpl->setVariable( 'module', $module );

        $parameters = array( 'client_id' => $pClientId,
                             'response_type' => $pResponseType,
                             'scope' => $pScope,
                             'redirect_uri' => $pRedirectUri );
        $tpl->setVariable( 'httpParameters', $parameters );

        $result = array();
        $result['content'] = $tpl->fetch( 'design:oauth/authorize.tpl' );
        $result['pagelayout'] = 'oauthloginpagelayout.tpl';
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/user', 'oAuth' ),
                                        'url' => false ),
                                 array( 'text' => ezpI18n::tr( 'kernel/user', 'authorization' ),
                                        'url' => false ) );

        return $result;
    }
}

$tokenTTL = (int)$restINI->variable( 'OAuthSettings', 'TokenTTL' );
if ( $pResponseType == 'token')
{
    // At this point, the we know the user HAS granted access, and can hand over a token
    $rAccessToken = ezpRestToken::generateToken( $pScope );
    $rRefreshToken = ezpRestToken::generateToken( $pScope );

    $token = new ezpRestToken();
    $token->id = $rAccessToken;
    $token->refresh_token = $rRefreshToken;
    $token->client_id = $pClientId;
    $token->user_id = $user->attribute( 'contentobject_id' );
    $token->expirytime = time() + $tokenTTL;

    $session = ezcPersistentSessionInstance::get();
    $session->save( $token );


    // The user has a agreed to authorize this app.
    // Redirect to the redirect_uri with these parameters:
    // - code, only if request_type == code OR code_and_token @todo Implement
    // - access_token, only if request_type == token OR code_and_token
    // - expires_in, the token lifetime in seconds
    // - scope, the permission scope the provided code / token grants, if different from the requested one (not implemented yet)
    // - state, not implemented yet (state persistency related)
    $parameters = array();

    $rExpiresIn = $tokenTTL;

    $parameters[] = 'access_token=' . urlencode( $rAccessToken );
    $parameters[] = 'refresh_token=' . urlencode( $rRefreshToken );
    $parameters[] = "expires_in=$rExpiresIn";
    $location = "{$pRedirectUri}?" . implode( $parameters, '&' );

    response( '302 Found', $location );
}
elseif ( $pResponseType ==  'code')
{
    // At this point, the we know the user HAS granted access, and can hand over a token
    $rCode = ezpRestToken::generateToken( $pScope );

    $code = new ezpRestAuthcode();
    $code->id = $rCode;
    $code->client_id = $pClientId;
    $code->user_id = $user->attribute( 'contentobject_id' );
    $code->expirytime = time() + $tokenTTL;

    $session = ezcPersistentSessionInstance::get();
    $session->save( $code );


    // The user has a agreed to authorize this app.
    // Redirect to the redirect_uri with these parameters:
    // - code, only if request_type == code OR code_and_token @todo Implement
    // - access_token, only if request_type == token OR code_and_token
    // - expires_in, the token lifetime in seconds
    // - scope, the permission scope the provided code / token grants, if different from the requested one (not implemented yet)
    // - state, not implemented yet (state persistency related)
    $parameters = array();

    $rExpiresIn = $tokenTTL;

    $parameters[] = 'code=' . urlencode( $rCode );
    $parameters[] = "expires_in=$rExpiresIn";
    $location = "{$pRedirectUri}?" . implode( $parameters, '&' );

    response( '302 Found', $location );
}

/**
 * oAuth error handler function. Terminates execution after redirecting.
 *
 * @param string $redirectUri The URI the error should be sent to
 * @param string $errorCode The error code, as defined in section 3.2.1
 * @param string $message A human readable error message explaining the error
 *
 * @return void
 */
function error( $redirectUri, $errorCode, $message = null )
{
    $location = "{$redirectUri}?error=" . urlencode( $errorCode );
    if( $message !== null )
        $location .= '&error_description=' . urlencode( $message );
    response( '302 Found', $location );
}

/**
 * oAuth2 response handler function. Terminates execution after sending the headers.
 *
 * @param string $httpHeader The HTTP header to be sent as a response
 * @param string $location The location to redirect to. No redirection is done if not provided.
 *
 * @return void
 */
function response( $httpHeader, $location = null )
{
    header( "HTTP/1.1 $httpHeader" );
    if ( $location !== null )
        // debug stuff: echo "header( \"Location: $location\" );\n";
        header( "Location: $location" );
    eZExecution::cleanExit();
}

/**
 * Helper function that reads an HTTP variable over GET or POST, depending on the stage.
 * The POST variables AuthorizeButton and DenyButton will make the function read from POST
 *
 * @param string $variable
 * @return mixed The parameter, or false if not set
 */
function getHTTPVariable( $variable )
{
    static $hasPost;
    static $http;

    if ( $http === null )
        $http = eZHTTPTool::instance();

    if ( $hasPost === null )
        $hasPost = $http->hasPostVariable( 'AuthorizeButton' ) or $http->hasPostVariable( 'DenyButton' );

    if ( $hasPost )
        return $http->hasPostVariable( $variable ) ? $http->postVariable( $variable ) : false;
    else
        return $http->hasGetVariable( $variable ) ? $http->getVariable( $variable ) : false;
}
?>
