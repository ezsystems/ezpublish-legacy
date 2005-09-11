<?php
//
// Definition of eZRSSImport class
//
// Created on: <24-Sep-2003 12:53:56 kk>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezrssimport.php
*/

/*!
  \class eZRSSImport ezrssimport.php
  \brief Handles RSS Import in eZ publish

  RSSImport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );

define( "EZ_RSSIMPORT_STATUS_VALID", 1 );
define( "EZ_RSSIMPORT_STATUS_DRAFT", 0 );

class eZRSSImport extends eZPersistentObject
{
    /*!
     Initializes a new RSSImport.
    */
    function eZRSSImport( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
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
                                         'object_owner_id' => array( 'name' => 'ObjectOwnerID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'name' => array( 'name' => 'Name',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'url' => array( 'name' => 'URL',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'destination_node_id' => array( 'name' => 'DestinationNodeID',
                                                                         'datatype' => 'int',
                                                                         'default' => '',
                                                                         'required' => true ),
                                         'class_id' => array( 'name' => 'ClassID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'class_title' => array( 'name' => 'ClassTitle',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => false ),
                                         'class_url' => array( 'name' => 'ClassURL',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => false ),
                                         'class_description' => array( 'name' => 'ClassDescription',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => false ),
                                         'active' => array( 'name' => 'Active',
                                                            'datatype' => 'integer',
                                                            'default' => 1,
                                                            'required' => true ) ),
                      "keys" => array( "id", 'status' ),
                      'function_attributes' => array( 'class_attributes' => 'classAttributes',
                                                      'destination_path' => 'destinationPath',
                                                      'modifier' => 'modifier',
                                                      'object_owner' => 'objectOwner' ),
                      "increment_key" => "id",
                      "class_name" => "eZRSSImport",
                      "name" => "ezrss_import" );
    }

    /*!
     \static
     Creates a new RSS Import
     \param User ID

     \return the new RSS Import object
    */
    function create( $user_id )
    {
        $dateTime = time();
        $row = array( 'id' => null,
                      'name' => ezi18n( 'kernel/rss', 'New RSS Import' ),
                      'modifier_id' => $user_id,
                      'modified' => $dateTime,
                      'creator_id' => $user_id,
                      'created' => $dateTime,
                      'object_owner_id' => $user_id,
                      'url' => '',
                      'status' => 0,
                      'destination_node_id' => 0,
                      'class_id' => 1,
                      'class_title' => '',
                      'class_url' => '',
                      'class_description' => '',
                      'active' => 1 );

        return new eZRSSImport( $row );
    }

    /*!
     Store Object to database
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store()
    {
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $dateTime = time();
        $user =& eZUser::currentUser();

        $this->setAttribute( 'modifier_id', $user->attribute( 'contentobject_id' ) );
        $this->setAttribute( 'modified', $dateTime );
        eZPersistentObject::store();
    }

    /*!
     \static
      Fetches the RSS Import by ID.

     \param RSS Import ID
    */
    function fetch( $id, $asObject = true, $status = EZ_RSSIMPORT_STATUS_VALID )
    {
        return eZPersistentObject::fetchObject( eZRSSImport::definition(),
                                                null,
                                                array( "id" => $id,
                                                       'status' => $status ),
                                                $asObject );
    }

    /*!
     \static
      Fetches complete list of RSS Imports.
    */
    function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZRSSImport::definition(),
                                                    null, array( 'status' => 1 ), null, null,
                                                    $asObject );
    }

    /*!
     \static
      Fetches complete list of active RSS Imports.
    */
    function &fetchActiveList( $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList( eZRSSImport::definition(),
                                                            null,
                                                            array( 'status' => 1,
                                                                   'active' => 1 ),
                                                            null,
                                                            null,
                                                            $asObject );
        return $objectList;
    }


    function &objectOwner()
    {
        if ( isset( $this->ObjectOwnerID ) and $this->ObjectOwnerID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::fetch( $this->ObjectOwnerID );
        }
        else
            $user = null;
        return $user;
    }

    function &modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::fetch( $this->ModifierID );
        }
        else
            $user = null;
        return $user;
    }

    function &classAttributes()
    {
        if ( isset( $this->ClassID ) and $this->ClassID )
        {
            include_once( 'kernel/classes/ezcontentclass.php' );
            $contentClass = eZContentClass::fetch( $this->ClassID );
            if ( $contentClass )
                $attributes =& $contentClass->fetchAttributes();
            else
                $attributes = null;
        }
        else
            $attributes = null;
        return $attributes;
    }

    function &destinationPath()
    {
        if ( isset( $this->DestinationNodeID ) and $this->DestinationNodeID )
        {
            include_once( "kernel/classes/ezcontentobjecttreenode.php" );
            $objectNode = eZContentObjectTreeNode::fetch( $this->DestinationNodeID );
            if ( isset( $objectNode ) )
            {
                $path_array =& $objectNode->attribute( 'path_array' );
                for ( $i = 0; $i < count( $path_array ); $i++ )
                {
                    $treenode = eZContentObjectTreeNode::fetch( $path_array[$i] );
                    if( $i == 0 )
                        $retValue = $treenode->attribute( 'name' );
                    else
                        $retValue .= '/'.$treenode->attribute( 'name' );
                }
            }
            else
                $retValue = null;
        }
        else
            $retValue = null;
        return $retValue;
    }

}

?>
