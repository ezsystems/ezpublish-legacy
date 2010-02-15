<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
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

require_once( "kernel/common/template.php" );
$http = eZHTTPTool::instance();

$module = $Params['Module'];
$packageName = $Params['PackageName'];
$currentItem = 0;
$doItemInstall = false;

if ( $http->hasSessionVariable( 'eZPackageInstallerData' ) )
{
    $persistentData = $http->sessionVariable( 'eZPackageInstallerData' );
    if ( isset( $persistentData['currentItem'] ) )
        $currentItem = $persistentData['currentItem'];
}
else
{
    $persistentData = array();
    $persistentData['currentItem'] = $currentItem;
    $persistentData['error'] = array();
    $persistentData['error_default_actions'] = array();
}

if ( !eZPackage::canUsePolicyFunction( 'install' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$package = eZPackage::fetch( $packageName );
if ( !$package )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $module->isCurrentAction( 'SkipPackage' ) )
{
    $http->removeSessionVariable( 'eZPackageInstallerData' );
    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
}

$tpl = templateInit();

// Get all uninstall items and reverse array
$uninstallItems = array_reverse( $package->installItemsList( false, false, false, false ) );

if ( $module->isCurrentAction( 'HandleError' ) )
{
    // Choosing error action
    if ( $module->hasActionParameter( 'ActionID' ) )
    {
        $choosenAction = $module->actionParameter( 'ActionID' );
        $persistentData['error']['choosen_action'] = $choosenAction;
        if ( $module->hasActionParameter( 'RememberAction' ) )
        {
            $errorCode = $persistentData['error']['error_code'];
            $itemType = $uninstallItems[$currentItem]['type'];
            if ( !isset( $persistentData['error_default_actions'][$itemType] ) )
                $persistentData['error_default_actions'][$itemType] = array();
            $persistentData['error_default_actions'][$itemType][$errorCode] = $choosenAction;
        }
    }
    elseif ( !isset( $persistentData['error']['error_code'] ) )
    {
        // If this is an unhandled error, we are skipping this item
        $currentItem++;
    }
    $doItemInstall = true;
}
elseif ( $module->isCurrentAction( 'UninstallPackage' ) )
{
    $doItemInstall = true;
}
else
{
    $uninstallElements = array();
    foreach ( $uninstallItems as $uninstallItem )
    {
        $handler = eZPackage::packageHandler( $uninstallItem['type'] );
        if ( $handler )
        {
            $uninstallElement = $handler->explainInstallItem( $package, $uninstallItem );

            if ( $uninstallElement )
            {
                if ( isset( $uninstallElement[0] ) )
                    $uninstallElements = array_merge( $uninstallElements, $uninstallElement );
                else
                    $uninstallElements[] = $uninstallElement;
            }
        }
    }

    $templateName = "design:package/uninstall.tpl";
    $tpl->setVariable( 'uninstall_elements', $uninstallElements );
}

if ( $doItemInstall )
{
    while( $currentItem < count( $uninstallItems ) )
    {
        $uninstallItem = $uninstallItems[$currentItem];
        $result = $package->uninstallItem( $uninstallItem, $persistentData );

        if ( !$result )
        {
            $templateName = "design:package/uninstall_error.tpl";
            break;
        }
        else
        {
            $persistentData['error'] = array();
            $currentItem++;
        }
    }
}

if ( $currentItem >= count( $uninstallItems ) )
{
    $package->setInstalled( false );
    $http->removeSessionVariable( 'eZPackageInstallerData' );
    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
}

$persistentData['currentItem'] = $currentItem;
$http->setSessionVariable( 'eZPackageInstallerData', $persistentData );
$tpl->setVariable( 'persistent_data', $persistentData );
$tpl->setVariable( 'package', $package );

$Result = array();
$Result['content'] = $tpl->fetch( $templateName );
$Result['path'] = array( array( 'url' => 'package/list',
                                'text' => eZi18n::translate( 'kernel/package', 'Packages' ) ),
                         array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/package', 'Uninstall' ) ) );

?>
