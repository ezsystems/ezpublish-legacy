<?php
//
// Created on: <17-Jan-2004 12:36:36 oh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//
include_once( "kernel/common/template.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$settingTypeArray = array( 'array' => 'Array',
                           'true/false' => 'True/False',
                           'enable/disable' => 'Enabled/Disabled',
                           'string' => 'String',
                           'numeric' => 'Numeric' );

$tpl =& templateInit();
$http =& eZHTTPTool::instance();
$ini =& eZINI::instance();

if ( $Params['INIFile'] )
    $iniFile = $Params['INIFile'];

if ( $Params['SiteAccess'] )
    $siteAccess = $Params['SiteAccess'];

if ( $Params['Block'] )
    $block = $Params['Block'];

if ( $Params['Setting'] )
    $settingName = $Params['Setting'];

if ( $Params['Placement'] )
    $settingPlacement = $Params['Placement'];

if ( $http->hasPostVariable( 'INIFile' )  )
    $iniFile = $http->variable( "INIFile" );

if ( $http->hasPostVariable( 'SiteAccess' ) )
    $siteAccess = $http->postVariable( 'SiteAccess' );

if ( $http->hasPostVariable( 'Block' ) )
    $block = trim( $http->postVariable( 'Block' ) );

if ( $http->hasPostVariable( 'SettingType' )  )
    $settingType = trim( $http->postVariable( 'SettingType' ) );

if ( $http->hasPostVariable( 'SettingName' )  )
    $settingName = trim( $http->postVariable( 'SettingName' ) );

if ( $http->hasPostVariable( 'SettingPlacement' )  )
    $settingPlacement = trim( $http->postVariable( 'SettingPlacement' ) );

if ( $http->hasPostVariable( 'Value' ) )
    $valueToWrite = trim( $http->postVariable( 'Value' ) );

if ( !isset( $settingName ) )
    $settingName = '';

if ( !isset( $settingPlacement ) )
    $settingPlacement = 'siteaccess';

if ( $http->hasPostVariable( 'WriteSetting' ) )
{
    if ( $settingPlacement == 'siteaccess' )
        $path = "settings/siteaccess/$siteAccess";
    else
        $path = 'settings/override';

    $ini =& eZINI::instance( $iniFile . '.append.php', $path, null, null, null, true );

    $hasValidationError = false;
    include_once( 'kernel/settings/validation.php' );
    $validationResult = validate( array( 'Name' => $settingName,
                                         'Value' => $valueToWrite ),
                                  array( 'name', $settingType ), true );
    if ( $validationResult['hasValidationError'] )
    {
        $tpl->setVariable( 'validation_field', $validationResult['fieldContainingError'] );
        $hasValidationError = true;
    }

    if ( !$hasValidationError )
    {
        if ( $settingType == 'array' )
        {
            $valueArray = explode( "\n", $valueToWrite );
            $valuesToWriteArray = array();
            $settingCount = 0;
            foreach( $valueArray as $value )
            {
                $value = trim( $value );
                if ( preg_match( "/^\[(.+)\]\=(.+)$/", $value, $matches ) )
                {
                    $valuesToWriteArray[$matches[1]] = $matches[2];
                }
                else
                {
                    $value = substr( strchr( $value, '=' ), 1 );
                    if ( $value == "" )
                    {
                        if ( $settingCount == 0 )
                            $valuesToWriteArray[] = NULL;
                    }
                    else
                    {
                        $valuesToWriteArray[] = $value;
                    }
                }
                ++$settingCount;
            }
            $ini->setVariable( $block, $settingName, $valuesToWriteArray );
        }
        else
        {
            $ini->setVariable( $block, $settingName, $valueToWrite );
        }
        $writeOk = $ini->save();

        if ( !$writeOk )
        {
            $tpl->setVariable( 'validation_error', true );
            $tpl->setVariable( 'validation_error_type', 'write_error' );
            $tpl->setVariable( 'path', $path );
            $tpl->setVariable( 'filename',  $iniFile . '.append.php' );
        }
        else
        {
            $Module->redirectTo( '/settings/view/' . $siteAccess . '/' . $iniFile );
        }
    }
    else // found validation errors...
    {
        $tpl->setVariable( 'validation_error', true );
        $tpl->setVariable( 'validation_error_type', $validationResult['type'] );
        $tpl->setVariable( 'validation_error_message', $validationResult['message'] );
    }
}
else
{
    $tpl->setVariable( 'validation_error', false );
    $tpl->setVariable( 'validation_error_type', false );
    $tpl->setVariable( 'validation_error_message', false );
}

if ( $http->hasPostVariable( 'Cancel' ) )
{
    $Module->redirectTo( '/settings/view/' . $siteAccess . '/' . $iniFile );
}

$ini =& eZINI::instance( $iniFile, 'settings', null, null, false );

if ( isset( $settingPlacement ) and $settingPlacement == 'siteaccess' )
{
    $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
    $ini->loadCache();
}

$value = $ini->variable( $block, $settingName );

// Do modifications to the value before it's sent to the template
if ( $value and !$settingType )
{
    $settingType = $ini->settingType( $value );
    if ( $settingType == 'array' )
    {
        $valueArray = array();
        foreach( $value as $param=>$key )
        {
            if ( !is_numeric( $param ) )
            {
                $valueArray[] = "[$param]=$key";
            }
            else
            {
                $valueArray[] = "=$key";
            }
        }
        $value = $valueArray;
        $value = implode( "\n", $value );
    }
}

if ( !isset( $settingType ) )
    $settingType = 'string';

$tpl->setVariable( 'setting', $settingName );
$tpl->setVariable( 'current_siteaccess', $siteAccess );
$tpl->setVariable( 'setting_type_array', $settingTypeArray );
$tpl->setVariable( 'setting_type', $settingType );
$tpl->setVariable( 'ini_file', $iniFile );
$tpl->setVariable( 'block', $block );
$tpl->setVariable( 'value', $value );
$tpl->setVariable( 'placement', $settingPlacement );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:settings/edit.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'settings/edit', 'Settings' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'settings/edit', 'Edit' ),
                                'url' => false ) );
?>
