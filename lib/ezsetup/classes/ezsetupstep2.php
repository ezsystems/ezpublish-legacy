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


/*!
    Step 2: Do some tests on the database and ask for db information
*/
function eZSetupStep( &$tpl, &$http )
{
    // Get our configuration
    $testItems = configuration();

    // Set variables to handover to next step
    $handoverResult = array();
    completeItems( $testItems, $http, $handoverResult );
    $tpl->setVariable( "handover", $handoverResult );


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
    if ( $http->hasVariable( "dbDeleteTables" ) && $http->postVariable( "dbDeleteTables" ) == "yes" )
        $dbDeleteTables = "checked";
    else
        $dbDeleteTables = "";
    if ( $http->hasVariable( "dbCharset" ) )
        $dbCharset     = $http->postVariable( "dbCharset" );
    else
        $dbCharset     = "iso-8859-1";
    if ( $http->hasVariable( "builtin_encoding" ) )
        $dbEncoding    = $http->postVariable( "builtin_encoding" );
    else
        $dbEncoding    = "true";
    if ( $http->hasVariable( "dbCreateUser" ) )
        $dbCreateUser  = $http->postVariable( "dbCreateUser" );
    else
        $dbCreateUser  = "";

    
    // Assign some defaults
    $tpl->setVariable( "dbName", $dbName );
    $tpl->setVariable( "dbServer", $dbServer );
    $tpl->setVariable( "dbMainUser", $dbMainUser );        
    $tpl->setVariable( "dbCharset", $dbCharset );        
    $tpl->setVariable( "dbEncoding", $dbEncoding );        
    $tpl->setVariable( "dbCreateUser", $dbCreateUser );
    $tpl->setVariable( "dbDeleteTables", $dbDeleteTables );


    // Set available databases and charsets
    $databasesArray = array();
    $charsetArray = array();
    foreach( array_keys( $testItems ) as $key )
    {
        if ( $testItems[$key]["pass"] === true 
             && isset( $testItems[$key]["type"] ) 
             && $testItems[$key]["type"] == "db" )
        {
            $databasesArray[] = array( "name" => $testItems[$key]["modulename"], 
                                       "desc" => $testItems[$key]["selectname"] );
            if ( isset( $testItems[$key]["charsets"] ) )
                $charsetArray = $testItems[$key]["charsets"];
            else
                $charsetArray[] = array( "iso-8859-1" );
        }
    }
    $tpl->setVariable( "databasesArray", $databasesArray );
    $tpl->setVariable( "charsetArray", $charsetArray );

	// Switch unpack demo data. Only show, if we have zlib!
	if ( $testItems["zlib"]["pass"] == true )
		$tpl->setVariable( "unpackDemo", true );
	else
		$tpl->setVariable( "unpackDemo", false );
    
	// Show the template
    $tpl->display( "design/standard/templates/setup/step2.tpl" );        
}

    
?>
