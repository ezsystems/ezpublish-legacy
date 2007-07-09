<?php
//
// Definition of eZRSSExport class
//
// Created on: <21-Nov-2003 15:59:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

/*! \file ezrssexport.php
*/

/*!
  \class for storing PDF exports

  RSSExport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezrssexportitem.php' );

define( "EZ_PDFEXPORT_VERSION_VALID", 0 );
define( "EZ_PDFEXPORT_VERSION_DRAFT", 1 );

define( "EZ_PDFEXPORT_CREATE_ONCE",  1 );
define( "EZ_PDFEXPORT_CREATE_ONFLY", 2 );

class eZPDFExport extends eZPersistentObject
{
    /*!
     Initializes a new RSSExport.
    */
    function eZPDFExport( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'title' => array( 'name' => 'Title',
                                                           'datatype' => 'string',
                                                           'default' => ezi18n( 'kernel/pdfexport', 'New PDF Export' ),
                                                           'required' => true ),
                                         'show_frontpage' => array( 'name' => 'DisplayFrontpage',
                                                                       'datatype' => 'integer',
                                                                       'default' => 1,
                                                                       'required' => true ),
                                         'intro_text' => array( 'name' => 'IntroText',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => false ),
                                         'sub_text' => array( 'name' => 'SubText',
                                                              'datatype' => 'text',
                                                              'default' => '',
                                                              'required' => false ),
                                         'source_node_id' => array( 'name' => 'SourceNodeID',
                                                                    'datatype' => 'int',
                                                                    'default' => '',
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZContentObjectTreeNode',
                                                                    'foreign_attribute' => 'node_id',
                                                                    'multiplicity' => '1..*' ),
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
                                         'export_structure' => array( 'name' => 'ExportStructure',
                                                                      'datatype' => 'string',
                                                                      'default' => 'tree',
                                                                      'required' => false ),
                                         'export_classes' => array( 'name' => 'ExportClasses',
                                                                    'datatype' => 'string',
                                                                    'default' => 0,
                                                                    'required' => false ),
                                         'pdf_filename' => array( 'name' => 'PDFFileName',
                                                                   'datatype' => 'string',
                                                                   'default' => 'file.pdf',
                                                                   'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => EZ_PDFEXPORT_CREATE_ONCE,
                                                            'required' => true ),
                                         'version' => array( 'name' => 'Version',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      'keys' => array( 'id', 'version' ),
                      'function_attributes' => array ( 'modifier' => 'modifier',
                                                       'source_node' => 'sourceNode',
                                                       'filepath' => 'filepath',
                                                       'export_classes_array' => 'exportClassesArray' ),
                      'increment_key' => 'id',
                      'sort' => array( 'title' => 'asc' ),
                      'class_name' => 'eZPDFExport',
                      'name' => 'ezpdf_export' );
    }

    /*!
     \static
     Creates a new RSS Export with the new RSS Export
     \param User ID
    */
    function create( $user_id )
    {
        $config = eZINI::instance( 'site.ini' );
        $dateTime = time();
        $row = array( 'id' => null,
                      'title' => ezi18n( 'kernel/pdfexport', 'New PDF Export' ),
                      'show_frontpage' => 1,
                      'intro_text' => '',
                      'sub_text' => '',
                      'source_node_id' => 0,
                      'export_structure' => 'tree',
                      'export_classes' => '',
                      'site_access' => '',
                      'pdf_filename' => 'file.pdf',
                      'modifier_id' => $user_id,
                      'modified' => $dateTime,
                      'creator_id' => $user_id,
                      'created' => $dateTime,
                      'status' => 0,
                      'version' => 1 );
        return new eZPDFExport( $row );
    }

    /*!
     Store Object to database
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store( $publish = false )
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

        if ( $publish )
        {
            $originalVersion = $this->attribute( 'version' );
            $this->setAttribute( 'version', EZ_PDFEXPORT_VERSION_VALID );
        }
        $user = eZUser::currentUser();
        $this->setAttribute( 'modified', time() );
        $this->setAttribute( 'modifier_id', $user->attribute( 'contentobject_id' ) );

        $db = eZDB::instance();
        $db->begin();
        eZPersistentObject::store();
        if ( $publish )
        {
            $this->setAttribute( 'version', EZ_PDFEXPORT_VERSION_DRAFT );
            $this->remove();
            $this->setAttribute( 'version', $originalVersion );
        }
        $db->commit();
    }

    /*!
     \static
      Fetches the RSS Export by ID.

     \param RSS Export ID
    */
    function fetch( $id, $asObject = true, $version = EZ_PDFEXPORT_VERSION_VALID )
    {
        return eZPersistentObject::fetchObject( eZPDFExport::definition(),
                                                null,
                                                array( 'id' => $id,
                                                       'version' => $version ),
                                                $asObject );
    }

    /*!
     \reimp
     \transaction unsafe.
    */
    function remove()
    {
        if ( $this->attribute( 'version' ) == EZ_PDFEXPORT_VERSION_VALID &&
             $this->attribute( 'status' ) != EZ_PDFEXPORT_CREATE_ONFLY )
        {
            $sys = eZSys::instance();
            $storage_dir = $sys->storageDirectory();

            $filename = $storage_dir . '/pdf/' . $this->attribute( 'pdf_filename' );
            if ( file_exists( $filename ) )
            {
                unlink( $filename );
            }
        }
        eZPersistentObject::remove();
    }

    /*!
     \static
      Fetches complete list of RSS Exports.
    */
    function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZPDFExport::definition(),
                                                    null,
                                                    array( 'version' => EZ_PDFEXPORT_VERSION_VALID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user = eZUser::fetch( $this->ModifierID );
        }
        else
            $user = null;
        return $user;
    }

    function &sourceNode()
    {
        if ( isset( $this->SourceNodeID ) and $this->SourceNodeID )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $sourceNode = eZContentObjectTreeNode::fetch( $this->SourceNodeID );
        }
        else
            $sourceNode = null;
        return $sourceNode;
    }

    function &filepath()
    {
        $sys = eZSys::instance();
        $storage_dir = $sys->storageDirectory();
        $filePath = $storage_dir . '/pdf/' . $this->attribute( 'pdf_filename' );
        return $filePath;
    }

    function &exportClassesArray()
    {
        $exportClassesArray = explode( ':',  eZPersistentObject::attribute( 'export_classes' ) );
        return $exportClassesArray;
    }

    function countGeneratingOnceExports( $filename = '' )
    {
        $conditions = array( 'version' => EZ_PDFEXPORT_VERSION_VALID,
                             'status' =>  EZ_PDFEXPORT_CREATE_ONCE,
                             'pdf_filename' => $filename );

        if ( $filename === '' && isset( $this ) )
        {
            $conditions['pdf_filename'] = $this->attribute( 'pdf_filename' );
            $conditions['id'] = array( '<>', $this->attribute( 'id' ) );
        }

        $queryResult = eZPersistentObject::fetchObjectList( eZPDFExport::definition(),
                                                            array(),
                                                            $conditions,
                                                            false,
                                                            null,
                                                            false,
                                                            null,
                                                            array( array( 'operation' => 'count( * )',
                                                                          'name' => 'count' ) ) );
        if ( isset( $queryResult[0]['count'] ) )
        {
            return ( int ) $queryResult[0]['count'];
        }
        return 0;

    }

}

?>
