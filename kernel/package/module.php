<?php
//
// Created on: <11-Aug-2003 18:11:32 amos>
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

$Module = array( 'name' => 'eZPackage' );

$ViewList = array();
$ViewList['list'] = array(
    'functions' => array( 'list' ),
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ChangeRepositoryButton' => 'ChangeRepository',
                                    'InstallPackageButton' => 'InstallPackage',
                                    'RemovePackageButton' => 'RemovePackage',
                                    'ConfirmRemovePackageButton' => 'ConfirmRemovePackage',
                                    'CancelRemovePackageButton' => 'CancelRemovePackage',
                                    'CreatePackageButton' => 'CreatePackage' ),
    'post_action_parameters' => array( 'ChangeRepository' => array( 'RepositoryID' => 'RepositoryID' ),
                                       'RemovePackage' => array( 'PackageSelection' => 'PackageSelection' ),
                                       'ConfirmRemovePackage' => array( 'PackageSelection' => 'PackageSelection' ) ),
    "unordered_params" => array( "offset" => "Offset" ),
    'params' => array( 'RepositoryID' ) );

$ViewList['upload'] = array(
    'functions' => array( 'import' ),
    'script' => 'upload.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'UploadPackageButton' => 'UploadPackage' ),
    'params' => array() );

$ViewList['create'] = array(
    'functions' => array( 'create' ),
    'script' => 'create.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreatePackageButton' => 'CreatePackage',
                                    'PackageStep' => 'PackageStep' ),
    'post_action_parameters' => array( 'CreatePackage' => array( 'CreatorItemID' => 'CreatorItemID' ),
                                       'PackageStep' => array( 'CreatorItemID' => 'CreatorItemID',
                                                               'CreatorStepID' => 'CreatorStepID',
                                                               'PreviousStep' => 'PreviousStepButton',
                                                               'NextStep' => 'NextStepButton' ) ),
    'params' => array() );

$ViewList['export'] = array(
    'functions' => array( 'export' ),
    'script' => 'export.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'PackageName' ) );

$ViewList['view'] = array(
    'functions' => array( 'read' ),
    'script' => 'view.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'InstallButton' => 'Install',
                                    'UninstallButton' => 'Uninstall',
                                    'ExportButton' => 'Export' ),
    'params' => array( 'ViewMode', 'PackageName', 'RepositoryID' ) );

$ViewList['install'] = array(
    'functions' => array( 'install' ),
    'script' => 'install.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'InstallPackageButton' => 'InstallPackage',
                                    'PackageStep' => 'PackageStep',
                                    'SkipPackageButton' => 'SkipPackage' ),
    'post_action_parameters' => array( 'InstallPackage' => array( 'InstallItemID' => 'InstallItemID' ),
                                       'PackageStep' => array( 'InstallItemID' => 'InstallItemID',
                                                               'InstallStepID' => 'InstallStepID',
                                                               'PreviousStep' => 'PreviousStepButton',
                                                               'NextStep' => 'NextStepButton' ) ),

    'params' => array( 'PackageName' ) );

$ViewList['uninstall'] = array(
    'functions' => array( 'install' ),
    'script' => 'uninstall.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'UninstallPackageButton' => 'UninstallPackage',
                                    'SkipPackageButton' => 'SkipPackage' ),
    'params' => array( 'PackageName' ) );

$TypeID = array(
    'name'=> 'Type',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezpackage.php',
    'class' => 'eZPackage',
    'function' => 'typeList',
    'parameter' => array(  false )
    );

$CreatorTypeID = array(
    'name'=> 'CreatorType',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezpackagecreationhandler.php',
    'class' => 'eZPackageCreationHandler',
    'function' => 'creatorLimitationList',
    'parameter' => array(  false )
    );

$RoleID = array(
    'name'=> 'Role',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezpackage.php',
    'class' => 'eZPackage',
    'function' => 'maintainerRoleListForRoles',
    'parameter' => array(  false )
    );

$FunctionList['read'] = array( 'Type' => $TypeID );

$FunctionList['list'] = array( 'Type' => $TypeID );

$FunctionList['create'] = array( 'Type' => $TypeID,
                                 'CreatorType' => $CreatorTypeID,
                                 'Role' => $RoleID );
$FunctionList['edit'] = array( 'Type' => $TypeID );

$FunctionList['remove'] = array( 'Type' => $TypeID );


$FunctionList['install'] = array( 'Type' => $TypeID );

$FunctionList['import'] = array( 'Type' => $TypeID );

$FunctionList['export'] = array( 'Type' => $TypeID );

?>
