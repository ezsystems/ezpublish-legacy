<?php
//
// Created on: <19-Sep-2002 15:40:08 kk>
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

include_once( 'kernel/classes/ezrssexport.php' );
include_once( 'kernel/classes/ezrssexportitem.php' );
include_once( 'kernel/classes/ezrssimport.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

function storeRSSExport( &$Module, &$http, $publish = false )
{
    /* Kill the RSS cache */
    $config =& eZINI::instance( 'site.ini' );
    $cacheDir = $config->variable( 'FileSettings', 'VarDir' ).'/'.$config->variable( 'FileSettings', 'CacheDir' );
    $cacheFile = $cacheDir . '/rss/' . md5( $http->postVariable( 'Access_URL' ) ) . '.xml';
    unlink( $cacheFile );

    /* Create the new RSS feed */
    for ( $itemCount = 0; $itemCount < $http->postVariable( 'Item_Count' ); $itemCount++ )
    {
        $rssExportItem =& eZRSSExportItem::fetch( $http->postVariable( 'Item_ID_'.$itemCount ), true, EZ_RSSEXPORT_STATUS_DRAFT );
        if( $rssExportItem == null )
            continue;
        $rssExportItem->setAttribute( 'class_id', $http->postVariable( 'Item_Class_'.$itemCount ) );
        $rssExportItem->setAttribute( 'title', $http->postVariable( 'Item_Class_Attribute_Title_'.$itemCount ) );
        $rssExportItem->setAttribute( 'description', $http->postVariable( 'Item_Class_Attribute_Description_'.$itemCount ) );
        $rssExportItem->store();
    }
    $rssExport =& eZRSSExport::fetch( $http->postVariable( 'RSSExport_ID' ), true, EZ_RSSEXPORT_STATUS_DRAFT );
    $rssExport->setAttribute( 'title', $http->postVariable( 'title' ) );
    $rssExport->setAttribute( 'url', $http->postVariable( 'url' ) );
//    $rssExport->setAttribute( 'site_access', $http->postVariable( 'SiteAccess' ) );
    $rssExport->setAttribute( 'description', $http->postVariable( 'Description' ) );
    $rssExport->setAttribute( 'rss_version', $http->postVariable( 'RSSVersion' ) );
    $rssExport->setAttribute( 'image_id', $http->postVariable( 'RSSImageID' ) );
    if ( $http->hasPostVariable( 'active' ) )
        $rssExport->setAttribute( 'active', 1 );
    else
        $rssExport->setAttribute( 'active', 0 );
    $rssExport->setAttribute( 'access_url', $http->postVariable( 'Access_URL' ) );

    if ( $publish )
    {
        $rssExport->store( true );
        // remove draft
        $rssExport->remove();
        return $Module->redirectTo( '/rss/list' );
    }
    else
    {
        $rssExport->store();
    }
}

function storeRSSImport( &$Module, &$http, $publish = false )
{
    $rssImport =& eZRSSImport::fetch( $http->postVariable( 'RSSImport_ID' ), true, EZ_RSSIMPORT_STATUS_DRAFT );
    $rssImport->setAttribute( 'name', $http->postVariable( 'name' ) );
    $rssImport->setAttribute( 'url', $http->postVariable( 'url' ) );
    if ( $http->hasPostVariable( 'active' ) )
        $rssImport->setAttribute( 'active', 1 );
    else
        $rssImport->setAttribute( 'active', 0 );
    $rssImport->setAttribute( 'class_id', $http->postVariable( 'Class_ID' ) );
    $rssImport->setAttribute( 'class_title', $http->postVariable( 'Class_Attribute_Title' ) );
    $rssImport->setAttribute( 'class_url', $http->postVariable( 'Class_Attribute_Link' ) );
    $rssImport->setAttribute( 'class_description', $http->postVariable( 'Class_Attribute_Description' ) );

    if ( $publish )
    {
        $rssImport->setAttribute( 'status', EZ_RSSIMPORT_STATUS_VALID );
        $rssImport->store();
        // remove draft
        $rssImport->setAttribute( 'status', EZ_RSSIMPORT_STATUS_DRAFT );
        $rssImport->remove();
        return $Module->redirectTo( '/rss/list' );
    }
    else
    {
        $rssImport->store();
    }
}

?>
