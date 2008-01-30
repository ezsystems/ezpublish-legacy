#!/usr/bin/env php
<?php
//
// Definition of eZCsvexport class
//
// Created on: <27-Sep-2006 17:23:23 sp>
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

/*! \file ezcsvexport.php
*/

/*!
  \class eZCsvexport ezcsvexport.php
  \brief The class eZCsvexport does

*/

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish CSV export script\n" .
                                                        "\n" .
                                                        "ezcsvexport.php --storage-dir=export 2" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true,
                                     'user' => true ) );

$script->startup();

$options = $script->getOptions( "[storage-dir:]",
                                "[node]",
                                array( 'storage-dir' => 'directory to place exported files in' ),
                                false,
                                array( 'user' => true ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $cli->error( 'Specify a node to export' );
    $script->shutdown( 1 );
}

$nodeID = $options['arguments'][0];

if ( !is_numeric( $nodeID ) )
{
    $cli->error( 'Specify a numeric node ID' );
    $script->shutdown( 2 );
}

if ( $options['storage-dir'] )
{
    $storageDir = $options['storage-dir'];
}
else
{
    $storageDir = '';
}

$node = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$node )
{
    $cli->error( "No node with ID: $nodeID" );
    $script->shutdown( 3 );
}

$cli->output( "Going to export subtree from node $nodeID to directory $storageDir \n" );

$subTreeCount = $node->subTreeCount();

$script->setIterationData( '.', '~' );

$script->resetIteration( $subTreeCount );

$subTree = $node->subTree();
$openedFPs = array();

while ( list( $key, $childNode ) = each( $subTree ) )
{
    $status = true;

    $object = $childNode->attribute( 'object' );

    $classIdentifier = $object->attribute( 'class_identifier' );

    if ( !isset( $openedFPs[$classIdentifier] ) )
    {
        $tempFP = @fopen( $storageDir . '/' . $classIdentifier . '.csv', "w" );
        if ( $tempFP )
        {
            $openedFPs[$classIdentifier] = $tempFP;
        }
        else
        {
            $cli->error( "Can not open output file for $classIdentifier class" );
            $script->shutdown( 4 );
        }
    }
    else
    {
        if ( !$openedFPs[$classIdentifier] )
        {
            $cli->error( "Can not open output file for $classIdentifier class" );
            $script->shutdown( 4 );
        }
    }

    $fp = $openedFPs[$classIdentifier];

    $objectData = array();
    foreach ( $object->attribute( 'contentobject_attributes' ) as $attribute )
    {
        $attributeStringContent = $attribute->toString();

        if ( $attributeStringContent != '' )
        {
            switch ( $datatypeString = $attribute->attribute( 'data_type_string' ) )
            {
                case 'ezimage':
                {
                    $imagePathParts = explode( '/', $attributeStringContent );
                    $imageFile = array_pop( $imagePathParts );
                    // here it would be nice to add a check if such file allready exists
                    $success = eZFileHandler::copy( $attributeStringContent, $storageDir . '/' . $imageFile );
                    if ( !$success )
                    {
                        $status = false;
                    }
                    $attributeStringContent = $imageFile;
                } break;

                case 'ezbinaryfile':
                case 'ezmedia':
                {
                    $binaryData = explode( '|', $attributeStringContent );
                    $success = eZFileHandler::copy( $binaryData[0], $storageDir . '/' . $binaryData[1] );
                    if ( !$success )
                    {
                        $status = false;
                    }
                    $attributeStringContent = $binaryData[1];
                } break;

                default:
            }
        }

        $objectData[] = $attributeStringContent;
    }

    if ( !$fp )
    {
        $cli->error( "Can not open output file" );
        $script->shutdown( 5 );
    }

    if ( !fputcsv( $fp, $objectData, ';' ) )
    {
        $cli->error( "Can not write to file" );
        $script->shutdown( 6 );
    }

    $script->iterate( $cli, $status );
}

while ( $fp = each( $openedFPs ) )
{
    fclose( $fp['value'] );
}

$script->shutdown();

?>