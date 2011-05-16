<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ViewMode = $Params['ViewMode'];
$ItemID = $Params['ItemID'];

$Offset = $Params['Offset'];
if ( !is_numeric( $Offset ) )
    $Offset = 0;

$collabItem = eZCollaborationItem::fetch( $ItemID );
if ( $collabItem === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

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
