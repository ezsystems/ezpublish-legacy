<?php
//
// Definition of eZBinaryFileHandler class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
 \group eZBinaryHandlers Binary file handlers
*/

/*!
  \class eZBinaryFileHandler ezbinaryfilehandler.php
  \ingroup eZKernel
  \brief Interface for all binary file handlers

*/

define( "EZ_BINARY_FILE_HANDLE_UPLOAD", 0x1 );
define( "EZ_BINARY_FILE_HANDLE_DOWNLOAD", 0x2 );

define( "EZ_BINARY_FILE_HANDLE_ALL", EZ_BINARY_FILE_HANDLE_UPLOAD |
                                     EZ_BINARY_FILE_HANDLE_DOWNLOAD );

define( "EZ_BINARY_FILE_TYPE_FILE", 1 );
define( "EZ_BINARY_FILE_TYPE_MEDIA", 2 );

define( "EZ_BINARY_FILE_RESULT_OK", 1 );
define( "EZ_BINARY_FILE_RESULT_UNAVAILABLE", 2 );
                                     
class eZBinaryFileHandler
{
    function eZBinaryFileHandler( $identifier, $name, $handleType )
    {
        $this->Info = array();
        $this->Info['identifier'] = $identifier;
        $this->Info['name'] = $name;
        $this->Info['handle-type'] = $handleType;
    }
    
    function attributes()
    {
        return array_keys( $this->Info );
    }
    
    function hasAttribute( $attribute )
    {
        return isset( $this->Info[$attribute] );
    }
    
    function &attribute( $attribute )
    {
        if ( isset( $this->Info[$attribute] ) )
            return $this->Info[$attribute];
        return null;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function &viewTemplate( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function &editTemplate( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \return the suffix for the template name which will be used for attribute viewing.
     \note Default returns false which means no special template.
    */
    function &informationTemplate( &$contentobjectAttribute )
    {
        return false;
    }
    
    function storedFilename( &$binary )
    {
        $origDir = eZSys::storageDirectory() . '/original';

        $fileName = $origDir . "/" . $binary->attribute( 'mime_type_category' ) . '/'.  $binary->attribute( "filename" );
        return $fileName;
    }
    
    function handleUpload()
    {
        return false;
    }
    
    function handleDownload( &$contentObject, &$contentObjectAttribute, $type )
    {
        return false;
    }
    
    function repositories()
    {
        return array( 'kernel/classes/binaryhandlers' );
    }
    
    function &instance( $identifier = false )
    {
        if ( $identifier === false )
        {
            $fileINI =& eZINI::instance( 'file.ini' );
            $identifier = $fileINI->variable( 'BinaryFileSettings', 'Handler' );
        }
        $instance =& $GLOBALS['eZBinaryFileHandlerInstance-' . $identifier];
        if ( !isset( $instance ) )
        {
            $handlerDirectory = $identifier;
            $handlerFilename = $identifier . "handler.php";
            $repositories = eZBinaryFileHandler::repositories();
            foreach ( $repositories as $repository )
            {
                $file = eZDir::path( array( $repository, $handlerDirectory, $handlerFilename ) );
                eZDebug::writeDebug( $file );
                if ( file_exists( $file ) )
                {
                    include_once( $file );
                    $classname = $identifier . "handler";
                    $instance = new $classname();
                    break;
                }
                else
                    eZDebug::writeError( "Could not find binary file handler '$identifier'", 'eZBinaryFileHandler::instance' );
            }
        }
        return $instance;
    }
    
    /// \privatesection
    var $Info;
}

?>