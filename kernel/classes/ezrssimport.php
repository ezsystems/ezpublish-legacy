<?php
//
// Definition of eZRSSImport class
//
// Created on: <24-Sep-2003 12:53:56 kk>
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

/*! \file ezrssimport.php
*/

/*!
  \class eZRSSImport ezrssimport.php
  \brief Handles RSS Import in eZ publish

  RSSImport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );

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
    function &definition()
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
                      "keys" => array( "id" ),
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
    function &create( $user_id )
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
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZRSSImport::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /*!
     \static
      Fetches complete list of RSS Imports.
    */
    function &fetchList( $asObject = true )
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
        return eZPersistentObject::fetchObjectList( eZRSSImport::definition(),
                                                    null,
                                                    array( 'status' => 1,
                                                           'active' => 1 ),
                                                    null,
                                                    null,
                                                    $asObject );
    }


    /*!
     \reimp
    */
    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(),
                            array( 'class_attributes', 'destination_path', 'modifier', 'object_owner' ) );
    }

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return ( $attr == 'class_attributes' or $attr == 'destination_path' or
                 $attr == 'modifier' or $attr == 'object_owner' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'object_owner':
            {
                include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
                return eZUser::fetch( $this->ObjectOwnerID );
            } break;

            case 'modifier':
            {
                include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
                return eZUser::fetch( $this->ModifierID );
            } break;

            case 'class_attributes':
            {
                if ( $this->ClassID == 0 )
                    return null;
                include_once( 'kernel/classes/ezcontentclass.php' );
                $contentClass =& eZContentClass::fetch( $this->ClassID );
                return $contentClass->fetchAttributes();
            } break;

            case 'destination_path':
            {
                include_once( "kernel/classes/ezcontentobjecttreenode.php" );
                $objectNode =& eZContentObjectTreeNode::fetch( $this->DestinationNodeID );
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

            default:
                return eZPersistentObject::attribute( $attr );
        }

        return null;
    }

}

?>
