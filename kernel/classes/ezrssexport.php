<?php
//
// Definition of eZRSSExport class
//
// Created on: <18-Sep-2003 11:13:56 kk>
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

/*! \file ezrssexport.php
*/

/*!
  \class eZRSSExport ezrssexport.php
  \brief Handles RSS Export in eZ publish

  RSSExport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezrssexportitem.php' );

class eZRSSExport extends eZPersistentObject
{
    /*!
     Initializes a new RSSExport.
    */
    function eZRSSExport( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'title' => array( 'name' => 'Title',
                                                           'datatype' => 'string',
                                                           'default' => 'New RSS Export',
                                                           'required' => true ),
                                         'url' => array( 'name' => 'URL',
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => true ),
                                         'site_access' => array( 'name' => 'SiteAccess',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'modifier_id' => array( 'name' => 'ModifierID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'creator_id' => array( 'name' => 'CreatorID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'description' => array( 'name' => 'Description',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => false ),
                                         'image_id' => array( 'name' => 'ImageID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => false ),
                                         'rss_version' => array( 'name' => 'RSSVersion',
                                                                 'datatype' => 'string',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'active' => array( 'name' => 'Active',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'access_url' => array( 'name' => 'AccessURL',
                                                                'datatype' => 'string',
                                                                'default' => 'rss_feed',
                                                                'required' => false ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "title" => "asc" ),
                      "class_name" => "eZRSSExport",
                      "name" => "ezrss_export" );

    }

    /*!
     \static
     Creates a new RSS Export with the new RSS Export
     \param User ID

     \return the URL alias object
    */
    function &create( $user_id )
    {
        $config =& eZINI::instance( 'site.ini' );
        $dateTime = time();
        $row = array( 'id' => null,
                      'title' => 'New RSS Export',
                      'site_access' => '',
                      'modifier_id' => $user_id,
                      'modified' => $dateTime,
                      'creator_id' => $user_id,
                      'created' => $dateTime,
                      'status' => 0,
                      'url' => 'http://'. $config->variable( 'SiteSettings', 'SiteURL' ),
                      'description' => '',
                      'image_id' => 0,
                      'active' => 0,
                      'access_url' => '' );
        return new eZRSSExport( $row );
    }

    /*!
     Store Object to database

     \param export item array
    */
    function store( $export_items = null )
    {
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $dateTime = time();
        $user =& eZUser::currentUser();

        if ( isset( $export_items ) && is_array( $export_items ) )
        {
            foreach ( $export_items as $export_item )
            {
                $export_item->store();
            }
        }
        $this->setAttribute( 'modified', $dateTime );
        $this->setAttribute( 'modifier_id', $user->attribute( "contentobject_id" ) );
        eZPersistentObject::store();
    }

    /*!
     Remove the RSS Export.

     \param export item array also to be removed
    */
    function remove( $export_items )
    {
        if ( isset( $export_items ) && is_array( $export_items ) )
        {
            foreach ( $export_items as $export_item )
            {
                $export_item->remove();
            }
        }
        $exportItems = $this->fetchItems();
        foreach ( $exportItems as $item )
        {
            $item->remove();
        }
        eZPersistentObject::remove();
    }

    /*!
     \static
      Fetches the RSS Export by ID.

     \param RSS Export ID
    */
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZRSSExport::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /*!
     \static
      Fetches the RSS Export by feed access url and is active.

     \param RSS Export access url
    */
    function &fetchByName( $access_url, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZRSSExport::definition(),
                                                null,
                                                array( 'access_url' => $access_url,
                                                       'active' => 1,
                                                       'status' => 1 ),
                                                $asObject );
    }

    /*!
     \static
      Fetches complete list of RSS Exports.
    */
    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZRSSExport::definition(),
                                                    null, array( 'status' => 1 ), null, null,
                                                    $asObject );
    }

    /*!
     \reimp
    */
    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(),
                            'item_list',
                            'modifier',
                            'rss-xml',
                            'image_path',
                            'image_node' );
    }

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return ( $attr == 'item_list' or $attr == 'modifier' or $attr == 'rss-xml' or $attr == 'image_path' or $attr == 'image_node' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'item_list':
            {
                return $this->fetchItems();
            } break;

            case 'image_node':
            {
                include_once( "kernel/classes/ezcontentobjecttreenode.php" );
                return eZContentObjectTreeNode::fetch( $this->ImageID );
            }

            case 'image_path':
            {
                include_once( "kernel/classes/ezcontentobjecttreenode.php" );
                $objectNode =& eZContentObjectTreeNode::fetch( $this->ImageID );
                if ( !isset( $objectNode ) )
                    return null;
                $path_array =& $objectNode->attribute( 'path_array' );
                for ( $i = 0; $i < count( $path_array ); $i++ )
                {
                    $treenode = eZContentObjectTreeNode::fetch( $path_array[$i] );
                    if( $i == 0 )
                        $return = $treenode->attribute( 'name' );
                    else
                        $return .= '/'.$treenode->attribute( 'name' );
                }
                return $return;

            } break;

            case 'modifier':
            {
                include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
                return eZUser::fetch( $this->ModifierID );
            } break;

            case 'rss-xml':
            {
                switch( $this->attribute( 'rss_version' ) )
                {
                    case '1.0':
                    {
                        return $this->fetchRSS1_0();
                    } break;

                    case '2.0':
                    {
                        return $this->fetchRSS2_0();
                    } break;
                }
            } break;

            default:
                return eZPersistentObject::attribute( $attr );
        }

        return null;
    }

    /*!
      Fetches RSS Items related to this RSS Export. The RSS Export Items contain information about which nodes to export information from

      \param RSSExport ID (optional). Uses current RSSExport's ID as default

      \return RSSExportItem list. null if no RSS Export items found
    */
    function &fetchItems( $id = false )
    {
        if ( $id === false )
        {
            if ( isset( $this ) )
            {
                $id = $this->ID;
            }
            else
                return null;
        }

        return eZRSSExportItem::fetchFilteredList( array( 'rssexport_id' => $id ) );
    }

    /*!
     Get a RSS xml document based on the RSS 2.0 standard based on the RSS Export settings defined by this object

     \param

     \return RSS 2.0 XML docuemnt
    */
    function &fetchRSS2_0( $id = null )
    {
        if ( $id != null )
        {
            $rssExport = eZRSSExport::fetch( $id );
            return $rssExport->fetchRSSDocument();
        }

        include_once( 'lib/ezxml/classes/ezdomdocument.php' );

        // Get URL Translation settings.
        $config =& eZINI::instance( 'site.ini' );
        if ( $config->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            $useURLAlias = true;
        }
        else
        {
            $useURLAlias = false;
        }

        $baseItemURL = $this->attribute( 'url' ).'/'; //.$this->attribute( 'site_access' ).'/';

        $doc = new eZDOMDocument();
        $doc->setName( 'eZ publish RSS Export' );
        $root =& $doc->createElementNode( 'rss', array( 'version' => '2.0' ) );
        $doc->setRoot( $root );

        $channel =& $doc->createElementNode( 'channel' );
        $root->appendChild( $channel );

        $channel->appendChild( $doc->createElementTextNode( 'title', $this->attribute( 'title' ) ) );
        $channel->appendChild( $doc->createElementTextNode( 'link', $this->attribute( 'url' ) ) );
        $channel->appendChild( $doc->createElementTextNode( 'description', $this->attribute( 'description' ) ) );
        $channel->appendChild( $doc->createElementTextNode( 'language', $this->attribute( 'language' ) ) );

        $imageURL = $this->fetchImageURL();
        if ( $imageURL !== false )
        {
            $image =& $doc->createElementNode( 'image' );
            $image->appendChild( $doc->createElementTextNode( 'url', $imageURL ) );
            $image->appendChild( $doc->createElementTextNode( 'title', $this->attribute( 'title' ) ) );
            $image->appendChild( $doc->createElementTextNode( 'link', $this->attribute( 'url' ) ) );
            $channel->appendChild( $image );
        }

        $rssItemArray =& $this->fetchItems();
        foreach ( $rssItemArray as $rssItem )
        {
            $nodeArray =& $rssItem->attribute( 'object_list' );
            foreach ( $nodeArray as $node )
            {
                $object =& $node->attribute( 'object' );
                $dataMap =& $object->dataMap();
                if ( $useURLAlias === true )
                {
                    $nodeURL = $baseItemURL.$node->urlAlias();
                }
                else
                {
                    $nodeURL = $baseItemURL.'content/view/full/'.$object->attribute( 'id' );
                }

                $itemTitle =& $doc->createElementNode( 'title' );
                $title =& $dataMap[$rssItem->attribute( 'title' )];
                $titleContent =& $title->attribute( 'content' );
                if ( get_class( $titleContent ) == 'ezxmltext' )
                {
                    $outputHandler =& $titleContent->attribute( 'output' );
                    $itemTitle->appendChild( $doc->createTextNode( $outputHandler->attribute( 'output_text' ) ) );
                }
                else
                {
                    $itemTitle->appendChild( $doc->createTextNode( $titleContent ) );
                }

                $itemDescription =& $doc->createElementNode( 'description' );
                $description =& $dataMap[$rssItem->attribute( 'description' )];
                $descriptionContent =& $description->attribute( 'content' );
                if ( get_class( $descriptionContent ) == 'ezxmltext' )
                {
                    $outputHandler =& $descriptionContent->attribute( 'output' );
                    $itemDescription->appendChild( $doc->createTextNode( $outputHandler->attribute( 'output_text' ) ) );
                }
                else
                {
                    $itemDescription->appendChild( $doc->createTextNode( $descriptionContent ) );
                }

                $itemLink =& $doc->createElementNode( 'link' );
                $itemLink->appendChild( $doc->createTextNode( $nodeURL ) );

                $item =& $doc->createElementNode( 'item' );

                $item->appendChild( $doc->createElementTextNode( 'pubDate',
                                                                 gmdate( 'D, d M Y H:i:s', $object->attribute( 'published' ) ) .'GMT' ) );

                $item->appendChild( $itemTitle );
                $item->appendChild( $itemLink );
                $item->appendChild( $itemDescription );

                $channel->appendChild( $item );

            }
        }
        return $doc;
    }

    /*!
     Get a RSS xml document based on the RSS 1.0 standard based on the RSS Export settings defined by this object

     \param object ID

     \return RSS 1.0 XML document
    */
    function &fetchRSS1_0( $id = null )
    {
        if ( $id != null )
        {
            $rssExport = eZRSSExport::fetch( $id );
            return $rssExport->fetchRSSDocument();
        }

        include_once( 'lib/ezxml/classes/ezdomdocument.php' );

        $imageURL = $this->fetchImageURL();

        // Get URL Translation settings.
        $config =& eZINI::instance( 'site.ini' );
        if ( $config->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            $useURLAlias = true;
        }
        else
        {
            $useURLAlias = false;
        }

        $baseItemURL = $this->attribute( 'url' ).'/'; //.$this->attribute( 'site_access' ).'/';

        $doc = new eZDOMDocument();
        $doc->setName( 'eZ publish RSS Export' );
        $root =& $doc->createElementNode( 'rdf:RDF' );
        $doc->setRoot( $root );

        $channel =& $doc->createElementNode( 'channel' );
        $root->appendChild( $channel );

        $channel->appendChild( $doc->createElementTextNode( 'title', $this->attribute( 'title' ) ) );
        $channel->appendChild( $doc->createElementTextNode( 'link', $this->attribute( 'url' ) ) );
        $channel->appendChild( $doc->createElementTextNode( 'description', $this->attribute( 'description' ) ) );

        if ( $imageURL !== false )
        {
            $channel->appendChild( $doc->createElementNode( 'image', array( 'rdf:resource' => $imageURL ) ) );
            $image =& $doc->createElementNode( 'image', array( 'rdf:about' => $imageURL ) );
            $image->appendChild( $doc->createElementTextNode( 'title', $this->attribute( 'title' ) ) );
            $image->appendChild( $doc->createElementTextNode( 'link', $this->attribute( 'url' ) ) );
            $image->appendChild( $doc->createElementTextNode( 'url', $imageURL ) );
            $root->appendChild( $image );
        }

        $items =& $doc->createElementNode( 'items' );
        $channel->appendChild( $items );

        $rdfSeq =& $doc->createElementNode( 'rdf:seq' );
        $items->appendChild( $rdfSeq );

        $rssItemArray =& $this->fetchItems();
        foreach ( $rssItemArray as $rssItem )
        {
            $nodeArray =& $rssItem->attribute( 'object_list' );
            foreach ( $nodeArray as $node )
            {
                $object =& $node->attribute( 'object' );
                $dataMap =& $object->dataMap();
                if ( $useURLAlias === true )
                {
                    $nodeURL = $baseItemURL.$node->urlAlias();
                }
                else
                {
                    $nodeURL = $baseItemURL.'content/view/full/'.$object->attribute( 'id' );
                }

                $rdfSeq->appendChild( $doc->createElementNode( 'rdf:li', array( 'rdf:resource' => $nodeURL ) ) );

                $itemTitle =& $doc->createElementNode( 'title' );
                $title =& $dataMap[$rssItem->attribute( 'title' )];
                $titleContent =& $title->attribute( 'content' );
                if ( get_class( $titleContent ) == 'ezxmltext' )
                {
                    $outputHandler =& $titleContent->attribute( 'output' );
                    $itemTitle->appendChild( $doc->createTextNode( $outputHandler->attribute( 'output_text' ) ) );
                }
                else
                {
                    $itemTitle->appendChild( $doc->createTextNode( $titleContent ) );
                }

                $itemDescription =& $doc->createElementNode( 'description' );
                $description =& $dataMap[$rssItem->attribute( 'description' )];
                $descriptionContent =& $description->attribute( 'content' );
                if ( get_class( $descriptionContent ) == 'ezxmltext' )
                {
                    $outputHandler =& $descriptionContent->attribute( 'output' );
                    $itemDescription->appendChild( $doc->createTextNode( $outputHandler->attribute( 'output_text' ) ) );
                }
                else
                {
                    $itemDescription->appendChild( $doc->createTextNode( $descriptionContent ) );
                }

                $itemLink =& $doc->createElementNode( 'link' );
                $itemLink->appendChild( $doc->createTextNode( $nodeURL ) );

                $item =& $doc->createElementNode( 'item', array ( 'rdf:about' => $nodeURL ) );

                $item->appendChild( $itemTitle );
                $item->appendChild( $itemLink );
                $item->appendChild( $itemDescription );

                $root->appendChild( $item );
            }
        }
        return $doc;
    }

    /*!
     \private

     Fetch Image from current ezrss export object. If non exist, or invalid, return false

     \return valid image url
    */
    function fetchImageURL()
    {

        $imageNode =& $this->attribute( 'image_node' );
        if ( !$imageNode )
            return false;

        $imageObject =& $imageNode->attribute( 'object' );
        if ( !$imageObject )
            return false;

        $dataMap =& $imageObject->attribute( 'data_map' );
        if ( !$dataMap )
            return false;

        $imageAttribute =& $dataMap['image'];
        if ( !$imageAttribute )
            return false;

        $imageHandler =& $imageAttribute->attribute( 'content' );
        if ( !$imageHandler )
            return false;

        $imageAlias =& $imageHandler->imageAlias( 'rss' );
        if( !$imageAlias )
            return false;

        $url = eZSys::hostname() . eZSys::wwwDir() .'/'. $imageAlias['url'];
        $url = preg_replace( "#^(//)#", "/", $url );

        return 'http://'.$url;
    }
}
?>
