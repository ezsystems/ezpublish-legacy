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
    Step 4: Ask for some more input
*/
function eZSetupStep( &$tpl, &$http, &$ini )
{
    // Get our configuration
    $testItems = configuration();

    // Complete testItems with the test results and set the handover array
    $handoverResult = array();
    completeItems( $testItems, $http, $handoverResult );

    // Get our variables from the form
    $dbParams["type"]     = $http->postVariable( "dbType" );
    $dbParams["server"]   = $http->postVariable( "dbServer" );
    $dbParams["database"] = $http->postVariable( "dbName" );
    $dbParams["user"]     = $http->postVariable( "dbUser" );
    $dbParams["password"] = $http->postVariable( "dbPass" );
    $dbParams["charset"]  = $http->postVariable( "dbCharset" );
          
    // Database values for the handover form
    $handoverResult[] = array( "name" => "dbType",    "value" => $dbParams["type"]     );
    $handoverResult[] = array( "name" => "dbServer",  "value" => $dbParams["server"]   );
    $handoverResult[] = array( "name" => "dbName",    "value" => $dbParams["database"] );    
    $handoverResult[] = array( "name" => "dbUser",    "value" => $dbParams["user"]     );    
    $handoverResult[] = array( "name" => "dbPass",    "value" => $dbParams["password"] );    
    $handoverResult[] = array( "name" => "dbCharset", "value" => $dbParams["charset"]  );    

    // Set the template variables
    $tpl->setVariable( "handover", $handoverResult );
    
    // Fill the template fields with defaults
    $tpl->setVariable( "siteName", $ini->variable( "SiteSettings", "SiteName" ) );
    $tpl->setVariable( "siteURL", $ini->variable( "SiteSettings", "SiteURL" ) );    
    $tpl->setVariable( "charsetArray", getCharsets() );

    // Show template
    $tpl->display( "design/standard/templates/setup/step4.tpl" );    
}



/***************************************************************/
/****** Helping functions that are only used by this file ******/
/***************************************************************/


/*!
	Get all charsets that we support. We use the directory share/codepages for this.
*/
function getCharsets()
{
    $fileArray = array();
    $codepagesDir = "share/codepages/";
    if ( $handle = opendir( $codepagesDir ) ) 
    {
        while ( false !== ( $file = readdir( $handle ) ) ) 
        { 
            if ( is_file( $codepagesDir . $file ) )       
                $fileArray[] = $file;
        }
        closedir($handle);
        asort( $fileArray );
        return $fileArray;
    }
    else
        return false;   
}

?>
