<?php
//
// Created on: <15-Aug-2002 16:40:11 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );
//include_once( "kernel/classes/ezcontentobjecttreenode.php" );
//include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "kernel/classes/ezbinaryfilehandler.php" );
include_once( "kernel/classes/datatypes/ezmedia/ezmedia.php" );
//include_once( "kernel/common/template.php" );

//$tpl =& templateInit();

$contentObjectID = $Params['ContentObjectID'];
$contentObjectAttributeID = $Params['ContentObjectAttributeID'];
//$version = $Params['version'];
//$version = 4;
$contentObject = eZContentObject::fetch( $contentObjectID );
if ( !is_object( $contentObject ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE );
}
$version = $contentObject->attribute( 'current_version' );
$contentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeID, $version, true );
if ( !is_object( $contentObjectAttribute ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE );
}
$contentObjectID = $contentObjectAttribute->attribute( 'contentobject_id' );
//$version = $contentObjectAttribute->attribute( 'version' );

if ( ! $contentObject->attribute( 'can_read' ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED );
}

$fileHandler =& eZBinaryFileHandler::instance();
$result = $fileHandler->handleDownload( $contentObject, $contentObjectAttribute, EZ_BINARY_FILE_TYPE_FILE );

if ( $result == EZ_BINARY_FILE_RESULT_UNAVAILABLE )
{
    eZDebug::writeError( "The specified file could not be found." );
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

/*

$binaryType =& eZBinaryFile::fetch( $contentObjectAttributeID, $version );
$mediaType =& eZMedia::fetch( $contentObjectAttributeID, $version );

if( $binaryType != null )
    $binary = $binaryType
elseif( $mediaType != null )
    $binary = $mediaType;
else
{
    eZDebug::writeError( "No specified file exist." );
}

$sys =& eZSys::instance();
$storage_dir = $sys->storageDirectory();
$origDir = $storage_dir . '/original';

$fileName = $origDir . "/" . $binary->attribute( 'mime_type_category' ) . '/'.  $binary->attribute( "filename" );
if ( $binary->attribute( "filename" ) != "" and file_exists( $fileName ) )
{
    $fileSize = filesize ( $fileName );
    $mimeType =  $binary->attribute( 'mime_type' );
    $originalFileName = $binary->attribute( 'original_filename' );

    header( "Cache-Control:" );
    header( "Content-Length: $fileSize" );
    header( "Content-Type: $mimeType" );
    header( "X-Powered-By: eZ publish" );
    header( "Content-disposition: attachment; filename=$originalFileName" );
    header( "Content-Transfer-Encoding: binary" );

    $fh = fopen( "$fileName", "rb" );

    ob_end_clean();
    fpassthru( $fh );
    fflush();
    eZExecution::cleanExit();
}
else
{
    eZDebug::writeNotice( $binary, 'binary');
    eZDebug::writeNotice( $fileName, 'fileName');
}
*/

?>
