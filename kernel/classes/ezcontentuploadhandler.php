<?php
//
// Definition of eZContentUploadHandler class
//
// Created on: <18-Mar-2005 18:14:02 amos>
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

/*! \file ezcontentuploadhandler.php
*/

/*!
  \class eZContentUploadHandler ezcontentuploadhandler.php
  \brief Interface for all content upload handlers

  This defines the interface for upload handlers for content objects. The handler
  will be used if upload.ini is configured for a specific MIME type.
  The uploading system is general and will be used both by Web uploads as well
  as WebDAV PUT requests.

  The handler must inherit this class and implement the following methods:
  - handleFile() - This takes care of creating content objects from the uploaded file.

  Also the constructor must pass a proper name and identifier to the eZContentUploadHandler.

*/

class eZContentUploadHandler
{
    /*!
     Initialises the handler with the name.
    */
    function eZContentUploadHandler( $name, $identifier )
    {
        $this->Name = $name;
        $this->Identifier = $identifier;
    }

    /*!
     \pure
     Handles the file \a $filePath and creates one ore more content objects.

     \param $upload The eZContentUpload object that instantiated the request, public methods on this can be used.
     \param $filePath Path to file which should be stored, do not remove or move this file.
     \param $mimeInfo Contains MIME-Type information on the file.
     \param $result Result data, will be filled with information which the client can examine, contains:
                    - errors - An array with errors, each element is an array with \c 'description' containing the text
     \param $location The node ID which the new object will be placed or the string \c 'auto' for automatic placement of type.
     \param $existingNode Pass a contentobjecttreenode object to let the uploading be done to an existing object,
                          if not it will create one from scratch.

     \return \c false if something failed or \c true if succesful.
     \note might be transaction unsafe.
    */
    function handleFile( &$upload, &$result,
                         $filePath, $originalFilename, $mimeInfo,
                         $location, $existingNode )
    {
        return false;
    }

    /// \privatesection
    /// The name of the handler, can be displayed to the end user.
    var $Name;
    /// The identifier of the handler.
    var $Identifier;
}

?>
