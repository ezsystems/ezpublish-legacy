<?php
//
// Definition of eZRSSExport class
//
// Created on: <18-Sep-2003 11:13:56 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
                                                                'required' => true ) ),
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
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $config =& eZINI::instance( 'site.ini' );
        $dateTime = eZDateTime::currentTimeStamp();
        $row = array( 'id' => null,
                      'title' => 'New RSS Export',
                      'site_access' => '',
                      'modifier_id' => $user_id,
                      'modified' => $dateTime,
                      'creator_id' => $user_id,
                      'created' => $dateTime,
                      'status' => 0,
                      'url' => 'http://'.$config->variable( 'SiteSettings', 'SiteURL' ),
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
    function store( $export_items )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $dateTime = eZDateTime::currentTimeStamp();
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
                            'rss-xml' );
    }

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return ( $attr == 'item_list' or $attr == 'modifier' or 'rss-xml' or
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

            case 'modifier':
            {
                include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
                return eZUser::fetch( $this->ModifierID );
            } break;

            case 'rss-xml':
            {
                return $this->fetchRSSDocument();
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
    function &fetchRSS2( $id = null )
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

        $baseItemURL = $this->attribute( 'url' ).'/'.$this->attribute( 'site_access' ).'/';

        $doc = new eZDOMDocument();
        $doc->setName( 'eZ publish RSS Export' );
        $root =& $doc->createElementNode( 'rss' );
        $doc->setRoot( $root );

    }
    /*!
     Get a RSS xml document based on the RSS 1.0 standard based on the RSS Export settings defined by this object

     \param object ID

     \return RSS 1.0 XML document
    */
    function &fetchRSSDocument( $id = null )
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

        $baseItemURL = $this->attribute( 'url' ).'/'.$this->attribute( 'site_access' ).'/';

        $doc = new eZDOMDocument();
        $doc->setName( 'eZ publish RSS Export' );
        $root =& $doc->createElementNode( 'rdf:RDF' );
        $doc->setRoot( $root );

        $channel =& $doc->createElementNode( 'channel' );
        $root->appendChild( $channel );

        $title =& $doc->createElementNode( 'title' );
        $title->appendChild( $doc->createTextNode( $this->attribute( 'title' ) ) );
        $channel->appendChild( $title );

        $link =& $doc->createElementNode( 'link' );
        $link->appendChild( $doc->createTextNode( $this->attribute( 'url' ) ) );
        $channel->appendChild( $link );

        $description =& $doc->createElementNode( 'description' );
        $description->appendChild( $doc->createTextNode( $this->attribute( 'description' ) ) );
        $channel->appendChild( $description );

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
}
?>
