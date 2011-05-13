<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$Result = array();
$Result['content'] = '';

// Identify whether the input data was submitted through URL parameters or through POST
if ( $Module->isCurrentAction( 'Assign' )                 and
     $Module->hasActionParameter( 'SelectedStateIDList' ) and
     $Module->hasActionParameter( 'ObjectID' ) )
{
    $selectedStateIDList = $Module->actionParameter( 'SelectedStateIDList' );
    $objectID = $Module->actionParameter( 'ObjectID' );
}
else
{
    $objectID = isset( $Params['ObjectID'] ) ? $Params['ObjectID'] : false;
    $selectedStateIDList = isset( $Params['SelectedStateID'] ) ? array( $Params['SelectedStateID'] ) : false ;
}

// Change object's state
if ( $objectID and $selectedStateIDList )
{
    if ( eZOperationHandler::operationIsAvailable( 'content_updateobjectstate' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content', 'updateobjectstate',
                                                        array( 'object_id'     => $objectID,
                                                               'state_id_list' => $selectedStateIDList ) );
    }
    else
    {
        eZContentOperationCollection::updateObjectState( $objectID, $selectedStateIDList );
    }

    // Redirect to the provided URI, or to the root if not provided.
    // @TODO : in case this view is called through Ajax, make sure the module ends another way.
    $Module->hasActionParameter( 'RedirectRelativeURI' ) ? $Module->redirectTo( $Module->actionParameter( 'RedirectRelativeURI' ) ) : $Module->redirectTo( '/' );
}
elseif ( $objectID )
{
    // Propose an interface. The end-user probably accessed this view through a simple URL like
    // '/state/assign/<object_id>'
    if ( ( $object = eZContentObject::fetch( $objectID ) ) !== null  )
    {
        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'node', $object->attribute( 'main_node' ) );
        $Result['content'] = $tpl->fetch( 'design:state/assign.tpl' );
    }
}
else
    $Module->hasActionParameter( 'RedirectRelativeURI' ) ? $Module->redirectTo( $Module->actionParameter( 'RedirectRelativeURI' ) ) : $Module->redirectTo( '/' );

$Result['path'] = array(
                    array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
                    array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'Assign' ) )
                   );
?>
