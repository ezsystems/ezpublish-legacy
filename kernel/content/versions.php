<?php
//
// Created on: <22-Apr-2002 15:41:30 bf>
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

include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjectversion.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$ObjectID = $Params["ObjectID"];

$object =& eZContentObject::fetch( $ObjectID );

if ( ! $object->attribute( 'can_read' ) )
{
        $Module->redirectTo( '/error/403' );
        return;
}

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( "RevertButton" )  )
{
    if ( !$object->attribute( 'can_edit' ) )
    {
        $Module->redirectTo( '/error/403' );
        return;
    }
    $assignedNodes =& $object->attribute( 'assigned_nodes' );
    $versionNodes = array();
    $object->revertTo( $http->postVariable( "RevertToVersionID" )  );
    $version =& $object->version( $http->postVariable( "RevertToVersionID" ) );
    $versionParentNodes =& $version->attribute( 'node_assignments' );
    foreach ( array_keys($versionParentNodes ) as $key )
    {
        $nodeAssignment =& $versionParentNodes[$key];
        $node =& eZContentObjectTreeNode::findNode( $nodeAssignment->attribute( 'parent_node' ), $object->attribute( 'id' ), true );
        if ( $node == null )
        {
            $parentNode = eZContentObjectTreeNode::fetch( $nodeAssignment->attribute( 'parent_node' ) );
            $node =& $parentNode->addChild( $object->attribute( 'id' ), $parentNode->attribute( 'id' ), true );
        }
        $node->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
        $node->store();
        $versionNodes[] = $node->attribute( 'node_id' );

    }
    foreach( array_keys( $assignedNodes ) as $key )
    {
        $node =& $assignedNodes[$key];
        if ( !in_array( $node->attribute( 'node_id' ), $versionNodes ) )
        {
            $node->remove();
        }
    }
    
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ObjectID . '/' );
    return;
}

if ( $http->hasPostVariable( "CopyRevertButton" )  )
{
    if ( !$object->attribute( 'can_edit' ) )
    {
        $Module->redirectTo( '/error/403' );
        return;
    }

    $object->copyRevertTo( $http->postVariable( "RevertToVersionID" )  );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ObjectID . '/' );
    return;
}

$versions =& $object->versions();

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "object", $object->attribute( "id" ) ), // Object ID
//                       array( "class", $class->attribute( "id" ) ), // Class ID
                      array( "section", 0 ) ) ); // Section ID, 0 so far

$tpl->setVariable( "object", $object );

$tpl->setVariable( "versions", $versions );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/versions.tpl" );

?>
