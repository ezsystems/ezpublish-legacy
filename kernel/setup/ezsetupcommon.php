<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

// This file holds shared functions for the ezsetup files

include_once( 'lib/ezutils/classes/ezini.php' );

/*!
 \return an array with tests that need to be run
         and succeed for the setup to continue.
*/
function eZSetupCriticalTests()
{
    $ini =& eZINI::instance();
    return $ini->variableArray( 'SetupSettings', 'CriticalTests' );
}

/*!
 \return an array with tests that when run will give information on finetuning.
*/
function eZSetupOptionalTests()
{
    $ini =& eZINI::instance();
    return $ini->variableArray( 'SetupSettings', 'OptionalTests' );
}

function eZSetupDatabaseMap()
{
    return array( 'mysql' => array( 'type' => 'mysql',
                                    'driver' => 'ezmysql',
                                    'name' => 'MySQL',
                                    'required_version' => '3.23',
                                    'has_demo_data' => true,
                                    'supports_unicode' => false ),
                  'pgsql' => array( 'type' => 'pgsql',
                                    'driver' => 'ezpostgresql',
                                    'name' => 'PostgreSQL',
                                    'required_version' => '7.3',
                                    'has_demo_data' => false,
                                    'supports_unicode' => true ) );
}

function eZSetupFetchPersistenceList()
{
    $persistenceList = array();
    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    $http =& eZHTTPTool::instance();
    $postVariables = $http->attribute( 'post' );
    foreach ( $postVariables as $name => $value )
    {
        if ( preg_match( '/^P_([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)$/', $name, $matches ) )
        {
            $persistenceGroup = $matches[1];
            $persistenceName = $matches[2];
            $persistenceList[$persistenceGroup][$persistenceName] = $value;
        }
    }
    return $persistenceList;
}

function eZSetupMergePersistenceList( &$persistenceList, $persistenceDataList )
{
    foreach ( $persistenceDataList as $persistenceData )
    {
        $persistenceName = $persistenceData[0];
        $persistenceValues = $persistenceData[1];
        if ( !isset( $persistenceList[$persistenceName] ) )
        {
            $values =& $persistenceList[$persistenceName];
            foreach ( $persistenceValues as $persistenceValueName => $persistenceValueData )
            {
                $values[$persistenceValueName] = $persistenceValueData['value'];
            }
        }
        else
        {
            $oldValues =& $persistenceList[$persistenceName];
            foreach ( $persistenceValues as $persistenceValueName => $persistenceValueData )
            {
//                eZDebug::writeDebug( $oldValues, 'oldValues' );
//                eZDebug::writeDebug( $persistenceValueName, 'persistenceValueName' );
//                eZDebug::writeDebug( $persistenceValueData, 'persistenceValueData' );
                if ( !isset( $oldValues[$persistenceValueName] ) )
                {
                    $oldValues[$persistenceValueName] = $persistenceValueData['value'];
                }
                else if ( is_array( $persistenceValueData['value'] ) and
                          isset( $persistenceValueData['merge'] ) and
                          $persistenceValueData['merge'] )
                {
                     $merged = array_merge( $oldValues[$persistenceValueName], $persistenceValueData['value'] );
                     if ( isset( $persistenceValueData['unique'] ) and
                          $persistenceValueData['unique'] )
                          $merged = array_unique( $merged );
                     $oldValues[$persistenceValueName] = $merged;
                }
                else
                {
                    $oldValues[$persistenceValueName] = $persistenceValueData['value'];
                }
            }
        }
    }
}
?>
