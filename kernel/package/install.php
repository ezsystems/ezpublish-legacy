<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
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

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpackage.php' );
include_once( 'kernel/classes/ezpackageinstallationhandler.php' );

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

$package =& eZPackage::fetch( $packageName );
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
        $package->installItem( $installItem );
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
        $installer->finalize( $package, $http, $persistentData );
        $http->setSessionVariable( 'eZPackageInstallationCounter', $installItemCount + 1 );
        $package->setAttribute( 'is_active', true );
        $http->removeSessionVariable( 'eZPackageInstallerData' );
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
