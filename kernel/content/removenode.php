<?php
//
// Created on: <12-Ноя-2002 16:14:13 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file removenode.php
*/

include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/common/template.php' );
include_once( 'kernel/common/i18n.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$http =& eZHTTPTool::instance();

$tpl =& templateInit();

$Module =& $Params['Module'];
$ObjectID = $Params['ObjectID'];

$NodeID = $Params['NodeID'];
if ( !isset( $EditVersion ) )
    $EditVersion =& $Params['EditVersion'];

$object =& eZContentObject::fetch( $ObjectID );
if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_remove' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$version =& $object->version( $EditVersion );
$node =& eZContentObjectTreeNode::fetchNode( $ObjectID, $NodeID );
if ( $node !== null )
    $ChildObjectsCount = $node->subTreeCount();
else
    $ChildObjectsCount = 0;
$ChildObjectsCount .= " ";
if ( $ChildObjectsCount == 1 )
    $ChildObjectsCount .= ezi18n( 'kernel/content/removenode',
                                  'child',
                                  '1 child' );
else
    $ChildObjectsCount .= ezi18n( 'kernel/content/removenode',
                                  'children',
                                  'several children' );

if ( $Module->isCurrentAction( 'ConfirmAssignmentRemove' ) )
{
    $nodeID = $http->postVariable( 'RemoveNodeID' ) ;
    $version->removeAssignment( $nodeID );
    $Module->redirectToView( "edit", array( $ObjectID, $EditVersion ) );
}
elseif ( $Module->isCurrentAction( 'CancelAssignmentRemove' ) )
{
    $Module->redirectToView( "edit", array( $ObjectID, $EditVersion ) );
}

$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'content_version', $version );
$tpl->setVariable( 'ChildObjectsCount', $ChildObjectsCount );
$tpl->setVariable( 'node', $node );


$Result['content'] =& $tpl->fetch( 'design:node/removenode.tpl' );

$Result['path'] = array( array( 'text' => $object->attribute( 'name' ),
                                'url' => false ) );

?>
