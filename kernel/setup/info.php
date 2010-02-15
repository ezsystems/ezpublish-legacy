<?php
//
// Created on: <30-Apr-2003 13:40:19 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$module = $Params['Module'];
$mode = $Params['Mode'];

if ( $mode and $mode === 'php' )
{
    phpinfo();
    eZExecution::cleanExit();
}

require_once( "kernel/common/template.php" );
$http = eZHTTPTool::instance();
$ini = eZINI::instance();
$tpl = templateInit();
$db = eZDB::instance();

try
{
    $info = ezcSystemInfo::getInstance();
}
catch ( ezcSystemInfoReaderCantScanOSException $e )
{
    $info = null;
    eZDebug::writeNotice( "Could not read system information, returned: '" . $e->getMessage(). "'", 'system/info' );
}

if ( $info instanceof ezcSystemInfo )
{
    // Workaround until ezcTemplate is used, as properties can not be accessed directly in ezp templates.
    $systemInfo = array(
        'cpu_type' => $info->cpuType,
        'cpu_speed' => $info->cpuSpeed,
        'cpu_count' =>$info->cpuCount,
        'memory_size' => $info->memorySize
    );

    if ( $info->phpAccelerator !== null )
    {
        $phpAcceleratorInfo = array(   'name' => $info->phpAccelerator->name,
                                       'url' => $info->phpAccelerator->url,
                                       'enabled' => $info->phpAccelerator->isEnabled,
                                       'version_integer' => $info->phpAccelerator->versionInt,
                                       'version_string' => $info->phpAccelerator->versionString
        );
    }
    else
    {
        $phpAcceleratorInfo = array();
    }
}
else
{
       $systemInfo = array(
        'cpu_type' => '',
        'cpu_speed' => '',
        'cpu_count' => '',
        'memory_size' => ''
    );
    $phpAcceleratorInfo = array();
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

$tpl->setVariable( 'ezpublish_version', eZPublishSDK::version() . " (" . eZPublishSDK::alias() . ")" );
$tpl->setVariable( 'ezpublish_revision', eZPublishSDK::revision() );
$tpl->setVariable( 'ezpublish_extensions', eZExtension::activeExtensions() );
$tpl->setVariable( 'php_version', phpversion() );
$tpl->setVariable( 'php_accelerator', $phpAcceleratorInfo );
$tpl->setVariable( 'webserver_info', $webserverInfo );
$tpl->setVariable( 'database_info', $db->databaseName() );
$tpl->setVariable( 'database_charset', $db->charset() );
$tpl->setVariable( 'database_object', $db );
$tpl->setVariable( 'php_loaded_extensions', get_loaded_extensions() );
$tpl->setVariable( 'autoload_functions', spl_autoload_functions() );

// Workaround until ezcTemplate
// The new system info class uses properties instead of attributes, so the
// values are not immediately available in the old template engine.
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
                                'text' => eZi18n::translate( 'kernel/setup', 'System information' ) ) );

?>