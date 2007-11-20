<?php
//
// Created on: <30-Apr-2003 13:40:19 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

require_once( "kernel/common/template.php" );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'lib/ezutils/classes/ezextension.php' );
//include_once( 'lib/ezutils/classes/ezsysinfo.php' );
//include_once( 'lib/version.php' );

$ini = eZINI::instance( );
$tpl = templateInit();

$db = eZDB::instance();

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
if ( extension_loaded( "eAccelerator" ) )
{
    $phpAcceleratorInfo = array();
    $phpAcceleratorInfo['name'] = "eAccelerator";
    $phpAcceleratorInfo['url'] = "http://sourceforge.net/projects/eaccelerator/";
    $phpAcceleratorInfo['enabled'] = true;
    $phpAcceleratorInfo['version_integer'] = false;
    $phpAcceleratorInfo['version_string'] = phpversion('eAccelerator');
}
if ( extension_loaded( "apc" ) )
{
    $phpAcceleratorInfo = array();
    $phpAcceleratorInfo['name'] = "APC";
    $phpAcceleratorInfo['url'] = "http://pecl.php.net/package/APC";
    $phpAcceleratorInfo['enabled'] = ini_get( 'apc.enabled' ) != 0;
    $phpAcceleratorInfo['version_integer'] = false;
    $phpAcceleratorInfo['version_string'] = phpversion( 'apc' );
}
if ( extension_loaded( "Zend Performance Suite" ) )
{
    $phpAcceleratorInfo = array();
    $phpAcceleratorInfo['name'] = "Zend WinEnabler (Zend Performance Suite)";
    $phpAcceleratorInfo['url'] = "http://www.zend.com/store/products/zend-win-enabler.php";
    $phpAcceleratorInfo['enabled'] = true;
    $phpAcceleratorInfo['version_integer'] = false;
    $phpAcceleratorInfo['version_string'] = false;
}

$webserverInfo = false;
if ( function_exists( 'apache_get_version' ) )
{
    $webserverInfo = array( 'name' => 'Apache',
                            'modules' => false,
                            'version' => apache_get_version() );
    if ( function_exists( 'apache_get_modules' ) )
        $webserverInfo['modules'] = apache_get_modules();
}

$systemInfo = new eZSysInfo();
$systemInfo->scan();

$tpl->setVariable( 'ezpublish_version', eZPublishSDK::version() . " (" . eZPublishSDK::alias() . ")" );
$tpl->setVariable( 'ezpublish_revision', eZPublishSDK::revision() );
$tpl->setVariable( 'ezpublish_extensions', eZExtension::activeExtensions() );
$tpl->setVariable( 'php_version', phpversion() );
$tpl->setVariable( 'php_accelerator', $phpAcceleratorInfo );
$tpl->setVariable( 'webserver_info', $webserverInfo );
$tpl->setVariable( 'apache_version', eZPublishSDK::version() . " (" . eZPublishSDK::alias() . ")" );
$tpl->setVariable( 'database_info', $db->databaseName() );
$tpl->setVariable( 'database_charset', $db->charset() );
$tpl->setVariable( 'database_object', $db );
$tpl->setVariable( 'php_loaded_extensions', get_loaded_extensions() );
$tpl->setVariable( 'system_info', $systemInfo );

$phpINI = array();
foreach ( array( 'safe_mode', 'register_globals', 'file_uploads' ) as $iniName )
{
    $phpINI[ $iniName ] = ini_get( $iniName ) != 0;
}
foreach ( array( 'open_basedir', 'post_max_size', 'memory_limit', 'max_execution_time' ) as $iniName )
{
    $value = ini_get( $iniName );
    if ( $value !== '' )
        $phpINI[$iniName] = $value;
}
$tpl->setVariable( 'php_ini', $phpINI );

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/info.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'System information' ) ) );

?>
