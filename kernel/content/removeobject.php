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
include_once( 'kernel/common/i18n.php' );

$Module =& $Params["Module"];

$http =& eZHTTPTool::instance();

$viewMode = $http->sessionVariable( "CurrentViewMode" );
$deleteIDArray = $http->sessionVariable( "DeleteIDArray" );
if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] = $policy->attribute( 'limitations' );
    }
}

$contentObjectID = $http->sessionVariable( 'ContentObjectID' );
$contentNodeID = $http->sessionVariable( 'ContentNodeID' );

$deleteResult = array();
$ChildObjectsCount = 0;
foreach ( $deleteIDArray as $deleteID )
{
    $node =& eZContentObjectTreeNode::fetch( $deleteID );
    if ( $node != null )
    {
        $object = $node->attribute( 'object' );
        $NodeName = $object->attribute( 'name' );
        $contentObject = $node->attribute( 'object' );
        $nodeID =  $node->attribute( 'node_id' );

        if ( !$object->attribute( 'can_remove' ) )
            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        if ( $object === null )
            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
        $ChildObjectsCount = $node->subTreeCount() . " ";
        if ( $ChildObjectsCount == 1 )
            $ChildObjectsCount .= ezi18n( 'kernel/content/removeobject',
                                          'child',
                                          '1 child' );
        else
            $ChildObjectsCount .= ezi18n( 'kernel/content/removeobject',
                                          'children',
                                          'several children' );
        $item = array( "nodeName" => $NodeName,
                       "childCount" => $ChildObjectsCount );
        $deleteResult[] = $item;
    }
}

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    foreach ( $deleteIDArray as $deleteID )
    {
        $node =& eZContentObjectTreeNode::fetch( $deleteID );
        if ( $node != null )
        {
            $object =  $node->attribute( 'object' );
            $children =& $node->subTree();
            foreach ( $children as $child )
            {
                $childObject =& $child->attribute( 'object' );
                $childNodeID = $child->attribute( 'node_id' );
                $childObject->remove( true, $childNodeID );
            }
            $object->remove( true, $deleteID );
        }
    }
    $Module->redirectTo( '/content/view/' . $viewMode . '/' . $contentNodeID . '/'  );
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/content/view/' . $viewMode . '/' . $contentNodeID . '/'  );
}
$Module->setTitle( "Remove " .$NodeName );

$tpl =& templateInit();

$tpl->setVariable( "module", $Module );
//$tpl->setVariable( "NodeID", $NodeID );
//$tpl->setVariable( "NodeName", $NodeName );
$tpl->setVariable( "ChildObjectsCount", $ChildObjectsCount );
$tpl->setVariable( "DeleteResult",  $deleteResult );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:node/removeobject.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Remove object' ) );
?>
