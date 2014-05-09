<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$NodeID = $Params['NodeID'];

$curNode = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$curNode )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$curNode->attribute( 'can_hide' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

if ( eZOperationHandler::operationIsAvailable( 'content_hide' ) )
{
    $operationResult = eZOperationHandler::execute( 'content',
                                                    'hide',
                                                     array( 'node_id' => $NodeID ),
                                                     null, true );
}
else
{
    eZContentOperationCollection::changeHideStatus( $NodeID );
}


$hasRedirect = eZRedirectManager::redirectTo( $Module, false );
if ( !$hasRedirect )
{
    // redirect to the parent node
    if( ( $parentNodeID = $curNode->attribute( 'parent_node_id' ) ) == 1 )
        $redirectNodeID = $NodeID;
    else
        $redirectNodeID = $parentNodeID;
    return $Module->redirectToView( 'view', array( 'full', $redirectNodeID ) );
}

?>
