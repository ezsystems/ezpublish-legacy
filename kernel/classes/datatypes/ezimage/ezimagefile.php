<?php
//
// Definition of eZImageFile class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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

/*!
  \class eZImageFile ezimagefile.php
  \ingroup eZKernel
  \brief The class eZImageFile handles registered images

*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );

class eZImageFile extends eZPersistentObject
{
    function eZImageFile( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'id',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         'filepath' => array( 'name' => 'Filepath',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'class_name' => 'eZImageFile',
                      'name' => 'ezimagefile' );
    }

    function &create( $contentObjectAttributeID, $filepath  )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "filepath" => $filepath );
        return new eZImageFile( $row );
    }

    function &fetchForContentObjectAttribute( $contentObjectAttributeID, $asObject = false )
    {
        $rows =& eZPersistentObject::fetchObjectList( eZImageFile::definition(),
                                                      null,
                                                      array( "contentobject_attribute_id" => $contentObjectAttributeID ),
                                                      null,
                                                      null,
                                                      $asObject );
        if ( !$asObject )
        {
            $files = array();
            foreach ( array_keys( $rows ) as $rowKey )
            {
                $row =& $rows[$rowKey];
                $files[] = $row['filepath'];
            }
            $files = array_unique( $files );
            return $files;
        }
        else
            return $rows;
    }

    function &fetchByFilepath( $contentObjectAttributeID, $filepath, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZImageFile::definition(),
                                                null,
                                                array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                                                       'filepath' => $filepath ),
                                                $asObject );
    }

    function moveFilepath( $contentObjectAttributeID, $oldFilepath, $newFilepath )
    {
        eZImageFile::removeFilepath( $contentObjectAttributeID, $oldFilepath );
        return eZImageFile::appendFilepath( $contentObjectAttributeID, $newFilepath );
    }

    function appendFilepath( $contentObjectAttributeID, $filepath )
    {
        if ( empty( $filepath ) )
            return false;
        $fileObject =& eZImageFile::fetchByFilePath( $contentObjectAttributeID, $filepath );
        if ( $fileObject )
            return false;
        $fileObject =& eZImageFile::create( $contentObjectAttributeID, $filepath );
        $fileObject->store();
        return true;
    }

    function removeFilepath( $contentObjectAttributeID, $filepath )
    {
        if ( empty( $filepath ) )
            return false;
        $fileObject =& eZImageFile::fetchByFilePath( $contentObjectAttributeID, $filepath );
        if ( !$fileObject )
            return false;
        $fileObject->remove();
        return true;
    }

    function removeForContentObjectAttribute( $contentObjectAttributeID )
    {
        if ( !isset( $this ) or
             get_class( $this ) != 'ezimagefile' )
            $this =& eZImageFile::instance();
        $this->remove( array( 'contentobject_attribute_id' => $contentObjectAttributeID ) );
    }

    function &instance()
    {
        $instance =& $GLOBALS['eZImageFileInstance'];
        if ( !isset( $instance ) )
        {
            $instance = new eZImageFile( array() );
        }
        return $instance;
    }


    /// \privatesection
    var $ID;
    var $ContentObjectAttributeID;
    var $Filepath;
}

?>
