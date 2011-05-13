<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

$http = eZHTTPTool::instance();

if ( $Module->isCurrentAction( 'Custom' ) )
{
    $typeIdentifier = $Module->actionParameter( 'TypeIdentifer' );
    $itemID = $Module->actionParameter( 'ItemID' );
    $collaborationItem = eZCollaborationItem::fetch( $itemID );
    $handler = eZCollaborationItemHandler::instantiate( $typeIdentifier );
    return $handler->handleCustomAction( $Module, $collaborationItem );
}

$Result = array();
$Result['content'] = false;
$Result['path'] = array( array( 'url' => false,
                                ezpI18n::tr( 'kernel/collaboration', 'Collaboration custom action' ) ) );

?>
