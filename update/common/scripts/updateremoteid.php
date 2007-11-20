#!/usr/bin/env php
<?php
//
// Created on: <13-Apr-2004 14:19:36 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Remote ID Generator\n\n" .
                                                        "This script will go over all objects, classes and nodes and make sure they have a remote id,\n" .
                                                        "\n" .
                                                        "updateremoteid.php" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions();
$script->initialize();

$db = eZDB::instance();

$contentINI = eZINI::instance( 'content.ini' );

//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezcontentclass.php' );

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
