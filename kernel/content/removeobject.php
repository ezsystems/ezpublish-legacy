<?php
//
//
// Created on: <08-Nov-2002 16:02:26 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/common/template.php" );

$Module =& $Params["Module"];

$http =& eZHTTPTool::instance();

$viewMode = $http->sessionVariable( "CurrentViewMode" );
$deleteIDArray = $http->sessionVariable( "DeleteIDArray" );
$contentObjectID = $http->sessionVariable( 'ContentObjectID' );
$contentNodeID = $http->sessionVariable( 'ContentNodeID' );
if ( count( $deleteIDArray ) <= 0 )
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID ) );

// Cleanup and redirect back when cancel is clicked
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $http->removeSessionVariable( "CurrentViewMode" );
    $http->removeSessionVariable( "DeleteIDArray" );
    $http->removeSessionVariable( 'ContentObjectID' );
    $http->removeSessionVariable( 'ContentNodeID' );
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID ) );
}

$moveToTrash = true;
if ( $http->hasPostVariable( 'SupportsMoveToTrash' ) )
{
    if ( $http->hasPostVariable( 'MoveToTrash' ) )
        $moveToTrash = true;
    else
        $moveToTrash = false;
}

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    eZContentObjectTreeNode::removeSubtrees( $deleteIDArray, $moveToTrash );
    return $Module->redirectToView( 'view', array( $viewMode, $contentNodeID ) );
}

$moveToTrashAllowed = true;
$deleteResult = array();
$childCount = 0;
$info = eZContentObjectTreeNode::subtreeRemovalInformation( $deleteIDArray );
$deleteResult = $info['delete_list'];
if ( !$info['move_to_trash'] )
{
    $moveToTrashAllowed = false;
}
$totalChildCount = $info['total_child_count'];
$canRemoveAll = $info['can_remove_all'];

$tpl =& templateInit();

$tpl->setVariable( "module", $Module );
$tpl->setVariable( 'moveToTrashAllowed', $moveToTrashAllowed ); // Backwards compatability
$tpl->setVariable( "ChildObjectsCount", $totalChildCount ); // Backwards compatability
$tpl->setVariable( "DeleteResult",  $deleteResult ); // Backwards compatability
$tpl->setVariable( 'move_to_trash_allowed', $moveToTrashAllowed );
$tpl->setVariable( "remove_list",  $deleteResult );
$tpl->setVariable( 'total_child_count', $totalChildCount );
$tpl->setVariable( 'remove_info', $info );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:node/removeobject.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', 'Remove object' ) ) );
?>
