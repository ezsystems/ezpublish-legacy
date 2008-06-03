<?php
//
// Created on: <01-Mar-2005 15:47:23 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezini.php' );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'kernel/classes/ezpreferences.php' );

$allSettingsList = $module->actionParameter( 'AllSettingsList' );

if ( $module->hasActionParameter( 'SelectedList' ) )
    $selectedList = $module->actionParameter( 'SelectedList' );
else
    $selectedList=array();

$siteAccess = $module->actionParameter( 'SiteAccess' );
if ( !$siteAccess )
    $siteAccess = 'global_override';

eZPreferences::setValue( 'admin_quicksettings_siteaccess', $siteAccess );

$iniFiles = array();

foreach( $allSettingsList as $index => $setting )
{
    $settingArray = explode( ';', $setting );

    if ( !array_key_exists( $settingArray[2], $iniFiles ) )
        $iniFiles[$settingArray[2]] = array();

    $iniFiles[$settingArray[2]][] = array ( $settingArray[0], $settingArray[1], in_array( $index, $selectedList ) );
}
unset( $setting );

$iniPath = ( $siteAccess == "global_override" ) ? "settings/override" : "settings/siteaccess/$siteAccess";

foreach( $iniFiles as $fileName => $settings )
{
    $ini = eZINI::instance( $fileName . '.append', $iniPath, null, null, null, true, true );
    $baseIni = eZINI::instance( $fileName );

    foreach( $settings as $setting )
    {
        if ( $ini->hasVariable( $setting[0], $setting[1] ) )
            $value = $ini->variable( $setting[0], $setting[1] );
        else
            $value = $baseIni->variable( $setting[0], $setting[1] );

        if ( $value == 'true' || $value == 'false' )
            $ini->setVariable( $setting[0], $setting[1], $setting[2] ? 'true' : 'false' );
        else
            $ini->setVariable( $setting[0], $setting[1], $setting[2] ? 'enabled' : 'disabled' );
    }

    if ( !$ini->save() )
    {
        eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );
    }

    unset( $baseIni );
    unset( $ini );

    // Remove variable from the global override
    if ( $siteAccess != "global_override" )
    {
        $ini = eZINI::instance( $fileName . '.append', "settings/override", null, null, null, true, true );
        foreach( $settings as $setting )
        {
            if ( $ini->hasVariable( $setting[0], $setting[1] ) )
                $ini->removeSetting( $setting[0], $setting[1] );
        }
        if ( !$ini->save() )
        {
            eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );
        }

        unset($ini);
    }
}

$uri = $http->sessionVariable( "LastAccessedModifyingURI" );
$module->redirectTo( $uri );

?>
