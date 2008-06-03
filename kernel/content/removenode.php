<?php
//
// Created on: <12-Ноя-2002 16:14:13 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file removenode.php
*/

//include_once( 'lib/ezutils/classes/ezhttptool.php' );
require_once( 'kernel/common/template.php' );
require_once( 'kernel/common/i18n.php' );
//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezcontentobjectversion.php' );
//include_once( 'kernel/classes/ezcontentobjectattribute.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$http = eZHTTPTool::instance();

$tpl = templateInit();

$Module = $Params['Module'];
$ObjectID = $Params['ObjectID'];

$NodeID = $Params['NodeID'];
if ( !isset( $EditVersion ) )
    $EditVersion = $Params['EditVersion'];

$object = eZContentObject::fetch( $ObjectID );
if ( $object === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_remove' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$version = $object->version( $EditVersion );
$node = eZContentObjectTreeNode::fetchNode( $ObjectID, $NodeID );
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


$Result['content'] = $tpl->fetch( 'design:node/removenode.tpl' );

$Result['path'] = array( array( 'text' => $object->attribute( 'name' ),
                                'url' => false ) );

?>
