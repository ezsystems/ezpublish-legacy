<?php
//
// This is the eZWebDAVContentServer class. Manages WebDAV sessions.
//
// Created on: <15-Aug-2003 15:15:15 bh>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//
include_once( "lib/ezutils/classes/ezsession.php" );
include_once( 'lib/ezwebdav/classes/ezwebdavserver.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezexecution.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( "kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinput.php" );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( "lib/ezutils/classes/ezdir.php" );
include_once( "kernel/classes/ezurlalias.php" );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

//
eZModule::setGlobalPathList( array( "kernel" ) );

//
$varDir = eZSys::varDirectory();

define( "VIRTUAL_CONTENT_FOLDER_NAME", "content" );
define( "VIRTUAL_INFO_FILE_NAME",      $varDir."/webdav/root/info.txt" );
define( "WEBDAV_INI_FILE", "webdav.ini" );



/*! Checks if the current user has administrator privileges.
    This is done by checking the roles assigned to the user.
    We're looking for the star "*").
    The function returns TRUE if the user has admin rights,
    and FALSE if not.
 */
function gotPermission()
{
    // Get the current user ID.
    $user = eZUser::currentUser();

    // Get the roles assigned to this user.
    $roles = $user->roles();

    // For all the roles:
    foreach( $roles as $role )
    {
        // Grab the list of policies.
        $policies = $role->policyList();

        // For all the policies:
        foreach( $policies as $policy )
        {
            // For all the vars within the policies:
            foreach( $policy as $var )
            {
                // Look for the *star* - if found, return with success.
                if ( $var == '*' ) return( TRUE );
            }
        }
    }

    // Still haven't found the star? -> permission denied!
    return( FALSE );
}





/*!
 */
function getPathToOriginalImageDir()
{
    // Build the path to where the original images are:
    $sys =& eZSys::instance();
    $storageDir = $sys->storageDirectory();
    $originalImageDir  = $storageDir . '/' . "original/image";

    // Return the path to the dir where the original images are stored.
    return( $originalImageDir );
}




/*!
 */
function getPathToReferenceImageDir()
{
    // Build the path to where the original images are:
    $sys =& eZSys::instance();
    $storageDir = $sys->storageDirectory();
    $referenceImageDir  = $storageDir . '/' . "reference/image";

    // Return the path to the dir where the reference images are stored.
    return( $referenceImageDir );
}




/*!
 */
function getNodeByTranslation( $nodePathString )
{
    // Remove the content folder part of the path.
    $nodePathString = substr( $nodePathString, (int) strlen( VIRTUAL_CONTENT_FOLDER_NAME ) + 1 );

    // Get rid of possible extensions, remove .jpeg .txt .html etc..
    $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
    $nodePathString = preg_replace( "#\/$#", "", $nodePathString );

    // Remove the first slash.
    $nodePathString = substr( $nodePathString, 1 );

    // Attempt to translate the URL to something like "/content/view/full/84".
    $translateResult =& eZURLAlias::translate( $nodePathString );

    // Get the ID of the node (which is the last part of the translated path).
    $nodeID  = basename( $nodePathString );

    // Attempt to fetch the node.
    $node = eZContentObjectTreeNode::fetch( $nodeID );

    // Return the node.
    return( $node );
}




/*!
 */
function getParentNodeByTranslation( $nodePathString )
{
    // Get rid of the virtual content folder from the path.
    $nodePathString = substr( $nodePathString, (int) strlen( VIRTUAL_CONTENT_FOLDER_NAME ) + 1 );

    // Strip extensions. E.g. .jpg
    $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
    $nodePathString = preg_replace( "#\/$#", "", $nodePathString );

    // Remove the first slash.
    $nodePathString = substr( $nodePathString, 1 );

    // Get rid of the last part; strip away last slash and anything behind it.
    $cut = strrpos( $nodePathString, '/' );
    $nodePathString = substr( $nodePathString, 0, $cut );

    // Attempt to translate the URL to something like "/content/view/full/84".
    $translateResult =& eZURLAlias::translate( $nodePathString );

    // Get the ID of the node (which is the last part of the translated path).
    $nodeID = basename( $nodePathString );

    // Attempt to fetch the node.
    $node = eZContentObjectTreeNode::fetch( $nodeID );

    // Return the node.
    return( $node );
}




/*!
 */
function convertString( $string )
{
    //
    $string = strtolower( $string );
    $string = str_replace( " ", "_", $string );

    //
    return( $string );
}




/*!
 */
function getVirtualStartFolderContent()
{
    // Set up attributes for the virtual content folder:
    $contentEntry = array ();
    $contentEntry["name"]     = VIRTUAL_CONTENT_FOLDER_NAME;
    $contentEntry["size"]     = 0;
    $contentEntry["mimetype"] = 'httpd/unix-directory';
    $contentEntry["ctime"]    = filectime( 'var' );
    $contentEntry["mtime"]    = filemtime( 'var' );
    $contentEntry["href"]     = $_SERVER["SCRIPT_URL"].'/'.$contentEntry["name"];

    // Location of the info file.
    $infoFile = $_SERVER['DOCUMENT_ROOT'].'/'.VIRTUAL_INFO_FILE_NAME;

    // If the info file actually exists: set up attributes and
    // include it in the entries-to-be served array:
    if ( file_exists ( $infoFile ) )
    {
        $infoEntry = array ();
        $infoEntry["name"]     = basename( $infoFile );
        $infoEntry["size"]     = filesize( $infoFile );
        $infoEntry["mimetype"] = 'text/plain';
        $infoEntry["ctime"]    = filectime( $infoFile );
        $infoEntry["mtime"]    = filemtime( $infoFile );
        $infoEntry["href"]     = $_SERVER["SCRIPT_URL"].'/'.$infoEntry["name"];

        // Include the info-file's attributes.
        $startFolderEntries = array( $contentEntry, $infoEntry );
    }
    // Else: info file can't be located, skip it...
    else
    {
        $startFolderEntries = array( $contentEntry );
    }

    // Return the content of the virtual start folder.
    return( $startFolderEntries );
}




/*!
 */
function getContent( $target )
{
    // Attempt to fetch the desired node.
    $node = getNodeByTranslation( $target );

    // If unable to fetch the node: get the content/root folder instead.
    if ( !$node )
    {
        $node =& eZContentObjectTreeNode::fetch( 2 );
    }

    // Get all the children of the target node.
    $subTree =& $node->subTree( array ( 'Depth' => 1 ) );

    // We'll return an array of entries (which is an array of attributes).
    $entries = array();

    // Build the entries array by going through all the
    // nodes in the subtree and getting their attributes:
    foreach ( $subTree as $someNode )
    {
        $entries[] = getNodeInfo( $someNode );
    }

    // In addition: add the attributes of THIS node (webdav headace!!):
    $thisNodeInfo = array ();
    $thisNodeInfo = getNodeInfo( $node );
    $thisNodeInfo["href"] = $_SERVER['SCRIPT_URI'];
    $entries[] = $thisNodeInfo;

    // Return the content of the target.
    return( $entries );
}




/*!
 */
function putImage ( $target, $tempFile, $parentNodeID )
{
    // Attempt to get the current user ID.
    $user = eZUser::currentUser();
    $userID = $user->id();

    $imageFileName = basename( $target );
    $imageOriginalFileName = $imageFileName;
    $imageCaption = $imageFileName;

    // Fetch the image class.
    $class =& eZContentClass::fetch( 5 );

    // Create object by user id in section 1.
    $contentObject =& $class->instantiate( $userID, 1 );

    //
    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $parentNodeID,
                                                     'sort_field' => 2,
                                                     'sort_order' => 0,
                                                     'is_main' => 1
                                                     )
                                                 );
    $nodeAssignment->store();

    //
    $version =& $contentObject->version( 1 );
    $version->setAttribute( 'modified', $imageCreatedTime );
    $version->setAttribute( 'created', $imageCreatedTime );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
    $version->store();

    $contentObjectID = $contentObject->attribute( 'id' );
    $contentObjectAttributes =& $version->contentObjectAttributes();

    $storeInDBName = preg_replace( "/\.\w*$/", "", $imageFileName );
    $storeInDBName = preg_replace( "#\/$#", "", $storeInDBName );

    $contentObjectAttributes[0]->setAttribute( 'data_text', $storeInDBName );
    $contentObjectAttributes[0]->store();

    $contentObjectAttribute =& $contentObjectAttributes[2];

    // Attempt to store the image object in the DB and copy the file.
    $result = storeImage( $tempFile, $imageOriginalFileName, $imageCaption, $contentObjectAttribute );

    // If the store operation was OK:
    if ( $result )
    {
        //
        $contentObjectAttributes[2]->store();

        //
        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );

        // We're safe.
        return( EZ_WEBDAV_OK_CREATED );
    }
    // Else: the store didn't succeed...
    else
    {
        return( EZ_WEBDAV_FAILED_FORBIDDEN );
    }
}




/*!
 */
function putFile( $target, $tempFile, $parentNodeID )
{
    // Attempt to get the current user ID.
    $user = eZUser::currentUser();
    $userID = $user->id();

    // Fetch the file class.
    $class =& eZContentClass::fetch( 12 );

    // Create object by user id in section 1.
    $contentObject =& $class->instantiate( $userID, 1 );

    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $parentNodeID,
                                                     'sort_field' => 2,
                                                     'sort_order' => 0,
                                                     'is_main' => 1
                                                     )
                                                 );
    $nodeAssignment->store();

    //
    $version =& $contentObject->version( 1 );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
    $version->store();

    $contentObjectID = $contentObject->attribute( 'id' );
    $contentObjectAttributes =& $version->contentObjectAttributes();

    $storeInDBName = basename( $target );
    $storeInDBName = preg_replace( "/\.\w*$/", "", $storeInDBName );
    $storeInDBName = preg_replace( "#\/$#", "", $storeInDBName );

    $contentObjectAttributes[0]->setAttribute( 'data_text', $storeInDBName );
    $contentObjectAttributes[0]->store();

    $contentObjectAttribute =& $contentObjectAttributes[2];

    // Attempt to store the file object in the DB and copy the file.
    $result = storeFile( $tempFile, basename( $target ), $contentObjectAttribute );

    // If the store operation succeded:
    if ( $result )
    {
        $contentObjectAttributes[2]->store();

        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version'   => 1 ) );
        $contentObject->store();

        return( EZ_WEBDAV_OK_CREATED );
    }
    // Else: the store operation failed.
    else
    {
        return( EZ_WEBDAV_FAILED_FORBIDDEN );
    }
}




/*! Creates a new instance of an ezimage object, stores it
    in the database. Sets the necessary object attributes.
    The image that was uploaded is copied to its
    final location (original and reference).
 */
function storeImage( $imageFileName, $originalImageFileName, $caption, &$contentObjectAttribute )
{
    // Get the file/base-name part of the filepath.
    $filename = basename( $imageFileName );

    // Attempt to reveal the MIME type for this image.
    $mimeObj = new eZMimeType();
    $mime = $mimeObj->mimeTypeFor( false, $originalImageFileName );

    // Extract stuff from the MIME string.
    list( $type, $extension ) = split ("/", $mime );

    // Get contentobjectattributeid and version.
    $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
    $version = $contentObjectAttribute->attribute( "version" );

    // Necessary for eZIMage stuff.
    include_once( "kernel/common/image.php" );

    // Create a new instance of an image object.
    $image =& eZImage::create( $contentObjectAttributeID , $version );

    // Set the attributes of the newly created image object.
    $image->setAttribute( "filename", $filename . '.' . $extension );
    $image->setAttribute( "original_filename", $originalImageFileName );
    $image->setAttribute( "mime_type", $mime );
    $image->setAttribute( "alternative_text", $caption );

    // Store the object in the database.
    $image->store();

    // Get the path to the storage directory.
    $sys =& eZSys::instance();
    $storageDir = $sys->storageDirectory();

    // Get original & reference directory paths.
    $originalDir  = getPathToOriginalImageDir();
    $referenceDir = getPathToReferenceImageDir();

    // Check if the original dir for images exists, if not: create it!
    if ( !file_exists( $originalDir ) )
    {
        eZDir::mkdir( $originalDir, 0777, true);
    }

    // Check if the reference dir for images exists, if not: create it!
    if ( !file_exists( $referenceDir ) )
    {
        eZDir::mkdir( $ref_dir, 0777, true);
    }

    // Set the location of the source, original and reference files.
    $originalFile  = $originalDir  . '/' . $filename . '.' . $extension;
    $referenceFile = $referenceDir . '/' . $filename . '.' . $extension;

    // Attempt to copy the source file to the original location.
    $result = copy( $imageFileName, $originalFile );

    if (!$result)
    {
        // Remove the object from the databse.
        eZImage::remove( $contentObjectAttributeID , $version );

        // Bail...
        return( FALSE );
    }

    // Attempt to copy the source file to the reference location.
    $result = copy( $imageFileName, $referenceFile );

    if (!$result)
    {
        // Remove the object from the databse.
        eZImage::remove( $contentObjectAttributeID , $version );

        // Bail...
        return( FALSE );
    }

    // Get rid of the uploaded/source file.
    unlink( $imageFileName );

    // If we got this far: everything is OK.
    return( TRUE );
}




/*! Creates a new instance of a file object, sets the attributes.
    Stores the object in the database. The file which was uploaded
    is copied to its final location.
 */
function storeFile( $fileFileName, $fileOriginalFileName, &$contentObjectAttribute )
{

    // Get the file/base-name part of the filepath.
    $filename = basename( fileFileName );

    // Create a new mime object.
    $mimeObj = new eZMimeType();

    // Attempt to determine the mime type of the file to be saved.
    $mime = $mimeObj->mimeTypeFor( false, $fileOriginalFileName );

    // Extract elements from the mime array.
    list( $subdir, $extension ) = split ("/", $mime );

    $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );

    $version = $contentObjectAttribute->attribute( "version" );

    // Create a new instance of a file object.
    $file =& eZBinaryFile::create( $contentObjectAttributeID , $version );

    // Set the attributes for the newly created object:
    $file->setAttribute( "filename", $filename . '.' . $extension );
    $file->setAttribute( "original_filename", $fileOriginalFileName );
    $file->setAttribute( "mime_type", $mime );

    // Store the object in the database.
    $file->store();

    // Get the path to the storage directory.
    $sys =& eZSys::instance();
    $storageDir = $sys->storageDirectory();

    // Build the path to the destination directory.
    $destinationDir = $storageDir . '/' . 'original/' . $subdir;

    // If no directory for these files exists: create one!
    if ( !file_exists( $destinationDir ) )
    {
        eZDir::mkdir( $destinationDir, 0777, true);
    }

    // Build the target filename.
    $targetFile = $destinationDir . "/" . $filename . '.' . $extension ;

    // Attempt to copy the file from upload to its final destination.
    $result = copy( $fileFileName, $targetFile );

    // Check if the move operation succeeded and return true/false...
    if ( $result )
    {
        return( TRUE );
    }
    else
    {
        // Remove the object from the databse.
        eZImage::remove( $contentObjectAttributeID , $version );

        // Bail...
        return( FALSE );
    }

    // Attempt to remove the uploaded file.
    $result = unlink( $fileFileName );
}





/*!
 */
function createFolder( $node, $target )
{
    // Attempt to get the current user ID.
    $user = eZUser::currentUser();
    $userID = $user->id();

    // Set the parent node ID.
    $parentNodeID = $node->attribute( 'node_id' );

    // Grab settings from the ini file:
    $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
    $iniSettings = $webdavINI->variable( 'FolderSettings', 'FolderClass' );

    // Fetch the folder class.
    $class =& eZContentClass::fetch( 1 );

    // Create object by user id in section 1.
    $contentObject =& $class->instantiate( $userID, 1 );

    //
    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $parentNodeID,
                                                     'sort_field' => 2,
                                                     'sort_order' => 0,
                                                     'is_main' => 1
                                                     )
                                                 );
    //
    $nodeAssignment->store();

    //
    $version =& $contentObject->version( 1 );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
    $version->store();

    //
    $contentObjectID = $contentObject->attribute( 'id' );
    $contentObjectAttributes =& $version->contentObjectAttributes();

    //
    $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $target ) );
    $contentObjectAttributes[0]->store();

    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );
    $contentObject->store();

    return( EZ_WEBDAV_OK_CREATED );
    return( EZ_WEBDAV_FAILED_FORBIDDEN );
    return( EZ_WEBDAV_FAILED_EXISTS );
}




/*! Gathers information about a given node (specified as parameter to the function).
 */
function getNodeInfo( $node )
{
    // When finished, we'll return an array of attributes/properties.
    $entry = array();

    // Grab settings from the ini file:
    $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
    $iniSettings = $webdavINI->variable( 'DisplaySettings', 'FileAttribute' );

    // Get the object associated with this node.
    $object  = $node->attribute( 'object' );

    // Get the content class ID number of the object.
    $classID = $object->attribute( 'contentclass_id' );

    // Get the content class ID string of the object (image, folder, file, etc.).
    $class =& $object->attribute( 'content_class' );
    $classIdentifier =& $class->attribute( 'identifier' );

    // By default, everything is displayed as a folder:
    $entry["name"]     = basename( $node->attribute( 'url_alias' ) );
    $entry["size"]     = 0;
    $entry["mimetype"] = 'httpd/unix-directory';
    $entry["ctime"]    = $object->attribute( 'published' );
    $entry["mtime"]    = $object->attribute( 'modified' );

    // Attempt to determine the attribute that should be used for display:
    $attributeID = $iniSettings[$classIdentifier];

    // Only proceed to the special cases if the
    // attribute is actually defined in the ini file:
    if ( $attributeID )
    {
        // Get the object's datamap.
        $dataMap = $object->dataMap();

        // 
        $attribute = $dataMap[$attributeID];

        //
        $attributeContent = $attribute->content();

        // Get the type of class.
        $attributeClass = get_class( $attributeContent );

        //
        switch ( $attributeClass )
        {
            // If the file being uploaded is an image:
            case 'ezimage':
            {
                $mime         = $attributeContent->attribute( 'mime_type' );
                $filename     = $attributeContent->attribute( 'filename' );
                $originalName = $attributeContent->attribute( 'original_filename' );

                $pathInfo  = pathinfo( $originalName );
                $extension = '.'.$pathInfo["extension"];

                $entry["size"]      = filesize( $filePath );
                $entry["mimetype"]  = $mime;
                $entry["name"]      .= $extension;
            }break;


            // If the file being uploaded is a regular file:
            case 'ezbinaryfile':
            {
                $mime = $attributeContent->attribute( 'mime_type' );
                $originalName = $attributeContent->attribute( 'original_filename' );
                $pathInfo = pathinfo( $originalName );
                $extension = '.'.$pathInfo["extension"];

                $entry["size"]      = $attributeContent->attribute( 'filesize' );
                $entry["mimetype"]  = $mime;
                $entry["name"]      .= $extension;
            }break;
        }
    }

    // Set the href attribute (note that it doesn't just equal the name).
    $entry["href"] = $_SERVER["SCRIPT_URL"].'/'.$entry['name'];

    // Return array of attributes/properties (name, size, mime, times, etc.).
    return( $entry );
}




/*! This is the real thing. __FIX_ME__
 */
class eZWebDAVContentServer extends eZWebDAVServer
{
    /*!
     */
    function options()
    {
        //
        $options = "Allow: OPTIONS,PROPFIND,HEAD,GET,PUT,MKCOL,MOVE";

        //
        return( $options );
    }




    /*! Produces the collection content. Builds either the virtual start folder
        with the virtual content folder in it (and additional files). OR: if
        we're browsing within the content folder: it gets the content of the
        target/given folder.
     */
    function getCollectionContent( $collection )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
          return( FALSE );
        }

        // If we're browsing inside the content folder:
        if ( stristr( $collection, 'content' ) )
        {
            // Get the content of the target.
            $entries = getContent( $collection );
        }
        // Else: we're browsing the virtual start folder.
        else
        {
            // Get the content of the virtual start folder.
            $entries = getVirtualStartFolderContent();
        }

        // Return an array with nodes and their attributes.
        return( $entries );
    }




   /*!
    */
    function get( $target )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return( FALSE );
        }

        // At the end, we'll return an array; let's initialize it:
        $result         = array();
        $result["data"] = FALSE;
        $result["file"] = FALSE;

        // Check if we're browsing actual content.
        if ( stristr( $target, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            // Attempt to fetch the node the client wants to get.
            $node = getNodeByTranslation( $target );

            // Proceed only if the node is valid:
            if ( $node != null )
            {
                // Get the object.
                $object = $node->attribute( 'object' );

                // Get the object's content class ID.
                $classID = $object->attribute( 'contentclass_id' );

                // Get the content class ID string of the object (image, folder, file, etc.).
                $class =& $object->attribute( 'content_class' );
                $classIdentifier =& $class->attribute( 'identifier' );

                // Grab settings from the ini file:
                $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
                $iniSettings = $webdavINI->variable( 'GetSettings', 'FileAttribute' );

                // Attempt to determine the attribute that should be used for display:
                $attributeID = $iniSettings[$classIdentifier];

                // Only proceed to the special cases if the
                // attribute is actually defined in the ini file:
                if ( $attributeID )
                {
                    // Get the object's datamap.
                    $dataMap = $object->dataMap();

                    //
                    $attribute = $dataMap[$attributeID];

                    //
                    $attributeContent = $attribute->content();

                    // Get the type of class.
                    $attributeClass = get_class( $attributeContent );

                    switch ( $attributeClass )
                    {
                        case 'ezimage':
                        {
                            // Grab the filename.
                            $filename = $attributeContent->attribute( 'filename' );

                            // The actual location of the image.
                            $filePath = getPathToOriginalImageDir().'/'.$filename;
                        }break;


                        case 'ezbinaryfile':
                        {
                            // The actual location of the file.
                            $filePath = $attributeContent->attribute( 'filepath' );
                        }break;
                    }
                }

                // Set the result (to the file) & return it.
                $result["file"] = $filePath;
                return( $result );
            }
            // Else: the node was invalid:
            else
            {
                // Return empty result.
                return( $result );
            }
        }
        // Else: the target is the virtual info file: serve it.
        elseif ( $target == '/'.basename( VIRTUAL_INFO_FILE_NAME ) )
        {
            // Set the file.
            $result["file"] = VIRTUAL_INFO_FILE_NAME;

            // Return the file.
            return( $result );
        }
    }




   /*!
    */
    function head( $target )
    {
        // For now: always return a not-found reply.
        return( EZ_WEBDAV_FAILED_NOT_FOUND );
    }




    /*!
     */
    function put( $target, $tempFile )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }

        // If we're browsing content:
        if ( stristr( $target, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            // Attempt to get the parent node of the target.
            $parentNode = getParentNodeByTranslation( $target );

            // Proceed only if the parentNode is OK:
            if ( $parentNode != null )
            {
                // Get the node ID of the node.
                $parentNodeID = $parentNode->attribute( 'node_id' );

                // Attempt to determine the mime type of the file that has been uploaded.
                $mimeObj = new eZMimeType();
                $mime = $mimeObj->mimeTypeFor( false, basename( $target ) );

                // Extract elements from the mime array.
                list( $type, $extension ) = split ("/", $mime );

                // Grab settings from the ini file:
                $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
                $iniSettings = $webdavINI->variable( 'GetSettings', 'MIME' );
                $iniDefaultSetting = $webdavINI->variable( 'GetSettings', 'DefaultClass' );

                // Attempt to determine the attribute that should be used for display:
                $attributeID = $iniSettings[$mime];

                // If no attribute ID : set to default (from ini file).
                if ( !$attributeID )
                {
                    $attributeID = $iniDefaultSetting;
                }

                //
                switch ( $attributeID )
                {
                    // Image case: image upload.
                    case 'image':
                    {
                        // Attempt to create image object, store in DB, copy file, etc.
                        return( putImage( $target, $tempFile, $parentNodeID ) );
                    }
                    break;

                    // Default case (file upload).
                    default:
                    {
                        // Attempt to create file object, store in DB, copy file, etc.
                        return( putFile( $target, $tempFile, $parentNodeID ) );
                    }
                    break;
                }
            }
            // Else: the parent node was invalid:
            else
            {
                return( EZ_WEBDAV_FAILED_FORBIDDEN );
            }
        }
        // Else: somebody is trying to put stuff in the virtual dir: no deal!
        else
        {
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }
    }




    /*!
     */
    function mkcol( $target )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return( FALSE );
        }

        // If we're browsing content:
        if ( stristr( $target, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            // Attempt to get the parent node.
            $parentNode = getParentNodeByTranslation( $target );

            // If no node: use the root node.
            if ( !$parentNode )
            {
                $parentNode =& eZContentObjectTreeNode::fetch( 2 );
            }

            // Attempt to create the folder.
            return( createFolder( $parentNode, $target ) );
        }
        // Else: somebody is trying to make a folder in the virtual dir: no deal!
        else
        {
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }
    }




    /*!
     */
    function move( $source, $destination )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }

        // If we're browsing content:
        if ( stristr( $source, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            // Convert the source string (lowercase, replace spaces with _, etc).
            $source = convertString( $source );

            // Attempt to get the node.
            $node = getNodeByTranslation ( $source );

            // Proceed only if we were able to find the node:
            if ( $node != null )
            {
                // Get the object.
                $object = $node->attribute( 'object' );

                // Get rid of possible extensions, remove .jpeg .txt .html etc..
                $destination = preg_replace( "/\.\w*$/", "", $destination );
                $destination = preg_replace( "#\/$#", "", $destination );

                //
                $contentObjectID = $object->attribute( 'id' );
                $contentObjectAttributes =& $object->contentObjectAttributes();
                $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $destination ) );
                $contentObjectAttributes[0]->store();

                include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );
                $object->store();

                // __FIX_ME__
                return( EZ_WEBDAV_OK_CREATED );
            }
        }
        // Else: somebody is trying to move stuff in the virtual dir: no deal!
        else
        {
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }
    }
}

