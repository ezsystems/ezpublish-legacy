#!/usr/bin/env php
<?php
//
// Definition of updateeztimetype
//
// Created on: <17-May-2005 10:40:00 rl>
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

/*! \file updateiscontainer.php
*/

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );


$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish eZTimeType update script\n\n" .
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
                                     'min_version' => '3.4.7',
                                     'max_version' => '3.5.3' ) );

$script->startup();

$options = $script->getOptions( "", "", array() );

$script->initialize();

if ( !$script->validateVersion() )
{
    $cli->output( "Unsuitable eZ Publish version: " );
    $cli->output( eZPublishSDK::version() );
    $script->shutdown( 1 );
}

//include_once( 'lib/ezlocale/classes/eztime.php' );
//include_once( 'kernel/classes/ezcontentobjectattribute.php' );

$db = eZDB::instance();

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
    // if attribute was already update the clear old flag and skip it:
    if ( $item[ 'data_float' ] == 1 )
    {
        $sql = "UPDATE ezcontentobject_attribute " .
               "SET data_float=0 " .
               "WHERE id=" . $item[ 'id' ];

        if ( !$db->query( $sql ) )
        {
            $cli->error( "Failed to run update query..." );
            $cli->error( $db->errorMessage() );
        }
        continue;
    }

    $oldtimestamp = $item[ 'data_int' ];
    $timestamp = $item[ 'data_int' ];

    if ( !is_null( $timestamp ) )
    {
        // if time stamp more when 24 hours then identify
        // it as old style full timestamp, and update it
        if ( $timestamp >= eZTime::SECONDS_A_DAY )
        {
            $date = getdate( $timestamp );
            $timestamp = $date[ 'hours' ] * eZTime::SECONDS_AN_HOUR +
                         $date[ 'minutes' ] * eZTime::SECONDS_A_MINUTE +
                         $date[ 'seconds' ];
        }
        else
        {
            $timestamp = ( $timestamp + $timezone_offset ) % eZTime::SECONDS_A_DAY;
        }
    }

    if ( $timestamp != $oldtimestamp )
    {
        $sql = "UPDATE ezcontentobject_attribute " .
               "SET data_int=$timestamp, " .
                   "sort_key_int=$timestamp, " .
                   "data_float=0 " .
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
