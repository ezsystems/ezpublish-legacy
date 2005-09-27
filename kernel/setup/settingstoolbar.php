<?php
//
// Created on: <01-Mar-2005 15:47:23 ks>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/ezpreferences.php' );

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
    $ini =& eZINI::instance( $fileName . '.append', $iniPath, null, null, null, true, true );
    $baseIni =& eZINI::instance( $fileName );

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
        eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );

    unset( $baseIni );
    unset( $ini );

    // Remove variable from the global override
    if ( $siteAccess != "global_override" )
    {
        $ini =& eZINI::instance( $fileName . '.append', "settings/override", null, null, null, true, true );
        foreach( $settings as $setting )
        {
            if ( $ini->hasVariable( $setting[0], $setting[1] ) )
                $ini->removeSetting( $setting[0], $setting[1] );
        }
        if ( !$ini->save() )
            eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );

        unset($ini);
    }
}

$uri = $http->sessionVariable( "LastAccessedModifyingURI" );
$module->redirectTo( $uri );
	
?>
