<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
                                    'supports_unicode' => false ),
                  'postgresql' => array( 'type' => 'postgresql',
                                         'driver' => 'ezpostgresql',
                                         'name' => 'PostgreSQL',
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
            foreach ( $persistenceValues as $persitenceValueName => $persistenceValueData )
            {
                eZDebug::writeDebug( $oldValues, 'oldValues' );
                eZDebug::writeDebug( $persistenceValueName, 'persistenceValueName' );
                eZDebug::writeDebug( $persistenceValueData, 'persistenceValueData' );
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

/*!
    Define the test items. Also define error messages. These should later be internationalized.
*/
function configuration()
{
	$config = eZINI::instance( "setup.ini" );
	$namedArray = $config->getNamedArray();

	// Convert the pseudo array (like "item1;item2;item3") to real arrays 
	// and convert "true" and "false" to real true and false
	foreach( array_keys( $namedArray ) as $mainKey )
	{
		foreach( array_keys( $namedArray[$mainKey] ) as $key )
		{
			if ( preg_match( "/;/", $namedArray[$mainKey][$key] ) )
				$namedArray[$mainKey][$key] = preg_split( "/;/", $namedArray[$mainKey][$key] );
			else if ( $namedArray[$mainKey][$key] == "true" )
			    $namedArray[$mainKey][$key] = true;
			else if ( $namedArray[$mainKey][$key] == "false" )
			    $namedArray[$mainKey][$key] = false;
		}
	}
	return $namedArray;
}


/*!
	Complete the testItems array with the values that we got of the former post form
	Create an array that we can use for the handover in forms
*/
function completeItems( &$testItems, &$http, &$handoverResult )
{
	foreach( array_keys( $testItems ) as $key )
	{
		if ( $http->hasVariable( $key ) )
		{
			// Transform "true" to true and "false" to false
			switch( $http->postVariable( $key ) )
			{
				case "true":
				{
					$testItems[$key]["pass"] = true;
				}break;

				case "false":
				{
					$testItems[$key]["pass"] = false;
				}break;
			}
			$handoverResult[] = array( "name" => $key, "value" => $testItems[$key]["pass"] ? "true" : "false" );
		}
	}   
}


?>
