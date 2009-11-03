<?php
//
// Definition of eZRSSExport class
//
// Created on: <18-Sep-2003 11:13:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZRSSExport ezrssexport.php
  \brief Handles RSS Export in eZ Publish

  RSSExport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

class eZRSSExport extends eZPersistentObject
{
    const STATUS_VALID = 1;
    const STATUS_DRAFT = 0;

    /*!
     Initializes a new RSSExport.
    */
    function eZRSSExport( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => false ),
                                         'title' => array( 'name' => 'Title',
                                                           'datatype' => 'string',
                                                           'default' => ezi18n( 'kernel/rss', 'New RSS Export' ),
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
                                                              'required' => true,
                                                                 'foreign_class' => 'eZUser',
                                                                 'foreign_attribute' => 'contentobject_id',
                                                                 'multiplicity' => '1..*' ),
                                         'created' => array( 'name' => 'Created',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'creator_id' => array( 'name' => 'CreatorID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                                'foreign_class' => 'eZUser',
                                                                'foreign_attribute' => 'contentobject_id',
                                                                'multiplicity' => '1..*' ),
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
                                                                'required' => false ),
                                         'number_of_objects' => array( 'name' => 'NumberOfObjects',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true ),
                                         'main_node_only' => array( 'name' => 'MainNodeOnly',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ) ),
                      'keys' => array( 'id', 'status' ),
                      'function_attributes' => array( 'item_list' => 'itemList',
                                                      'modifier' => 'modifier',
                                                      'rss-xml' => 'rssXml', // deprecated
                                                      'rss-xml-content' => 'rssXmlContent', // new attribute which uses the Feed component
                                                      'image_path' => 'imagePath',
                                                      'image_node' => 'imageNode' ),
                      'increment_key' => 'id',
                      'sort' => array( 'title' => 'asc' ),
                      'class_name' => 'eZRSSExport',
                      'name' => 'ezrss_export' );

    }

    /*!
     \static
     Creates a new RSS Export
     \param User ID

     \return the URL alias object
    */
    static function create( $user_id )
    {
        $config = eZINI::instance( 'site.ini' );
        $dateTime = time();
        $row = array( 'id' => null,
                      'node_id', '',
                      'title' => ezi18n( 'kernel/classes', 'New RSS Export' ),
                      'site_access' => '',
                      'modifier_id' => $user_id,
                      'modified' => $dateTime,
                      'creator_id' => $user_id,
                      'created' => $dateTime,
                      'status' => 0,
                      'url' => 'http://'. $config->variable( 'SiteSettings', 'SiteURL' ),
                      'description' => '',
                      'image_id' => 0,
                      'active' => 1,
                      'access_url' => '' );
        return new eZRSSExport( $row );
    }

    /*!
     Store Object to database
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store( $storeAsValid = false )
    {
        $dateTime = time();
        $user = eZUser::currentUser();
        if (  $this->ID == null )
        {
            eZPersistentObject::store();
            return;
        }

        $db = eZDB::instance();
        $db->begin();
        if ( $storeAsValid )
        {
            $oldStatus = $this->attribute( 'status' );
            $this->setAttribute( 'status', eZRSSExport::STATUS_VALID );
        }
        $this->setAttribute( 'modified', $dateTime );
        $this->setAttribute( 'modifier_id', $user->attribute( "contentobject_id" ) );
        eZPersistentObject::store();
        $db->commit();
        if ( $storeAsValid )
        {
            $this->setAttribute( 'status', $oldStatus );
        }
    }

    /*!
     Remove the RSS Export.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeThis()
    {
        $exportItems = $this->fetchItems();

        $db = eZDB::instance();
        $db->begin();
        foreach ( $exportItems as $item )
        {
            $item->remove();
        }
        eZPersistentObject::remove();
        $db->commit();
    }

    /*!
     \static
      Fetches the RSS Export by ID.

     \param RSS Export ID
    */
    static function fetch( $id, $asObject = true, $status = eZRSSExport::STATUS_VALID )
    {
        return eZPersistentObject::fetchObject( eZRSSExport::definition(),
                                                null,
                                                array( "id" => $id,
                                                       'status' => $status ),
                                                $asObject );
    }

    /*!
     \static
      Fetches the RSS Export by feed access url and is active.

     \param RSS Export access url
    */
    static function fetchByName( $access_url, $asObject = true )
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
    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZRSSExport::definition(),
                                                    null, array( 'status' => 1 ), null, null,
                                                    $asObject );
    }

    function itemList()
    {
        return $this->fetchItems();
    }

    function imageNode()
    {
        if ( isset( $this->ImageID ) and $this->ImageID )
        {
            return eZContentObjectTreeNode::fetch( $this->ImageID );
        }
        return null;
    }

    function imagePath()
    {
        if ( isset( $this->ImageID ) and $this->ImageID )
        {
            $objectNode = eZContentObjectTreeNode::fetch( $this->ImageID );
            if ( isset( $objectNode ) )
            {
                $retValue = '';
                $path_array = $objectNode->attribute( 'path_array' );
                for ( $i = 0; $i < count( $path_array ); $i++ )
                {
                    $treenode = eZContentObjectTreeNode::fetch( $path_array[$i], false, false );

                    if( $i != 0 )
                    {
                        $retValue .= '/';
                    }

                    $retValue .= array_key_exists( 'name', $treenode ) ? $treenode['name'] : '';
                }
                return $retValue;
            }
        }
        return null;

    }

    function modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            return eZUser::fetch( $this->ModifierID );
        }
        return null;
    }

    /**
     * Generates an RSS feed document based on the rss_version attribute.
     *
     * @deprecated
     * @return DomDocument XML document
     */
    function rssXml()
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

            default:
            {
                return null;
            } break;
        }

        return null;
    }

    /**
     * Generates an RSS feed document based on the rss_version attribute.
     *
     * It uses the Feed component from eZ Components.
     *
     * Supported types: 'rss1', 'rss2', 'atom'.
     *
     * @return string XML document as a string
     */
    function rssXmlContent()
    {
        try
        {
            switch ( $this->attribute( 'rss_version' ) )
            {
                case '1.0':
                {
                    return $this->generateFeed( 'rss1' );
                } break;

                case '2.0':
                {
                    return $this->generateFeed( 'rss2' );
                } break;

                case 'ATOM':
                {
                    return $this->generateFeed( 'atom' );
                } break;

                default:
                {
                    return null;
                } break;
            }
        }
        catch ( ezcFeedException $e )
        {
            return '<?xml version="1.0" encoding="utf-8"?><feed xmlns="http://www.w3.org/2005/Atom" xml:lang=""><title>The RSS feed you were trying to access contains some errors and cannot be generated: ' . $e->getMessage() . ' Please contact the webmaster.</title></feed>';
        }

        return null;
    }

    /*!
      Fetches RSS Items related to this RSS Export. The RSS Export Items contain information about which nodes to export information from

      \param RSSExport ID (optional). Uses current RSSExport's ID as default

      \return RSSExportItem list. null if no RSS Export items found
    */
    function fetchItems( $id = false, $status = eZRSSExport::STATUS_VALID )
    {
        if ( $id === false )
        {
            if ( isset( $this ) )
            {
                $id = $this->ID;
                $status = $this->Status;
            }
            else
            {
                $itemList = null;
                return $itemList;
            }
        }
        if ( $id !== null )
            $itemList = eZRSSExportItem::fetchFilteredList( array( 'rssexport_id' => $id, 'status' => $status ) );
        else
            $itemList = null;
        return $itemList;
    }

    function getObjectListFilter()
    {
        if ( $this->MainNodeOnly == 1 )
        {
            $this->MainNodeOnly = true;
        }
        else
        {
            $this->MainNodeOnly = false;
        }

        return array( 'number_of_objects' => intval($this->NumberOfObjects),
                      'main_node_only'    => $this->MainNodeOnly
                     );
    }

    /**
     * Get a RSS xml document based on the RSS 2.0 standard based on the RSS Export settings defined by this object
     *
     * @deprecated
     * @return string RSS 2.0 XML document
     */
    function fetchRSS2_0()
    {
        $locale = eZLocale::instance();

        // Get URL Translation settings.
        $config = eZINI::instance();
        if ( $config->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            $useURLAlias = true;
        }
        else
        {
            $useURLAlias = false;
        }

        if ( $this->attribute( 'url' ) == '' )
        {
            $baseItemURL = '';
            eZURI::transformURI( $baseItemURL, false, 'full' );
            $baseItemURL .= '/';
        }
        else
        {
            $baseItemURL = $this->attribute( 'url' ).'/'; //.$this->attribute( 'site_access' ).'/';
        }

        $doc = new DOMDocument( '1.0', 'utf-8' );
        $doc->formatOutput = true;
        $root = $doc->createElement( 'rss' );
        $root->setAttribute( 'version', '2.0' );
        $root->setAttribute( 'xmlns:atom', 'http://www.w3.org/2005/Atom' );
        $doc->appendChild( $root );

        $channel = $doc->createElement( 'channel' );
        $root->appendChild( $channel );

        $atomLink = $doc->createElement( 'atom:link' );
        $atomLink->setAttribute( 'href', $baseItemURL . "rss/feed/" . $this->attribute( 'access_url' ) );
        $atomLink->setAttribute( 'rel', 'self' );
        $atomLink->setAttribute( 'type', 'application/rss+xml' );
        $channel->appendChild( $atomLink );

        $channelTitle = $doc->createElement( 'title' );
        $channelTitle->appendChild( $doc->createTextNode( $this->attribute( 'title' ) ) );
        $channel->appendChild( $channelTitle );

        $channelLink = $doc->createElement( 'link' );
        $channelLink->appendChild( $doc->createTextNode( $this->attribute( 'url' ) ) );
        $channel->appendChild( $channelLink );

        $channelDescription = $doc->createElement( 'description' );
        $channelDescription->appendChild( $doc->createTextNode( $this->attribute( 'description' ) ) );
        $channel->appendChild( $channelDescription );

        $channelLanguage = $doc->createElement( 'language' );
        $channelLanguage->appendChild( $doc->createTextNode( $locale->httpLocaleCode() ) );
        $channel->appendChild( $channelLanguage );

        $imageURL = $this->fetchImageURL();
        if ( $imageURL !== false )
        {
            $image = $doc->createElement( 'image' );

            $imageUrlNode = $doc->createElement( 'url' );
            $imageUrlNode->appendChild( $doc->createTextNode( $imageURL ) );
            $image->appendChild( $imageUrlNode );

            $imageTitle = $doc->createElement( 'title' );
            $imageTitle->appendChild( $doc->createTextNode( $this->attribute( 'title' ) ) );
            $image->appendChild( $imageTitle );

            $imageLink = $doc->createElement( 'link' );
            $imageLink->appendChild( $doc->createTextNode( $this->attribute( 'url' ) ) );
            $image->appendChild( $imageLink );

            $channel->appendChild( $image );
        }

        $cond = array(
                    'rssexport_id'  => $this->ID,
                    'status'        => $this->Status
                    );
        $rssSources = eZRSSExportItem::fetchFilteredList( $cond );

        $nodeArray = eZRSSExportItem::fetchNodeList( $rssSources, $this->getObjectListFilter() );

        if ( is_array( $nodeArray ) && count( $nodeArray ) )
        {
            $attributeMappings = eZRSSExportItem::getAttributeMappings( $rssSources );

            foreach ( $nodeArray as $node )
            {
                $object = $node->attribute( 'object' );
                $dataMap = $object->dataMap();
                if ( $useURLAlias === true )
                {
                    $nodeURL = $this->urlEncodePath( $baseItemURL . $node->urlAlias() );
                }
                else
                {
                    $nodeURL = $baseItemURL . 'content/view/full/' . $node->attribute( 'node_id' );
                }

                // keep track if there's any match
                $doesMatch = false;
                // start mapping the class attribute to the respective RSS field
                foreach ( $attributeMappings as $attributeMapping )
                {
                    // search for correct mapping by path
                    if ( $attributeMapping[0]->attribute( 'class_id' ) == $object->attribute( 'contentclass_id' ) and
                         in_array( $attributeMapping[0]->attribute( 'source_node_id' ), $node->attribute( 'path_array' ) ) )
                    {
                        // found it
                        $doesMatch = true;
                        // now fetch the attributes
                        $title =  $dataMap[$attributeMapping[0]->attribute( 'title' )];
                        $description =  $dataMap[$attributeMapping[0]->attribute( 'description' )];
                        // category is optional
                        $catAttributeIdentifier = $attributeMapping[0]->attribute( 'category' );
                        $category = $catAttributeIdentifier ? $dataMap[$catAttributeIdentifier] : false;
                        break;
                    }
                }

                if( !$doesMatch )
                {
                    // no match
                    eZDebug::writeWarning( __METHOD__ . ': Cannot find matching RSS source node for content object in '.__FILE__.', Line '.__LINE__ );
                    $retValue = null;
                    return $retValue;
                }

                $item = $doc->createElement( 'item' );

                // title RSS element with respective class attribute content
                $titleContent =  $title->attribute( 'content' );
                if ( $titleContent instanceof eZXMLText )
                {
                    $outputHandler = $titleContent->attribute( 'output' );
                    $itemTitleText = $outputHandler->attribute( 'output_text' );
                }
                else
                {
                    $itemTitleText = $titleContent;
                }

                $itemTitle = $doc->createElement( 'title' );
                $itemTitle->appendChild( $doc->createTextNode( $itemTitleText ) );
                $item->appendChild( $itemTitle );

                $itemLink = $doc->createElement( 'link' );
                $itemLink->appendChild( $doc->createTextNode( $nodeURL ) );
                $item->appendChild( $itemLink );

                $itemGuid = $doc->createElement( 'guid' );
                $itemGuid->appendChild( $doc->createTextNode( $nodeURL ) );
                $item->appendChild( $itemGuid );

                // description RSS element with respective class attribute content
                $descriptionContent =  $description->attribute( 'content' );
                if ( $descriptionContent instanceof eZXMLText )
                {
                    $outputHandler =  $descriptionContent->attribute( 'output' );
                    $itemDescriptionText = $outputHandler->attribute( 'output_text' );
                }
                else
                {
                    $itemDescriptionText = $descriptionContent;
                }

                $itemDescription = $doc->createElement( 'description' );
                $itemDescription->appendChild( $doc->createTextNode( $itemDescriptionText ) );
                $item->appendChild( $itemDescription );

                // category RSS element with respective class attribute content
                if ( $category )
                {
                    $categoryContent =  $category->attribute( 'content' );
                    if ( $categoryContent instanceof eZXMLText )
                    {
                        $outputHandler = $categoryContent->attribute( 'output' );
                        $itemCategoryText = $outputHandler->attribute( 'output_text' );
                    }
                    elseif ( $categoryContent instanceof eZKeyword )
                    {
                        $itemCategoryText = $categoryContent->keywordString();
                    }
                    else
                    {
                        $itemCategoryText = $categoryContent;
                    }

                    $itemCategory = $doc->createElement( 'category' );
                    $itemCategory->appendChild( $doc->createTextNode( $itemCategoryText ) );
                    $item->appendChild( $itemCategory );
                }

                $itemPubDate = $doc->createElement( 'pubDate' );
                $itemPubDate->appendChild( $doc->createTextNode( gmdate( 'D, d M Y H:i:s', $object->attribute( 'published' ) ) .' GMT' ) );

                $item->appendChild( $itemPubDate );

                $channel->appendChild( $item );
            }
        }

        return $doc;
    }

    /**
     * Get a RSS xml document based on the RSS 1.0 standard based on the RSS Export settings defined by this object
     *
     * @deprecated
     * @return DomDocument RSS 1.0 XML document
     */
    function fetchRSS1_0()
    {
        $imageURL = $this->fetchImageURL();

        // Get URL Translation settings.
        $config = eZINI::instance( 'site.ini' );
        if ( $config->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            $useURLAlias = true;
        }
        else
        {
            $useURLAlias = false;
        }

        if ( $this->attribute( 'url' ) == '' )
        {
            $baseItemURL = '';
            eZURI::transformURI( $baseItemURL, false, 'full' );
            $baseItemURL .= '/';
        }
        else
        {
            $baseItemURL = $this->attribute( 'url' ).'/'; //.$this->attribute( 'site_access' ).'/';
        }

        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElementNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:RDF' );
        $doc->appendChild( $root );

        $channel = $doc->createElementNS( 'http://purl.org/rss/1.0/', 'channel' );
        $channel->setAttributeNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:about', $this->attribute( 'url' ) );
        $root->appendChild( $channel );

        $channelTitle = $doc->createElement( 'title' );
        $channelTitle->appendChild( $doc->createTextNode( $this->attribute( 'title' ) ) );
        $channel->appendChild( $channelTitle );

        $channelUrl = $doc->createElement( 'link' );
        $channelUrl->appendChild( $doc->createTextNode( $this->attribute( 'url' ) ) );
        $channel->appendChild( $channelUrl );

        $channelDescription = $doc->createElement( 'description' );
        $channelDescription->appendChild( $doc->createTextNode( $this->attribute( 'description' ) ) );
        $channel->appendChild( $channelDescription );

        if ( $imageURL !== false )
        {
            $channelImage = $doc->createElement( 'image' );
            $channelImage->setAttributeNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:resource', $imageURL );
            $channel->appendChild( $channelImage );

            $image = $doc->createElement( 'image' );
            $image->setAttributeNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:about', $imageURL );

            $imageTitle = $doc->createElement( 'title' );
            $imageTitle->appendChild( $doc->createTextNode( $this->attribute( 'title' ) ) );
            $image->appendChild( $imageTitle );

            $imageLink = $doc->createElement( 'link' );
            $imageLink->appendChild( $doc->createTextNode( $this->attribute( 'url' ) ) );
            $image->appendChild( $imageLink );

            $imageUrlNode = $doc->createElement( 'url' );
            $imageUrlNode->appendChild( $doc->createTextNode( $imageURL ) );
            $image->appendChild( $imageUrlNode );

            $root->appendChild( $image );
        }

        $items = $doc->createElement( 'items' );
        $channel->appendChild( $items );

        $rdfSeq = $doc->createElementNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:Seq' );
        $items->appendChild( $rdfSeq );

        $cond = array(
                    'rssexport_id'  => $this->ID,
                    'status'        => $this->Status
                    );
        $rssSources = eZRSSExportItem::fetchFilteredList( $cond );

        $nodeArray = eZRSSExportItem::fetchNodeList( $rssSources, $this->getObjectListFilter() );

        if ( is_array( $nodeArray ) && count( $nodeArray ) )
        {
            $attributeMappings = eZRSSExportItem::getAttributeMappings( $rssSources );

            foreach ( $nodeArray as $node )
            {
                $object = $node->attribute( 'object' );
                $dataMap = $object->dataMap();
                if ( $useURLAlias === true )
                {
                    $nodeURL = $this->urlEncodePath( $baseItemURL . $node->urlAlias() );
                }
                else
                {
                    $nodeURL = $baseItemURL . 'content/view/full/' . $node->attribute( 'node_id' );
                }

                $rdfSeqLi = $doc->createElementNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:li' );
                $rdfSeqLi->setAttributeNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:resource', $nodeURL );
                $rdfSeq->appendChild( $rdfSeqLi );

                // keep track if there's any match
                $doesMatch = false;
                // start mapping the class attribute to the respective RSS field
                foreach ( $attributeMappings as $attributeMapping )
                {
                    // search for correct mapping by path
                    if ( $attributeMapping[0]->attribute( 'class_id' ) == $object->attribute( 'contentclass_id' ) and
                         in_array( $attributeMapping[0]->attribute( 'source_node_id' ), $node->attribute( 'path_array' ) ) )
                    {
                        // found it
                        $doesMatch = true;
                        // now fetch the attributes
                        $title =  $dataMap[$attributeMapping[0]->attribute( 'title' )];
                        $description =  $dataMap[$attributeMapping[0]->attribute( 'description' )];
                        break;
                    }
                }

                if( !$doesMatch )
                {
                    // no match
                    eZDebug::writeWarning( __METHOD__ . ': Cannot find matching RSS source node for content object in '.__FILE__.', Line '.__LINE__ );
                    $retValue = null;
                    return $retValue;
                }

                // title RSS element with respective class attribute content
                $titleContent =  $title->attribute( 'content' );
                if ( $titleContent instanceof eZXMLText )
                {
                    $outputHandler =  $titleContent->attribute( 'output' );
                    $itemTitleText = $outputHandler->attribute( 'output_text' );
                }
                else
                {
                    $itemTitleText = $titleContent;
                }

                $itemTitle = $doc->createElement( 'title' );
                $itemTitle->appendChild( $doc->createTextNode( $itemTitleText ) );

                // description RSS element with respective class attribute content
                $descriptionContent =  $description->attribute( 'content' );
                if ( $descriptionContent instanceof eZXMLText )
                {
                    $outputHandler =  $descriptionContent->attribute( 'output' );
                    $itemDescriptionText = $outputHandler->attribute( 'output_text' );
                }
                else
                {
                    $itemDescriptionText = $descriptionContent;
                }

                $itemDescription = $doc->createElement( 'description' );
                $itemDescription->appendChild( $doc->createTextNode( $itemDescriptionText ) );

                $itemLink = $doc->createElement( 'link' );
                $itemLink->appendChild( $doc->createTextNode( $nodeURL ) );

                $item = $doc->createElementNS( 'http://purl.org/rss/1.0/', 'item' );
                $item->setAttributeNS( 'http://www.w3.org/1999/02/22-rdf-syntax-ns#', 'rdf:about', $nodeURL );

                $item->appendChild( $itemTitle );
                $item->appendChild( $itemLink );
                $item->appendChild( $itemDescription );

                $root->appendChild( $item );
            }
        }

        return $doc;
    }

    /**
     * Generates an RSS feed document with type $type and returns it as a string.
     *
     * It uses the Feed component from eZ Components.
     *
     * Supported types: 'rss1', 'rss2', 'atom'.
     *
     * @param string $type One of 'rss1', 'rss2' and 'atom'
     * @return string XML document as a string
     */
    function generateFeed( $type )
    {
        $locale = eZLocale::instance();

        // Get URL Translation settings.
        $config = eZINI::instance();
        if ( $config->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            $useURLAlias = true;
        }
        else
        {
            $useURLAlias = false;
        }

        if ( $this->attribute( 'url' ) == '' )
        {
            $baseItemURL = '';
            eZURI::transformURI( $baseItemURL, false, 'full' );
            $baseItemURL .= '/';
        }
        else
        {
            $baseItemURL = $this->attribute( 'url' ) . '/'; //.$this->attribute( 'site_access' ).'/';
        }

        $feed = new ezcFeed();

        $feed->title = $this->attribute( 'title' );

        $link = $feed->add( 'link' );
        $link->href = $baseItemURL;

        $feed->description = $this->attribute( 'description' );
        $feed->language = $locale->httpLocaleCode();

        // to add the <atom:link> element needed for RSS2
        $feed->id = $baseItemURL . 'rss/feed/' . $this->attribute( 'access_url' );

        // required for ATOM
        $feed->updated = time();
        $author        = $feed->add( 'author' );
        $author->email = $config->variable( 'MailSettings', 'AdminEmail' );
        $creatorObject = eZContentObject::fetch( $this->attribute( 'creator_id' ) );
        if ( $creatorObject instanceof eZContentObject )
        {
            $author->name = $creatorObject->attribute('name');
        }

        $imageURL = $this->fetchImageURL();
        if ( $imageURL !== false )
        {
            $image = $feed->add( 'image' );

            // Required for RSS1
            $image->about = $imageURL;

            $image->url = $imageURL;
            $image->title = $this->attribute( 'title' );
            $image->link = $baseItemURL;
        }

        $cond = array(
                    'rssexport_id'  => $this->ID,
                    'status'        => $this->Status
                    );
        $rssSources = eZRSSExportItem::fetchFilteredList( $cond );

        $nodeArray = eZRSSExportItem::fetchNodeList( $rssSources, $this->getObjectListFilter() );

        if ( is_array( $nodeArray ) && count( $nodeArray ) )
        {
            $attributeMappings = eZRSSExportItem::getAttributeMappings( $rssSources );

            foreach ( $nodeArray as $node )
            {
                $object = $node->attribute( 'object' );
                $dataMap = $object->dataMap();
                if ( $useURLAlias === true )
                {
                    $nodeURL = $this->urlEncodePath( $baseItemURL . $node->urlAlias() );
                }
                else
                {
                    $nodeURL = $baseItemURL . 'content/view/full/' . $node->attribute( 'node_id' );
                }

                // keep track if there's any match
                $doesMatch = false;
                // start mapping the class attribute to the respective RSS field
                foreach ( $attributeMappings as $attributeMapping )
                {
                    // search for correct mapping by path
                    if ( $attributeMapping[0]->attribute( 'class_id' ) == $object->attribute( 'contentclass_id' ) and
                         in_array( $attributeMapping[0]->attribute( 'source_node_id' ), $node->attribute( 'path_array' ) ) )
                    {
                        // found it
                        $doesMatch = true;
                        // now fetch the attributes
                        $title =  $dataMap[$attributeMapping[0]->attribute( 'title' )];
                        // description is optional
                        $descAttributeIdentifier = $attributeMapping[0]->attribute( 'description' );
                        $description = $descAttributeIdentifier ? $dataMap[$descAttributeIdentifier] : false;
                        // category is optional
                        $catAttributeIdentifier = $attributeMapping[0]->attribute( 'category' );
                        $category = $catAttributeIdentifier ? $dataMap[$catAttributeIdentifier] : false;
                        // enclosure is optional
                        $enclosureAttributeIdentifier = $attributeMapping[0]->attribute( 'enclosure' );
                        $enclosure = $enclosureAttributeIdentifier ? $dataMap[$enclosureAttributeIdentifier] : false;
                        break;
                    }
                }

                if( !$doesMatch )
                {
                    // no match
                    eZDebug::writeError( 'Cannot find matching RSS attributes for datamap on node: ' . $node->attribute( 'node_id' ), __METHOD__ );
                    return null;
                }

                // title RSS element with respective class attribute content
                $titleContent =  $title->attribute( 'content' );
                if ( $titleContent instanceof eZXMLText )
                {
                    $outputHandler = $titleContent->attribute( 'output' );
                    $itemTitleText = $outputHandler->attribute( 'output_text' );
                }
                else
                {
                    $itemTitleText = $titleContent;
                }

                $item = $feed->add( 'item' );

                $item->title = $itemTitleText;

                $link = $item->add( 'link' );
                $link->href = $nodeURL;

                $item->id = $nodeURL;

                $itemCreatorObject = $node->attribute('creator');
                if ( $itemCreatorObject instanceof eZContentObject )
                {
                    $author = $item->add( 'author' );
                    $author->name = $itemCreatorObject->attribute('name');
                    $author->email = $config->variable( 'MailSettings', 'AdminEmail' );
                }

                // description RSS element with respective class attribute content
                if ( $description )
                {
                    $descContent = $description->attribute( 'content' );
                    if ( $descContent instanceof eZXMLText )
                    {
                        $outputHandler =  $descContent->attribute( 'output' );
                        $itemDescriptionText = $outputHandler->attribute( 'output_text' );
                    }
                    else if ( $descContent instanceof eZImageAliasHandler )
                    {
                        $itemImage   = $descContent->hasAttribute( 'rssitem' ) ? $descContent->attribute( 'rssitem' ) : $descContent->attribute( 'rss' );
                        $origImage   = $descContent->attribute( 'original' );
                        eZURI::transformURI( $itemImage['full_path'], true, 'full' );
                        eZURI::transformURI( $origImage['full_path'], true, 'full' );
                        $itemDescriptionText = '&lt;a href="' . htmlspecialchars( $origImage['full_path'] )
                                             . '"&gt;&lt;img alt="' . htmlspecialchars( $descContent->attribute( 'alternative_text' ) )
                                             . '" src="' . htmlspecialchars( $itemImage['full_path'] )
                                             . '" width="' . $itemImage['width']
                                             . '" height="' . $itemImage['height']
                                             . '" /&gt;&lt;/a&gt;';
                    }
                    else
                    {
                        $itemDescriptionText = $descContent;
                    }
                    $item->description = $itemDescriptionText;
                }

                // category RSS element with respective class attribute content
                if ( $category )
                {
                    $categoryContent =  $category->attribute( 'content' );
                    if ( $categoryContent instanceof eZXMLText )
                    {
                        $outputHandler = $categoryContent->attribute( 'output' );
                        $itemCategoryText = $outputHandler->attribute( 'output_text' );
                    }
                    elseif ( $categoryContent instanceof eZKeyword )
                    {
                        $itemCategoryText = $categoryContent->keywordString();
                    }
                    else
                    {
                        $itemCategoryText = $categoryContent;
                    }

                    if ( $itemCategoryText )
                    {
                        $cat = $item->add( 'category' );
                        $cat->term = $itemCategoryText;
                    }
                }
                
                // enclosure RSS element with respective class attribute content
                if ( $enclosure )
                {
                    $encItemURL       = false;
                    $enclosureContent = $enclosure->attribute( 'content' );
                    if ( $enclosureContent instanceof eZMedia )
                    {
                        $enc         = $item->add( 'enclosure' );
                        $enc->length = $enclosureContent->attribute('filesize');
                        $enc->type   = $enclosureContent->attribute('mime_type');
                        $encItemURL = 'content/download/' . $enclosure->attribute('contentobject_id')
                                    . '/' . $enclosureContent->attribute( 'contentobject_attribute_id' )
                                    . '/' . urlencode( $enclosureContent->attribute( 'original_filename' ) );
                        eZURI::transformURI( $encItemURL, false, 'full' );
                    }
                    else if ( $enclosureContent instanceof eZBinaryFile )
                    {
                        $enc         = $item->add( 'enclosure' );
                        $enc->length = $enclosureContent->attribute('filesize');
                        $enc->type   = $enclosureContent->attribute('mime_type');
                        $encItemURL = 'content/download/' . $enclosure->attribute('contentobject_id')
                                    . '/' . $enclosureContent->attribute( 'contentobject_attribute_id' )
                                    . '/version/' . $enclosureContent->attribute( 'version' )
                                    . '/file/' . urlencode( $enclosureContent->attribute( 'original_filename' ) );
                        eZURI::transformURI( $encItemURL, false, 'full' );
                    }
                    else if ( $enclosureContent instanceof eZImageAliasHandler )
                    {
                        $enc         = $item->add( 'enclosure' );
                        $origImage   = $enclosureContent->attribute( 'original' );
                        $enc->length = $origImage['filesize'];
                        $enc->type   = $origImage['mime_type'];
                        $encItemURL  = $origImage['full_path'];
                        eZURI::transformURI( $encItemURL, true, 'full' );
                    }

                    if ( $encItemURL )
                    {
                        $enc->url = $encItemURL;
                    }
                }

                $item->published = $object->attribute( 'published' );
                $item->updated = $object->attribute( 'published' );
            }
        }
        return $feed->generate( $type );
    }

    /*!
     \private

     Fetch Image from current ezrss export object. If non exist, or invalid, return false

     \return valid image url
    */
    function fetchImageURL()
    {

        $imageNode =  $this->attribute( 'image_node' );
        if ( !$imageNode )
            return false;

        $imageObject =  $imageNode->attribute( 'object' );
        if ( !$imageObject )
            return false;

        $dataMap =  $imageObject->attribute( 'data_map' );
        if ( !$dataMap )
            return false;

        $imageAttribute =  $dataMap['image'];
        if ( !$imageAttribute )
            return false;

        $imageHandler =  $imageAttribute->attribute( 'content' );
        if ( !$imageHandler )
            return false;

        $imageAlias =  $imageHandler->imageAlias( 'rss' );
        if( !$imageAlias )
            return false;

        $url = eZSys::hostname() . eZSys::wwwDir() .'/'. $imageAlias['url'];
        $url = preg_replace( "#^(//)#", "/", $url );

        return 'http://'.$url;
    }

    /*!
     \private

     Performs rawurlencode() on the path part of the URL. The rest is not touched.

     \return partially encoded url
    */
    function urlEncodePath( $url )
    {
        // Raw encode the path part of the URL
        $urlComponents = parse_url( $url );
        $pathParts = explode( '/', $urlComponents['path'] );
        foreach ( $pathParts as $key => $pathPart )
        {
            $pathParts[$key] = rawurlencode( $pathPart );
        }
        $encodedPath = implode( '/', $pathParts );

        // Rebuild the URL again, like this: scheme://user:pass@host/path?query#fragment
        $encodedUrl = $urlComponents['scheme'] . '://';

        if ( isset( $urlComponents['user'] ) )
        {
            $encodedUrl .= $urlComponents['user'];
            if ( isset( $urlComponents['pass'] ) )
            {
                $encodedUrl .= ':' . $urlComponents['pass'];
            }
            $encodedUrl .= '@';
        }

        $encodedUrl .= $urlComponents['host'];
        if ( isset( $urlComponents['port'] ) )
        {
            $encodedUrl .= ':' . $urlComponents['port'];
        }
        $encodedUrl .= $encodedPath;

        if ( isset( $urlComponents['query'] ) )
        {
            $encodedUrl .= '?' . $urlComponents['query'];
        }

        if ( isset( $urlComponents['fragment'] ) )
        {
            $encodedUrl .= '#' . $urlComponents['fragment'];
        }

        return $encodedUrl;
    }
}
?>
