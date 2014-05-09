<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ViewMode = $Params['ViewMode'];
$ItemID = $Params['ItemID'];

$Offset = $Params['Offset'];
if ( !is_numeric( $Offset ) )
    $Offset = 0;

/** @var eZCollaborationItem $collabItem */
$collabItem = eZCollaborationItem::fetch( $ItemID );

if ( !$collabItem->userIsParticipant( eZUser::currentUser() ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
}

$collabHandler = $collabItem->handler();
$collabItem->handleView( $ViewMode );
$template = $collabHandler->template( $ViewMode );
$collabTitle = $collabItem->title();

$viewParameters = array( 'offset' => $Offset );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'collab_item', $collabItem );

$Result = array();
$Result['content'] = $tpl->fetch( $template );

$collabHandler->readItem( $collabItem );

$Result['path'] = array( array( 'url' => 'collaboration/view/summary',
                                'text' => ezpI18n::tr( 'kernel/collaboration', 'Collaboration' ) ),
                         array( 'url' => false,
                                'text' => $collabTitle ) );

?>
