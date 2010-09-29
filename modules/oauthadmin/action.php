<?php
/**
 * File containing the oauthadmin/action view definition
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

include 'extension/oauth/modules/oauthadmin/tmppo.php';

$module = $Params['Module'];

// new application: create draft, redirect to edit this draft
if ( $module->isCurrentAction( 'NewApplication' ) )
{
    $user = eZUser::currentUser();
    $application = new ezpRestClient();
    $application->name = ezpI18n::tr( 'extension/oauthadmin', 'New REST application' );
    $application->version = ezpRestClient::STATUS_DRAFT;
    $application->owner_id = $user->attribute( 'contentobject_id' );
    $application->created = time();
    $application->updated = 0;
    $application->version = ezpRestClient::STATUS_DRAFT;

    $session->save( $application );

    // The following does not work on PostgreSQL, incorrect id. Probably need refresh from DB.
    return $module->redirectToView( 'edit', array( $application->id ) );
}

// delete one application (dedicated button from full view)
if ( $module->isCurrentAction( 'DeleteApplication' ) )
{
    $applicationId = $module->actionParameter( 'ApplicationID' );
    $application = $session->load( 'ezpRestClient', $applicationId );
    if ( $module->hasActionParameter( 'ConfirmDelete' ) )
    {
        $session->delete( $application );
        return $module->redirectToView( 'list' );
    }
    // display confirmation request
    else
    {
        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'module', $module );
        $tpl->setVariable( 'application', $application );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezpI18n::tr( 'extension/oauthadmin', 'oAuthAdmin' ) ),
                                 array( 'url' => false,
                                        'text' => ezpI18n::tr( 'extension/oauthadmin', 'Confirm removal' ) )
        );

        $Result['content'] = $tpl->fetch( 'design:oauthadmin/delete_confirmation.tpl' );
        return $Result;
    }
}

// delete several applications (checkboxes on list view)
// not implemented yet. @todo: implement it !
if ( $module->isCurrentAction( 'DeleteApplicationList' ) )
{
    if ( $module->hasActionParameter['ConfirmDelete'] )
    {
        // confirmed, remove the application
    }
    else
    {
        // display confirmation request
    }
}

return $Result;
?>
