<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ViewMode = $Params['ViewMode'];

$Offset = $Params['Offset'];
if ( !is_numeric( $Offset ) )
    $Offset = 0;

if ( !eZCollaborationViewHandler::exists( $ViewMode ) )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$view = eZCollaborationViewHandler::instance( $ViewMode );

$template = $view->template();

// $collaborationHandlers =& eZCollaborationItemHandler::fetchList();

$viewParameters = array( 'offset' => $Offset );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( $template );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/collaboration', 'Collaboration' ) ) );

?>
