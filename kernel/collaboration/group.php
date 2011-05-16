<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ViewMode = $Params['ViewMode'];
$GroupID = $Params['GroupID'];

$Offset = $Params['Offset'];
if ( !is_numeric( $Offset ) )
    $Offset = 0;

$collabGroup = eZCollaborationGroup::fetch( $GroupID );
if ( $collabGroup === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !eZCollaborationViewHandler::groupExists( $ViewMode ) )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$view = eZCollaborationViewHandler::instance( $ViewMode, eZCollaborationViewHandler::TYPE_GROUP );

$template = $view->template();

$collabGroupTitle = $collabGroup->attribute( 'title' );

$viewParameters = array( 'offset' => $Offset );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'collab_group', $collabGroup );

$Result = array();
$Result['content'] = $tpl->fetch( $template );
$Result['path'] = array( array( 'url' => 'collaboration/view/summary',
                                'text' => ezpI18n::tr( 'kernel/collaboration', 'Collaboration' ) ),
                         array( 'url' => false,
                                'text' => 'Group' ),
                         array( 'url' => false,
                                'text' => $collabGroupTitle ) );

?>
