<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
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

// This file holds the test functions that are used by step 1

define( 'EZ_SETUP_TEST_SUCCESS', 1 );
define( 'EZ_SETUP_TEST_FAILURE', 2 );

function eZSetupTestTable()
{
    return array( 'phpversion' => array( 'eZSetupTestPhpVersion' ),
                  'directory_permissions' => array( 'eZSetupTestFilePermissions' ),
                  'settings_permission' => array( 'eZSetupTestFilePermissions' ),
                  'database_extensions' => array( 'eZSetupTestExtension' ),
                  'database_all_extensions' => array( 'eZSetupTestExtension' ),
                  'php_magicquotes' => array( 'eZSetupCheckMagicQuotes' ),
                  'magic_quotes_runtime' => array( 'eZSetupCheckMagicQuotesRuntime' ),
                  'php_register_globals' => array( 'eZSetupCheckRegisterGlobals' ),
                  'mbstring_extension' => array( 'eZSetupMBStringExtension' ),
                  'zlib_extension' => array( 'eZSetupTestExtension' ),
                  'file_upload' => array( 'eZSetupTestFileUpload' ),
                  'open_basedir' => array( 'eZSetupTestOpenBasedir' ),
                  'safe_mode' => array( 'eZSetupTestSafeMode' ),
                  'image_conversion' => array( 'eZSetupCheckTestFunctions' ),
                  'imagegd_extension' => array( 'eZSetupCheckGDVersion' ),
                  'texttoimage_functions' => array( 'eZSetupTestFunctionExists' ),
                  'imagemagick_program' => array( 'eZSetupCheckExecutable' ),
                  'memory_limit' => array( 'eZSetupTestMemLimit' ),
                  'execution_time' => array( 'eZSetupTestExecutionTime' ),
                  'accept_path_info' => array( 'eZSetupTestAcceptPathInfo' ) );
}

function eZSetupConfigVariable( $type, $name )
{
    $config =& eZINI::instance( 'setup.ini' );
    return $config->variable( $type, $name );
}

function eZSetupImageConfigVariableArray( $type, $name )
{
    $config =& eZINI::instance( 'image.ini' );
    return $config->variableArray( $type, $name );
}

function eZSetupConfigVariableArray( $type, $name )
{
    $config =& eZINI::instance( 'setup.ini' );
    return $config->variableArray( $type, $name );
}

function eZSetupRunTests( $testList, &$arguments, $client, &$givenPersistentList )
{
    eZSetupPrvtExtractExtraPaths( $givenPersistentList );

    $testTable = eZSetupTestTable();

    $testResults = array();
    $persistenceResults = array();
    $testResult = EZ_SETUP_TEST_SUCCESS;
    $successCount = 0;
    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    $http =& eZHTTPTool::instance();
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
        if ( $http->hasPostVariable( $testItem . '_Ignore' ) and
             $http->postVariable( $testItem . '_Ignore' ) != 0 )
        {
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

    $runResult = eZSetupRunTests( $testList, $arguments, 'eZSetupCheckTestFunctions', $dummy=null );
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

function eZSetupTestFileUpload( $type, &$arguments )
{
    $fileUploads = ini_get( 'file_uploads' );
    $result = $fileUploads == "1";
    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ) ) );
}

function eZSetupCheckMagicQuotesRuntime( $type, &$arguments )
{
    $magicQuote = get_magic_quotes_runtime();
    $result = ( $magicQuote == 0 );
    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ) ) );
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
    $unstableVersionArray = eZSetupConfigVariableArray( $type, 'UnstableVersions' );

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
    $currentVersionArray = explode( '.', $currentVersion );
    $neededVersionArray = explode( '.', $neededVersion );

    $warningVersion = false;
    $result = eZSetupPrvtVersionCompare( $currentVersionArray, $neededVersionArray ) >= 0;

    if ( $result )
    {
        foreach ( array_keys( $unstableVersionArray ) as $key )
        {
            $unstableVersion = explode( '.', $unstableVersionArray[$key] );
            if ( eZSetupPrvtVersionCompare( $currentVersionArray, $unstableVersion ) == 0 )
            {
                $result = false;
                $warningVersion = true;
                break;
            }
        }
    }

    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ),
                                              'found' => array( 'value' => $currentVersion ),
                                              'required' => array( 'value' => $neededVersion ) ),
                  'needed_version' => $neededVersion,
                  'current_version' => $currentVersion,
                  'warning_version' => $warningVersion );
}

/*!
  Test if Apache setting for AcceptPathInfo is enabled
*/
function eZSetupTestAcceptPathInfo( $type, &$arguments )
{
    $testPath = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/eZ_accept_path_info_test';
    $testPath = 'http://' . str_replace( '//', '/', $testPath );
    $fp = fopen( $testPath, 'r' );

    return array( 'result' => ( $fp !== false ),
                  'persistent_data' => array( 'result' => array( 'value' => ( $fp !== false ) ) ) );
}

function eZSetupTestFunctionExists( $type, &$arguments )
{
    $functionList = eZSetupConfigVariableArray( $type, 'Functions' );
    $requireType = eZSetupConfigVariable( $type, 'Require' );
    $foundFunctions = array();
    $failedFunctions = array();
    foreach ( $functionList as $function )
    {
        $function = strtolower( $function );
        if ( function_exists( $function ) )
        {
            $foundFunctions[] = $function;
        }
        else
        {
            $failedFunctions[] = $function;
        }
    }
    $result = true;
    if ( $requireType == 'one' )
    {
        if ( count( $foundFunctions ) == 0 )
            $result = false;
    }
    else if ( count( $foundFunctions ) < count( $functionList ) )
        $result = false;

    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ),
                                              'found' => array( 'value' => $foundFunctions,
                                                                'merge' => false,
                                                                'unique' => true ),
                                              'checked' => array( 'value' => $functionList,
                                                                  'merge' => true,
                                                                  'unique' => true ) ),
                  'require_type' => $requireType,
                  'extension_list' => $functionList,
                  'failed_extensions' => $failedFunctions,
                  'found_extensions' => $foundFunctions );
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

            if ( !eZSetupPrvtAreDirAndFilesWritable( $dir ) )
            {
                $result     = false;
                $fileResult = false;
            }
        }
    	else if ( is_file( $file ) )
    	{
            $filePerm = $filePermission;

            if ( !eZFile::isWriteable( $file ) )
            {
                $result = false;
                $fileResult = false;
            }
    	}
    }
    $safeMode = ini_get( 'safe_mode' );
    return array( 'result' => $result,
                  'safe_mode' => $safeMode,
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
    $imageIniPath = eZSetupImageConfigVariableArray( 'ShellSettings', 'ConvertPath' );

    /*
     * We save once entered extra path in the persistent data list
     * to keep it within setup steps.
     *
     * This treek is needed, for example, in "registration" step,
     * where user has no chance to enter extra path again
     * due to missing input field for this purpose.
     */

    // compute extra path
    $extraPath = array();
    if ( $http->hasPostVariable( $type . '_ExtraPath' ) )
    {
        $GLOBALS['eZSetupCheckExecutable_'.$type.'_ExtraPath'] = $http->postVariable( $type . '_ExtraPath' );
        $extraPath = explode( $envSeparator, $http->postVariable( $type . '_ExtraPath' ) );
    }
    else if ( isset( $GLOBALS['eZSetupCheckExecutable_'.$type.'_ExtraPath'] ) )
        $extraPath = explode( $envSeparator, $GLOBALS['eZSetupCheckExecutable_'.$type.'_ExtraPath'] );

    // if extra path was given in any way
    if ( $extraPath )
    {
        // remove program from path name if entered
        foreach ( $extraPath as $path )
        {
            foreach ( $programs as $program )
            {
                if ( strpos( $path, $program) == strlen( $path ) - strlen( $program ) )
                {
                    $extraPath[] = substr( $path, strpos( $path, $program) );
                }
            }
        }
    }

    $searchPaths = array_merge( $systemSearchPaths, $additionalSearchPaths, $extraPath, $imageIniPath );

	$result = false;
    $correctPath = false;
    $correctProgram = false;
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
                else // windows
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
                        $correctProgram = $program;
                        break;
                    }
                }
                else
                {
                    // Windows system
                    $result = true;
                    $correctPath = $path;
                    $correctProgram = $program;
                    break;
                }
            }
        }
        if ( $result )
            break;
	}

    $extraPathAsString = implode( $envSeparator, $extraPath );

	return array( 'result' => $result,
                  'persistent_data' => array( 'path' => array( 'value' => $correctPath ),
                                              'program' => array( 'value' => $correctProgram ),
                                              'extra_path' => array( 'value' => $extraPathAsString,
                                                                     'merge' => TRUE ),
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
  Test GD version
*/
function eZSetupCheckGDVersion( $type, &$arguments )
{
    $result = function_exists( 'imagegd2' );
    return array( 'result' => $result,
                  'persistent_data' => array( 'result' => array( 'value' => $result ) ) );
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


function eZSetupCheckRegisterGlobals( $type, &$arguments )
{
    $registerGlobals = ini_get( 'register_globals' );
    $result = ( $registerGlobals == 0 );
    return array( 'result' => $result,
                  'persistent_data' => array() );
}

/*!
 Check the php.ini file to get timeout limit
*/
function eZSetupTestExecutionTime( $type, &$arguments )
{
    $minExecutionTime = eZSetupConfigVariable( $type, 'MinExecutionTime' );
    $execTimeLimit = get_cfg_var( 'max_execution_time' );

    if ( $execTimeLimit === false )
    {
        return array( 'result' => true,
                      'persistent_data' => array( 'result' => array( 'value' => true ) ) );
    }

    if ( $minExecutionTime <= $execTimeLimit )
        return array( 'result' => true,
                      'persistent_data' => array( 'result' => array( 'value' => true ) ) );

    return array( 'result' => false,
                  'persistent_data' => array( 'result' => array( 'value' => false ) ),
                  'required_execution_time' => $minExecutionTime,
                  'current_execution_time' => $execTimeLimit );
}

/*!
 Checks the php.ini file to see if the memory limit is set high enough
*/
function eZSetupTestMemLimit( $type, &$arguments )
{
    $minMemory = eZSetupConfigVariable( $type, 'MinMemoryLimit' );
    $memoryLimit = get_cfg_var( 'memory_limit' );
    if ( $memoryLimit  === false )
    {
        return array( 'result' => true,
                      'persistent_data' => array( 'result' => array( 'value' => true ) ) );
    }

    $byteMinMem = intval( $minMemory );
    switch ( $minMemory{strlen( $minMemory ) - 1} )
    {
        case 'G':
            $byteMinMem *= 1024;
        case 'M':
            $byteMinMem *= 1024;
        case 'K':
            $byteMinMem *= 1024;
    }

    $byteMemLimit = intval( $memoryLimit );
    switch ( $memoryLimit{strlen( $memoryLimit ) - 1} )
    {
        case 'G':
            $byteMemLimit *= 1024;
        case 'M':
            $byteMemLimit *= 1024;
        case 'K':
            $byteMemLimit *= 1024;
    }

    if ( $byteMinMem <= $byteMemLimit )
        return array( 'result' => true,
                      'persistent_data' => array( 'result' => array( 'value' => true ) ) );

    return array( 'result' => false,
                  'persistent_data' => array( 'result' => array( 'value' => false ) ),
                  'required_memory' => $minMemory,
                  'current_memory' => $memoryLimit );
}

function eZSetupTestOpenBasedir( $type, &$arguments )
{
    $openBasedir = ini_get( 'open_basedir' );
    $returnData = array( 'result' => true,
                         'persistent_data' => array() );
    if ( $openBasedir != '' and
         $openBasedir != '.' )
    {
        $returnData['warnings'] = array( array( 'name' => 'open_basedir',
                                                'text' => array( 'open_basedir is in use and can give problems running eZ publish due to bugs in some PHP versions.',
                                                                 'It\'s recommended that it is turned off if you experience problems running eZ publish.' ) ) );
    }
    return $returnData;
}

/*!
 Check if setup is installed using windows or linux installer

 \return 'linux' if using linux installer,
         'windows' if using windows installer,
         false if not using any installer
*/
function eZSetupTestInstaller()
{
    if ( file_exists( '.linux' ) )
    {
        return 'linux';
    }
    else if ( file_exists( '.windows' ) )
    {
        return 'windows';
    }
    return false;
}

function eZSetupTestSafeMode( $type, &$arguments )
{
    $safeMode = ini_get( 'safe_mode' );
//     print( "safe_mode=$safeMode<br/>" );
    $safeModeIncludeDir = ini_get( 'safe_mode_include_dir' );
//     print( "safe_mode_include_dir=$safeModeIncludeDir<br/>" );
    $safeModeGID = ini_get( 'safe_mode_gid' );
//     print( "safe_mode_gid=$safeModeGID<br/>" );
    $result = true;
    if ( $safeMode or strtolower( $safeMode ) == 'off' )
    {
        $result = false;
    }
    return array( 'result' => $result,
                  'current_path' => realpath( '.' ),
                  'persistent_data' => array() );
}

/*!
 Check if two version arrays are equel, greater or less than each other

 \param user first version array
 \param second version array

 \return < 0 if 1. version less than 2. version
           0 if versions are equal
         > 0 if 1. version is greater than 2. version
*/
function eZSetupPrvtVersionCompare( $versionArray1, $versionArray2 )
{
    $equal = false;
    $count = min( count( $versionArray1 ), count( $versionArray2 ) );
    for ( $i = 0; $i < $count; ++$i )
    {
        $equal = false;
        if ( $versionArray1[$i] > $versionArray2[$i] )
        {
            return 1;
        }
        else if ( $versionArray1[$i] < $versionArray2[$i] )
        {
            return -1;
        }
        $equal = true;
    }
    if ( $equal )
        return 0;
}


/* Find previously saved extra paths and export them
 * to global variables.
 */
function eZSetupPrvtExtractExtraPaths( &$givenPersistentList )
{
    if( !$givenPersistentList ) // null or empty array
        return;

    foreach( $givenPersistentList as $key => $val )
    {
        if( isset( $val['extra_path'] ) )
            $GLOBALS['eZSetupCheckExecutable_'.$key.'_ExtraPath'] = $val['extra_path'];
    }
}

/*! Check if a given directory and all files within that directory
 * are writable
 */
function eZSetupPrvtAreDirAndFilesWritable( $dir )
{
    if ( !eZDir::isWriteable( $dir ) )
        return FALSE;

   // Check if all files within a given directory are writeable
   $files =& eZDir::findSubitems( $dir, 'f' ); // find only files, skip dirs and symlinks
   $fileSeparator = eZSys::fileSeparator();

   foreach ( $files as $file )
   {
       if ( !eZFile::isWriteable( $dir . $fileSeparator . $file ) )
           return FALSE;
   }

   return TRUE;
}

?>
