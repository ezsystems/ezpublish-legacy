#!/usr/bin/env php
<?php
//
// Created on: <13-Apr-2004 14:19:36 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Remote ID Generator\n\n" .
                                                         "This script will go over all objects, classes and nodes and make sure they have a remote id,\n" .
                                                         "\n" .
                                                         "updateremoteid.php" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions();
$script->initialize();

$db =& eZDB::instance();

$contentINI =& eZINI::instance( 'content.ini' );

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentclass.php' );

$script->setIterationData( '.', '~' );

// Handle classes

$classList = eZContentClass::fetchList();
$script->resetIteration( count( $classList ) );
$cli->output( $cli->stylize( 'header', "Generating for classes" ) );

foreach ( array_keys( $classList ) as $classKey )
{
    $class =& $classList[$classKey];
    $class->remoteID();
    $script->iterate( $cli, true, "Generated remote ID for class " . $class->attribute( 'name' ) .
                      "(" . $class->attribute( 'id' ) . ")" );
}

$cli->output();

// Handle nodes

$nodeCount = eZContentObjectTreeNode::fetchListCount();
$script->resetIteration( $nodeCount );
$cli->output( $cli->stylize( 'header', "Generating for nodes" ) );

$index = 0;
while ( $index < $nodeCount )
{
    $nodeList = eZContentObjectTreeNode::fetchList( true, $index, 50 );
    foreach ( array_keys( $nodeList ) as $nodeKey )
    {
        $node =& $nodeList[$nodeKey];
        $node->remoteID();
        $script->iterate( $cli, true, "Generated remote ID for node " . $node->attribute( 'name' ) .
                          "(" . $node->attribute( 'node_id' ) . ")" );
    }
    $index += count( $nodeList );
}

$cli->output();

// Handle objects

$objectCount = eZContentObject::fetchListCount();
$script->resetIteration( $objectCount );
$cli->output( $cli->stylize( 'header', "Generating for objects" ) );

$index = 0;
while ( $index < $objectCount )
{
    $objectList = eZContentObject::fetchList( true, null, $index, 50 );
    foreach ( array_keys( $objectList ) as $objectKey )
    {
        $object =& $objectList[$objectKey];
        $object->remoteID();
        $script->iterate( $cli, true, "Generated remote ID for object " . $object->attribute( 'name' ) .
                          "(" . $object->attribute( 'id' ) . ")\n" .
                          "Status: (" . $object->attribute( 'status' ) . ")" );
    }
    $index += count( $objectList );
}

$script->shutdown();

?>
