<?php
//
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

$http = eZHTTPTool::instance();

$viewMode = $http->sessionVariable( "CurrentViewMode" );
$deleteIDArray = $http->sessionVariable( "DeleteIDArray" );
$contentNodeID = $http->sessionVariable( 'ContentNodeID' );

$requestedURI = '';
$userRedirectURI = '';
$requestedURI = $GLOBALS['eZRequestedURI'];
if ( $requestedURI instanceof eZURI )
{
    $userRedirectURI = $requestedURI->uriString( true );
}
$http->setSessionVariable( 'userRedirectURIReverseRelatedList', $userRedirectURI );

if ( $http->hasSessionVariable( 'ContentLanguage' ) )
{
    $contentLanguage = $http->sessionVariable( 'ContentLanguage' );
}
else
{
    $contentLanguage = false;
}
if ( is_array( $deleteIDArray ) && count( $deleteIDArray ) <= 0 )
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );

// Cleanup and redirect back when cancel is clicked
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $http->removeSessionVariable( "CurrentViewMode" );
    $http->removeSessionVariable( "DeleteIDArray" );
    $http->removeSessionVariable( 'ContentNodeID' );
    $http->removeSessionVariable( 'userRedirectURIReverseRelatedList' );
    $http->removeSessionVariable( 'HideRemoveConfirmation' );
    $http->removeSessionVariable( 'RedirectURIAfterRemove' );

    if ( $http->hasSessionVariable( 'RedirectIfCancel' )
      && $http->sessionVariable( 'RedirectIfCancel' ) )
    {
        $Module->redirectTo( $http->sessionVariable( 'RedirectIfCancel' ) );
        return $http->removeSessionVariable( 'RedirectIfCancel' );
    }
    else
    {
        return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );
    }
}

$contentINI = eZINI::instance( 'content.ini' );

$RemoveAction = $contentINI->hasVariable( 'RemoveSettings', 'DefaultRemoveAction' ) ?
                   $contentINI->variable( 'RemoveSettings', 'DefaultRemoveAction' ) : 'trash';
if ( $RemoveAction != 'trash' and $RemoveAction != 'delete' )
    $RemoveAction = 'trash';

$moveToTrash = ( $RemoveAction == 'trash' ) ? true : false;
if ( $http->hasPostVariable( 'SupportsMoveToTrash' ) )
{
    if ( $http->hasPostVariable( 'MoveToTrash' ) )
        $moveToTrash = $http->postVariable( 'MoveToTrash' ) ? true : false;
    else
        $moveToTrash = false;
}

$hideRemoveConfirm = $contentINI->hasVariable( 'RemoveSettings', 'HideRemoveConfirmation' ) ?
                     (( $contentINI->variable( 'RemoveSettings', 'HideRemoveConfirmation' ) == 'true' ) ? true : false ) : false;
if ( $http->hasSessionVariable( 'HideRemoveConfirmation' ) )
    $hideRemoveConfirm = $http->sessionVariable( 'HideRemoveConfirmation' );

if ( $http->hasPostVariable( "ConfirmButton" ) or
     $hideRemoveConfirm )
{
    if ( eZOperationHandler::operationIsAvailable( 'content_delete' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content',
                                                        'delete',
                                                         array( 'node_id_list' => $deleteIDArray,
                                                                'move_to_trash' => $moveToTrash ),
                                                          null, true );
    }
    else
    {
        eZContentOperationCollection::deleteObject( $deleteIDArray, $moveToTrash );
    }

    if ( $http->hasSessionVariable( 'RedirectURIAfterRemove' )
      && $http->sessionVariable( 'RedirectURIAfterRemove' ) )
    {
        $Module->redirectTo( $http->sessionVariable( 'RedirectURIAfterRemove' ) );
        $http->removeSessionVariable( 'RedirectURIAfterRemove' );
        return $http->removeSessionVariable( 'RedirectIfCancel' );
    }
    else
    {
        return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );
    }
}

$showCheck = $contentINI->hasVariable( 'RemoveSettings', 'ShowRemoveToTrashCheck' ) ?
             (( $contentINI->variable( 'RemoveSettings', 'ShowRemoveToTrashCheck' ) == 'false' ) ? false : true ) : true;

$info               = eZContentObjectTreeNode::subtreeRemovalInformation( $deleteIDArray );
$deleteResult       = $info['delete_list'];
$moveToTrashAllowed = $info['move_to_trash'];
$totalChildCount    = $info['total_child_count'];
$hasPendingObject   = $info['has_pending_object'];
$exceededLimit      = false;
$deleteNodeIdArray  = array();

// Check if number of nodes being removed not more then MaxNodesRemoveSubtree setting.
$maxNodesRemoveSubtree = $contentINI->hasVariable( 'RemoveSettings', 'MaxNodesRemoveSubtree' ) ?
                            $contentINI->variable( 'RemoveSettings', 'MaxNodesRemoveSubtree' ) : 100;

$deleteItemsExist = true; // If false, we should disable 'OK' button if count of each deletion items more then MaxNodesRemoveSubtree setting.

foreach ( array_keys( $deleteResult ) as $removeItemKey )
{
    $removeItem =& $deleteResult[$removeItemKey];
    $deleteNodeIdArray[$removeItem['node']->attribute( 'node_id' )] = 1;
    if ( $removeItem['child_count'] > $maxNodesRemoveSubtree )
    {
        $removeItem['exceeded_limit_of_subitems'] = true;
        $exceededLimit = true;
        $nodeObj = $removeItem['node'];
        if ( !$nodeObj )
            continue;

        $nodeID = $nodeObj->attribute( 'node_id' );
        $deleteIDArrayNew = array();
        foreach ( $deleteIDArray as $deleteID )
        {
            if ( $deleteID != $nodeID )
                $deleteIDArrayNew[] = $deleteID;
        }
        $deleteItemsExist = count( $deleteIDArrayNew ) != 0;
        $http->setSessionVariable( "DeleteIDArray", $deleteIDArrayNew );
    }
}

// We check if we can remove the nodes without confirmation
// to do this the following must be true:
// - The total child count must be zero
// - There must be no object removal (i.e. it is the only node for the object)
if ( $totalChildCount == 0 )
{
    $canRemove = true;
    foreach ( $deleteResult as $item )
    {
        if ( $item['object_node_count'] <= 1 )
        {
            $canRemove = false;
            break;
        }
    }
    if ( $canRemove )
    {
        if ( eZOperationHandler::operationIsAvailable( 'content_removelocation' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content',
                                                            'removelocation',
                                                             array( 'node_list' => array_keys( $deleteNodeIdArray ),
                                                                    'move_to_trash' => $moveToTrash ),
                                                              null, true );
        }
        else
        {
            eZContentOperationCollection::removeNodes( array_keys( $deleteNodeIdArray ) );
        }

        if ( $http->hasSessionVariable( 'RedirectURIAfterRemove' )
          && $http->sessionVariable( 'RedirectURIAfterRemove' ) )
        {
            $Module->redirectTo( $http->sessionVariable( 'RedirectURIAfterRemove' ) );
            $http->removeSessionVariable( 'RedirectURIAfterRemove' );
            return $http->removeSessionVariable( 'RedirectIfCancel' );
        }
        else
        {
            return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID, $contentLanguage ) );
        }
    }
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'reverse_related'        , $info['reverse_related_count'] );
$tpl->setVariable( 'module'                 , $Module );
$tpl->setVariable( 'moveToTrashAllowed'     , $moveToTrashAllowed ); // Backwards compatibility
$tpl->setVariable( 'ChildObjectsCount'      , $totalChildCount ); // Backwards compatibility
$tpl->setVariable( 'DeleteResult'           , $deleteResult ); // Backwards compatibility
$tpl->setVariable( 'move_to_trash_allowed'  , ( $moveToTrashAllowed and $showCheck ) );
$tpl->setVariable( 'remove_list'            , $deleteResult );
$tpl->setVariable( 'total_child_count'      , $totalChildCount );
$tpl->setVariable( 'remove_info'            , $info );
$tpl->setVariable( 'exceeded_limit'         , $exceededLimit );
$tpl->setVariable( 'delete_items_exist'     , $deleteItemsExist );
$tpl->setVariable( 'move_to_trash'          , $moveToTrash );
$tpl->setVariable( 'has_pending_object'     , $hasPendingObject );

$Result = array();
$Result['content'] = $tpl->fetch( "design:node/removeobject.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/content', 'Remove object' ) ) );
?>
