<?php
/**
 * File containing the eZContentUploadHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
    public $Name;
    /// The identifier of the handler.
    public $Identifier;
}

?>
