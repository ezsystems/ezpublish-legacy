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
include_once( "lib/ezutils/classes/ezhttpfile.php" );

$module =& $Params['Module'];

if ( !eZPackage::canUsePolicyFunction( 'import' ) )
    return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$package = false;
$installElements = false;
$errorList = array();

if ( $module->isCurrentAction( 'UploadPackage' ) )
{
    if ( eZHTTPFile::canFetch( 'PackageBinaryFile' ) )
    {
        $file =& eZHTTPFile::fetch( 'PackageBinaryFile' );
        if ( $file )
        {
            $packageFilename = $file->attribute( 'filename' );
            $packageName = $file->attribute( 'original_filename' );
            if ( preg_match( "#^(.+)-[0-9](\.[0-9]+)-[0-9].ezpkg$#", $packageName, $matches ) )
                $packageName = $matches[1];
            $packageName = preg_replace( array( "#[^a-zA-Z0-9]+#",
                                                "#_+#",
                                                "#(^_)|(_$)#" ),
                                         array( '_',
                                                '_',
                                                '' ), $packageName );
            $package =& eZPackage::import( $packageFilename, $packageName );
            if ( is_object( $package ) )
            {
                if ( $package->attribute( 'install_type' ) != 'install' or
                     !$package->attribute( 'can_install' ) )
                {
                    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
                }
                else if ( $package->attribute( 'install_type' ) == 'install' )
                {
                    return $module->redirectToView( 'install', array( $package->attribute( 'name' ) ) );
                }
            }
            else if ( $package == EZ_PACKAGE_STATUS_ALREADY_EXISTS )
            {
                $errorList[] = array( 'description' => ezi18n( 'kernel/package', 'Package %packagename already exists, cannot import the package', false, array( '%packagename' => $packageName ) ) );
            }
            else
                eZDebug::writeError( "Uploaded file is not an eZ publish package" );
        }
        else
            eZDebug::writeError( "Failed fetching upload package file" );
    }
    else
    {
        eZDebug::writeError( "No uploaded package file was found" );
    }
}

$tpl =& templateInit();

$tpl->setVariable( 'package', $package );
$tpl->setVariable( 'error_list', $errorList );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:package/upload.tpl" );
$Result['path'] = array( array( 'url' => 'package/list',
                                'text' => ezi18n( 'kernel/package', 'Packages' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/package', 'Upload' ) ) );

?>
