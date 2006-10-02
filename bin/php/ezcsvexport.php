<?php
//
// Definition of eZCsvexport class
//
// Created on: <27-Sep-2006 17:23:23 sp>
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

/*! \file ezcsvexport.php
*/

/*!
  \class eZCsvexport ezcsvexport.php
  \brief The class eZCsvexport does

*/

function fputcsv4( $fh, $arr )
{
    $csv = "";
    while ( list( $key, $val ) = each( $arr ) )
    {
        $val = str_replace( '"', '""', $val );
        $csv .= '"'.$val.'";';
    }
    $csv = substr( $csv, 0, -1 );
    $csv .= "\n";
    if ( ! $num = @fwrite( $fh, $csv ) )
        return FALSE;
    else
        return $num;
}

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish CSV export script\n\n" .
                                                         "\n" .
                                                         "\n" .
                                                         "\n" .
                                                         "\n" .
                                                         "" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true,
                                      'user' => true ) );

$script->startup();

$options = $script->getOptions( "[storage-dir:]",
                                "[node]",
                                array( 'node' => 'node_id or url_alias of the node to export',
                                       'storage-dir' => 'directory to place exported files if any'),
                                false,
                                array( 'user' => true ));
$script->initialize();

if ( !$options['node'] and count( $options['arguments'] ) < 1 )
{
    $cli->error( "Need a node to export and file for output" );
    $script->shutdown( 1 );
}


$nodeID = $options['arguments'][0];

if ( $options['storage-dir'] )
{
    $storageDir = $options['storage-dir'];
}
else
{
    $storageDir = '';
}


$cli->output( "Going to export subtree from node " . $nodeID . " to directory " . $storageDir .  "\n" );


$node = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$node )
{
    $cli->error( "No such node" );
    $script->shutdown( 1 );
}

$subTree = $node->subTree();
$openedFPs = array();

while ( list( $key, $childNode ) = each( $subTree ) )
{
    $object = $childNode->attribute( 'object' );

    $classIdentifier = $object->attribute( 'class_identifier' );

    if ( !isset( $openedFPs[$classIdentifier] ) )
    {
        $tempFP = @fopen( $storageDir . $classIdentifier . '.csv', "w" );
        if ( !$tempFP )
        {
            $cli->error( "Can not open output file for $classIdentifier class" );
            $script->shutdown( 1 );
        }
        else
        {
            $cli->output( "Created file $classIdentifier.csv " );
            $openedFPs[$classIdentifier] = $tempFP;
        }
    }
    else
    {
        if ( ! $openedFPs[$classIdentifier] )
        {
            $cli->error( "Can not open output file for $classIdentifier class" );
            $script->shutdown( 1 );
        }
    }

    $fp = $openedFPs[$classIdentifier];




    $objectData = array();
    foreach ( $object->attribute( 'contentobject_attributes' ) as $attribute )
    {
        $attributeStringContent = $attribute->toString();

        switch ( $datatypeString = $attribute->attribute( 'data_type_string' ) )
        {
            case 'ezimage':
            {
                $imageFile =  array_pop( explode( '/', $attributeStringContent ) );
                // here it would be nice to add a check if such file allready exists
                eZFileHandler::copy( $attributeStringContent, $storageDir . $imageFile );
                $attributeStringContent = $imageFile;
                break;
            }
            case 'ezbinaryfile':
            case 'ezmedia':
            {
                $binaryData = explode( '|', $attributeStringContent );
                eZFileHandler::copy( $binaryData[0], $storageDir . $binaryData[1] );
                $attributeStringContent = $binaryData[1];
                break;
            }
            default:
        }

        $objectData[] = $attributeStringContent;
    }

//    $fp = fopen( $outputFileName, "w" );
    if ( !$fp )
    {
        $cli->error( "Can not open output file" );
        $script->shutdown( 1 );
    }
    if ( !fputcsv4( $fp, $objectData ) )
    {
        $cli->error( "Can not write to file" );
        $script->shutdown( 1 );
    }


}

while ( $fp = each( $openedFPs ) )
{
    fclose( $fp );
}

$script->shutdown();

?>
