<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
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

require_once( "kernel/common/template.php" );
//include_once( "kernel/classes/ezpackage.php" );

$module = $Params['Module'];
$viewMode = $Params['ViewMode'];
$packageName = $Params['PackageName'];
$repositoryID = false;
if ( isset( $Params['RepositoryID'] ) and $Params['RepositoryID'] )
    $repositoryID = $Params['RepositoryID'];

$package = eZPackage::fetch( $packageName, false, $repositoryID );
if ( !is_object( $package ) )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$package->attribute( 'can_read' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );


if ( $module->isCurrentAction( 'Export' ) )
{
    return $module->run( 'export', array( $packageName ) );
}
else if ( $module->isCurrentAction( 'Install' ) )
{
    return $module->redirectToView( 'install', array( $packageName ) );
}
else if ( $module->isCurrentAction( 'Uninstall' ) )
{
    return $module->redirectToView( 'uninstall', array( $packageName ) );
}

$repositoryInformation = $package->currentRepositoryInformation();

$tpl = templateInit();

$tpl->setVariable( 'package_name', $packageName );
$tpl->setVariable( 'repository_id', $repositoryID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/view/$viewMode.tpl" );
$path = array( array( 'url' => 'package/list',
                      'text' => ezi18n( 'kernel/package', 'Packages' ) ) );
if ( $repositoryInformation and $repositoryInformation['id'] != 'local' )
{
    $path[] = array( 'url' => 'package/list/' . $repositoryInformation['id'],
                     'text' => $repositoryInformation['name'] );
}
$path[] = array( 'url' => false,
                 'text' => $package->attribute( 'name' ) );
$Result['path'] = $path;

?>
