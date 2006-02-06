<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.6.x
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

include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezpackage.php" );

$module =& $Params['Module'];
$packageName =& $Params['PackageName'];

if ( !eZPackage::canUsePolicyFunction( 'install' ) )
    return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$package =& eZPackage::fetch( $packageName );
if ( !$package )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );


$uninstallItems = $package->installItems( false, eZSys::osType(), false, false );
$uninstallElements = array();
foreach ( $uninstallItems as $uninstallItem )
{
    $handler =& eZPackage::packageHandler( $uninstallItem['type'] );
    if ( $handler )
    {
        $uninstallElement = $handler->explainInstallItem( $package, $uninstallItem, true );
        if ( $uninstallElement )
            $uninstallElements[] = $uninstallElement;
    }
}

if ( $module->isCurrentAction( 'UninstallPackage' ) )
{
    $package->uninstall();
    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
}
else if ( $module->isCurrentAction( 'SkipPackage' ) )
{
    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
}

$tpl =& templateInit();

$tpl->setVariable( 'package', $package );
$tpl->setVariable( 'uninstall_elements', $uninstallElements );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:package/uninstall.tpl" );
$Result['path'] = array( array( 'url' => 'package/list',
                                'text' => ezi18n( 'kernel/package', 'Packages' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/package', 'Uninstall' ) ) );

?>
