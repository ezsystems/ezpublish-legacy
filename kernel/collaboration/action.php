<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
