<?php
//
// Definition of eZBinaryFile class
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
  \class eZBinaryFile ezbinaryfile.php
  \ingroup eZDatatype
  \brief The class eZBinaryFile handles registered binaryfiles

*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezbinaryfilehandler.php' );

class eZBinaryFile extends eZPersistentObject
{
    function eZBinaryFile( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         'version' => array( 'name' => 'Version',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'filename' =>  array( 'name' => 'Filename',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         'original_filename' =>  array( 'name' => 'OriginalFilename',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                         'mime_type' => array( 'name' => 'MimeType',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         'download_count' => array( 'name' => 'DownloadCount',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ) ),
                      'keys' => array( 'contentobject_attribute_id', 'version' ),
                      'relations' => array( 'contentobject_attribute_id' => array( 'class' => 'ezcontentobjectattribute',
                                                                                   'field' => 'id' ) ),
                      "function_attributes" => array( 'filesize' => 'filesize',
                                                      'filepath' => 'filepath',
                                                      'mime_type_category' => 'mimeTypeCategory',
                                                      'mime_type_part' => 'mimeTypePart' ),
                      'class_name' => 'eZBinaryFile',
                      'name' => 'ezbinaryfile' );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'mime_type_category' or
                 $attr == 'mime_type_part' or
                 $attr == 'filepath' or
                 $attr == 'filesize' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        $ini =& eZINI::instance();

        switch( $attr )
        {
            case 'filesize':
            {
                $storedFile = eZBinaryFileHandler::storedFilename( $this );
                if ( file_exists( $storedFile ) )
                    return filesize( $storedFile );
                else
                    return 0;
            } break;
            case 'filepath':
            {
                return eZBinaryFileHandler::storedFilename( $this );
            } break;
            case 'mime_type_category':
            {
                $types = explode( '/', eZPersistentObject::attribute( 'mime_type' ) );
                return $types[0];
            } break;
            case 'mime_type_part':
            {
                $types = explode( '/', eZPersistentObject::attribute( 'mime_type' ) );
                return $types[1];
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        return null;
    }

    function &create( $contentObjectAttributeID, $version )
    {
        $row = array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                      'version' => $version,
                      'filename' => '',
                      'original_filename' => '',
                      'mime_type' => ''
                      );
        return new eZBinaryFile( $row );
    }

    function &fetch( $id, $version = null, $asObject = true )
    {
        if ( $version == null )
        {
            return eZPersistentObject::fetchObjectList( eZBinaryFile::definition(),
                                                        null,
                                                        array( 'contentobject_attribute_id' => $id ),
                                                        null,
                                                        null,
                                                        $asObject );
        }
        else
        {
            return eZPersistentObject::fetchObject( eZBinaryFile::definition(),
                                                    null,
                                                    array( 'contentobject_attribute_id' => $id,
                                                           'version' => $version ),
                                                    $asObject );
        }
    }

    function &remove( $id, $version )
    {
        if ( $version == null )
        {
            eZPersistentObject::removeObject( eZBinaryFile::definition(),
                                              array( 'contentobject_attribute_id' => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZBinaryFile::definition(),
                                              array( 'contentobject_attribute_id' => $id,
                                                     'version' => $version ) );
        }
    }


    /*!
     \return the medatata from the binary file, if extraction is supported
      for the current mimetype.
    */
    function &metaData()
    {
        $metaData = "";
        $binaryINI =& eZINI::instance( 'binaryfile.ini' );

        $handlerSettings = $binaryINI->variable( 'HandlerSettings', 'MetaDataExtractor' );

        if ( isset( $handlerSettings[$this->MimeType] ) )
        {
            // Check if plugin exists

            if ( file_exists( 'kernel/classes/datatypes/ezbinaryfile/plugins/ez' .  $handlerSettings[$this->MimeType] . 'parser.php' ) )
            {
                include_once( 'kernel/classes/datatypes/ezbinaryfile/plugins/ez' .  $handlerSettings[$this->MimeType] . 'parser.php' );

                $parserClass = 'ez' . $handlerSettings[$this->MimeType] . 'parser';
                $parserObject = new $parserClass();
                $metaData =& $parserObject->parseFile( eZBinaryFileHandler::storedFilename( $this ) );
            }
            else
            {
                eZDebug::writeWarning( "Plugin for $this->MimeType was not found", 'eZBinaryFile' );
            }
        }
        else
        {
            eZDebug::writeWarning( "Mimetype $this->MimeType not supported for indexing", 'eZBinaryFile' );
        }

        return $metaData;
    }

    var $ContentObjectAttributeID;
    var $Filename;
    var $OriginalFilename;
    var $MimeType;
}

?>
