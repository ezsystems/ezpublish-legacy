<?php
//
// Created on: <21-Nov-2003 18:11:45 amos>
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

include_once( "kernel/classes/ezpackage.php" );

$module =& $Params['Module'];

$packageName = $Params['PackageName'];

$package =& eZPackage::fetch( $packageName );
if ( !$package )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$package->attribute( 'can_export' ) )
    return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );


$exportDirectory = eZPackage::temporaryExportPath();
$exportName = $package->exportName();
$exportPath = $exportDirectory . '/' . $exportName;
$exportPath = $package->archive( $exportPath );

$fileName = $exportPath;
if ( $fileName != "" and file_exists( $fileName ) )
{
    $fileSize = filesize( $fileName );
    $mimeType =  'application/octet-stream';
    $originalFileName = $exportName;
    $contentLength = $fileSize;
    $fileOffset = false;
    $fileLength = false;
    if ( isset( $_SERVER['HTTP_RANGE'] ) )
    {
        $httpRange = trim( $_SERVER['HTTP_RANGE'] );
        if ( preg_match( "/^bytes=([0-9]+)-$/", $httpRange, $matches ) )
        {
            $fileOffset = $matches[1];
            header( "Content-Range: bytes $fileOffset-" . $fileSize - 1 . "/$fileSize" );
            header( "HTTP/1.1 206 Partial content" );
            $contentLength -= $fileOffset;
        }
    }

    header( "Pragma: " );
    header( "Cache-Control: " );
    header( "Content-Length: $contentLength" );
    header( "Content-Type: $mimeType" );
    header( "X-Powered-By: eZ publish" );
    header( "Content-disposition: attachment; filename=$originalFileName" );
    header( "Content-Transfer-Encoding: binary" );
    header( "Accept-Ranges: bytes" );

    $fh = fopen( "$fileName", "rb" );
    if ( $fileOffset )
    {
        eZDebug::writeDebug( $fileOffset, "seeking to fileoffset" );
        fseek( $fh, $fileOffset );
    }

    ob_end_clean();
    fpassthru( $fh );
    fclose( $fh );
    fflush();
    unlink( $fileName );
    eZExecution::cleanExit();
}


?>
