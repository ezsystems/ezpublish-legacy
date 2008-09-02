<?php
//
// Definition of eZBinaryFile class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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

/*!
  \class eZBinaryFile ezbinaryfile.php
  \ingroup eZDatatype
  \brief The class eZBinaryFile handles registered binaryfiles

*/

//include_once( 'lib/ezdb/classes/ezdb.php' );
//include_once( 'kernel/classes/ezpersistentobject.php' );
//include_once( 'kernel/classes/ezcontentclassattribute.php' );
//include_once( 'kernel/classes/ezbinaryfilehandler.php' );

class eZBinaryFile extends eZPersistentObject
{
    function eZBinaryFile( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
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
                      "function_attributes" => array( 'filesize' => 'fileSize',
                                                      'filepath' => 'filePath',
                                                      'mime_type_category' => 'mimeTypeCategory',
                                                      'mime_type_part' => 'mimeTypePart' ),
                      'class_name' => 'eZBinaryFile',
                      'name' => 'ezbinaryfile' );
    }


    function fileSize()
    {
        $fileInfo = $this->storedFileInfo();

        // VS-DBFILE
        $file = eZClusterFileHandler::instance( $fileInfo['filepath'] );
        if ( $file->exists() )
        {
            $stat = $file->stat();
            return $stat['size'];
        }

        return 0;
    }

    function filePath()
    {
        $fileInfo = $this->storedFileInfo();
        return $fileInfo['filepath'];
    }

    function mimeTypeCategory()
    {
        $types = explode( '/', eZPersistentObject::attribute( 'mime_type' ) );
        return $types[0];
    }

    function mimeTypePart()
    {
        $types = explode( '/', eZPersistentObject::attribute( 'mime_type' ) );
        return $types[1];
    }

    static function create( $contentObjectAttributeID, $version )
    {
        $row = array( 'contentobject_attribute_id' => $contentObjectAttributeID,
                      'version' => $version,
                      'filename' => '',
                      'original_filename' => '',
                      'mime_type' => ''
                      );
        return new eZBinaryFile( $row );
    }

    static function fetch( $id, $version = null, $asObject = true )
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

    static function fetchByFileName( $filename, $version = null, $asObject = true )
    {
        if ( $version == null )
        {
            return eZPersistentObject::fetchObjectList( eZBinaryFile::definition(),
                                                        null,
                                                        array( 'filename' => $filename ),
                                                        null,
                                                        null,
                                                        $asObject );
        }
        else
        {
            return eZPersistentObject::fetchObject( eZBinaryFile::definition(),
                                                    null,
                                                    array( 'filename' => $filename,
                                                           'version' => $version ),
                                                    $asObject );
        }
    }

    static function removeByID( $id, $version )
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
    function metaData()
    {
        $metaData = "";
        $binaryINI = eZINI::instance( 'binaryfile.ini' );

        $handlerSettings = $binaryINI->variable( 'HandlerSettings', 'MetaDataExtractor' );

        if ( isset( $handlerSettings[$this->MimeType] ) )
        {
            // Check if plugin exists
            if ( eZExtension::findExtensionType( array( 'ini-name' => 'binaryfile.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-directory' => false,
                                                    'type' => $handlerSettings[$this->MimeType],
                                                    'subdir' => 'plugins',
                                                    'extension-subdir' => 'plugins',
                                                    'suffix-name' => 'parser.php' ),
                                             $out ) )
            {
                $filePath = $out['found-file-path'];
                include_once( $filePath );
                $class = $handlerSettings[$this->MimeType] . 'Parser';

                $parserObject = new $class( );
                $fileInfo = $this->storedFileInfo();

                // VS-DBFILE
                $file = eZClusterFileHandler::instance( $fileInfo['filepath'] );
                if ( $file->exists() )
                {
                    $fetchedFilePath = $file->fetchUnique();
                    $metaData = $parserObject->parseFile( $fetchedFilePath );
                    $file->fileDeleteLocal( $fetchedFilePath );
                }
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

    function storedFileInfo()
    {
        $fileName = $this->attribute( 'filename' );
        $mimeType = $this->attribute( 'mime_type' );
        $originalFileName = $this->attribute( 'original_filename' );
        $storageDir = eZSys::storageDirectory();
        list( $group, $type ) = explode( '/', $mimeType );
        $filePath = $storageDir . '/original/' . $group . '/' . $fileName;
        return array( 'filename' => $fileName,
                      'original_filename' => $originalFileName,
                      'filepath' => $filePath,
                      'mime_type' => $mimeType );
    }

    public $ContentObjectAttributeID;
    public $Filename;
    public $OriginalFilename;
    public $MimeType;
}

?>
