<?php
//
// This is the eZWebDAVContentServer class. Manages WebDAV sessions.
//
// Created on: <15-Aug-2003 15:15:15 bh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( 'lib/ezwebdav/classes/ezwebdavserver.php' );
include_once( "lib/ezutils/classes/ezsession.php" );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezexecution.php' );
include_once( "kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinput.php" );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( "kernel/classes/ezurlalias.php" );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( "access.php" );
include_once( "kernel/common/i18n.php" );

// __FIX_ME__
eZModule::setGlobalPathList( array( "kernel" ) );

// Get the path to the var directory.
$varDir = eZSys::varDirectory();

define( "VIRTUAL_CONTENT_FOLDER_NAME", ezi18n( 'kernel/content', "Content" ) );
define( "VIRTUAL_INFO_FILE_NAME",      $varDir."/webdav/root/info.txt" );
define( "WEBDAV_INI_FILE",             "webdav.ini" );
define( "WEBDAV_AUTH_REALM",           "eZ publish WebDAV interface" );
define( "WEBDAV_AUTH_FAILED",          "Invalid username or password!" );
define( "WEBDAV_INVALID_SITE",         "Invalid site name specified!" );
define( "WEBDAV_DISABLED",             "WebDAV functionality is disabled!" );




/*! Gets and returns a list of the available sites (from site.ini).
 */
function getSiteList()
{
    // The site list is an array of strings.
    $siteList = array();

    // Grab the sitelist from the ini file.
    $webdavINI =& eZINI::instance();
    $siteList = $webdavINI->variable( 'SiteSettings', 'SiteList' );

    // Return the site list.
    return $siteList ;
}




/*! Sets/changes the current site(access) to a given site.
 */
function setSiteAccess( $site )
{
    $access = array( 'name' => $site,
                     'type' => EZ_ACCESS_TYPE_STATIC );

    $access = changeAccess( $access );

    eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'current siteaccess' );

    $GLOBALS['eZCurrentAccess'] =& $access;

    // Clear/flush global database instance.
    $nullVar = null;
    $db =& eZDB::setInstance( $nullVar );
}




/*! Detects a possible/valid site-name in start of a path.
    Returns the name of the site that was detected.
 */
function getCurrentSiteFromPath( $path )
{
    append_to_log( "getCurrentSiteFromPath: path is: $path" );

    //
    $indexDir = eZSys::indexDir();

    // Remove indexDir if used in non-virtualhost mode.
    if ( preg_match( "#^$indexDir(.+)$#", $path, $matches ) )
    {
        $path = $matches[1];
    }

    append_to_log( "getCurrentSiteFromPath: path is: $path" );

    // Get the list of available sites.
    $sites = getSiteList();

    // For each site:
    foreach( $sites as $site )
    {
        // Check if given path starts with this site-name, if so: return it.
        if ( preg_match( "#^/$site(.*)$#", $path, $matches ) )
        {
            return $site ;
        }
    }

    append_to_log( "getCurrentSiteFromPath: no valid site was found.." );
    // No valid site was found!
    return false ;
}




/*! Removes the index file /if in NVH mode/ and
    the site name from a given target URL.
 */
function removeIndexAndSiteName( $targetURI, $currentSite )
{
    // Just in case none of the strings we want to remove exist.
    $stripped = false;

    //
    $indexDir = eZSys::indexDir();

    // Remove indexDir if used in non-virtualhost mode.
    if ( preg_match( "#^$indexDir(.+)$#", $targetURI, $matches ) )
    {
        $stripped = $matches[1];
    }

    // Get rid of the site name:
    if ( preg_match( "#^/$currentSite(.+)$#", $stripped, $matches ) )
    {
        $stripped = $matches[1];
    }

    // Return a stripped version of the inputted URL/path.
    return $stripped;
}




/*! Checks if the current user has administrator privileges.
    This is done by checking the roles assigned to the user.
    We're looking for the star "*").
    The function returns TRUE if the user has admin rights,
    and FALSE if not.
 */
function gotPermission()
{
    append_to_log( "gotPermission was called..." );

    // Get the current user ID.
    $user = eZUser::currentUser();

    $userObject =& $user->attribute( 'contentobject' );
    append_to_log( "gotPermission: username:".$userObject->attribute( 'name' ) );

    $status = $user->hasAccessTo( '*', '*' );

    return $status['accessWord'] == 'yes';
}




/*! Gets and returns the path to the original image directory.
 */
function getPathToOriginalImageDir()
{
    // Build the path to where the original images are:
    $sys =& eZSys::instance();
    $storageDir = $sys->storageDirectory();
    $originalImageDir  = $storageDir . '/' . "original/image";

    // Return the path to the dir where the original images are stored.
    return $originalImageDir ;
}




/*! Gets and returns the path to the reference image directory.
 */
function getPathToReferenceImageDir()
{
    // Build the path to where the original images are:
    $sys =& eZSys::instance();
    $storageDir = $sys->storageDirectory();
    $referenceImageDir  = $storageDir . '/' . "reference/image";

    // Return the path to the dir where the reference images are stored.
    return $referenceImageDir;
}




/*! Attepmts to fetch a possible/existing node by translating
    the inputted string/path to a node-number.
*/
function getNodeByTranslation( $nodePathString )
{
    append_to_log( "getNodeByTranslation: nodepathstring: $nodePathString");
    //
    $indexDir = eZSys::indexDir();

    append_to_log( "indexDir: $indexDir" );

    // Remove indexDir if used in non-virtualhost mode.
    if ( preg_match( "#^$indexDir/(.+)$#", $nodePathString, $matches ) )
    {
        $nodePathString = $matches[1];
    }

    append_to_log( "getNodeByTranslation: nodepathstring0: $nodePathString");
    // If exists: remove the content folder from the path.

    if ( preg_match( "#^" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
    {
        append_to_log( "getNodeByTranslation: REMOVING CONTENT FOLDER: nodepathstring1: $nodePathString");
        $nodePathString = $matches[1];
    }

    if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
    {
        append_to_log( "getNodeByTranslation: REMOVING CONTENT FOLDER: nodepathstring2: $nodePathString");
        $nodePathString = $matches[1];
    }

    append_to_log( "getNodeByTranslation: nodepathstring1: $nodePathString");
    // Get rid of possible extensions, remove .jpeg .txt .html etc..
    $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
    $nodePathString = preg_replace( "#\/$#", "", $nodePathString );

    // Remove the first slash if it exists.
    if ( $nodePathString[1] == '/' )
    {
        $nodePathString = substr( $nodePathString, 1 );
    }

    //
    $nodePathString = eZURLAlias::convertPathToAlias( $nodePathString );

    //
    append_to_log( "getNodeByTranslation: nodepathstring2: $nodePathString");

    // Attempt to translate the URL to something like "/content/view/full/84".
    $translateResult =& eZURLAlias::translate( $nodePathString );

    append_to_log( "getNodeByTranslation: nodepathstring3: $nodePathString");

    if ( !$translateResult )
    {
        append_to_log( "getNodeByTranslation: Node translation failed: $nodePathString" );
    }

    // Get the ID of the node (which is the last part of the translated path).
    if ( preg_match ( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
    {
        $nodeID = $matches[1];
        append_to_log( "getNodeByTranslation: nodeID: $nodeID");
    }
    else
    {
        append_to_log( "getNodeByTranslation: no nodeID");
        return false;
    }

    // Attempt to fetch the node.
    $node = eZContentObjectTreeNode::fetch( $nodeID );

    // Return the node.
    return $node;
}




/*! Attepmts to fetch a possible node by translating
    the inputted string/path to a node-number. The last
    section of the path is removed before the actual
    translation: hence, the PARENT node is returned.
 */
function getParentNodeByTranslation( $nodePathString )
{
    append_to_log( "getParentNodeByTranslation: nodePathString1: $nodePathString" );
    $indexDir = eZSys::indexDir();

    append_to_log( "indexDir: $indexDir" );

    // Remove indexDir if used in non-virtualhost mode.
    if ( preg_match( "#^$indexDir/(.+)$#", $nodePathString, $matches ) )
    {
        $nodePathString = $matches[1];
    }

    if ( preg_match( "#^" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
    {
        append_to_log( "getNodeByTranslation: REMOVING CONTENT FOLDER: nodepathstring1: $nodePathString");
        $nodePathString = $matches[1];
    }

    if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
    {
        append_to_log( "getNodeByTranslation: REMOVING CONTENT FOLDER: nodepathstring2: $nodePathString");
        $nodePathString = $matches[1];
    }

    // Strip extensions. E.g. .jpg
    $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
    $nodePathString = preg_replace( "#\/$#", "", $nodePathString );

    append_to_log( "getParentNodeByTranslation: nodePathString2: $nodePathString" );

    // Remove the first slash if it exists.
    if ( $nodePathString[1] == '/' )
    {
        $nodePathString = substr( $nodePathString, 1 );
    }

    append_to_log( "getParentNodeByTranslation: nodePathString3: $nodePathString" );

    // Get rid of the last part; strip away last slash and anything behind it.
    $cut = strrpos( $nodePathString, '/' );
    $nodePathString = substr( $nodePathString, 0, $cut );

    append_to_log( "getParentNodeByTranslation: nodePathString4: $nodePathString" );
    //
    $nodePathString = eZURLAlias::convertPathToAlias( $nodePathString );

    // Attempt to translate the URL to something like "/content/view/full/84".
    $translateResult =& eZURLAlias::translate( $nodePathString );

    // Get the ID of the node (which is the last part of the translated path).
    if ( preg_match ( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
    {
        $nodeID = $matches[1];
        append_to_log( "getParentNodeByTranslation: nodeID: $nodeID");
    }
    else
    {
        append_to_log( "getParentNodeByTranslation: no nodeID");
        return false;
    }

    // Attempt to fetch the node.
    $node = eZContentObjectTreeNode::fetch( $nodeID );

    // Return the node.
    return $node;
}




/*! Builds and returns the content of the virtual start fodler
    for a site. The virtual startfolder is an intermediate step
    between the site-list and actual content. This directory
    contains the "content" folder which leads to the site's
    actual content.
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
    return $startFolderEntries;
}




/*! Builds a content-list of available sites and returns it.
 */
function getSiteListContent()
{
    // At the end: we'll return an array of entry-arrays.
    $siteListFolderEntries = array();

    // An entry consists of several attributes (name, size, etc).
    $contentEntry = array ();

    // Get list of available sites.
    $sites = getSiteList();

    // For all available sites:
    foreach( $sites as $site )
    {
        // Set up attributes for the virtual site-list folder:
        $contentEntry["name"]     = $_SERVER['SCRIPT_URI'].$site;
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = 'httpd/unix-directory';
        $contentEntry["ctime"]    = filectime( 'var' );
        $contentEntry["mtime"]    = filemtime( 'var' );

        //
        if ( $_SERVER["SCRIPT_URL"] == '/' )
        {
            $contentEntry["href"] = $contentEntry["name"];
        }
        else
        {
            $contentEntry["href"] = $_SERVER["SCRIPT_URL"] . $contentEntry["name"];
        }

        //
        $siteListFolderEntries[] = $contentEntry;
    }

    // Set up attributes for the virtual site-list folder:
    $contentEntry["name"]     = '/';
    $contentEntry["href"]     = $_SERVER['SCRIPT_URI'];
    $contentEntry["size"]     = 0;
    $contentEntry["mimetype"] = 'httpd/unix-directory';
    $contentEntry["ctime"]    = filectime( 'var' );
    $contentEntry["mtime"]    = filemtime( 'var' );

    $siteListFolderEntries[] = $contentEntry;

    // Return the content of the virtual start folder.
    return $siteListFolderEntries;
}




/*! Gets and returns the content of an actual node.
    List of other nodes belonging to the target node
    (one level below it) will be returned.
 */
function getContent( $target )
{
    append_to_log( "getContent: target is: $target" );
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
    return $entries;
}




/*! Creates a new instance of an ezimage object, stores it
    in the database. Sets the necessary object attributes.
    The image that was uploaded is copied to its
    final location.
 */
function storeImage( $imageFileName, $originalImageFileName, $caption, &$contentObjectAttribute )
{
    $handler =& $contentObjectAttribute->content();
    return $handler->initializeFromFile( $imageFileName, false, $originalImageFileName );
}




/*! Creates a new instance of a file object, sets the attributes.
    Stores the object in the database. The file which was uploaded
    is copied to its final location.
 */
function storeFile( $fileFileName, $fileOriginalFileName, &$contentObjectAttribute )
{
    // Get the file/base-name part of the filepath.
    $filename = basename( $fileFileName );

    // Create a new mime object.
    $mimeObj = new eZMimeType();

    // Attempt to determine the mime type of the file to be saved.
    $mime = $mimeObj->mimeTypeFor( false, strtolower( $fileOriginalFileName ) );

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
        eZDir::mkdir( $destinationDir, eZDir::directoryPermission(), true);
    }

    // Build the target filename.
    $targetFile = $destinationDir . "/" . $filename . '.' . $extension ;

    // Attempt to copy the file from upload to its final destination.
    $result = copy( $fileFileName, $targetFile );

    // Check if the move operation succeeded and return true/false...
    if ( $result )
    {
        return true;
    }
    else
    {
        // Remove the object from the databse.
        eZBinaryFile::remove( $contentObjectAttributeID , $version );

        // Bail...
        return false;
    }

    // Attempt to remove the uploaded file.
    $result = unlink( $fileFileName );
}




/*!
 */
function putImage( $target, $tempFile, $parentNodeID )
{
    // Attempt to get the current user ID.
    $user = eZUser::currentUser();
    $userID = $user->id();

    //
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
    append_to_log( "putImage result: " . ( $result ? 'true' : 'false' ) );

    // If the store operation was OK:
    if ( $result )
    {
        //
        $contentObjectAttributes[2]->store();

        //
        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );

        // We're safe.
        return EZ_WEBDAV_OK_CREATED;
    }
    // Else: the store didn't succeed...
    else
    {
        return EZ_WEBDAV_FAILED_FORBIDDEN;
    }
}




/*!
 */
function putFile( $target, $tempFile, $parentNodeID )
{
    // Attempt to get the current user ID.
    $user = eZUser::currentUser();
    $userID = $user->id();

    append_to_log( "putFile: User is: $userID" );

    // Fetch the file class.
    $class =& eZContentClass::fetch( 12 );

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

        return EZ_WEBDAV_OK_CREATED;
    }
    // Else: the store operation failed.
    else
    {
        return EZ_WEBDAV_FAILED_FORBIDDEN;
    }
}




/*! Creates a new folder under the given target node.
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
    $folderClassID = $webdavINI->variable( 'FolderSettings', 'FolderClass' );

    // Fetch the folder class.
    $class =& eZContentClass::fetch( $folderClassID );

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

    return EZ_WEBDAV_OK_CREATED;
    return EZ_WEBDAV_FAILED_FORBIDDEN;
    return EZ_WEBDAV_FAILED_EXISTS;
}




/*! Gathers information about a given node (specified as parameter).
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
    $entry["name"]     = $node->attribute( 'name' );
    $entry["size"]     = 0;
    $entry["mimetype"] = 'httpd/unix-directory';
    $entry["ctime"]    = $object->attribute( 'published' );
    $entry["mtime"]    = $object->attribute( 'modified' );

    // Attempt to determine the attribute that should be used for display:
    $attributeID = $iniSettings[$classIdentifier];

    append_to_log( "getNodeInfo: classIdentifier is: $classIdentifier" );
    append_to_log( "getNodeInfo: attributeID is: $attributeID" );

    // Only proceed to the special cases if the
    // attribute is actually defined in the ini file:
    if ( $attributeID )
    {
        append_to_log( "getNodeInfo: inside if attributeID" );

        // Get the object's datamap.
        $dataMap =& $object->dataMap();

        //
        $attribute =& $dataMap[$attributeID];

        //
        $attributeContent =& $attribute->attribute( 'content' );
        $attributeDataType =& $attribute->dataType();
        $attributeInformation =& $attributeDataType->attribute( "information" );
        $attributeDataTypeIdentifier = $attributeInformation['string'];

        // Get the type of class.
        $attributeClass = get_class( $attributeContent );

        append_to_log( "getNodeInfo: attributeClass is: $attributeClass" );
        append_to_log( "getNodeInfo: attributeIdentifier is: $attributeDataTypeIdentifier" );

        //
        switch ( $attributeDataTypeIdentifier )
        {
            // If the file being uploaded is an image:
            case 'ezimage':
            {
                $originalAlias =& $attributeContent->attribute( 'original' );
                $mime = $originalAlias['mime_type'];
                $originalName = $originalAlias['original_filename'];
                $imageFile = $originalAlias['url'];
                $suffix = $originalAlias['suffix'];

                $entry["size"]      = filesize( $imageFile );
                $entry["mimetype"]  = $mime;
                $entry["name"]      .= '.' . $suffix;
                $entry["href"] = '/' . $imageFile;
            }break;


            // If the file being uploaded is a regular file:
            case 'ezbinaryfile':
            {
                $mime = $attributeContent->attribute( 'mime_type' );
                $originalName = $attributeContent->attribute( 'original_filename' );
                $fileLocation = $attributeContent->attribute( 'filepath' );
                $pathInfo     = pathinfo( $originalName );
                $extension    = '.'.$pathInfo["extension"];

                $entry["size"]      = $attributeContent->attribute( 'filesize' );
                $entry["mimetype"]  = $mime;
                $entry["name"]      .= $extension;
                $entry["ctime"]     = filectime( $fileLocation );
                $entry["mtime"]     = filemtime( $fileLocation );
            }break;
        }
    }

    // Set the href attribute (note that it doesn't just equal the name).
    if ( !isset( $entry['href'] ) )
        $entry["href"] = $_SERVER["SCRIPT_URL"].'/'.$entry['name'];

    // Return array of attributes/properties (name, size, mime, times, etc.).
    return $entry;
}




/*! This is the real thing. __FIX_ME__
 */
class eZWebDAVContentServer extends eZWebDAVServer
{
    /*!
     */
    function options()
    {
        // Only a few WebDAV operations are allowed.
        $options = "Allow: OPTIONS, PROPFIND, HEAD, GET, PUT, MKCOL, MOVE";
//         $options = "Allow: OPTIONS, GET, HEAD, POST, DELETE, TRACE, PROPFIND, PROPPATCH, COPY, MOVE, LOCK, UNLOCK, SEARCH";

        // Return the allowed options.
        return $options;
    }




    /*! Produces the collection content. Builds either the virtual start folder
        with the virtual content folder in it (and additional files). OR: if
        we're browsing within the content folder: it gets the content of the
        target/given folder.
     */
    function getCollectionContent( $collection )
    {
        // Get the name of the site that is being browsed.
        $currentSite = getCurrentSiteFromPath( $collection );

        // Get rid of the index-file /NVH mode/ and the site name.
        $collection = removeIndexAndSiteName( $collection, $currentSite );

        // Proceed only if the current site is valid.
        if ( $currentSite )
        {
            // Switch to the site being browsed.
            setSiteAccess( $currentSite );

            // Bail if the current user doesn't have the required privileges:
            if ( !gotPermission() )
            {
                return false;
            }

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $collection ) )
            {
                append_to_log( "We're browsing actual content, collection is: $collection" );
                $entries = getContent( $collection );
            }
            // We aren't browsing content just yet, show the virtual start folder:
            else
            {
                append_to_log( "We're browsing the virtual start folder.." );
                $entries = getVirtualStartFolderContent();
            }
        }
        // Else: we're browsing the list of sites.
        else
        {
            append_to_log( "We're browsing list of sites.." );
            // Get the list of sites.
            $entries = getSiteListContent();
        }

        // Return an array with content entries and their attributes.
        return $entries;
    }




    /*!
     */
    function get( $target )
    {
        // At the end, we'll return an array; let's initialize it:
        $result         = array();
        $result["data"] = FALSE;
        $result["file"] = FALSE;

        // Get the name of the site that is being browsed.
        $currentSite = getCurrentSiteFromPath( $target );

        // Get rid of the index-file /NVH mode/ and the site name.
        $target = removeIndexAndSiteName( $target, $currentSite );


        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            append_to_log( "get: current site er $currentSite" );
            // Switch site to the site being browsed.
            setSiteAccess( $currentSite );

            // Bail if the current user doesn't have the required privileges:
            if ( !gotPermission() )
            {
                return false;
            }

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $target ) )
            {
                append_to_log( "get: attempting to fetch node, target is: $target");

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
                        $attributeDataTypeString = $attribute->attribute( 'data_type_string' );

                        // Get the type of class.
                        $attributeClass = get_class( $attributeContent );

                        switch ( $attributeDataTypeString )
                        {
                            case 'ezimage':
                            {
                                $originalAlias = $attributeContent->attribute( 'original' );
                                $filePath = $originalAlias['url'];
                            }break;


                            case 'ezbinaryfile':
                            {
                                $filePath = $attributeContent->attribute( 'filepath' );
                            }break;
                        }
                    }

                    // Set the result (to the file) & return it.
                    $result["file"] = $filePath;
                    return $result;
                }
                // Else: the node was invalid:
                else
                {
                    // Return empty result.
                    return $result;
                }
            }
        }
        // Else: the target is the virtual info file: serve it.
        elseif ( $target == '/'.basename( VIRTUAL_INFO_FILE_NAME ) )
        {
            // Set the file.
            $result["file"] = VIRTUAL_INFO_FILE_NAME;

            // Return the file.
            return $result;
        }
    }




    /*! __FIX_ME__
     */
    function head( $target )
    {
        // For now: always return a not-found reply.
        return EZ_WEBDAV_FAILED_NOT_FOUND;
    }




    /*! __FIX_ME__
     */
    function put( $target, $tempFile )
    {
        append_to_log( "put: target is: $target" );

        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // Get the name of the site that is being browsed.
        $currentSite = getCurrentSiteFromPath( $target );

        // Get rid of the index-file /NVH mode/ and the site name.
        $target = removeIndexAndSiteName( $target, $currentSite );

        append_to_log( "put: target is: $target" );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            append_to_log( "put: current site is: $currentSite" );

            // Switch to the site being browsed.
            setSiteAccess( $currentSite );

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $target ) )
            {
                append_to_log( "put: inside virtual content folder, ok" );

                // Attempt to get the parent node of the target.
                $parentNode = getParentNodeByTranslation( $target );

                // Proceed only if the parentNode is OK:
                if ( $parentNode != null )
                {
                    // Get the node ID of the node.
                    $parentNodeID = $parentNode->attribute( 'node_id' );

                    // Attempt to determine the mime type of the file that has been uploaded.
                    $mimeObj = new eZMimeType();
                    $mime = $mimeObj->mimeTypeFor( false, strtolower( basename( $target ) ) );

                    // Extract elements from the mime array.
                    list( $type, $extension ) = split ("/", $mime );

                    // Grab settings from the ini file:
                    $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
                    $iniSettings = $webdavINI->variable( 'PutSettings', 'MIME' );
                    $iniDefaultSetting = $webdavINI->variable( 'PutSettings', 'DefaultClass' );

                    // Attempt to determine the attribute that should be used for display:
                    $attributeID = $iniSettings[$mime];

                    // If no attribute ID : set to default (from ini file).
                    if ( !$attributeID )
                    {
                        $attributeID = $iniDefaultSetting;
                    }

                    switch ( $attributeID )
                    {
                        case 'image':
                        {
                            return putImage( $target, $tempFile, $parentNodeID );
                        }
                        break;

                        default:
                        {
                            return putFile( $target, $tempFile, $parentNodeID );
                        }
                        break;
                    }
                }
                // Else: the parent node was invalid:
                else
                {
                    return EZ_WEBDAV_FAILED_FORBIDDEN;
                }
            }
            // Else: somebody is trying to put stuff in to a virtual dir: no deal!
            else
            {
                return EZ_WEBDAV_FAILED_FORBIDDEN;
            }
        }
    }




    /*!
     */
    function mkcol( $target )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return false;
        }

        // Get the name of the site that is being browsed.
        $currentSite = getCurrentSiteFromPath( $target );

        // Get rid of the index-file /NVH mode/ and the site name.
        $target = removeIndexAndSiteName( $target, $currentSite );

        // Proceed only if the current site is valid:
        if ( $currentSite != "" )
        {
            // Switch to the site being browsed.
            setSiteAccess( $currentSite );

            // If the path starts with "/content":
            if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $target ) )
            {
                // Attempt to get the parent node.
                $parentNode = getParentNodeByTranslation( $target );

                // If no node: use the root node.
                if ( !$parentNode )
                {
                    $parentNode =& eZContentObjectTreeNode::fetch( 2 );
                }

                // Attempt to create the folder.
                return createFolder( $parentNode, $target );
            }
            // Else: somebody is trying to make a folder in a virtual dir: no deal!
            else
            {
                return EZ_WEBDAV_FAILED_FORBIDDEN;
            }
        }
    }




    /*!
     */
    function move( $source, $destination )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !gotPermission() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // Get the name of the site that is being browsed.
        $currentSite = getCurrentSiteFromPath( $source );

        // Get rid of the index-file /NVH mode/ and the site name.
        $source = removeIndexAndSiteName( $source, $currentSite );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            // Switch to the site being browsed.
            setSiteAccess( $currentSite );

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $source ) )
            {
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
                    return EZ_WEBDAV_OK_CREATED;
                }
            }
            // Else: somebody is trying to move stuff in a virtual dir: no deal!
            else
            {
                return EZ_WEBDAV_FAILED_FORBIDDEN;
            }
        }
    }

}
?>
