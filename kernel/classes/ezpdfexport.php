<?php
//
// Definition of eZRSSExport class
//
// Created on: <21-Nov-2003 15:59:56 kk>
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
  \class for storing PDF exports

  RSSExport is used to create RSS feeds from published content. See kernel/rss for more files.
*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezrssexportitem.php' );

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
    function &definition()
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
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'sub_text' => array( 'name' => 'SubText',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => false ),
                                         'source_node_id' => array( 'name' => 'SourceNodeID',
                                                                    'datatype' => 'int',
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
                                                            'default' => 0,
                                                            'required' => true ) ),
                      'keys' => array( 'id' ),
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
    function &create( $user_id )
    {
        $config =& eZINI::instance( 'site.ini' );
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
                      'status' => 0 );
        return new eZPDFExport( $row );
    }

    /*!
     Store Object to database

     \param save as viewable version
    */
    function store( $status = 0 )
    {
        if ( $status == 0 )
            $status = $this->attribute( 'status' );

        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $user =& eZUser::currentUser();
        $this->setAttribute( 'modified', time() );
        $this->setAttribute( 'modifier_id', $user->attribute( 'contentobject_id' ) );
        $this->setAttribute( 'status', $status );
        eZPersistentObject::store();
    }

    /*!
     \static
      Fetches the RSS Export by ID.

     \param RSS Export ID
    */
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZPDFExport::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    /*!
     \reimp
    */
    function remove()
    {
        $sys =& eZSys::instance();
        $storage_dir = $sys->storageDirectory();

        $filename = $storage_dir . '/pdf/' . $this->attribute( 'pdf_filename' );
        if (  file_exists( $filename ) )
        {
            unlink( $filename );
        }
        eZPersistentObject::remove();
    }

    /*!
     \static
      Fetches complete list of RSS Exports.
    */
    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZPDFExport::definition(),
                                                    null,
                                                    array( 'status' => array( array( 1, 2 ) ) ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
     \reimp
    */
    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(), 'modifier', 'source_node', 'filepath', 'export_classes_array' );
    }

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return ( $attr == 'modifier' or $attr == 'source_node' or $attr == 'filepath' or $attr == 'export_classes_array' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'modifier':
            {
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                return eZUser::fetch( $this->ModifierID );
            } break;

            case 'filepath':
            {
                $sys =& eZSys::instance();
                $storage_dir = $sys->storageDirectory();

                return $storage_dir . '/pdf/' . $this->attribute( 'pdf_filename' );
            }

            case 'source_node':
            {
                include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
                return eZContentObjectTreeNode::fetch( $this->SourceNodeID );
            } break;

            case 'export_classes_array':
            {
                return explode( ':',  eZPersistentObject::attribute( 'export_classes' ) );
            } break;

            default:
            {
                return eZPersistentObject::attribute( $attr );
            } break;
        }
    }

}

?>
