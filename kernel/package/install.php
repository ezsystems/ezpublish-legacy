<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
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

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpackage.php' );
include_once( 'kernel/classes/ezpackageinstallationhandler.php' );
include_once( "lib/ezdb/classes/ezdb.php" );

$http =& eZHTTPTool::instance();

$module =& $Params['Module'];
$packageName =& $Params['PackageName'];
if ( !$packageName )
{
    $packageName = $http->sessionVariable( 'eZPackageInstallationName' );
}
else
{
    $http->setSessionVariable( 'eZPackageInstallationName', $packageName );
}

if ( !eZPackage::canUsePolicyFunction( 'install' ) )
    return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$package = eZPackage::fetch( $packageName );
if ( !$package )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$installer = false;
$installItemArray = $package->installItems( false, eZSys::osType() );

$initializeStep = false;

if ( $module->isCurrentAction( 'PackageStep' ) )
{
    if ( $module->hasActionParameter( 'InstallItemID' ) )
    {
        $installItemCount = $http->sessionVariable( 'eZPackageInstallationCounter' );
        $installItem = $installItemArray[$installItemCount];
        $installerID = $module->actionParameter( 'InstallItemID' );
        $installer = eZPackageInstallationHandler::instance( $package, $installerID, $installItem );
        $persistentData = $http->sessionVariable( 'eZPackageInstallerData' );
        $installer->generateStepMap( $package, $persistentData );
    }
}
else if ( $module->isCurrentAction( 'InstallPackage' ) || $http->hasSessionVariable( 'eZPackageInstallationCounter' ) )
{
    if ( $http->hasSessionVariable( 'eZPackageInstallationCounter' ) )
    {
        $installItemCount = $http->sessionVariable( 'eZPackageInstallationCounter' );
    }
    else
    {
        $installItemCount = 0;
        $http->setSessionVariable( 'eZPackageInstallationCounter', $installItemCount );
    }

    if ( count( $installItemArray ) <= $installItemCount )
    {
        $package->Parameters['is_installed'] = true;
        $package->store();
        $http->removeSessionVariable( 'eZPackageInstallationCounter' );
        $http->removeSessionVariable( 'eZPackageInstallationName' );
        return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
    }

    $installItem = $installItemArray[$installItemCount];
    $installer = eZPackageInstallationHandler::instance( $package, $installItem['type'], $installItem );
    if ( !$installer )
    {
        // weak try to process errors
        if( !$package->installItem( $installItem ) )
        {
            eZDebug::writeError( "Error installing the package" );
            return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
        }
        $http->setSessionVariable( 'eZPackageInstallationCounter', $installItemCount + 1 );
        return $module->redirectToView( 'install', array( $packageName ) );
    }

    $persistentData = array();
    $http->setSessionVariable( 'eZPackageInstallerData', $persistentData );
    $installer->generateStepMap( $package, $persistentData );
}
else if ( $module->isCurrentAction( 'SkipPackage' ) )
{
    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
}
else
{
    $installElements = array();
    foreach ( $installItemArray as $installItem )
    {
        $handler =& eZPackage::packageHandler( $installItem['type'] );
        if ( $handler )
        {
            $installElement = $handler->explainInstallItem( $package, $installItem );
            if ( $installElement )
                $installElements[] = $installElement;
        }
    }
}

$tpl =& templateInit();

$templateName = 'design:package/install.tpl';
if ( $installer )
{
    $currentStepID = false;
    if ( $module->hasActionParameter( 'InstallStepID' ) )
        $currentStepID = $module->actionParameter( 'InstallStepID' );
    $steps =& $installer->stepMap();
    if ( !isset( $steps['map'][$currentStepID] ) )
        $currentStepID = $steps['first']['id'];
    $errorList = array();
    $hasAdvanced = false;

    $lastStepID = $currentStepID;
    if ( $module->hasActionParameter( 'NextStep' ) )
    {
        $hasAdvanced = true;
        $currentStepID = $installer->validateStep( $package, $http, $currentStepID, $steps, $persistentData, $errorList );
        if ( $currentStepID != $lastStepID )
        {
            $lastStep =& $steps['map'][$lastStepID];
            $installer->commitStep( $package, $http, $lastStep, $persistentData, $tpl );
        }
    }

    if ( $currentStepID )
    {
        $currentStep =& $steps['map'][$currentStepID];

        $stepTemplate = $installer->stepTemplate( $currentStep );
        $stepTemplateName = $stepTemplate['name'];
        $stepTemplateDir = $stepTemplate['dir'];

        $installer->initializeStep( $package, $http, $currentStep, $persistentData, $tpl, $module );

        if ( $package )
            $persistentData['package_name'] = $package->attribute( 'name' );

        $http->setSessionVariable( 'eZPackageInstallerData', $persistentData );

        $tpl->setVariable( 'installer', $installer );
        $tpl->setVariable( 'current_step', $currentStep );
        $tpl->setVariable( 'persistent_data', $persistentData );
        $tpl->setVariable( 'error_list', $errorList );
        $tpl->setVariable( 'package', $package );

        $templateName = "design:package/$stepTemplateDir/$stepTemplateName";
    }
    else
    {
        $db =& eZDB::instance();
        $db->begin();
        $installer->finalize( $package, $http, $persistentData );
        $http->setSessionVariable( 'eZPackageInstallationCounter', $installItemCount + 1 );
        $package->setAttribute( 'is_active', true );
        $http->removeSessionVariable( 'eZPackageInstallerData' );
        $db->commit();
        return $module->redirectToView( 'install', array( $packageName ) );
    }
}
else
{
    $tpl->setVariable( 'package', $package );
    $tpl->setVariable( 'install_elements', $installElements );
}

$Result = array();
$Result['content'] =& $tpl->fetch( $templateName );
$Result['path'] = array( array( 'url' => 'package/list',
                                'text' => ezi18n( 'kernel/package', 'Packages' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/package', 'Install' ) ) );

?>
