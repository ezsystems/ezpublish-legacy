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
