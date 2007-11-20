<?php
//
// Created on: <19-Sep-2002 15:40:08 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'kernel/classes/ezrssexport.php' );
//include_once( 'kernel/classes/ezrssexportitem.php' );
//include_once( 'kernel/classes/ezrssimport.php' );
//include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

class eZRSSEditFunction
{
    /*!
     Store RSSExport

     \static
     \param Module
     \param HTTP
     \param publish ( true/false )
    */
    static function storeRSSExport( $Module, $http, $publish = false )
    {
        $valid = true;
        $validationErrors = array();
/*        if ( $http->hasPostVariable( 'active' ) && $http->postVariable( 'active' )  == 'on'
             && $http->hasPostVariable( 'Access_URL' ) && strlen( trim( $http->postVariable( 'Access_URL' ) ) ) == 0 )
        {
            $valid = false;
            $publish = false;
            $validationErrors[] =ezi18n( 'design/admin/rss/edit_export',
                                         'If RSS Export is Active then a valid Access URL is required.'  );
        }*/
        // VS-DBFILE

        /* Kill the RSS cache in all siteaccesses */
        $config = eZINI::instance( 'site.ini' );
        $cacheDir = eZSys::cacheDirectory();

        $availableSiteAccessList = $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        foreach ( $availableSiteAccessList as $siteAccess )
        {
            $cacheFilePath = $cacheDir . '/rss/' . md5( $siteAccess . $http->postVariable( 'Access_URL' ) ) . '.xml';
            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
            if ( $cacheFile->exists() )
            {
                // VS-DBFILE : FIXME: optimize not to use recursive delete.
                $cacheFile->delete();
            }
        }

        $db = eZDB::instance();
        $db->begin();
        /* Create the new RSS feed */
        for ( $itemCount = 0; $itemCount < $http->postVariable( 'Item_Count' ); $itemCount++ )
        {
            $rssExportItem = eZRSSExportItem::fetch( $http->postVariable( 'Item_ID_'.$itemCount ), true, eZRSSExport::STATUS_DRAFT );
            if( $rssExportItem == null )
            {
                continue;
            }

            // RSS is supposed to feed certain objects from the subnodes
            if ( $http->hasPostVariable( 'Item_Subnodes_'.$itemCount ) )
            {
                $rssExportItem->setAttribute( 'subnodes', 1 );
            }
            else // Do not include subnodes
            {
                $rssExportItem->setAttribute( 'subnodes', 0 );
            }

            $rssExportItem->setAttribute( 'class_id', $http->postVariable( 'Item_Class_'.$itemCount ) );
            $class = eZContentClass::fetch(  $http->postVariable( 'Item_Class_'.$itemCount ) );

            $titleClassAttributeIdentifier = $http->postVariable( 'Item_Class_Attribute_Title_'.$itemCount );
            $descriptionClassAttributeIdentifier = $http->postVariable( 'Item_Class_Attribute_Description_'.$itemCount );

            if ( !$class )
            {
                $validated = false;
                $validationErrors[] = ezi18n( 'kernel/rss/edit_export',
                                              'Selected class does not exist' );
            }
            else
            {
                $dataMap = $class->attribute( 'data_map' );
                if ( !isset( $dataMap[$titleClassAttributeIdentifier] ) )
                {
                    $valid = false;
                    $validationErrors[] = ezi18n( 'kernel/rss/edit_export',
                                                  'Invalid selection for title class %1 does not have attribute "%2"', null,
                                                  array( $class->attribute( 'name'), $titleClassAttributeIdentifier ) );
                }
                if ( !isset( $dataMap[$descriptionClassAttributeIdentifier] ) )
                {
                    $valid = false;
                    $validationErrors[] = ezi18n( 'kernel/rss/edit_export',
                                                  'Invalid selection for description class %1 does not have attribute "%2"', null,
                                                  array( $class->attribute( 'name'), $descriptionClassAttributeIdentifier ) );
                }
            }

            $rssExportItem->setAttribute( 'title', $http->postVariable( 'Item_Class_Attribute_Title_'.$itemCount ) );
            $rssExportItem->setAttribute( 'description', $http->postVariable( 'Item_Class_Attribute_Description_'.$itemCount ) );
            if( $publish && $valid )
            {
                $rssExportItem->setAttribute( 'status', 1 );
                $rssExportItem->store();
                // delete drafts
                $rssExportItem->setAttribute( 'status', 0 );
                $rssExportItem->remove();
            }
            else
            {
                $rssExportItem->store();
            }
        }
        $rssExport = eZRSSExport::fetch( $http->postVariable( 'RSSExport_ID' ), true, eZRSSExport::STATUS_DRAFT );
        $rssExport->setAttribute( 'title', $http->postVariable( 'title' ) );
        $rssExport->setAttribute( 'url', $http->postVariable( 'url' ) );
        // $rssExport->setAttribute( 'site_access', $http->postVariable( 'SiteAccess' ) );
        $rssExport->setAttribute( 'description', $http->postVariable( 'Description' ) );
        $rssExport->setAttribute( 'rss_version', $http->postVariable( 'RSSVersion' ) );
        $rssExport->setAttribute( 'number_of_objects', $http->postVariable( 'NumberOfObjects' ) );
        $rssExport->setAttribute( 'image_id', $http->postVariable( 'RSSImageID' ) );
        if ( $http->hasPostVariable( 'active' ) )
        {
            $rssExport->setAttribute( 'active', 1 );
        }
        else
        {
            $rssExport->setAttribute( 'active', 0 );
        }
        $rssExport->setAttribute( 'access_url', str_replace( array( '/', '?', '&', '>', '<' ), '',  $http->postVariable( 'Access_URL' ) ) );
        if ( $http->hasPostVariable( 'MainNodeOnly' ) )
        {
            $rssExport->setAttribute( 'main_node_only', 1 );
        }
        else
        {
            $rssExport->setAttribute( 'main_node_only', 0 );
        }

        $published = false;
        if ( $publish && $valid )
        {
            $rssExport->store( true );
            // remove draft
            $rssExport->remove();
            $published = true;
        }
        else
        {
            $rssExport->store();
        }
        $db->commit();
        return array( 'valid' => $valid,
                      'published' => $published,
                      'validation_errors' => $validationErrors );
    }
}
?>
