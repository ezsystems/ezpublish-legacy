<?php
//
// Created on: <11-Aug-2003 18:11:32 amos>
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
    'single_post_actions' => array( 'UploadPackageButton' => 'UploadPackage',
                                    'UploadCancelButton' => 'UploadCancel' ),
    'params' => array() );

$ViewList['create'] = array(
    'functions' => array( 'create' ),
    'script' => 'create.php',
    'ui_context' => 'edit',
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
    'ui_context' => 'edit',
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
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'HandleError' => 'HandleError',
                                    'InstallPackageButton' => 'InstallPackage',
                                    'PackageStep' => 'PackageStep',
                                    'SkipPackageButton' => 'SkipPackage' ),
    'post_action_parameters' => array( 'InstallPackage' => array( 'InstallerType' => 'InstallerType' ),
                                       'PackageStep' => array( 'InstallerType' => 'InstallerType',
                                                               'InstallStepID' => 'InstallStepID',
                                                               'PreviousStep' => 'PreviousStepButton',
                                                               'NextStep' => 'NextStepButton' ),
                                       'HandleError' => array( 'ActionID' => 'ActionID',
                                                               'RememberAction' => 'RememberAction' ) ),

    'params' => array( 'PackageName' ) );

$ViewList['uninstall'] = array(
    'functions' => array( 'install' ),
    'script' => 'uninstall.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'HandleError' => 'HandleError',
                                    'UninstallPackageButton' => 'UninstallPackage',
                                    'SkipPackageButton' => 'SkipPackage' ),
    'post_action_parameters' => array( 'HandleError' => array( 'ActionID' => 'ActionID',
                                                               'RememberAction' => 'RememberAction' ) ),
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


$FunctionList = array();
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
