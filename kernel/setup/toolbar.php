<?php
//
// Definition of Toolbar class
//
// Created on: <05-Mar-2004 12:36:14 wy>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file toolbar.php
*/

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];
if ( $Params['SiteAccess'] )
    $currentSiteAccess = $Params['SiteAccess'];
if ( $Params['Position'] )
    $toolbarPosition =& $Params['Position'];


include_once( "kernel/common/template.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$http =& eZHTTPTool::instance();

$iniPath = "settings/siteaccess/$currentSiteAccess";

if ( $http->hasPostVariable( 'NewToolButton' ) )
{
    $ini = eZINI::instance( "toolbar.ini", "settings", null, false, null, false );
    if ( $http->hasPostVariable( 'toolName' ) )
    {
        $addedToolName = $http->postVariable( 'toolName' );
        $iniAppend =& eZINI::instance( 'toolbar.ini.append', $iniPath, null, false, null, true );
        if ( $ini->hasVariable( "Toolbar_" . $toolbarPosition, "Tool" ) )
        {
            $existingToolArray =  $ini->variable( "Toolbar_" . $toolbarPosition, "Tool" );
        }
        array_push( $existingToolArray, $addedToolName );
        $iniAppend->setVariable( "Toolbar_" . $toolbarPosition, "Tool", $existingToolArray );
        $succeed = $iniAppend->save(  false, false, false, false, true, true );
    }
    $toolArray = $existingToolArray;
    $toolList = array();
    foreach ( array_keys( $toolArray ) as $toolKey )
    {
        unset( $actionParameters );
        $toolName = $toolArray[$toolKey];
        if ( $ini->hasGroup( "Tool_" . $toolName ) )
        {
            $actionParameters = $ini->group( "Tool_" . $toolName );
        }
        if ( $ini->hasGroup( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) ) )
        {
            $actionParameters = $ini->group( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) );
        }
        $toolParameters = array();
        foreach ( array_keys( $actionParameters ) as $key )
        {
            $toolParameterArray = array();
            $parameterValue = $actionParameters[$key];
            $toolParameterArray['name'] = $key;
            $toolParameterArray['value'] = $parameterValue;
            $toolParameters[] = $toolParameterArray;
        }
        $toolList[] = array( 'name' => $toolName, 'parameters' => $toolParameters );
    }
}

if ( $http->hasPostVariable( 'UpdatePlacementButton' ) )
{
    $ini = eZINI::instance( "toolbar.ini", "settings", null, false, null, false );
    $updatedToolArray = array();
    $iniAppend =& eZINI::instance( 'toolbar.ini.append', $iniPath, null, false, null, true );

    if (  $ini->hasVariable( "Toolbar_" . $toolbarPosition, "Tool" ) )
    {
        $existingToolArray = $ini->variable( "Toolbar_" . $toolbarPosition, "Tool" );
    }

    $updatedBlockArray = array();
    for( $i=0;$i<count( $existingToolArray );$i++ )
    {
        if ( $http->hasPostVariable( 'placement_' . $i ) )
        {
            $newPlacement = $http->postVariable( 'placement_' . $i );
            $updatedToolArray[($newPlacement-1)] = $existingToolArray[$i];
            if ( $ini->hasGroup( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ( $i + 1 ) ) )
            {
                $actionParameters = $ini->group( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ( $i + 1 ) );

                $updatedBlockArray[] = array( 'blackName' => "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . $newPlacement,
                                              'parameters' => $actionParameters );
                $iniAppend->removeGroup( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ( $i + 1 ) );
            }
        }
    }

    foreach ( $updatedBlockArray as $updatedBlock )
    {
        $blackName = $updatedBlock['blackName'];
        $parameters = $updatedBlock['parameters'];
        foreach ( array_keys( $parameters ) as $key )
        {
            $parameterValue = $parameters[$key];
            $iniAppend->setVariable( $blackName, $key, $parameterValue );
        }
    }

    ksort( $updatedToolArray );
    reset( $updatedToolArray );
    $iniAppend->setVariable( "Toolbar_" . $toolbarPosition, "Tool", $updatedToolArray );
    $succeed = $iniAppend->save(  false, false, false, false, true, true );

    $toolArray = $updatedToolArray;
    $toolList = array();
    foreach ( array_keys( $toolArray ) as $toolKey )
    {
        unset( $actionParameters );
        $toolName = $toolArray[$toolKey];
        if ( $iniAppend->hasGroup( "Tool_" . $toolName ) )
        {
            $actionParameters = $iniAppend->group( "Tool_" . $toolName );
        }
        if ( $iniAppend->hasGroup( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) ) )
        {
            $actionParameters = $iniAppend->group( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) );
        }

        if ( $actionParameters == null )
        {
            if ( $ini->hasGroup( "Tool_" . $toolName ) )
            {
                $actionParameters = $ini->group( "Tool_" . $toolName );
            }
            if ( $ini->hasGroup( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) ) )
            {
                $actionParameters = $ini->group( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) );
            }
        }
        $toolParameters = array();
        foreach ( array_keys( $actionParameters ) as $key )
        {
            $toolParameterArray = array();
            $parameterValue = $actionParameters[$key];
            $toolParameterArray['name'] = $key;
            $toolParameterArray['value'] = $parameterValue;
            $toolParameters[] = $toolParameterArray;
        }
        $toolList[] = array( 'name' => $toolName, 'parameters' => $toolParameters );
    }
}

if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    $updatedToolArray = array();
    if ( $http->hasPostVariable( 'deleteToolArray' ) )
    {
        $deletedToolArray = $http->postVariable( 'deleteToolArray' );
    }
    $iniAppend =& eZINI::instance( 'toolbar.ini.append', $iniPath, null, false, null, true );

    if ( $iniAppend->hasVariable( "Toolbar_" . $toolbarPosition, "Tool" ) )
    {
        $existingToolArray =  $iniAppend->variable( "Toolbar_" . $toolbarPosition, "Tool" );
    }

    for( $i=0;$i<count( $existingToolArray );$i++ )
    {
        $placementShift = 0;
        foreach ( array_keys( $deletedToolArray ) as $deleteKey )
        {
            if ( $i > $deleteKey )
                $placementShift++;
        }
        if ( !in_array( $i, $deletedToolArray ) )
        {
           $updatedToolArray[] = $existingToolArray[$i];
           $newPlacement = $i + 1 - $placementShift;
           if ( $iniAppend->hasGroup( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ( $i + 1 ) ) )
           {
               $actionParameters = $iniAppend->group( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ( $i + 1 ) );

               $iniAppend->removeGroup( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ( $i + 1 ) );

               foreach ( array_keys( $actionParameters ) as $key )
               {
                   $parameterValue = $actionParameters[$key];
                   $iniAppend->setVariable( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . $newPlacement, $key, $parameterValue );
               }
           }
        }
        else
        {
            if ( $iniAppend->hasGroup( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ($i+1) ) )
            {
                $iniAppend->removeGroup( "Tool_" . $toolbarPosition . "_" . $existingToolArray[$i] . "_" . ($i+1) );
            }
        }
    }

    $iniAppend->setVariable( "Toolbar_" . $toolbarPosition, "Tool", $updatedToolArray );
    $succeed = $iniAppend->save(  false, false, false, false, true, true );
}

if ( $http->hasPostVariable( 'StoreButton' ) )
{
    $ini =& eZINI::instance( "toolbar.ini" );

    $iniAppend =& eZINI::instance( 'toolbar.ini.append', $iniPath, null, false, null, true );

    if ( $ini->hasVariable( "Toolbar_" . $toolbarPosition, "Tool" ) )
    {
        $toolArray =  $ini->variable( "Toolbar_" . $toolbarPosition, "Tool" );
    }
    $toolList = array();
    foreach ( array_keys( $toolArray ) as $toolKey )
    {
        unset( $actionParameters );
        $makeNewBlock = false;
        $newActionParameters = array();
        $toolName = $toolArray[$toolKey];
        if ( $ini->hasGroup( "Tool_" . $toolName ) )
        {
            $actionParameters = $ini->group( "Tool_" . $toolName );
        }
        $toolParameters = array();
        foreach ( array_keys( $actionParameters ) as $key )
        {
            $toolParameterArray = array();
            $defaultParameterValue = $actionParameters[$key];
            if ( $http->hasPostVariable( $toolKey . "_parameter_" . $key ) )
            {
                $parameterValue = $http->postVariable( $toolKey . "_parameter_" . $key );
                if ( $defaultParameterValue != $parameterValue )
                {
                   $makeNewBlock = true;
                }
            }
            else
            {
                $parameterValue = $defaultParameterValue;
            }
            $newActionParameters[$key] = $parameterValue;
            $toolParameterArray['name'] = $key;
            $toolParameterArray['value'] = $parameterValue;
            $toolParameters[] = $toolParameterArray;
        }
        $toolList[] = array( 'name' => $toolName, 'parameters' => $toolParameters );
        if ( $makeNewBlock == true )
        {
            foreach( array_keys( $newActionParameters ) as $newKey )
            {
                $parameterValue = $newActionParameters[$newKey];
                $iniAppend->setVariable( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ), $newKey, $parameterValue );
            }
        }
    }
    $succeed = $iniAppend->save(  false, false, false, false, true, true );
}

$ini =& eZINI::instance( "toolbar.ini" );

if ( $ini->hasVariable( "Tool", "AvailableToolArray" ) )
{
    $availableToolArray =  $ini->variable( "Tool", "AvailableToolArray" );
}

if ( !isset( $toolArray ) )
{
    if ( $ini->hasVariable( "Toolbar_" . $toolbarPosition, "Tool" ) )
    {
        $toolArray =  $ini->variable( "Toolbar_" . $toolbarPosition, "Tool" );
    }

    $toolList = array();
    foreach ( array_keys( $toolArray ) as $toolKey )
    {
        unset( $actionParameters );
        $toolName = $toolArray[$toolKey];
        if ( $ini->hasGroup( "Tool_" . $toolName ) )
        {
            $actionParameters = $ini->group( "Tool_" . $toolName );
        }
        if ( $ini->hasGroup( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) ) )
        {
            $actionParameters = $ini->group( "Tool_" . $toolbarPosition . "_" . $toolName . "_" . ( $toolKey + 1 ) );
        }
        $toolParameters = array();
        foreach ( array_keys( $actionParameters ) as $key )
        {
            $toolParameterArray = array();
            $parameterValue = $actionParameters[$key];
            $toolParameterArray['name'] = $key;
            $toolParameterArray['value'] = $parameterValue;
            $toolParameters[] = $toolParameterArray;
        }
        $toolList[] = array( 'name' => $toolName, 'parameters' => $toolParameters );
    }
}

$tpl =& templateInit();

$tpl->setVariable( 'toolbar_position', $toolbarPosition );
$tpl->setVariable( 'tool_list', $toolList );
$tpl->setVariable( 'available_tool_list', $availableToolArray  );
$tpl->setVariable( 'current_siteaccess', $currentSiteAccess );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/toolbar.tpl" );
$Result['path'] = array( array( 'url' => 'setup/toolbarlist',
                                'text' => ezi18n( 'kernel/setup', 'Toolbar list' ) ) );

?>
