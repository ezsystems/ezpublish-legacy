#!/usr/bin/env php
<?php
//
// Created on: <23-Sep-2003 15:47:14 amos>
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

/*! \file updatexmltext.php
*/

set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$endl = $cli->endlineString();

$script =& eZScript::instance( array( 'description' => ( "eZ publish attribute filter update.".
                                                         "\n" .
                                                         "updateattributefileter.php" ),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[sql]",
                                "",
                                array( 'sql' => "Display sql queries"
                                       ) );
$script->initialize();

$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for xml text field update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezbinaryfilehandler.php' );
include_once( 'kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php' );

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( $showSQL );

$attributeList =& eZContentClassAttribute::fetchList( true, array( 'version' => 0 ) );

$classAttributeIDList = array();
$classDataTypeList = array();

// A list of datatypes which we know to have simple values and the field they use
$simpleTypes = array( array( 'field' => 'data_int',
                             'sort_field' => 'sort_key_int',
                             'datatypes' => array( 'ezinteger', 'ezboolean', 'ezdate', 'ezdatetime', 'ezobjectrelation' ) ),
                      array( 'field' => 'data_text',
                             'sort_field' => 'sort_key_string',
                             'datatypes' => array( 'ezstring', 'ezemail' ) ) );

$complexTypes = array();

for ( $i = 0; $i < count( $attributeList ); ++$i )
{
    $attribute =& $attributeList[$i];
    $classDataTypeString = $attribute->attribute( 'data_type_string' );
    if ( !isset( $classDataTypeList[$classDataTypeString] ) )
    {
        $classDataType =& $attribute->attribute( 'data_type' );
        $sortKeyType = $classDataType->sortKeyType();
        if ( $sortKeyType )
        {
            $classDataTypeList[$classDataTypeString] = array( 'sort_key_type' => $sortKeyType );
        }
        else
            $classDataTypeList[$classDataTypeString] = false;
    }
    if ( $classDataTypeList[$classDataTypeString] )
    {
        $isSimpleType = false;
        foreach ( $simpleTypes as $simpleTypeKey => $simpleType )
        {
            if ( in_array( $classDataTypeString, $simpleType['datatypes'] ) )
            {
                $isSimpleType = true;
                if ( !isset( $simpleTypes[$simpleTypeKey]['id_list'] ) )
                    $simpleTypes[$simpleTypeKey]['id_list'] = array();
                $simpleTypes[$simpleTypeKey]['id_list'][] = $attribute->attribute( 'id' );
                break;
            }
        }
        if ( !$isSimpleType )
        {
            if ( !isset( $complexTypes[$classDataTypeString] ) )
                $complexTypes[$classDataTypeString] = array();
            $complexTypes[$classDataTypeString][] = $attribute->attribute( 'id' );
        }
    }
}
unset( $attributeList );

foreach ( $simpleTypes as $simpleType )
{
    $cli->output( "Will update " . count( $simpleType['id_list'] )  ." class attribute(s) for datatype(s) " . implode( ", ", $simpleType['datatypes'] ) . " using field " . $simpleType['field'] );
    if ( count( $simpleType['id_list'] ) > 0 )
    {
        $sortField = $simpleType['sort_field'];
        $field = $simpleType['field'];
        $db =& eZDB::instance();
        $sql = "UPDATE ezcontentobject_attribute SET $sortField=$field WHERE contentclassattribute_id IN (" . implode( ",", $simpleType['id_list'] ) . ")";
        $db->query( $sql );
    }
}

foreach ( $complexTypes as $complexTypeString => $complexTypeList )
{
    $cli->output( "Will update " . count( $complexTypeList )  ." class attribute(s) for datatype " . $complexTypeString );
    $counter = 0;
    $column = 0;
    $objectAttributeList =& eZContentObjectAttribute::fetchListByClassID( $complexTypeList );
    $objectAttributeCount = count( $objectAttributeList );
    $cli->output( "Total of " . $objectAttributeCount . " attributes" );
    for ( $i = 0; $i < $objectAttributeCount; ++$i )
    {
        $objectAttribute =& $objectAttributeList[$i];
        // re-store the attribute
        $objectAttribute->store();
        $cli->output( ".", false );
        flush();
        ++$counter;
        ++$column;
        if ( $column > 70 or $counter == $objectAttributeCount )
        {
            $percent = ( $counter * 100 ) / ( $objectAttributeCount );
            $percentText = number_format( $percent, 2 );
            $cli->output( " $percentText%" );
            $column = 0;
        }
    }
    $cli->output();
    unset( $objectAttributeList );
}


// $attributeCount = eZContentObjectAttribute::fetchListByClassID( $classAttributeIDList, false, array( 'offset' => 0,
//                                                                                                      'length' => 3 ),
//                                                                 false, true );
// if ( $showDebug )
//     print( "Attribute count = '$attributeCount'\n" );

$script->shutdown();

?>
