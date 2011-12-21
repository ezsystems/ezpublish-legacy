<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
            header( "HTTP/1.1 206 Partial Content" );
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
