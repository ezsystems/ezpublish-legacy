#!/usr/bin/env php
<?php
//
// Definition of eZCsvimport class
//
// Created on: <27-Sep-2006 22:23:27 sp>
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

/*! \file ezcsvimport.php
*/

/*!
  \class eZCsvimport ezcsvimport.php
  \brief The class eZCsvimport does

*/


require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish CSV import script\n\n" .
                                                        "\n" .
                                                        "\n" .
                                                        "\n" .
                                                        "\n" .
                                                        "" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[class:][creator:][storage-dir:]",
                                "[node][file]",
                                array( 'node' => 'parent node_id to upload object under',
                                       'file' => 'file to read CSV data from',
                                       'class' => 'class identifier to create objects',
                                       'creator' => 'user id of imported objects creator',
                                       'storage-dir' => 'path to directory which will be added to the path of CSV elements' ),
                                false,
                                array( 'user' => true ));
$script->initialize();

if ( count( $options['arguments'] ) < 2 )
{
    $cli->error( "Need a parent node to place object under and file to read data from" );
    $script->shutdown( 1 );
}

$nodeID = $options['arguments'][0];
$inputFileName = $options['arguments'][1];
$createClass = $options['class'];
$creator = $options['creator'];
if ( $options['storage-dir'] )
{
    $storageDir = $options['storage-dir'];
}
else
{
    $storageDir = '';
}

$csvLineLength = 100000;

$cli->output( "Going to import objects of class $createClass under node $nodeID from file $inputFileName\n" );

$node = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$node )
{
    $cli->error( "No such node to import objects" );
    $script->shutdown( 1 );
}
$parentObject = $node->attribute( 'object' );

$class = eZContentClass::fetchByIdentifier( $createClass );

if ( !$class )
{
    $cli->error( "No class with identifier $createClass" );
    $script->shutdown( 1 );
}

$fp = @fopen( $inputFileName, "r" );
if ( !$fp )
{
    $cli->error( "Can not open file $inputFileName for reading" );
    $script->shutdown( 1 );
}

while ( $objectData = fgetcsv( $fp, $csvLineLength , ';', '"' ) )
{

    $contentObject = $class->instantiate( $creator );
    $contentObject->store();

    $nodeAssignment = eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $nodeID,
                                                     'is_main' => 1
                                                     )
                                                 );
    $nodeAssignment->store();

    $version = $contentObject->version( 1 );
    $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
    $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
    $version->store();

    $contentObjectID = $contentObject->attribute( 'id' );

    $attributes = $contentObject->attribute( 'contentobject_attributes' );

    while ( list( $key, $attribute ) = each( $attributes ) )
    {
        $dataString = $objectData[$key];
        switch ( $datatypeString = $attribute->attribute( 'data_type_string' ) )
        {
            case 'ezimage':
            case 'ezbinaryfile':
            case 'ezmedia':
            {
                $dataString = eZDir::path( array( $storageDir, $dataString ) );
                break;
            }
            default:
        }
        $attribute->fromString( $dataString );
        $attribute->store();
    }

    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                 'version' => 1 ) );
}

fclose( $fp );

$script->shutdown();

?>
