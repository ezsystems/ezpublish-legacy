<?php
//
// Created on: <21-Nov-2003 18:11:45 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

$module = $Params['Module'];

$packageName = $Params['PackageName'];

$package = eZPackage::fetch( $packageName );
if ( !$package )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$package->attribute( 'can_export' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );


$exportDirectory = eZPackage::temporaryExportPath();
$exportName = $package->exportName();
$exportPath = $exportDirectory . '/' . $exportName;
$exportPath = $package->exportToArchive( $exportPath );

//return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );

$fileName = $exportPath;
if ( $fileName != "" and file_exists( $fileName ) )
{
    clearstatcache();
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
    header( "X-Powered-By: eZ Publish" );
    header( "Content-disposition: attachment; filename=$originalFileName" );
    header( "Content-Transfer-Encoding: binary" );
    header( "Accept-Ranges: bytes" );

    $fh = fopen( $fileName, "rb" );
    if ( $fileOffset )
    {
        eZDebug::writeDebug( $fileOffset, "seeking to fileoffset" );
        fseek( $fh, $fileOffset );
    }

    ob_end_clean();
    fpassthru( $fh );
    fflush( $fh );
    fclose( $fh );
    unlink( $fileName );
    eZExecution::cleanExit();
}


?>
