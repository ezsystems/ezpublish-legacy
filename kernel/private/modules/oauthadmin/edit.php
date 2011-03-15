<?php
/**
 * File containing the oauthadmin/edit view definition
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

$session = ezcPersistentSessionInstance::get();

$module = $Params['Module'];

// @todo Instanciate the session maybe ?
$applicationId = $Params['ApplicationID'];
$application = $session->load( 'ezpRestClient', $applicationId );

// save the modified application
eZDebug::writeDebug( $module->currentAction() );
eZDebug::writeDebug( $_POST );

if ( $module->isCurrentAction( 'Store') )
{
    $application->name = $module->actionParameter( 'Name' );

    // generate id & secret
    if ( $application->version == ezpRestClient::STATUS_DRAFT )
    {
        $application->client_id = md5( $application->name . uniqid( $application->name ) );
        $application->client_secret = md5( $application->name . uniqid( $application->name ) );
    }
    $application->description = $module->actionParameter( 'Description' );
    $application->endpoint_uri = $module->actionParameter( 'EndPointURI' );
    $application->version = ezpRestClient::STATUS_PUBLISHED;
    $application->modified = time();
    $session->update( $application );

    return $module->redirectTo( $module->functionURI( 'list' ) );
}

if ( $module->isCurrentAction( 'Discard' ) )
{
    // if there is a draft, ditch it
    if ( $application->version == ezpRestClient::STATUS_DRAFT )
        $session->delete( $application);
    return $module->redirectTo( $module->functionURI( 'list' ) );
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'application', $application );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/oauthadmin', 'oAuth admin' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/oauthadmin', 'Edit REST application' ) )
);

$Result['content'] = $tpl->fetch( 'design:oauthadmin/edit.tpl' );
return $Result;
?>
