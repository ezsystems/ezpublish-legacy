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


/*
 
Yes, this file still needs A LOT of work.

Here a short todo list (in no order):
    - create new user for database
     - split the file in more files
    - use internationalization    
    - more tests: 
        + modules (libxml?)
        + functions (mbstring, ttftextbox, ...)
        + programs (imagemagick)
        + critical combinations (winxp + php_isapi + ezsession = crash)
        + redhat: multipart/form-data
        + security tests (.htaccess, admin password) 
    - set nVH variables (siteDir, wwwDir, indexFile, includeDir)
    - download of ez publish by php script
    - upgrade option
    - catching of more errors
    - register email to ez systems
    - better dealing with errors: try to fix them!
    - more config options for first site.ini
    - create classes
    - installation of demo data (convert .tgz package to phpzip?)
    - test on file permissions is still very immature
    - set charsets!
...feel free to add to this list, what I forgot.

--kai, 2002-11-08

*/


include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

// Initialize template
$tpl =& eZTemplate::instance();
$tpl->registerFunction( "section", new eZTemplateSectionFunction( "section" ) );
$tpl->registerFunction( "include", new eZTemplateIncludeFunction() );


// Initialize HTTP variables, etc.
$http =& eZHttpTool::instance();

//
// Test at what step we are
//
if ( $http->hasVariable( "nextStep" ) )
    $step = $http->postVariable( "nextStep" );
else
    $step = 1; 

$tpl->setVariable( "script", eZSys::serverVariable( 'PHP_SELF' ) );
$tpl->setVariable( "step", $step );
$tpl->setVariable( "nextStep", $step+1 );
if ( $step > 1 )
    $tpl->setVariable( "prevStep", $step-1 );

switch( $step )
{
    case "4":
    {
        stepFour( $tpl, $http, $ini );
    }break;
    case "3":
    { 
        stepThree( $tpl, $http );
    }break;
    case "2":
    { 
        stepTwo( $tpl, $http );
    }break;    
    case "1":
    default:
    {
        stepOne( $tpl, $http );
    }
}


/*!

    Define the test items. Also define error messages. These should later be internationalized.
    Maybe using an .ini file would be good too? 

*/
function configuration()
{
    $testItems = array();
    
    // What PHP version do we need. If a special version for an OS is needed, put it
    // in "req". See the example for "windows".    
    $testItems["phpversion"] = array( 
        "desc"             => "PHP Version",
        "req"              => array( "general" => "4.0.0", "windows" => "4.1.0" ),
        "errorDescription" => "Your version of PHP is too old.",
        "errorSuggestion"  => "<p>If you are using a webhoster, please ask them 
                               if they could update PHP to the newest version.</p>
                               <p>If you are setting up your own server, please update
                               to the newest version of <a href=\"http://www.php.net\">PHP</a>.</p>",
                                    );
    
    // Databases that eZ publish supports.                          
    $testItems["database"] = array(
        array( "desc"       => "PHP module: MySQL",
               "req"        => "one db", 
               "modulename" => "mysql",
               "selectname" => "mySQL", ),            
        array( "desc"       => "PHP module: PostgreSQL",
                 "req"        => "one db",
               "modulename" => "postgresql",
               "selectname" => "PostegreSQL" ),
        array( "desc"        => "PHP module: Oracle",
               "req"        => "one db",
               "modulename" => "oracle",
               "selectname" => "Oracle" ),
                                  );
                            
    // Modules that are required or useful for eZ publish
    $testItems["modules"] = array(
        array( "desc"             => "PHP module: libGD",
               "req"              => false,
               "modulename"       => "gd",
               "errorDescription" => "The required PHP module \"libGD\" is not installed.", 
               "errorSuggestion"  => "Please ask your webhoster to install this module or, if you
                                      have your own webserver, install it yourself." ),
                                  );
                                   
    // Test for filepermissions
    $testItems["filepermissions"] = array( 
        "desc"             => "Correct filepermissions",
        "req"              => true,
        "errorDescription" => "The webserver can't write to files, because file permissions are not set right.",
        "errorSuggestion"  => "Please set the permissions of directories to mode \"775\" and the 
                                 permissions of files to \"664\". Normally you can do this with the command \"chmod\"
                               and/ or with your FTP software." 
                                           );    

    return $testItems;
}


/*!

    Step 1: General tests and information for the databases

*/
function stepOne( &$tpl, &$http )
{

    $testItems = configuration();
    
    //
    // Start testing
    //
    $resultArray = array();
    
    // Test PHP version
    $resultArray["phpversion"] = testPhpVersion( $testItems["phpversion"] );   
    
    // Test database modules
    foreach( $testItems["database"] as $item )
        $resultArray[$item["modulename"]] = testModule( $item );        
    
    // Test for more modules
    foreach( $testItems["modules"] as $item )
        $resultArray[$item["modulename"]] = testModule( $item );

    // Test for file permissions
    $resultArray["filepermissions"] = testFilePermissions( $testItems["filepermissions"] );
    
    // Create the array that shows our results to the user
    $outputArray = array();
    foreach( $resultArray as $resultItem )
    {
        // Make the result a bit nicer looking
        if ( $resultItem["pass"] == true )
            $pass = "pass";
        else
            $pass = "failed";
        
        if ( $resultItem["exist"] == false )
            $exist = "no";
        else if ( is_string( $resultItem["exist"] ) )
            $exist = $resultItem["exist"];
        else
            $exist = "yes";
        
        if ( $resultItem["req"] == false )
            $req = "no";
        else if ( is_string( $resultItem["req"] ) )
            $req = $resultItem["req"];
        else
            $req = "yes";
            
        $outputArray[] = array( "desc" => $resultItem["desc"],
                                    "req"  => $req,
                                    "exist" => $exist,
                                    "pass" => $pass );
    }
    $tpl->setVariable( "itemsResult", $outputArray );

    
    //
    // Now see if we have a problem
    //
    
    // Shall we continue??
    $continue = true;
    
    // Error reporting for php version
    if ( $resultArray["phpversion"]["pass"] == false )
    {
        $tpl->setVariable( "errorDescription", $testItems["phpversion"]["errorDescription"] );
        $tpl->setVariable( "errorSuggestion", $testItems["phpversion"]["errorSuggestion"] );
        $continue = false;    
    }
    
    // Only continue with error testing, if the above test was successful
    if ( $continue == true )
    {
        // Error reporting for database modules
        $availableDatabases = count( $testItems["database"] ); 
        $availableDatabasesArray = array();
        foreach( $testItems["database"] as $item )
        {
            switch( $resultArray[$item["modulename"]]["pass"] )
            {
                case true:
                {
                    $availableDatabasesArray[] = array( "name" => $item["modulename"], "pass" => "true" );
                }break;
                case false:
                {
                    $availableDatabases--;
                    $availableDatabasesArray[] = array( "name" => $item["modulename"], "pass" => "false" );                    
                }break;                
            }
        }
        
        if ( $availableDatabases < 1 )
        {
            $continue = false;
            $tpl->setVariable( "errorDescription", "No usable database module found." );
            $tpl->setVariable( "errorSuggestion", "eZ publish needs at least one database that it supports. 
                                Please install the PHP module of one of the above listed databases.
                                You obviously also need a running database for the module." );
        }
        else
        {
            $tpl->setVariable( "databasesArray", $availableDatabasesArray );
        }
    }

    // Only continue with error testing, if the above test was successful
    if ( $continue == true )
    {
        // Error reporting for the rest of the modules
        foreach( $testItems["modules"] as $item )
        {
            if ( $item["req"] == true && $resultArray[$item["modulename"]]["pass"] == false )
            {
                $continue = false;
                $tpl->setVariable( "errorDescription", $item["errorDescription"] );
                $tpl->setVariable( "errorSuggestion", $item["errorSuggestion"] );  
            }
        }
    }

    // Only continue with error testing, if the above test was successful
    if ( $continue == true && $resultArray["filepermissions"]["pass"] == false )
    {
        $continue = false;
        $tpl->setVariable( "errorDescription", $testItems["filepermissions"]["errorDescription"] );
        $tpl->setVariable( "errorSuggestion", $testItems["filepermissions"]["errorSuggestion"] );        
    }
    
    //
    // DB Settings
    //
    $tpl->setVariable( "continue", $continue );
    
    // Display template
    $tpl->display( "design/standard/templates/setup/step1.tpl" );
}




/*!

    Step 2: Do some tests on the database and ask for db information

*/
function stepTwo( &$tpl, &$http )
{
    $testItems = configuration();

    // Get our variables
    if ( $http->hasVariable( "dbServer" ) )
        $dbServer      = $http->postVariable( "dbServer" );
    else
        $dbServer      = "localhost";
    if ( $http->hasVariable( "dbName" ) )
        $dbName        = $http->postVariable( "dbName" );
    else
        $dbName        = "ezpublish3";
    if ( $http->hasVariable( "dbMainUser" ) )
        $dbMainUser    = $http->postVariable( "dbMainUser" );
    else
        $dbMainUser    = "root";
    /*if ( $http->hasVariable( "dbCreateUser" ) )
        $dbCreateUser  = $http->postVariable( "dbCreateUser" );
    else
        $dbCreateUser  = "";*/

    
    // Assign some defaults
    $tpl->setVariable( "dbName", $dbName );
    $tpl->setVariable( "dbServer", $dbServer );
    $tpl->setVariable( "dbMainUser", $dbMainUser );        
//    $tpl->setVariable( "dbCreateUser", $dbCreateUser );
    

      // Databases
      $databasesArray = array();
      foreach( $testItems["database"] as $item )
      {
        // Only list the ones that are actually available on the server
           if ( $http->postVariable( $item["modulename"] ) == "true" )
           {
               $databasesArray[] = array( "name" => $item["modulename"], "desc" => $item["selectname"] );
            switch ( $item["modulename"] )
            {
                case "mysql":
                {
                    $databaseTest = @mysql_connect( "localhost" );
                    $tpl->setVariable( "dbServerExpl", "no server on \"localhost\" detected" );
                    $tpl->setVariable( "dbServer", "" );
                    switch( mysql_errno() )
                    {
                        case 1045: // Server runs
                        case 0:    // Successful login (without username...tststs)
                        {
                            $tpl->setVariable( "dbServer", "localhost" );
                            $tpl->setVariable( "dbServerExpl", "server detected" );
                        }break;
                    }
                }break;
                default:
                {
                    $tpl->setVariable( "dbserver", "localhost" );
                    $tpl->setVariable( "dbServerExpl", "no server detected" );
                }break;
            }
 
           }
      }
      $tpl->setVariable( "databasesArray", $databasesArray );
    
    if ( $http->hasVariable( "dbServer" ) )
        $tpl->setVariable( "dbServer", $http->postVariable( "dbServer" ) );
    if ( $http->hasVariable( "dbName" ) )
        $tpl->setVariable( "dbName", $http->postVariable( "dbName" ) );
    if ( $http->hasVariable( "dbMainUser" ) )
        $tpl->setVariable( "dbMainUser", $http->postVariable( "dbMainUser" ) );            
    if ( $http->hasVariable( "dbCreateUser" ) )
        $tpl->setVariable( "dbCreateUser", $http->postVariable( "dbCreateUser" ) );

    // Set available db types in case we have to go back to step two
    $availableDatabasesArray2 = array();
      foreach( $testItems["database"] as $item )
      {
        $availableDatabasesArray2[] = array( "name" => $item["modulename"], 
                                            "pass" => $http->postVariable( $item["modulename"] ) );
    }
    $tpl->setVariable( "databasesArray2", $availableDatabasesArray2 );
        

    $tpl->display( "design/standard/templates/setup/step2.tpl" );        
}



/*!

    Step 3: Try to create the database etc.

*/
function stepThree( &$tpl, &$http )
{
    $testItems = configuration();

    // Get our variables
    $dbType        = $http->postVariable( "dbType" );
    $dbServer      = $http->postVariable( "dbServer" );
    $dbName        = $http->postVariable( "dbName" );
    $dbMainUser    = $http->postVariable( "dbMainUser" );
    $dbMainPass    = $http->postVariable( "dbMainPass" );
    /* $dbCreateUser  = $http->postVariable( "dbCreateUser" );
    $dbCreatePass  = $http->postVariable( "dbCreatePass" );
    $dbCreatePass2 = $http->postVariable( "dbCreatePass2" ); */
    
    // Set template variables
    $tpl->setVariable( "dbType", $dbType );
    $tpl->setVariable( "dbServer", $dbServer );
    $tpl->setVariable( "dbName", $dbName );
    $tpl->setVariable( "dbMainUser", $dbMainUser );
    $tpl->setVariable( "dbMainPass", $dbMainPass );
    /*$tpl->setVariable( "dbCreateUser", $dbCreateUser );
    $tpl->setVariable( "dbCreatePass", $dbCreatePass );*/
    
    // Set available db types in case we have to go back to step two
    $availableDatabasesArray = array();
      foreach( $testItems["database"] as $item )
      {
        $availableDatabasesArray[] = array( "name" => $item["modulename"], 
                                            "pass" => $http->postVariable( $item["modulename"] ) );
    }
    $tpl->setVariable( "databasesArray", $availableDatabasesArray );
    
    // Only continue, if we are successful
    $tpl->setVariable( "createDb", false );
    $tpl->setVariable( "createSql", false );

    // Try to connect to the database
    $continue = false;
    switch ( $dbType )
    {
        case "mysql":
        {
            $dbConnection = @mysql_connect( $dbServer, $dbMainUser, $dbMainPass );
            switch( mysql_errno() )
            {
                case 0:    // Successful login
                {
                    $tpl->setVariable( "dbConnect", "successful" );
                    $continue = true;                    
                }break;
                case 1045:
                {
                    $tpl->setVariable( "dbConnect", "unsuccessful." );
                    $errorDescription = "Wrong username and/ or password!";
                    $errorSuggestion = "Please go back and reenter the username and password.";
                }break;
                case 2005:
                {
                    $tpl->setVariable( "dbConnect", "unsuccessful." );
                    $errorDescription = "\"$dbServer\" is no MySQL server!";
                    $errorSuggestion = "Please go back and enter the correct database hostname.";
                }break;
                
                default:
                {
                    $tpl->setVariable( "dbConnect", "unsuccessful." );
                    $errorDescription = "Unknown error while connecting to database!";
                    $errorSuggestion = "The mySQL error is: " . mysql_error() . "(" . mysql_errno() . ")";
                }break;
            }
        }break;
        default:
        {
            $tpl->setVariable( "dbConnect", "don't know server type, sorry." );
        }
    }
    
    if ( $continue )
    {
        $continue = false;
        $tpl->setVariable( "createDb", true );

        // Try to create the database    
        mysql_drop_db( $dbName, $dbConnection ); // TODO: let'S drop it immidiately because we continue testing
        mysql_create_db( $dbName, $dbConnection );
        switch ( mysql_errno() )
        {
            case 0:
            {
                $tpl->setVariable( "dbCreate", "successful" );
                $continue = true;            
            }break;
            case 1007:
            {
                $tpl->setVariable( "dbCreate", "unsuccessful." );
                $errorDescription = "Database \"$dbName\" exists.";
                $errorSuggestion = "Please go back and choose a different name.";        
            }break;
            
            default:
            {
                $tpl->setVariable( "dbCreate", "unsuccessful." );
                $errorDescription = "Unknown Error! MySQL error: " . mysql_error() . " (" . mysql_errno() . ")";
                $errorSuggestion = "Please try to fix the error and write to eZ systems about it.";
            }break;
        }
    }
    
    //
    // Create database structures
    //
    if ( $continue )
    {
        $continue = false;
        $tpl->setVariable( "createSql", true );
                
        // Read in SQL file
        $sqlFile = "kernel/sql/mysql/kernel.sql";
        $sqlQuery = @fread( @fopen( $sqlFile, 'r' ), filesize( $sqlFile ));
        if ( $sqlQuery == false )
        {
            $errorDescription = "Couldn't open file \"$sqlFile\"!";
            $errorSuggestion = "Please make sure the file is on the server and is readable.";
        }
        
        // Fix SQL file by deleting all comments and newlines
        $sqlQuery = preg_replace( array( "/#.*/", "/\n/", "/--.*/" ), array( "", "", "" ), $sqlQuery );
    
        // Split the query into an array (mysql_query doesn't like ";")
        $sqlQueryArray = preg_split( "/;/", $sqlQuery );
        
        // Execute the SQL queries
        mysql_select_db( $dbName );
        if ( mysql_errno() != 0 )
        {
            $errorDescription = "Couldn't select database \"$dbName\".";
            $errorSuggestion = "This error shouldn't show as the database was just created, but cannot be 
                                selected now. Please check the permissions of the database user.";
        }
            
        foreach( $sqlQueryArray as $singleQuery )
        {
            if ( trim( $singleQuery ) != "" )
                mysql_query( $singleQuery );
            if ( mysql_errno() != 0 )
                break;
        }
        
        if ( mysql_errno() == 0 )
        {
            $tpl->setVariable( "dbCreateSql", "successful" );
            $continue = true;
        }
        else
        {
            $tpl->setVariable( "dbCreateSql", "unsuccessful." );
            $errorDescription = "Couldn't create SQL structures.";
            $errorSuggestion = "Please report error to eZ systems or try to fix the SQL in the 
                                file \"$sqlFile\" yourself.<br />
                                mySQL error: " . mysql_error() . " (" . mysql_errno() . ")";
        } 
    }

    if ( $continue )
    {
        $tpl->setVariable( "continue", true );
    }
    else
    {
        $tpl->setVariable( "continue", false );
        $tpl->setVariable( "errorDescription", $errorDescription );
        $tpl->setVariable( "errorSuggestion", $errorSuggestion );
    }
    
    // Display template
    $tpl->display( "design/standard/templates/setup/step3.tpl" );    
}



/*!

    Step 4: Write site.ini

*/
function stepFour( &$tpl, &$http, &$ini )
{
    $testItems = configuration();

    // Get our variables
    $dbType        = $http->postVariable( "dbType" );
    $dbServer      = $http->postVariable( "dbServer" );
    $dbName        = $http->postVariable( "dbName" );
    $dbMainUser    = $http->postVariable( "dbMainUser" );
    $dbMainPass    = $http->postVariable( "dbMainPass" );
    /*$dbCreateUser    = $http->postVariable( "dbCreateUser" );
    $dbCreatePass    = $http->postVariable( "dbCreatePass" );*/
    
    if ( isset( $dbCreateUser ) and $dbCreateUser != "" )
    {
        $dbUser = $dbCreateUser;
        $dbPass = $dbCreatePass;
    }
    else
    {
        $dbUser = $dbMainUser;
        $dbPass = $dbMainPass;
    }
    
    // Write values to site.ini
    $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", "ez" . $dbType );
    $ini->setVariable( "DatabaseSettings", "Server", $dbServer );
    $ini->setVariable( "DatabaseSettings", "Database", $dbName );
    $ini->setVariable( "DatabaseSettings", "User", $dbUser );
    $ini->setVariable( "DatabaseSettings", "Password", $dbPass );
    
    // deactivate setup
    $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );
                        
    // write back site.ini
    $savingStatus = $ini->save( "site.ini" );
    if ( $savingStatus )
    {
        $tpl->setVariable( "configWrite", "successful" );
        $tpl->setVariable( "continue", true );
        $tpl->setVariable( "url", eZSys::wwwDir() . eZSys::indexFile() );
    }
    else
    {
        $tpl->setVariable( "configWrite", "unsuccessul" );
        $tpl->setVariable( "continue", false );
    }
    
    // TODO: do this better and more secure!
    if ($dir = @opendir("var/cache/ini"))
    {
        while ( ( $file = readdir( $dir ) ) !== false )
        {
            if($item=="." or $item=="..") continue;
            unlink( $file );
        }  
          closedir( $dir );
    }
    // Show template
    $tpl->display( "design/standard/templates/setup/step4.tpl" );    
}





/*!
    
    Test if PHP version is equal or greater than required version
     
*/
function testPhpVersion( $argArray )
{ 
    // Get the operating systems name
    $operatingSystem = split( " ", php_uname() );
    $operatingSystem = strtolower( $operatingSystem[0] );
    
    // Find out if there is an os specific version needed
    if ( isset( $argArray["req"][$operatingSystem] ) )
        $neededVersion = $argArray["req"][$operatingSystem];
    else if ( isset( $argArray["req"]["general"] ) )
        $neededVersion = $argArray["req"]["general"];
    else
        $neededVersion = $argArray["req"]; 

    // compare the versions
    $currentVersion = phpversion();
    $compCurrentVersion = str_replace( ".", "", $currentVersion );
    $compNeededVersion = str_replace( ".", "", $neededVersion );
    if ( $compCurrentVersion >= $compNeededVersion )
        $pass = true;
    else
        $pass = false;
    
    return array( "desc" => $argArray["desc"],
                  "req"  => $neededVersion,
                  "exist" => $currentVersion,
                  "pass" => $pass
                );     
}


/*!
    Test if a module is loaded
*/
function testModule( $argArray )
{
    if ( (bool) extension_loaded( $argArray["modulename"] ) )
        $pass = true;
    else
        $pass = false;

    return array( "desc" => $argArray["desc"],
                  "req" => $argArray["req"],
                  "exist" => $pass,
                  "pass" => $pass 
                );         
}



/*!
    Test if we can create a file in this directory.
    TODO: Argh. Improve this!
*/
function testFilePermissions( $argArray )
{
    $testFile = "boing.test";
    if ( touch( $testFile ) )
    { 
        $pass = true;
        $status = true;
    }
    else
    {
        $pass = false;
        $status = false;
    }
    return array( "desc" => $argArray["desc"],
                  "req" => $argArray["req"],
                  "exist" => $status,
                  "pass" => $pass );    
}


// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

eZDisplayDebug();
exit;
?>