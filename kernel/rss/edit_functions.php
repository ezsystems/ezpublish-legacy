<?php
//
// Created on: <19-Sep-2002 15:40:08 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

class eZRSSEditFunction
{
    /*!
     Store RSSExport

     \static
     \param Module
     \param HTTP
     \param publish ( true/false )
    */
    static function storeRSSExport( $Module, $http, $publish = false, $skipValuesID = null )
    {
        $valid = true;
        $validationErrors = array();

        /* Kill the RSS cache in all siteaccesses */
        $config = eZINI::instance( 'site.ini' );
        $cacheDir = eZSys::cacheDirectory();

        $availableSiteAccessList = $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        foreach ( $availableSiteAccessList as $siteAccess )
        {
            $cacheFilePath = $cacheDir . '/rss/' . md5( $siteAccess . $http->postVariable( 'Access_URL' ) ) . '.xml';
            $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
            if ( $cacheFile->exists() )
            {
                $cacheFile->delete();
            }
        }

        $db = eZDB::instance();
        $db->begin();
        /* Create the new RSS feed */
        for ( $itemCount = 0; $itemCount < $http->postVariable( 'Item_Count' ); $itemCount++ )
        {
            if ( $skipValuesID == $http->postVariable( 'Item_ID_' . $itemCount ) )
            {
                continue;
            }

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
            $categoryClassAttributeIdentifier = $http->postVariable( 'Item_Class_Attribute_Category_'.$itemCount );

            if ( !$class )
            {
                $validated = false;
                $validationErrors[] = ezpI18n::translate( 'kernel/rss/edit_export',
                                              'Selected class does not exist' );
            }
            else
            {
                $dataMap = $class->attribute( 'data_map' );
                if ( !isset( $dataMap[$titleClassAttributeIdentifier] ) )
                {
                    $valid = false;
                    $validationErrors[] = ezpI18n::translate( 'kernel/rss/edit_export',
                                                  'Invalid selection for title class %1 does not have attribute "%2"', null,
                                                  array( $class->attribute( 'name'), $titleClassAttributeIdentifier ) );
                }
                if ( $descriptionClassAttributeIdentifier != '' && !isset( $dataMap[$descriptionClassAttributeIdentifier] ) )
                {
                    $valid = false;
                    $validationErrors[] = ezpI18n::translate( 'kernel/rss/edit_export',
                                                  'Invalid selection for description class %1 does not have attribute "%2"', null,
                                                  array( $class->attribute( 'name'), $descriptionClassAttributeIdentifier ) );
                }
                if ( $categoryClassAttributeIdentifier != '' && !isset( $dataMap[$categoryClassAttributeIdentifier] ) )
                {
                    $valid = false;
                    $validationErrors[] = ezpI18n::translate( 'kernel/rss/edit_export',
                                                  'Invalid selection for category class %1 does not have attribute "%2"', null,
                                                  array( $class->attribute( 'name'), $categoryClassAttributeIdentifier ) );
                }
            }

            $rssExportItem->setAttribute( 'title', $http->postVariable( 'Item_Class_Attribute_Title_' . $itemCount ) );
            $rssExportItem->setAttribute( 'description', $http->postVariable( 'Item_Class_Attribute_Description_' . $itemCount ) );
            $rssExportItem->setAttribute( 'category', $http->postVariable( 'Item_Class_Attribute_Category_' . $itemCount ) );

            if ( $http->hasPostVariable( 'Item_Class_Attribute_Enclosure_' . $itemCount ) )
                $rssExportItem->setAttribute( 'enclosure', $http->postVariable( 'Item_Class_Attribute_Enclosure_' . $itemCount ) );

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

    /**
     * Set RSSExportItem defaults based on site.ini [RSSSettings] settings
     * 
     * @param eZRSSExportItem $rssExportItem
     * @return bool True if changes where made
     */
    static function setItemDefaults( eZRSSExportItem $rssExportItem )
    {
        $nodeId = $rssExportItem->attribute( 'source_node_id' );
        $node = $nodeId ? eZContentObjectTreeNode::fetch( $nodeId ) : null;

        if ( !$node instanceof eZContentObjectTreeNode )
            return false;

        $config = eZINI::instance( 'site.ini' );
        $nodeClassIdentifier =  $node->attribute( 'class_identifier' );
        $defaultFeedItemClasses = $config->variable( 'RSSSettings', 'DefaultFeedItemClasses' );
        if ( !isset( $defaultFeedItemClasses[$nodeClassIdentifier] ) )
            return false;

        $feedItemClasses = explode( ';', $defaultFeedItemClasses[$nodeClassIdentifier] );
        $iniSection = 'RSSSettings_' . $feedItemClasses[0];
        if ( !$config->hasVariable( $iniSection, 'FeedObjectAttributeMap' ) )
            return false;

        $feedObjectAttributeMap = $config->variable( $iniSection, 'FeedObjectAttributeMap' );
        $subNodesMap = $config->hasVariable( $iniSection, 'Subnodes' ) ? $config->variable( $iniSection, 'Subnodes' ) : array();

        $rssExportItem->setAttribute( 'class_id', eZContentObjectTreeNode::classIDByIdentifier( $feedItemClasses[0] ) );
        $rssExportItem->setAttribute( 'title', $feedObjectAttributeMap['title'] );
        if ( isset( $feedObjectAttributeMap['description'] ) )
            $rssExportItem->setAttribute( 'description', $feedObjectAttributeMap['description'] );

        if ( isset( $feedObjectAttributeMap['category'] ) )
            $rssExportItem->setAttribute( 'category', $feedObjectAttributeMap['category'] );

        if ( isset( $feedObjectAttributeMap['enclosure'] ) )
            $rssExportItem->setAttribute( 'enclosure', $feedObjectAttributeMap['enclosure'] );

        $rssExportItem->setAttribute( 'subnodes', isset( $subNodesMap[$nodeClassIdentifier] ) && $subNodesMap[$nodeClassIdentifier] === 'true' );
        $rssExportItem->store();
    }
}
?>