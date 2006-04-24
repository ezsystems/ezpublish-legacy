<?php
//
// Created on: <11-Oct-2004 15:41:12 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.6.x
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

/*! \file soap.php
*/

/*!
  \brief The SOAP file will handle all eZ publish soap requests.

  SOAP functions are
*/

ob_start();

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( 'lib/ezutils/classes/ezsys.php' );
include_once( 'lib/ezutils/classes/ezexecution.php' );

/*!
 Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    $ini =& eZINI::instance();

    list( $debugSettings['debug-enabled'], $debugSettings['debug-by-ip'], $debugSettings['debug-ip-list'] ) =
        $ini->variableMulti( 'DebugSettings', array( 'DebugOutput', 'DebugByIP', 'DebugIPList' ), array ( 'enabled', 'enabled' ) );
    eZDebug::updateSettings( $debugSettings );
}

$ini =& eZINI::instance();

// Initialize/set the index file.
eZSys::init( 'soap.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );
eZSys::initIni( $ini );


// include ezsession override implementation
include_once( "lib/ezutils/classes/ezsession.php" );

// Check for extension
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end


// Activate correct siteaccess
include_once( "access.php" );
$access = array( 'name' => $ini->variable( 'SiteSettings', 'DefaultAccess' ),
                 'type' => EZ_ACCESS_TYPE_DEFAULT );
$access = changeAccess( $access );
$GLOBALS['eZCurrentAccess'] =& $access;
// Siteaccess activation end

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini =& eZINI::instance( 'i18n.ini' );

    list( $i18nSettings['internal-charset'], $i18nSettings['http-charset'], $i18nSettings['mbstring-extension'] ) =
        $ini->variableMulti( 'CharacterSettings', array( 'Charset', 'HTTPCharset', 'MBStringExtension' ), array( false, false, 'enabled' ) );

    include_once( 'lib/ezi18n/classes/eztextcodec.php' );
    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

include_once( 'lib/ezdb/classes/ezdb.php' );
$db =& eZDB::instance();
if ( $sessionRequired and
     $db->isConnected() )
{
    eZSessionStart();
}


$soapINI =& eZINI::instance( 'soap.ini' );
$enableSOAP = $soapINI->variable( 'GeneralSettings', 'EnableSOAP' );

if ( $enableSOAP == 'true' )
{
    eZSys::init( 'soap.php' );

    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

    // Login if we have username and password.
    if ( isset( $_SERVER['PHP_AUTH_USER'] ) and isset( $_SERVER['PHP_AUTH_PW'] ) )
        eZUser::loginUser( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );

    include_once( 'lib/ezsoap/classes/ezsoapserver.php' );

    $server = new eZSOAPServer();

    foreach( $soapINI->variable( 'ExtensionSettings', 'SOAPExtensions' ) as $extension )
    {
        include_once( eZExtension::baseDirectory() . '/' . $extension . '/soap/initialize.php' );
    }

    $server->processRequest();
}

ob_end_flush();

eZExecution::cleanExit();

?>
