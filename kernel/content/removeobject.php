<?php
//
//
// Created on: <08-Nov-2002 16:02:26 wy>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file removeobject.php
*/
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

include_once( "kernel/common/template.php" );

$Module =& $Params["Module"];

$http =& eZHTTPTool::instance();

if ( isset( $Params["NodeID"] ) )
    $NodeID =& $Params["NodeID"];

$viewMode = $http->sessionVariable( "CurrentViewMode" );
if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] = $policy->attribute( 'limitations' );
    }
}

$node =& eZContentObjectTreeNode::fetch( $NodeID );
$ChildObjectsCount = $node->attribute( 'children_count' );

if ( $ChildObjectsCount <=1 )
    $ChildObjectsCount .= " child";
else
    $ChildObjectsCount .= " children";

if ( $node === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$object = $node->attribute( 'object' );

$NodeName = $object->attribute( 'name' );

if ( !$object->attribute( 'can_remove' ) )
     return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    $children =& $node->children();
    foreach ( $children as $child )
    {
       $childObject =& $child->attribute( 'object' );
       $childObject->remove( true, $NodeID );
    }
    $object->remove( true, $NodeID  );
    $Module->redirectTo( '/content/view/' . $viewMode . '/' .'2' . '/'  );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/content/view/' . $viewMode . '/' . '2' . '/'  );
}
$Module->setTitle( "Remove " .$NodeName );

$tpl =& templateInit();

$tpl->setVariable( "module", $Module );
$tpl->setVariable( "NodeID", $NodeID );
$tpl->setVariable( "NodeName", $NodeName );
$tpl->setVariable( "ChildObjectsCount", $ChildObjectsCount );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:node/removeobject.tpl" );
$Result['path'] = array( array( 'url' => '/content/removeobject/',
                                'text' => 'Remove object' ) );
?>

