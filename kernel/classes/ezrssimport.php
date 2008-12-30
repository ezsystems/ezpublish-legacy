<?php
//
// Definition of eZRSSImport class
//
// Created on: <24-Sep-2003 12:53:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezrssimport.php
*/

/*!
  \class eZRSSImport ezrssimport.php
  \brief Handles RSS Import in eZ Publish

  RSSImport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

class eZRSSImport extends eZPersistentObject
{
    const STATUS_VALID = 1;
    const STATUS_DRAFT = 0;

    /*!
     Initializes a new RSSImport.
    */
    function eZRSSImport( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
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
                                         'object_owner_id' => array( 'name' => 'ObjectOwnerID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZUser',
                                                                     'foreign_attribute' => 'contentobject_id',
                                                                     'multiplicity' => '1..*' ),
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
                                                                         'required' => true,
                                                                         'foreign_class' => 'eZContentObjectTreeNode',
                                                                         'foreign_attribute' => 'node_id',
                                                                         'multiplicity' => '1..*' ),
                                         'class_id' => array( 'name' => 'ClassID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZContentClass',
                                                              'foreign_attribute' => 'id',
                                                              'multiplicity' => '1..*' ),
                                         'class_title' => array( 'name' => 'ClassTitle', // deprecated
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => false ),
                                         'class_url' => array( 'name' => 'ClassURL', // deprecated
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => false ),
                                         'class_description' => array( 'name' => 'ClassDescription', // deprecated
                                                                       'datatype' => 'string',
                                                                       'default' => '',
                                                                       'required' => false ),
                                         'active' => array( 'name' => 'Active',
                                                            'datatype' => 'integer',
                                                            'default' => 1,
                                                            'required' => true ),
                                         'import_description' => array( 'name' => 'ImportDescriptionValue',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ) ),
                      "keys" => array( "id", 'status' ),
                      'function_attributes' => array( 'class_attributes' => 'classAttributes',
                                                      'destination_path' => 'destinationPath',
                                                      'modifier' => 'modifier',
                                                      'object_owner' => 'objectOwner',
                                                      'import_description_array' => 'importDescription',
                                                      'field_map' => 'fieldMap',
                                                      'object_attribute_list' => 'objectAttributeList' ),
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
    static function create( $userID = false )
    {
        if ( $userID === false )
        {
            $user = eZUser::currentUser();
            $userID = $user->attribute( "contentobject_id" );
        }

        $dateTime = time();
        $row = array( 'id' => null,
                      'name' => ezi18n( 'kernel/rss', 'New RSS Import' ),
                      'modifier_id' => $userID,
                      'modified' => $dateTime,
                      'creator_id' => $userID,
                      'created' => $dateTime,
                      'object_owner_id' => $userID,
                      'url' => '',
                      'status' => 0,
                      'destination_node_id' => 0,
                      'class_id' => 0,
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
    function store( $fieldFilters = null )
    {
        $dateTime = time();
        $user = eZUser::currentUser();

        $this->setAttribute( 'modifier_id', $user->attribute( 'contentobject_id' ) );
        $this->setAttribute( 'modified', $dateTime );
        eZPersistentObject::store( $fieldFilters );
    }

    /*!
     \static
      Fetches the RSS Import by ID.

     \param RSS Import ID
    */
    static function fetch( $id, $asObject = true, $status = eZRSSImport::STATUS_VALID )
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
    static function fetchList( $asObject = true, $status = eZRSSImport::STATUS_VALID )
    {
        $cond = null;
        if ( $status !== false )
        {
            $cond = array( 'status' => $status );
        }
        return eZPersistentObject::fetchObjectList( eZRSSImport::definition(),
                                                    null, $cond, null, null,
                                                    $asObject );
    }

    /*!
     \static
      Fetches complete list of active RSS Imports.
    */
    static function fetchActiveList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZRSSImport::definition(),
                                                    null,
                                                    array( 'status' => 1,
                                                           'active' => 1 ),
                                                    null,
                                                    null,
                                                    $asObject );
    }


    function objectOwner()
    {
        if ( isset( $this->ObjectOwnerID ) and $this->ObjectOwnerID )
        {
            return eZUser::fetch( $this->ObjectOwnerID );
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

    function classAttributes()
    {
        if ( isset( $this->ClassID ) and $this->ClassID )
        {
            $contentClass = eZContentClass::fetch( $this->ClassID );
            if ( $contentClass )
            {
                return $contentClass->fetchAttributes();
            }
        }
        return null;
    }

    function destinationPath()
    {
        $retValue = null;
        if ( isset( $this->DestinationNodeID ) and $this->DestinationNodeID )
        {
            $objectNode = eZContentObjectTreeNode::fetch( $this->DestinationNodeID );
            if ( isset( $objectNode ) )
            {
                $path_array = $objectNode->attribute( 'path_array' );
                $path_array_count = count( $path_array );
                for ( $i = 0; $i < $path_array_count; ++$i )
                {
                    $treenode = eZContentObjectTreeNode::fetch( $path_array[$i], false, false );
                    if ( is_array( $treenode ) && array_key_exists( 'name', $treenode ) )
                    {
                        if ( $i == 0 )
                        {
                            $retValue = $treenode['name'];
                        }
                        else
                        {
                            $retValue .= '/' . $treenode['name'];
                        }
                    }
                }
            }
        }
        return $retValue;
    }

    /*!
     \static
     Analize RSS import, and get RSS version number

     \param URL

     \return RSS version number, false if invalid URL
    */
    static function getRSSVersion( $url )
    {
        $xmlData = eZHTTPTool::getDataByURL( $url );

        if ( $xmlData === false )
            return false;

        // Create DomDocument from http data

        $domDocument = new DOMDocument( '1.0', 'utf-8' );
        $success = $domDocument->loadXML( $xmlData );

        if ( !$success )
        {
            return false;
        }

        $root = $domDocument->documentElement;

        switch( $root->getAttribute( 'version' ) )
        {
            default:
            case '1.0':
            {
                return '1.0';
            } break;

            case '0.91':
            case '0.92':
            case '2.0':
            {
                return $root->getAttribute( 'version' );
            } break;
        }
    }

    /*!
     \static
     Object attribute list
    */
    static function objectAttributeList()
    {
        return array( 'published' => 'Published',
                      'modified' => 'Modified' );
    }

    /*!
     \static

     Return default RSS field definition

     \param RSS version

     \return RSS field definition array.
    */
    static function rssFieldDefinition( $version = '2.0' )
    {
        switch ( $version )
        {
            case '1.0':
            {
                return array( 'item' => array( 'attributes' => array( 'about' ),
                                               'elements' => array( 'title',
                                                                    'link',
                                                                    'description' ) ),
                              'channel' => array( 'attributes' => array( 'about' ),
                                                  'elements' => array( 'title',
                                                                       'link',
                                                                       'description'.
                                                                       'image' => array( 'attributes' => array( 'resource' ) ) ) ) );
            } break;

            case '2.0':
            case '0.91':
            case '0.92':
            {
                return array( 'item' => array( 'elements' => array( 'title',
                                                                    'link',
                                                                    'description',
                                                                    'author',
                                                                    'category',
                                                                    'comments',
                                                                    'guid',
                                                                    'pubDate' ) ),
                              'channel' => array( 'elements' => array( 'title',
                                                                       'link',
                                                                       'description',
                                                                       'copyright',
                                                                       'managingEditor',
                                                                       'webMaster',
                                                                       'pubDate',
                                                                       'lastBuildDate',
                                                                       'category',
                                                                       'generator',
                                                                       'docs',
                                                                       'cloud',
                                                                       'ttl' ) ) );
            }
        }
    }

    /*!
     \static

     \param RSS version

     \return Ordered array of field definitions
    */
    static function fieldMap( $version = '2.0' )
    {
        $fieldDefinition = eZRSSImport::rssFieldDefinition();

        $ini = eZINI::instance();
        foreach( $ini->variable( 'RSSSettings', 'ActiveExtensions' ) as $activeExtension )
        {
            if ( file_exists( eZExtension::baseDirectory() . '/' . $activeExtension . '/rss/' . $activeExtension . 'rssimport.php' ) )
            {
                include_once( eZExtension::baseDirectory() . '/' . $activeExtension . '/rss/' . $activeExtension . 'rssimport.php' );
                $fieldDefinition = eZRSSImport::arrayMergeRecursive( $fieldDefinition, call_user_func( array(  $activeExtension . 'rssimport', 'rssFieldDefinition' ), array() ) );
            }
        }

        $returnArray = array();
        eZRSSImport::recursiveFieldMap( $fieldDefinition, '', '', $returnArray, 0 );

        return $returnArray;
    }

    /*!
     \static

     Recursivly build field map

     \param array
    */
    static function recursiveFieldMap( $definitionArray, $globalKey, $value, &$returnArray, $count )
    {
        foreach( $definitionArray as $key => $definition )
        {
            if ( is_string( $definition ) )
            {
                $returnArray[$globalKey . ' - ' . $definition ] = $value . ' - ' . ucfirst( $definition );
            }
            else
            {
                eZRSSImport::recursiveFieldMap( $definition,
                                                $globalKey . ( strlen( $globalKey ) ? ' - ' : '' ) . $key ,
                                                $value . ( strlen( $value ) && ( $count % 2 == 0 ) ? ' - ' : '' ) . ( $count % 2 == 0 ? ucfirst( $key ) : '' ),
                                                $returnArray, $count + 1 );
            }
        }
    }

    /*!
     Set import description

     Import definition must be set as an multidimentional array.

     Example : array( 'rss_version' => <version>,
                      'object_attributes' => array( ... ),
                      'class_attributes' => array( <content class attribute id> => <RSS import field>,  ... ) )
    */
    function setImportDescription( $definition = array() )
    {
        $this->setAttribute( 'import_description', serialize( $definition ) );
    }

    /*!
     Get import description

     \return import description
    */
    function importDescription()
    {
        $description = @unserialize( $this->attribute( 'import_description' ) );
        if ( !$description )
        {
            $description = array();
        }
        return $description;
    }

    static function arrayMergeRecursive( $arr1, $arr2 )
    {
        if ( !is_array( $arr1 ) ||
             !is_array( $arr2 ) )
        {
            return $arr2;
        }
        foreach ($arr2 AS $key => $value )
        {
            $arr1[$key] = eZRSSImport::arrayMergeRecursive( @$arr1[$key], $value);
        }

        return $arr1;
    }
}

?>
