#!/usr/bin/env php
<?php
//
// Definition of updateeztimetype
//
// Created on: <17-May-2005 10:40:00 rl>
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

/*! \file updateiscontainer.php
*/

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );


$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish eZTimeType update script\n\n" .
                                                         "This script will transform all eZTimeType attributes value\n" .
                                                         "from GMT to the server local time.\n Please backup your database" .
                                                         "before to restore your old values if results will something else" .
                                                         "you expect.".
                                                         "\n" .
                                                         "Note: The script must be run for each siteaccess" .
                                                         "\n" .
                                                         "updateeztimetype.php -sSITEACCESS" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true,
                                      'min_version'  => '3.5.2',
                                      'max_version' => '3.5.3' ) );

$script->startup();

$options = $script->getOptions( "", "", array() );

$script->initialize();

if ( !$script->validateVersion() )
{
    $cli->output( "Unsuitable eZ publish version: " );
    $cli->output( eZPublishSDK::version() );
    $script->shutdown( 1 );
}


include_once( 'lib/ezlocale/classes/eztime.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );

$db =& eZDB::instance();

if ( !is_object( $db ) )
{
    $cli->error( 'Could not initialize database:' );
    $script->shutdown( 1 );
}

$times_array = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                   /*$field_filters =*/null,
                                                   /*$conds =*/array( 'data_type_string' => 'eztime' ),
                                                   /*$sorts =*/null,
                                                   /*$limit =*/null,
                                                   /*$asObject =*/false,
                                                   /*$grouping =*/false,
                                                   /*$custom_fields =*/null );

$timezone_offset = date( 'Z' );
$count_updated = 0;

$cli->output( "Timezone offset: $timezone_offset sec." );
$cli->output( "\nUpdating eZTimeType attributes..." );

foreach( $times_array as $item )
{
    // if time stamp more when 24 hours then identify
    // it as old style full timestamp, and update it
    $oldtimestamp = $item[ 'data_int' ];
    $timestamp = $item[ 'data_int' ];

    if ( $timestamp >= EZTIME_SECONDS_A_DAY )
    {
        $date = getdate( $timestamp );
        $timestamp = $date[ 'hours' ] * EZTIME_SECONDS_AN_HOUR +
                     $date[ 'minutes' ] * EZTIME_SECONDS_A_MINUTE +
                     $date[ 'seconds' ];
    }
    else
    {
        $timestamp = ( $timestamp + $timezone_offset ) % EZTIME_SECONDS_A_DAY;
    }

    if ( $timestamp != $oldtimestamp )
    {
        $sql = "UPDATE ezcontentobject_attribute " .
               "SET data_int=$timestamp, sort_key_int=$timestamp " .
               "WHERE id=" . $item[ 'id' ];

        if ( !$db->query( $sql ) )
        {
            $cli->error( "Failed to run update query..." );
            $cli->error( $db->errorMessage() );

            if ( $count_updated > 0 )
            {
                $cli->output( "The update are not finished properly and attributes are\n" .
                              "updated partially. Check you settings and restore your\n" .
                              "database from backup before running script again" );
            }
            $script->shutdown( 1 );
        }

        $cli->output( "contentobject_id = " . $item[ 'contentobject_id' ] . ", " .
                      "attribute id = " . $item[ 'id' ] . ": " .
                      "old_timestamp = $oldtimestamp, new_timestamp = $timestamp" );

        $count_updated++;
    }
}

$cli->output( "\nNumber of updated eZTimeType attributes: $count_updated" );
$script->shutdown();

?>
