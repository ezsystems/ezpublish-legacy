<?php
//
// Created on: <15-Aug-2002 16:40:11 sp>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$contentObjectAttributeID = $Params['ContentObjectAttributeID'];

$contentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeID, true);
$contentObjectID = $contentObjectAttribute->attribute( 'contentobject_id' );

$contentObject = eZContentObject::fetch( $contentObjectID );

if ( ! $contentObject->attribute( 'can_read' ) )
{
        $Module->redirectTo( '/error/error/403' );
        return;
}

//$binaryFile =& $contentObjectAttribute->content();
$binary =& eZBinaryFile::fetch( $contentObjectAttributeID );

$ini =& eZINI::instance();
$origDir = $ini->variable( "FileSettings", "StorageDir" ) . '/original';

//$orig_dir = $binaryFile->storageDir( "original" );
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
    exit();



}else
{
    eZDebug::writeNotice( $binary, 'binary');
    eZDebug::writeNotice( $fileName, 'fileName');
}
    










?>
