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

    Step 4: Write site.ini

*/
function stepFive( &$tpl, &$http, &$ini )
{
    $testItems = configuration();

    // Get our variables
    $dbType       = $http->postVariable( "dbType" );
    $dbServer     = $http->postVariable( "dbServer" );
    $dbName       = $http->postVariable( "dbName" );
    $dbUser   = $http->postVariable( "dbUser" );
    $dbPass   = $http->postVariable( "dbPass" );
    $siteName    = $http->postVariable( "siteName" );
    $siteURL      = $http->postVariable( "siteURL" );
    $siteCharset  = $http->postVariable( "siteCharset" );
    
    
    // Write values to site.ini
    $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", "ez" . $dbType );
    $ini->setVariable( "DatabaseSettings", "Server", $dbServer );
    $ini->setVariable( "DatabaseSettings", "Database", $dbName );
    $ini->setVariable( "DatabaseSettings", "User", $dbUser );
    $ini->setVariable( "DatabaseSettings", "Password", $dbPass );
    
    // deactivate setup
    $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );
    $ini->setVariable( "SiteSettings", "SiteName", $siteName );
    $ini->setVariable( "SiteSettings", "SiteURL", $siteURL );
                        
    
    $savingStatus = writeIni( $ini );
    if ( $savingStatus )
    {
        @unlink( $filePath );
        $tpl->setVariable( "configWrite", "successful" );
        $tpl->setVariable( "continue", true );
        $tpl->setVariable( "url", eZSys::wwwDir() . eZSys::indexFile() );
    }
    else
    {
        $tpl->setVariable( "configWrite", "unsuccessful" );
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
    $tpl->display( "design/standard/templates/setup/step5.tpl" );    
}





function writeIni( $iniLocal )
{
    // Make backup of site.ini
    $filePath = $iniLocal->rootDir() . "/" . $iniLocal->FileName;
    $ext = ".bak.php";
    $i=0;
    while( file_exists( $filePath . $ext ) )
    {
        if ( $i < 10 )
            $ext = ".b0$i.php";
        else
            $ext = ".b$i.php";
        $i++;
    }
    if ( file_exists( $filePath . ".php" ) )
        $backup = copy( $filePath . ".php", $filePath . $ext ); 
    else
        $backup = copy( $filePath, $filePath . $ext ); 
    if ( $backup )
    {
        // write back ini file
        $savingStatus = $iniLocal->save( $iniLocal->FileName . ".php" );
        if ( $savingStatus )
            return true;
        else
            return false;
    }
    else
        return false;
    
    
}
?>
