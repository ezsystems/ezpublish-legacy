<?php
//
// Definition of eZCsvimport class
//
// Created on: <27-Sep-2006 22:23:27 sp>
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

/*! \file ezcsvimport.php
*/

/*!
  \class eZCsvimport ezcsvimport.php
  \brief The class eZCsvimport does

*/


include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( "lib/ezlocale/classes/ezdatetime.php" );


$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish CSV import script\n\n" .
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

if ( !$options['node'] and count( $options['arguments'] ) < 2 )
{
    $cli->error( "Need a parenrt node to place object under and file to read data from" );
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


$cli->output( "Going to import objects of class " . $createClass . " under  node " . $nodeID . " from file " . $inputFileName .  "\n" );


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

    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $nodeID,
                                                     'is_main' => 1
                                                     )
                                                 );
    $nodeAssignment->store();

    $version =& $contentObject->version( 1 );
    $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
    $version->store();


    $contentObjectID = $contentObject->attribute( 'id' );

    $attributes =& $contentObject->attribute( 'contentobject_attributes' );




    while ( list( $key, $attribute ) = each( $attributes ) )
    {
        $dataString = $objectData[$key];
        switch ( $datatypeString = $attribute->attribute( 'data_type_string' ) )
        {
            case 'ezimage':
            case 'ezbinaryfile':
            case 'ezmedia':
            {
                $dataString = $storageDir . $dataString;
                break;
            }
            default:
        }
        $attribute->fromString( $dataString );
        $attribute->store();
    }

    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                 'version' => 1 ) );
}

fclose( $fp );

$script->shutdown();



?>
