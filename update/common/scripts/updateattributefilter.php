#!/usr/bin/env php
<?php
//
// Created on: <23-Sep-2003 15:47:14 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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

include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

function help()
{
    $argv = $_SERVER['argv'];
    $cli =& eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]...\n" .
                  "eZ publish attribute filter update.\n" .
                  "\n" .
                  "General options:\n" .
                  "  -h,--help          display this help and exit \n" .
                  "  -q,--quiet         do not give any output except when errors occur\n" .
                  "  -s,--siteaccess    selected siteaccess for operations, if not specified default siteaccess is used\n" .
                  "  -d,--debug         display debug output at end of execution\n" .
                  "  -v,--verbose       be verbose during execution\n" .
                  "  -c,--colors        display output using ANSI colors\n" .
                  "  -n                 dry run, will not commit any data\n" .
                  "  --sql              display sql queries\n" .
                  "  --logfiles         create log files\n" .
                  "  --no-logfiles      do not create log files (default)\n" .
                  "  --no-colors        do not use ANSI coloring (default)\n" );
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

$siteaccess = false;
$debugOutput = false;
$showDebug = false;
$allowedDebugLevels = false;
$useDebugAccumulators = false;
$useDebugTimingpoints = false;
$useIncludeFiles = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = false;
$showSQL = false;

$fixErrors = true;
$fixAllAttributes = true;
$fixAttribute = true;
$fixURL = true;

$optionsWithData = array( 's' );
$longOptionsWithData = array( 'siteaccess' );

$readOptions = true;

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( $readOptions and
         strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
        if ( strlen( $arg ) > 1 and
             $arg[1] == '-' )
        {
            $flag = substr( $arg, 2 );
            if ( in_array( $flag, $longOptionsWithData ) )
            {
                $optionData = $argv[$i+1];
                ++$i;
            }
            if ( $flag == 'help' )
            {
                help();
                exit();
            }
            else if ( $flag == 'siteaccess' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
            else if ( $flag == 'debug' )
            {
                $debugOutput = true;
            }
            else if ( $flag == 'verbose' )
            {
                $showDebug = true;
            }
            else if ( $flag == 'quiet' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'colors' )
            {
                $useColors = true;
            }
            else if ( $flag == 'no-colors' )
            {
                $useColors = false;
            }
            else if ( $flag == 'no-logfiles' )
            {
                $useLogFiles = false;
            }
            else if ( $flag == 'logfiles' )
            {
                $useLogFiles = true;
            }
            else if ( $flag == 'sql' )
            {
                $showSQL = true;
            }
        }
        else
        {
            $flag = substr( $arg, 1, 1 );
            $optionData = false;
            if ( in_array( $flag, $optionsWithData ) )
            {
                if ( strlen( $arg ) > 2 )
                {
                    $optionData = substr( $arg, 2 );
                }
                else
                {
                    $optionData = $argv[$i+1];
                    ++$i;
                }
            }
            if ( $flag == 'h' )
            {
                help();
                exit();
            }
            else if ( $flag == 'q' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'c' )
            {
                $useColors = true;
            }
            else if ( $flag == 'v' )
            {
                $showDebug = true;
            }
            else if ( $flag == 'n' )
            {
                $fixErrors = false;
            }
            else if ( $flag == 'd' )
            {
                $debugOutput = true;
                if ( strlen( $arg ) > 2 )
                {
                    $levels = explode( ',', substr( $arg, 2 ) );
                    $allowedDebugLevels = array();
                    foreach ( $levels as $level )
                    {
                        if ( $level == 'all' )
                        {
                            $useDebugAccumulators = true;
                            $allowedDebugLevels = false;
                            $useDebugTimingpoints = true;
                            break;
                        }
                        if ( $level == 'accumulator' )
                        {
                            $useDebugAccumulators = true;
                            continue;
                        }
                        if ( $level == 'timing' )
                        {
                            $useDebugTimingpoints = true;
                            continue;
                        }
                        if ( $level == 'include' )
                        {
                            $useIncludeFiles = true;
                        }
                        if ( $level == 'error' )
                            $level = EZ_LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = EZ_LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = EZ_LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = EZ_LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = EZ_LEVEL_TIMING;
                        $allowedDebugLevels[] = $level;
                    }
                }
            }
            else if ( $flag == 's' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
        }
    }
}
$script->setUseDebugOutput( $debugOutput );
$script->setAllowedDebugLevels( $allowedDebugLevels );
$script->setUseDebugAccumulators( $useDebugAccumulators );
$script->setUseDebugTimingPoints( $useDebugTimingpoints );
$script->setUseIncludeFiles( $useIncludeFiles );

if ( $webOutput )
    $useColors = true;

$cli->setUseStyles( $useColors );
$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->setUseSiteAccess( $siteaccess );

$script->initialize();

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
