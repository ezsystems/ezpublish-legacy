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

// This file holds the test functions that are used by step 1

define( 'EZ_SETUP_TEST_SUCCESS', 1 );
define( 'EZ_SETUP_TEST_FAILURE', 2 );

function eZSetupTestTable()
{
    return array( 'phpversion' => array( 'eZSetupTestPhpVersion' ),
                  'directory_permissions' => array( 'eZSetupTestFilePermissions' ),
                  'settings_permission' => array( 'eZSetupTestFilePermissions' ),
                  'database_extensions' => array( 'eZSetupTestExtension' ),
                  'php_magicquotes' => array( 'eZSetupCheckMagicQuotes' ),
                  'mbstring_extension' => array( 'eZSetupMBStringExtension' ),
                  'image_conversion' => array( 'eZSetupCheckTestFunctions' ),
                  'imagegd_extension' => array( 'eZSetupTestExtension' ),
                  'imagemagick_program' => array( 'eZSetupCheckExecutable' ) );
}

function eZSetupConfigVariable( $type, $name )
{
    $config =& eZINI::instance( 'setup.ini' );
    return $config->variable( $type, $name );
}

function eZSetupConfigVariableArray( $type, $name )
{
    $config =& eZINI::instance( 'setup.ini' );
    return $config->variableArray( $type, $name );
}

function eZSetupRunTests( $testList, &$arguments, $client )
{
    $testTable = eZSetupTestTable();

    $testResults = array();
    $persistenceResults = array();
    $testResult = EZ_SETUP_TEST_SUCCESS;
    $successCount = 0;
    foreach ( $testList as $testItem )
    {
        $testName = $testItem;
        $testElement = array();
        $testElement[0] = EZ_SETUP_TEST_FAILURE;
        if ( !isset( $testTable[$testItem] ) )
        {
            eZDebug::writeError( "The setup test '$testName' is not defined", $client );
            continue;
        }
        $testInfo = $testTable[$testItem];
        $testFunction = $testInfo[0];
        if ( !function_exists( $testFunction ) )
            continue;
        $testResultArray = $testFunction( $testName, $arguments );
        if ( $testResultArray['result'] )
        {
            $testElement[0] = EZ_SETUP_TEST_SUCCESS;
            ++$successCount;
        }
        else
            $testResult = EZ_SETUP_TEST_FAILURE;
        if ( isset( $testResultArray['persistent_data'] ) )
        {
            $persistenceResults[] = array( $testName, $testResultArray['persistent_data'] );
        }
        else if ( isset( $testResultArray['persistence_list'] ) )
        {
            $persistenceResults = array_merge( $persistenceResults, $testResultArray['persistence_list'] );
        }
        $testElement[1] = $testName;
        $testElement[2] = $testResultArray;
        $testResults[] = $testElement;
    }
    return array( 'result' => $testResult,
                  'results' => $testResults,
                  'persistence_list' => $persistenceResults,
                  'success_count' => $successCount );
}

function eZSetupCheckTestFunctions( $type, &$arguments )
{
    $testList = eZSetupConfigVariableArray( $type, 'TestList' );
    $requireType = eZSetupConfigVariable( $type, 'Require' );

    $runResult = eZSetupRunTests( $testList, $arguments, 'eZSetupCheckTestFunctions' );
    $testResults = $runResult['results'];
    $testResult = $runResult['result'];
    $successCount = $runResult['success_count'];
    $persistenceData = $runResult['persistence_list'];

    $result = true;
    if ( $requireType == 'one' )
    {
        if ( $successCount == 0 )
            $result = false;
    }
    else if ( $successCount < count( $extensionList ) )
        $result = false;
    return array( 'result' => $result,
                  'persistence_list' => $persistenceData,
                  'test_results' => $testResults );
}

function eZSetupCheckMagicQuotes( $type, &$arguments )
{
    $magicQuote = get_magic_quotes_gpc();
    $result = ( $magicQuote == 0 );
    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ) ) );
}

/*!
    Test if PHP version is equal or greater than required version
*/
function eZSetupTestPhpVersion( $type, &$arguments )
{
    $minVersion = eZSetupConfigVariable( $type, 'MinimumVersion' );

    /*
    // Get the operating systems name
    $operatingSystem = split( " ", php_uname() );
    $operatingSystem = strtolower( $operatingSystem[0] );

	// Find out if there is an os specific version needed
    if ( isset( $argArray["req"][$operatingSystem] ) )
        $neededVersion = $argArray["req"][$operatingSystem];
    else if ( isset( $argArray["req"] ) )
        $neededVersion = $argArray["req"];
    else
        $neededVersion = $argArray["req"];
	*/

	$neededVersion = $minVersion;

    // compare the versions
    $currentVersion = phpversion();
    $compCurrentVersion = str_replace( ".", "", $currentVersion );
    $compNeededVersion = str_replace( ".", "", $neededVersion );
    $result = false;
    if ( $compCurrentVersion >= $compNeededVersion )
        $result = true;

    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ),
                                              'found' => array( 'value' => $currentVersion ),
                                              'required' => array( 'value' => $neededVersion ) ),
                  'needed_version' => $neededVersion,
                  'current_version' => $currentVersion );
}


/*!
    Test if the extensios are loaded
*/
function eZSetupTestExtension( $type, &$arguments )
{
    $extensionList = eZSetupConfigVariableArray( $type, 'Extensions' );
    $requireType = eZSetupConfigVariable( $type, 'Require' );
    $foundExtensions = array();
    $failedExtensions = array();
    foreach ( $extensionList as $extension )
    {
//        if ( false )
        if ( extension_loaded( $extension ) )
        {
            $foundExtensions[] = $extension;
        }
        else
        {
            $failedExtensions[] = $extension;
        }
    }
    $result = true;
    if ( $requireType == 'one' )
    {
        if ( count( $foundExtensions ) == 0 )
            $result = false;
    }
    else if ( count( $foundExtensions ) < count( $extensionList ) )
        $result = false;

    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ),
                                              'found' => array( 'value' => $foundExtensions,
                                                                'merge' => false,
                                                                'unique' => true ),
                                              'checked' => array( 'value' => $extensionList,
                                                                  'merge' => true,
                                                                  'unique' => true ) ),
                  'require_type' => $requireType,
                  'extension_list' => $extensionList,
                  'failed_extensions' => $failedExtensions,
                  'found_extensions' => $foundExtensions );
}


/*!
	Test file permissions
*/
function eZSetupTestFilePermissions( $type, &$arguments )
{
    $fileList = eZSetupConfigVariableArray( $type, 'CheckList' );
    include_once( 'lib/ezutils/classes/ezdir.php' );

    $ini =& eZINI::instance();
    $dirPermission = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
    $filePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );

    $result = true;
    $resultElements = array();
    foreach ( $fileList as $file )
    {
        $resultElement = array();
        $resultElement['file'] = $file;
        unset( $fileResult );
        $fileResult =& $resultElement['result'];
        $fileResult = true;
        unset( $filePerm );
        $filePerm =& $resultElement['permission'];
        $filePerm = false;
        $resultElements[] = $resultElement;

        $file = eZDir::cleanPath( $file );
        if ( !file_exists( $file ) )
            continue;
        if ( is_dir( $file ) )
        {
            $filePerm = $dirPermission;

            $dir = $file;

            $createdFile = false;
            $hash = md5( microtime() );
	    	$tmpfname = $dir . "/ezsetup_" . $hash . ".tmp";
            $tempCreated = false;
    		$fp = @fopen( $tmpfname, "w" );
    		if ( $fp )
            {
                $tempCreated = true;
	    		$test = fwrite( $fp, "This file can safely be deleted.\nIt gets created by the eZ setup module of eZ publish." );
        		if ( $test )
                {
    	    		$test = fclose( $fp );
                    if ( $test )
                    {
                        $test = unlink( $tmpfname );
                        if ( $test )
                            $createdFile = true;
                    }
                }
            }
            if ( $tempCreated and
                 file_exists( $tmpfname ) )
                unlink( $tmpfname );

            if ( !$createdFile )
            {
	    		$result = false;
                $fileResult = false;
            }
        }
    	else if ( is_file( $file ) )
    	{
            $filePerm = $filePermission;
	    	if ( !is_writable( $file ) )
            {
	    		$result = false;
                $fileResult = false;
            }
    	}
    }

    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ) ),
                  'current_path' => realpath( '.' ),
                  'result_elements'   => $resultElements );
}



/*!
	Test if a program can be found in our path and is executable
*/
function eZSetupCheckExecutable( $type, &$arguments )
{
    include_once( 'lib/ezutils/classes/ezsys.php' );
    include_once( 'lib/ezutils/classes/ezdir.php' );
    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    $http =& eZHTTPTool::instance();

    $filesystemType = eZSys::filesystemType();
    $envSeparator = eZSys::envSeparator();
	$programs = eZSetupConfigVariableArray( $type, $filesystemType . '_Executable' );
    $systemSearchPaths = explode( $envSeparator, eZSys::path() );
	$additionalSearchPaths = eZSetupConfigVariableArray( $type, $filesystemType . '_SearchPaths' );
	$excludePaths = eZSetupConfigVariableArray( $type, $filesystemType . '_ExcludePaths' );
    $extraPath = array();
    if ( $http->hasPostVariable( $type . '_ExtraPath' ) )
        $extraPath = explode( $envSeparator, $http->postVariable( $type . '_ExtraPath' ) );
    $searchPaths = array_merge( $systemSearchPaths, $additionalSearchPaths, $extraPath );

	$result = false;
    $correctPath = false;
    foreach ( $programs as $program )
    {
	foreach( $searchPaths as $path )
	{
		$pathProgram = eZDir::path( array( $path, $program ) );
		if ( file_exists( $pathProgram ) )
		{
            if ( $filesystemType == 'unix' )
            {
                $relativePath = $path;
                if ( preg_match( "#^/(.+)$#", $path, $matches ) )
                    $relativePath = $matches[1];
                $relativePath = eZDir::cleanPath( $relativePath );
            }
            else
            {
                $relativePath = $path;
                if ( preg_match( "#^[a-zA-Z]:[/\\\\](.+)$#", $path, $matches ) )
                    $relativePath = $matches[1];
                $relativePath = eZDir::cleanPath( $relativePath );
            }
            $exclude = false;
            foreach ( $excludePaths as $excludePath )
            {
                $excludePath = strtolower( $excludePath );
                $match = strtolower( $program . "@" . $relativePath );
                if ( $match == $excludePath )
                {
                    $exclude = true;
                    break;
                }
                else if ( $relativePath == $excludePath )
                {
                    $exclude = true;
                    break;
                }
            }
            if ( $exclude )
                continue;
			if ( function_exists( "is_executable" ) )
			{
				if ( is_executable( $pathProgram ) )
				{
					$result = true;
					$correctPath = $path;
					break;
				}
			}
			else
			{
				// Windows system
                $result = true;
                $correctPath = $path;
				break;
			}
		}
    }
    if ( $result )
        break;
	}

	return array( 'result' => $result,
                  'persistent_data' => array( 'path' => array( 'value' => $correctPath ),
                                              'result' => array( 'value' => $result ) ),
                  'env_separator' => $envSeparator,
                  'filesystem_type' => $filesystemType,
                  'extra_path' => $extraPath,
                  'correct_path' => $correctPath,
                  'system_search_path' => $systemSearchPaths,
                  'additional_search_path' => $additionalSearchPaths );
}



/*!
	Test php ini settings
*/
function testPHPIni( $parameters )
{
	$setting = $parameters["setting"];
    $state = $parameters["state"];

    if ( (bool) ini_get( $setting ) == $state )
        $pass = true;
    else
        $pass = false;

    $status = $pass;
	return array( "status" => $status, "pass" => $pass );
}


/*!
	Test if mbstring is available
*/
function eZSetupMBStringExtension( $type, &$arguments )
{
    include_once( "lib/ezi18n/classes/ezmbstringmapper.php" );
    $result = eZMBStringMapper::hasMBStringExtension();
    $charsetList = eZMBStringMapper::charsetList();
    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ) ),
                  'charset_list' => $charsetList );
}



?>
