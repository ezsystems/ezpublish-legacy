<?php
//
// Definition of eZRSSExportItem class
//
// Created on: <18-Sep-2003 13:13:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezrssexportitem.php
*/

/*!
  \class eZRSSExportItem ezrssexportitem.php
  \brief Handles RSS Export Item in eZ publish

  RSSExportItem is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezrssexport.php' );

class eZRSSExportItem extends eZPersistentObject
{
    /*!
     Initializes a new RSSExportItem.
    */
    function eZRSSExportItem( $row )
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
                                         'rssexport_id' => array( 'name' => 'RSSExportID',
                                                                  'datatype' => 'integer',
                                                                  'default' => '',
                                                                  'required' => true ),
                                         'source_node_id' => array( 'name' => 'SourceNodeID',
                                                                    'datatype' => 'integer',
                                                                    'default' => '',
                                                                    'required' => true ),
                                         'class_id' => array( 'name' => 'ClassID',
                                                              'datatype' => 'integer',
                                                              'default' => '',
                                                              'required' => true ),
                                         'description' => array( 'name' => 'Description',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'title' => array( 'name' => 'Title',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ) ),
                      "keys" => array( "id", 'status' ),
                      "increment_key" => "id",
                      "class_name" => "eZRSSExportItem",
                      "name" => "ezrss_export_item" );
    }

    /*!
     \static
     Creates a new RSS Export Item
     \param EZRSSExport objcted id. (The RSSExport this item belongs to)

     \return the URL alias object
    */
    function &create( $rssexport_id )
    {
        $row = array( 'id' => null,
                      'rssexport_id' => $rssexport_id,
                      'source_node_id' => 0,
                      'class_id' => 1,
                      'url_id' => '',
                      'description' => '',
                      'title' => '',
                      'status' => 0 );
        return new eZRSSExportItem( $row );
    }

    /*!
     \reimp
    */
    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(),
                            'class_attributes', 'source_node', 'source_path', 'object_list' );
    }

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return ( $attr == 'class_attributes' or $attr == 'source_node' or $attr == 'source_path' or $attr == 'object_list' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'object_list':
            {
                return $this->fetchObjectList();
            } break;
            case 'class_attributes':
            {
                if ( $this->ClassID == 0 )
                    return null;
                include_once( 'kernel/classes/ezcontentclass.php' );
                $contentClass =& eZContentClass::fetch( $this->ClassID );
                if ( !$contentClass )
                {
                    return null;
                }
                return $contentClass->fetchAttributes();
            } break;
            case 'source_path':
            {
                if ( !isset( $this->SourceNodeID ) || $this->SourceNodeID  == 0 )
                    return null;
                include_once( "kernel/classes/ezcontentobjecttreenode.php" );
                $objectNode =& eZContentObjectTreeNode::fetch( $this->SourceNodeID );
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
            case 'source_node':
            {
                if ( !isset( $this->SourceNodeID ) || $this->SourceNodeID  == 0 )
                    return null;
                include_once( "kernel/classes/ezcontentobjecttreenode.php" );
                return eZContentObjectTreeNode::fetch( $this->SourceNodeID );
            } break;

            case 'modifier_id':
            {
                include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
                return eZUser::fetch( $this->ModifierID );
            }

            default:
                return eZPersistentObject::attribute( $attr );
        }

        return null;
    }

    /*!
     \static
      Fetches the RSS Export by ID.

     \param RSS Export ID
    */
    function &fetch( $id, $asObject = true, $status = EZ_RSSEXPORT_STATUS_VALID )
    {
        return eZPersistentObject::fetchObject( eZRSSExportItem::definition(),
                                                null,
                                                array( "id" => $id,
                                                       'status' => $status ),
                                                $asObject );
    }

    /*
     Fetches the items belonging to the specified RSSExport
     example: fetchFilteredList( array( 'rssexport_id' => 24 ) )

     \param array, example: array( 'rssexport_id' => 24 )

     \return array containing RSSExport Items
    */
    function &fetchFilteredList( $cond, $asObject = true, $status = EZ_RSSEXPORT_STATUS_VALID )
    {
        return eZPersistentObject::fetchObjectList( eZRSSExportItem::definition(),
                                                    null, $cond, array( 'id' => 'asc',
                                                                        'status' => $status ), null,
                                                    $asObject );
    }

    /*!
     Get the N last published objects mathcing the specifications of this RSS Export item

     \param number of objects to fetch

     \return list of Objects
    */
    function &fetchObjectList( $num = 5 )
    {
        include_once( "kernel/classes/ezcontentobjecttreenode.php" );
        if (  $this->attribute( 'source_node_id' ) > 0 )
        {
            return eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                            'DepthOperator' => 'eq',
                                                            'Limit' => $num,
                                                            'SortBy' => array( 'published', false ),
                                                            'ClassFilterType' => 'include',
                                                            'ClassFilterArray' => array( intval( $this->attribute( 'class_id' ) ) ) ),
                                                     $this->attribute( 'source_node_id' ) );
        }
        else
        {
            $list = array();
            return $list;
        }
    }

}

?>
