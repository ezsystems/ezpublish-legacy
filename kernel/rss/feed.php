<?php
//
// Created on: <19-Sep-2002 16:45:08 kk>
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

$Module =& $Params["Module"];

if ( !isset ( $Params['RSSFeed'] ) )
    return $Module->setExitStatus( EZ_MODULE_STATUS_FAILED );

include_once( 'kernel/classes/ezrssexport.php' );

$feedName = $Params['RSSFeed'];
$RSSExport = eZRSSExport::fetchByName( $feedName );

// Get and check if RSS Feed exists
if ( $RSSExport == null )
    return $Module->setExitStatus( EZ_MODULE_STATUS_FAILED );

include_once( 'kernel/classes/ezrssexportitem.php' );

$config =& eZINI::instance( 'site.ini' );
$cacheDir = $config->variable( 'FileSettings', 'VarDir' ).'/'.$config->variable( 'FileSettings', 'CacheDir' );
$cacheFile = $cacheDir . '/rss/' . md5( $feedName ) . '.xml';

// If cache directory does not exist, create it. Get permissions settings from site.ini
if ( !is_dir( $cacheDir.'/rss' ) )
{
    $mode = $config->variable( 'FileSettings', 'TemporaryPermissions' );
    if ( !is_dir( $cacheDir ) )
    {
        mkdir( $cacheDir );
        chmod( $cacheDir, octdec( $mode ) );
    }
    mkdir( $cacheDir.'/rss' );
    chmod( $cacheDir.'/rss', octdec( $mode ) );
}

$rssFeed = null;

if ( !file_exists( $cacheFile ) or ( time() - filectime( $cacheFile ) > 20*60 ) )
{
    $xmlDoc =& $RSSExport->attribute( 'rss-xml' );

    $fid = @fopen( $cacheFile, 'w' );

    // If opening file for write access fails, write debug error
    if ( $fid === false )
    {
        eZDebug::writeError( 'Failed to open cache file for RSS export: '.$cacheFile );
    }
    else
    {
        // write, flush, close and change file access mode
        $mode = $config->variable( 'FileSettings', 'TemporaryPermissions' );
        $length = fwrite( $fid, $xmlDoc->toString() );
        fflush( $fid );
        fclose( $fid );
        chmod( $cacheFile, octdec( $mode ) );

        if ( $length === false )
        {
            eZDebug::writeError( 'Failed to write to cache file for RSS export: '.$cacheFile );
        }
    }
}

$length = filesize( $cacheFile );
$fid = fopen( $cacheFile, 'r' );

// Set header settings
header( 'Content-Type: text/xml' );
header( 'Content-Length: '.$length );
header( 'X-Powered-By: eZ publish' );

while ( @ob_end_flush() );

fpassthru( $fid );

fclose ( $fid );

eZExecution::cleanExit();

?>
