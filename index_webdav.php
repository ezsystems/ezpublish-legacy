<?php
//
// This is the index_webdav.php file. Manages WebDAV sessions.
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
include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( "lib/ezutils/classes/ezdir.php" );
include_once( "kernel/classes/ezurlalias.php" );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
eZModule::setGlobalPathList( array( "kernel" ) );

$varDir = eZSys::varDirectory();

define( "VIRTUAL_CONTENT_FOLDER_NAME", "content" );
define( "VIRTUAL_INFO_FILE_NAME",      $varDir."/webdav/root/info.txt" );




/*! Checks if the current user has administrator privileges.
    This is done by checking the roles assigned to the user.
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
                if ( $var == '*' ) return( TRUE );
            }
        }
    }

    // Still haven't found the star? -> permission denied!
    return( FALSE );
}




/*!
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

    // Build original & reference directory paths.
    $originalDir  = $storageDir . '/' . "original/image";
    $referenceDir = $storageDir . '/' . "reference/image";

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

    append_to_log( "imagefilename: $imageFileName" );
    append_to_log( "originalfile: $originalFile" );
    append_to_log( "referencefile: $referenceFile" );


    // Attempt to copy the source file to the original location.
    $result = copy( $imageFileName, $originalFile );

    if (!$result)
    {
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
}




/*!
 */
function storeFile( $fileFileName, $fileOriginalFileName, &$contentObjectAttribute )
{
    append_to_log( "fileFileName is:         $fileFileName" );
    append_to_log( "fileOriginalFileName is: $fileOriginalFileName" );

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

    append_to_log( "fileFileName is:  $fileFileName" );
    append_to_log( "fileOriginalFileName is:  $fileOriginalFileName" );
    append_to_log( "inside saveFile; copy: target_file: $targetFile " );

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
function getNodeInfo( $node )
{
    // When finished, we'll return an array of attributes/properties.
    $entry = array();

    // Grab settings from the ini file:
    $webdavINI =& eZINI::instance( 'webdav.ini' );
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
            //
            case 'ezimage':
            {
                $mime         = $attributeContent->attribute( 'mime_type' );
                $filename     = $attributeContent->attribute( 'filename' );
                $originalName = $attributeContent->attribute( 'original_filename' );

                $pathInfo  = pathinfo( $originalName );
                $extension = '.'.$pathInfo["extension"];

                $filePath = "var/storage/original/image/$filename";
                $entry["size"]      = filesize( $filePath );
                $entry["mimetype"]  = $mime;
                $entry["name"]      .= $extension;
            }break;


            //
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

    // __FIX_ME__ Greier..
    $entry["href"] = $_SERVER["SCRIPT_URL"].'/'.$entry['name'];

    // Return array of attributes/properties (name, size, mime, times, etc.).
    return( $entry );
}




/*!
 */
class eZWebDAVContentServer extends eZWebDAVServer
{
    /*!
     */
    function getCollectionContent( $collection )
    {
        // Bail if the current user doesn't have the required privileges:
        //       if ( !gotPermission() )
        //{
        //  return( FALSE );
        //}

        $contentEntry = array ();
        $contentEntry["name"]     = VIRTUAL_CONTENT_FOLDER_NAME;
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = 'httpd/unix-directory';
        $contentEntry["ctime"]    = filectime( 'var' );
        $contentEntry["mtime"]    = filemtime( 'var' );
        $contentEntry["href"]     = $_SERVER["SCRIPT_URL"].'/'.$contentEntry["name"];

        // Location to the info file.
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
            $entries = array( $contentEntry, $infoEntry );
        }
        // Else: info file can't be located, skip it...
        else
        {
            $entries = array( $contentEntry );
        }


        if ( stristr( $collection, 'content' ) )
        {
            // Get rid of the 'content' part of the path.
            $nodePath = substr( $collection, 8);

            // Get rid of any possible extensions (remove '.jpeg', etc.)
            $nodePath = preg_replace( "/\.\w*$/", "", $nodePath );
            $nodePath = preg_replace( "#\/$#", "", $nodePath );

            // Get rid of the first slash.
            $nodePath = substr( $nodePath, 1 );

            // Translate the URI into a valid destination URL ending with node number.
            $translateResult =& eZURLAlias::translate( $nodePath );

            // Get the last component of the URL, should be the node number.
            $nodeID = basename( $nodePath );

            // Attempt to fetch the desired node.
            $node = eZContentObjectTreeNode::fetch( $nodeID );

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
        }

        // Return an array with nodes and their attributes.
        return( $entries );
    }




   /*!
    */
    function get( $target )
    {
        append_to_log( "GET: target is $target, basename: ".basename( $VIRTUAL_INFO_FILE_NAME ) );

        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return( FALSE );
        }

        // If exists: get rid of the 'content' prefix of the path:
        if ( stristr( $target, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            append_to_log( "Getting rid of content prefix.." );
            $target = substr( $target, (int) strlen( VIRTUAL_CONTENT_FOLDER_NAME ) + 1 );
        }
        // If the target is the virtual info file: serve it.
        elseif ( $target == '/'.basename( VIRTUAL_INFO_FILE_NAME ) )
        {
            $result         = array();
            $result["data"] = FALSE;
            $result["file"] = FALSE;

            // Set the file.
            $result["file"] = VIRTUAL_INFO_FILE_NAME;

            append_to_log( "GET: file is ".$result["file"]);

            return( $result );
        }

        // Grab settings from the ini file:
        $webdavINI =& eZINI::instance( 'webdav.ini' );
        $iniSettings = $webdavINI->variable( 'GetSettings', 'FileAttribute' );

        append_to_log( "GET override function: target is $target" );
        $nodePathString = $target;

        // Strip extensions. E.g. .jpg
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );
        $nodePathString = substr( $nodePathString, 1 );

        append_to_log( "Nodepathstring er: $nodePathString" );

        $translateResult =& eZURLAlias::translate( $nodePathString );

        append_to_log( "Nodepathstring translated: $nodePathString" );

        $nodeID = basename( $nodePathString );
        $node    = eZContentObjectTreeNode::fetch( $nodeID );
        $object  = $node->attribute( 'object' );
        $classID = $object->attribute( 'contentclass_id' );

        append_to_log( "nodeID: $nodeID" );

        // Get the content class ID string of the object (image, folder, file, etc.).
        $class =& $object->attribute( 'content_class' );
        $classIdentifier =& $class->attribute( 'identifier' );

        append_to_log( "classIdentifier: $classIdentifier" );

        // Attempt to determine the attribute that should be used for display:
        $attributeID = $iniSettings[$classIdentifier];

        append_to_log( "attributeID: $attributeID" );

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

            append_to_log ("attributeclass er: $attributeClass" );
            //

            switch ( $attributeClass )
            {
                case 'ezimage':
                {
                    append_to_log( "Target is an image..." );
                    $filename = $attributeContent->attribute( 'filename' );
                    $filePath = "var/storage/original/image/$filename";
                }break;


                case 'ezbinaryfile':
                {
                    append_to_log( "Target is a binary file..." );
                    $filePath = $attributeContent->attribute( 'filepath' );
                    append_to_log( "inside get: filePath: ".$filePath );
                }break;
            }
        }

        append_to_log( "GET: target is: $filePath" );
        $result         = array();
        $result["data"] = FALSE;
        $result["file"] = FALSE;

        // Set the file.
        $result["file"] = $filePath;

        append_to_log( "GET: stuff returned from get() is: ".$result["file"]);

        return( $result );
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

        // Attempt to get the current user ID.
        $user = eZUser::currentUser();
        $userID = $user->id();

        // If exists: get rid of the 'content' prefix of the path:
        if ( stristr( $target, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            $target = substr( $target, (int) strlen( VIRTUAL_CONTENT_FOLDER_NAME ) + 1 );
        }
        // Else: somebody is trying to put stuff in the virtual dir: no deal!
        else
        {
            append_to_log( "HUH! cant do writing & stuff in virtual folder!" );
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }

        append_to_log( "PUT, inside or function: target   is: $target" );
        append_to_log( "PUT, inside or function: tempFile is: $tempFile" );

        // Attempt to determine the mime type of the file that has been uploaded.
        $mimeObj = new eZMimeType();
        $mime = $mimeObj->mimeTypeFor( false, basename( $target ) );

        // Extract elements from the mime array.
        list( $type, $extension ) = split ("/", $mime );

        append_to_log( "type is: $type" );

        // Grab settings from the ini file:
        $webdavINI =& eZINI::instance( 'webdav.ini' );
        $iniSettings = $webdavINI->variable( 'GetSettings', 'MIME' );
        $iniDefaultSetting = $webdavINI->variable( 'GetSettings', 'DefaultClass' );

        // Attempt to determine the attribute that should be used for display:
        $attributeID = $iniSettings[$mime];

        if ( !$attributeID )
        {
            $attributeID = $iniDefaultSetting;
        }

        append_to_log( "mime is $mime, we should create a $attributeID" );


        $nodePathString = $target;

        // Strip extensions. E.g. .jpg
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );
        $nodePathString = substr( $nodePathString, 1 );

        append_to_log( "nodePathString: $nodePathString" );

        // maa strippe vekk siste slash...
        $cut = strrpos( $nodePathString, '/' );
        $nodePathString = substr( $nodePathString, 0, $cut );

        append_to_log( "nodePathString,before translation er: $nodePathString" );

        $translateResult =& eZURLAlias::translate( $nodePathString );

        append_to_log( "nodePathString,translated er: $nodePathString" );

        $lastSlashPos = strrchr( $nodePathString, '/' );
        $nodeID = basename( $nodePathString );
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        $parentNodeID = $node->attribute( 'node_id' );

        append_to_log( "Parent node ID: $parentNodeID" );


        switch ( $attributeID )
        {
            case 'image':
            {
                $imageFileName = basename( $target );
                $imageOriginalFileName = basename( $target );
                $imageCaption = basename( $target );

                // Fetch the image class.
                $class =& eZContentClass::fetch( 5 );

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

                $version =& $contentObject->version( 1 );
                $version->setAttribute( 'modified', $imageCreatedTime );
                $version->setAttribute( 'created', $imageCreatedTime );
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

                storeImage( $tempFile, $imageOriginalFileName, $imageCaption, $contentObjectAttribute );
                $contentObjectAttributes[2]->store();

                include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                             'version' => 1 ) );
                return EZ_WEBDAV_OK_CREATED;
            }
            break;


            // DEFAULT CASE: File...

            default:
            {
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

                // Copy the file from one location to another...
                $result = storeFile( $tempFile, basename( $target ), $contentObjectAttribute );

                // If the save operation succeded:
                if ( $result )
                {
                    $contentObjectAttributes[2]->store();

                    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

                    $operationResult = eZOperationHandler::execute( 'content',
                                                                    'publish',
                                                                    array( 'object_id' => $contentObjectID,
                                                                           'version'   => 1 ) );
                    $contentObject->store();

                    return( EZ_WEBDAV_OK_CREATED );
                }
                else
                {
                    return( EZ_WEBDAV_FAILED_FORBIDDEN );
                }
            }
            break;

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

        // Attempt to get the current user ID.
        $user = eZUser::currentUser();
        $userID = $user->id();


        // If exists: get rid of the 'content' prefix of the path:
        if ( stristr( $target, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            $target = substr( $target, (int) strlen( VIRTUAL_CONTENT_FOLDER_NAME ) + 1 );
        }
        // Else: somebody is trying to put stuff in the virtual dir: no deal!
        else
        {
            append_to_log( "HUH! cant do writing & stuff in virtual folder!" );
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }

        // Grab settings from the ini file:
        $webdavINI =& eZINI::instance( 'webdav.ini' );
        $iniSettings = $webdavINI->variable( 'FolderSettings', 'FolderClass' );

        append_to_log( "Folderclass is: $iniSettings" );

        // Make real path.
        $realPath = $_SERVER["DOCUMENT_ROOT"].$target;

        append_to_log( "attempting to create collection/folder: $realPath" );

        $nodePathString = $target;

        // Strip extensions. E.g. .jpg
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );
        $nodePathString = substr( $nodePathString, 1 );

        append_to_log( "nodePathString: $nodePathString" );

        // maa strippe vekk siste slash...
        $cut = strrpos( $nodePathString, '/' );
        $nodePathString = substr( $nodePathString, 0, $cut );

        //$node         = eZContentObjectTreeNode::fetchByCRC( $nodePathString );

        $translateResult =& eZURLAlias::translate( $nodePathString );

        append_to_log( "nodepathstring er: $nodePathString" );

        $lastSlashPos = strrchr( $nodePathString, '/' );
        $nodeID = basename( $nodePathString );

        append_to_log( "nodeID er: $nodeID" );

        $node = eZContentObjectTreeNode::fetch( $nodeID );

        if ( !$node )
        {
            $node =& eZContentObjectTreeNode::fetch( 2 );
        }


        append_to_log( "nodePathString er: $nodePathString" );

        $parentNodeID = $node->attribute( 'node_id' );

        append_to_log( "Parent node ID: $parentNodeID" );

        // Fetch the folder class.
        $class =& eZContentClass::fetch( 1 );


        // Proceed only if the userID is valid:
        if ( $userID != null )
        {
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

            $version =& $contentObject->version( 1 );
            $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
            $version->store();

            $contentObjectID = $contentObject->attribute( 'id' );
            $contentObjectAttributes =& $version->contentObjectAttributes();

            $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $target ) );
            $contentObjectAttributes[0]->store();


            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                         'version' => 1 ) );
            $contentObject->store();

            return( EZ_WEBDAV_OK_CREATED );
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
            return( EZ_WEBDAV_FAILED_EXISTS );

        }

    }




    /*!
     */
    function move( $source, $destination )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return( FALSE );
        }

        // If exists: get rid of the 'content' prefix of the path:
        if ( stristr( $source, VIRTUAL_CONTENT_FOLDER_NAME ) )
        {
            $source = substr( $source, (int) strlen( VIRTUAL_CONTENT_FOLDER_NAME ) + 1 );
        }
        // Else: somebody is trying to put stuff in the virtual dir: no deal!
        else
        {
            append_to_log( "HUH! cant do writing & stuff in virtual folder!" );
            return( EZ_WEBDAV_FAILED_FORBIDDEN );
        }

        $source = strtolower( $source );
        $source = str_replace( " ", "_", $source );

        $destination = preg_replace( "/\.\w*$/", "", $destination );
        $destination = preg_replace( "#\/$#", "", $destination );

        append_to_log( "Source: $source   Destination: $destination" );

        $nodePathString = $source;

        // Strip extensions. E.g. .jpg
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );
        $nodePathString = substr( $nodePathString, 1 );

        append_to_log( "nodePathString: $nodePathString" );


        $translateResult =& eZURLAlias::translate( $nodePathString );

        append_to_log( "nodepathstring er: $nodePathString" );

        $lastSlashPos = strrchr( $nodePathString, '/' );
        $nodeID = basename( $nodePathString );

        append_to_log( "nodeID er: $nodeID" );

        $node = eZContentObjectTreeNode::fetch( $nodeID );

        if ( !$node )
        {
            $node =& eZContentObjectTreeNode::fetch( 2 );
        }


        append_to_log( "nodePathString er: $nodePathString" );


//        $nodeID = $node->attribute( 'node_id' );

        append_to_log( "node ID: $nodeID" );

        $object = $node->attribute( 'object' );

        $contentObjectID = $object->attribute( 'id' );
        $contentObjectAttributes =& $object->contentObjectAttributes();
        $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $destination ) );
        $contentObjectAttributes[0]->store();

        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                     'version' => 1 ) );
        $object->store();


        // __FIX_ME__
        return( EZ_WEBDAV_OK_CREATED );
        return( EZ_WEBDAV_FAILED_CONFLICT );
    }


}



// Check if the username & password actually contain someting, proceed
// only if empty values or if they are invalid (can't login):
if ( ( !isset( $PHP_AUTH_USER ) ) || ( !isset($PHP_AUTH_PW ) ) ||
     ( !ezuser::loginUser( $PHP_AUTH_USER, $PHP_AUTH_PW ) ) )

{
    header('HTTP/1.0 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="eZ publish WebDAV Admin interface"');
    echo( 'Authorization required!' );
}
// Else: non-empty & valid values were supplied: login successful!
else
{
    $testServer = new eZWebDAVContentServer ();
    $testServer->processClientRequest ();
}


?>
