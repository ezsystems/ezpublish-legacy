<?php
//
// Created on: <30-Apr-2003 13:40:19 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( "kernel/common/template.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'lib/version.php' );

$ini =& eZINI::instance( );
$tpl =& templateInit();

$db =& eZDB::instance();

$phpAcceleratorInfo = false;
if ( isset( $GLOBALS['_PHPA'] ) )
{
    $phpAcceleratorInfo = array();
    $phpAcceleratorInfo['name'] = "ionCube PHP Accelerator";
    $phpAcceleratorInfo['url'] = "http://www.php-accelerator.co.uk";
    $phpAcceleratorInfo['enabled'] = $GLOBALS['_PHPA']['ENABLED'];
    $phpAcceleratorInfo['version_integer'] = $GLOBALS['_PHPA']['iVERSION'];
    $phpAcceleratorInfo['version_string'] = $GLOBALS['_PHPA']['VERSION'];
}
if ( extension_loaded( "Turck MMCache" ) )
{
    $phpAcceleratorInfo = array();
    $phpAcceleratorInfo['name'] = "Turck MMCache";
    $phpAcceleratorInfo['url'] = "http://turck-mmcache.sourceforge.net";
    $phpAcceleratorInfo['enabled'] = true;
    $phpAcceleratorInfo['version_integer'] = false;
    $phpAcceleratorInfo['version_string'] = false;
}
if ( extension_loaded( "apc" ) )
{
    $phpAcceleratorInfo = array();
    $phpAcceleratorInfo['name'] = "APC";
    $phpAcceleratorInfo['url'] = "http://pecl.php.net/package/APC";
    $phpAcceleratorInfo['enabled'] = ini_get( 'apc.enabled' ) != 0;
    $phpAcceleratorInfo['version_integer'] = false;
    $phpAcceleratorInfo['version_string'] = false;
}

$tpl->setVariable( 'ezpublish_version', eZPublishSDK::version() . " (" . eZPublishSDK::alias() . ")" );
$tpl->setVariable( 'ezpublish_revision', eZPublishSDK::revision() );
$tpl->setVariable( 'ezpublish_extensions', eZExtension::activeExtensions() );
$tpl->setVariable( 'php_version', phpversion() );
$tpl->setVariable( 'php_accelerator', $phpAcceleratorInfo );
$tpl->setVariable( 'apache_version', eZPublishSDK::version() . " (" . eZPublishSDK::alias() . ")" );
$tpl->setVariable( 'database_info', $db->databaseName() );
$tpl->setVariable( 'database_charset', $db->charset() );
$tpl->setVariable( 'database_object', $db );
$tpl->setVariable( 'php_loaded_extensions', get_loaded_extensions() );
$phpINI = array();
foreach ( array( 'safe_mode', 'register_globals', 'open_basedir', 'file_uploads', 'post_max_size', 'memory_limit', 'max_execution_time' ) as $iniName )
{
    $phpINI[$iniName] = ini_get( $iniName );
}
$tpl->setVariable( 'php_ini', $phpINI );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/info.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'System information' ) ) );

?>
