<?php
//
// This is the index_webdav.php file. Manages WebDAV sessions.
//
// Created on: <15-Aug-2003 15:15:15 bh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

ob_start();

// Turn off session stuff, isn't needed for WebDAV operations.
$GLOBALS['eZSiteBasics']['session-required'] = false;

include_once( "kernel/classes/webdav/ezwebdavcontentserver.php" );
include_once( "lib/ezutils/classes/ezsys.php" );


/*! Reads settings from site.ini and passes them to eZDebug.
 */
function eZUpdateDebugSettings()
{
    $ini =& eZINI::instance();
    $debugSettings = array();
    $debugSettings['debug-enabled'] = $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled';
    $debugSettings['debug-by-ip']   = $ini->variable( 'DebugSettings', 'DebugByIP' )   == 'enabled';
    $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
    eZDebug::updateSettings( $debugSettings );
}

// Grab the main WebDAV setting (enable/disable) from the WebDAV ini file.
$ini =& eZINI::instance( WEBDAV_INI_FILE );
$enable = $ini->variable( 'GeneralSettings', 'EnableWebDAV' );

// Check and proceed only if WebDAV functionality is enabled:
if ( $enable == true )
{
    append_to_log( "Requested URI is: " . $_SERVER['REQUEST_URI'] );

    // Initialize/set the index file.
    eZSys::init( 'webdav.php' );

    // The top/root folder is publicly available (without auth):
    if ( $_SERVER['REQUEST_URI'] == ''  ||
         $_SERVER['REQUEST_URI'] == '/' ||
         $_SERVER['REQUEST_URI'] == '/webdav.php/' ||
         $_SERVER['REQUEST_URI'] == '/webdav.php' )
    {
        $testServer = new eZWebDAVContentServer();
        $testServer->processClientRequest();
    }
    // Else: need to login with username/password:
    else
    {
        // Get the name of the site that is being browsed.
        $currentSite = currentSiteFromPath( $_SERVER['REQUEST_URI'] );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            // Change site to the site being browsed:
            setSiteAccess( $currentSite );

            $loginUsername = "";
            // Get the username and the password.
            if ( isset( $_SERVER['PHP_AUTH_USER'] ) )
                $loginUsername = $_SERVER['PHP_AUTH_USER'];
            if ( isset( $_SERVER['PHP_AUTH_PW'] ) )
                $loginPassword = $_SERVER['PHP_AUTH_PW'];

            // Strip away "domainname\" from a possible "domainname\password" string.
            if ( preg_match( "#(.*)\\\\(.*)$#", $loginUsername, $matches ) )
            {
                $loginUsername = $matches[2];
            }
            // Check if username & password contain someting, attempt to login.
            if ( ( !isset( $loginUsername ) ) || ( !isset( $loginPassword ) ) ||
                 ( !eZUser::loginUser( $loginUsername, $loginPassword ) ) )
            {
                header('HTTP/1.0 401 Unauthorized');
                header('WWW-Authenticate: Basic realm="'.WEBDAV_AUTH_REALM.'"');
            }
            // Else: non-empty & valid values were supplied: login successful!
            else
            {
                append_to_log( "Logged in!" );

                // Create & initialize a new instance of the content server.
                $server = new eZWebDAVContentServer();

                // Process the request.
                $server->processClientRequest();
            }
        }
        // Else: site-name is invalid (was not among available sites).
        else
        {
            header( "HTTP/1.1 404 Not Found" );
        }
    }
}
// Else: WebDAV functionality is disabled, do nothing...
else
{
    print ( WEBDAV_DISABLED );
}

?>
