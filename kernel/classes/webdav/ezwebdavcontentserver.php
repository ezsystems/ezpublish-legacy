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
  \class eZWebDAVContentServer ezwebdavcontentserver.php
  \ingroup eZWebDAV
  \brief Provides access to eZ publish kernel using WebDAV

*/

include_once( 'lib/ezwebdav/classes/ezwebdavserver.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( "kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php" );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( "kernel/classes/ezurlalias.php" );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

// Get the path to the var directory.
$varDir = eZSys::varDirectory();

define( "VIRTUAL_CONTENT_FOLDER_NAME", ezi18n( 'kernel/content', "Content" ) );
define( "VIRTUAL_MEDIA_FOLDER_NAME", ezi18n( 'kernel/content', "Media" ) );
define( "VIRTUAL_INFO_FILE_NAME", $varDir . "/webdav/root/info.txt" );
define( "WEBDAV_INI_FILE", "webdav.ini" );
define( "WEBDAV_AUTH_REALM", "eZ publish WebDAV interface" );
define( "WEBDAV_AUTH_FAILED", "Invalid username or password!" );
define( "WEBDAV_INVALID_SITE", "Invalid site name specified!" );
define( "WEBDAV_DISABLED", "WebDAV functionality is disabled!" );

class eZWebDAVContentServer extends eZWebDAVServer
{
    /*!
     Initializes the eZWebDAVServer
    */
    function eZWebDAVContentServer()
    {
        $this->eZWebDAVServer();
        $this->User =& eZUser::currentUser();
    }

    /*!
     \reimp
     Makes sure $this->User is reinitialized with the current user,
     then calls the $super->processClientRequest().
    */
    function processClientRequest()
    {
        $this->User =& eZUser::currentUser();
        eZWebDAVServer::processClientRequest();
    }

    /*!
      @{
    */

    /*!
      \reimp
      Restricts the allowed methods to only the subset that this server supports.
    */
    function options( $target )
    {
        // Only a few WebDAV operations are allowed for now.
        $options = array();
        $options['methods'] = array( 'OPTIONS', 'PROPFIND', 'HEAD', 'GET', 'PUT', 'MKCOL', 'MOVE' );
//         $options['versions'] = array( '1' );

        return $options;
    }

    /*!
      \reimp
      Produces the collection content. Builds either the virtual start folder
      with the virtual content folder in it (and additional files). OR: if
      we're browsing within the content folder: it gets the content of the
      target/given folder.
    */
    function getCollectionContent( $collection, $depth, $properties )
    {
        $fullPath = $collection;
        $collection = $this->splitFirstPathElement( $collection, $currentSite );

        if ( !$currentSite )
        {
            // Display the root which contains a list of sites
            $this->appendLogEntry( "Root: Fethcing site list", 'CS:getCollectionContent' );
            $entries = $this->fetchSiteListContent( $depth, $properties );
            return $entries;
        }

        if ( !$this->userHasSiteAccess( $currentSite ) )
        {
            $this->appendLogEntry( "No access to site '$currentSite'", 'CS:getCollectionContent' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->getVirtualFolderCollection( $currentSite, $collection, $fullPath, $depth, $properties );
    }

    /*!
     \private
     Handles collections on the virtual folder level, if no virtual folder
     elements are accessed it lists the virtual folders.
    */
    function getVirtualFolderCollection( $currentSite, $collection, $fullPath, $depth, $properties )
    {
        $this->appendLogEntry( "Check virtual folder: site '$currentSite' in '$collection' ", 'CS:getCollectionContent' );
        $this->setCurrentSite( $currentSite );

        if ( !$collection )
        {
            // We are inside a site so we display the virtual folder for the site
            $this->appendLogEntry( "Virtual folder for '$currentSite'", 'CS:getCollectionContent' );
            $entries = $this->fetchVirtualSiteContent( $currentSite, $depth, $properties );
            return $entries;
        }

        $collection = $this->splitFirstPathElement( $collection, $virtualFolder );

        if ( !in_array( $virtualFolder, $this->virtualFolderList() ) )
        {
            $this->appendLogEntry( "Unknown virtual folder: '$virtualFolder' in site '$currentSite'", 'CS:getCollectionContent' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !$this->userHasVirtualAccess( $currentSite, $virtualFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$virtualFolder' in site '$currentSite'", 'CS:getCollectionContent' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->getContentTreeCollection( $currentSite, $virtualFolder, $collection, $fullPath, $depth, $properties );
    }

    /*!
     \private
     Handles collections on the content tree level.
     Depending on the virtual folder we will generate a node path url and fetch
     the nodes for that path.
    */
    function getContentTreeCollection( $currentSite, $virtualFolder, $collection, $fullPath, $depth, $properties )
    {
        $this->appendLogEntry( "Content collection: from site '$currentSite' in '$virtualFolder' using path '$collection'", 'CS:getCollectionContent' );
        $nodePath = $this->internalNodePath( $virtualFolder, $collection );
        $node = $this->fetchNodeByTranslation( $nodePath );

        if ( !$node )
        {
            $this->appendLogEntry( "Unknown node: $nodePath", 'CS:getCollectionContent' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        // Can we list the children of the node?
        if ( !$node->canRead() )
        {
            $this->appendLogEntry( "No access to content '$nodePath' in site '$currentSite'", 'CS:getCollectionContent' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $entries = $this->fetchContentList( $node, $nodePath, $depth, $properties );
        return $entries;
    }

    /*!
     \reimp
     Tries to figure out the filepath of the object being shown,
     if not we will pass the virtual url as the filepath.
    */
    function get( $target )
    {
        $result         = array();
        $result["data"] = false;
        $result["file"] = false;

        $fullPath = $target;
        $target = $this->splitFirstPathElement( $target, $currentSite );

        if ( !$currentSite )
        {
            // Sites are folders and have no data
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasSiteAccess( $currentSite ) )
        {
            $this->appendLogEntry( "No access to site '$currentSite'", 'CS:get' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->getVirtualFolderData( $result, $currentSite, $target, $fullPath );
    }

    /*!
     \private
     Handles data retrival on the virtual folder level.
    */
    function getVirtualFolderData( $result, $currentSite, $target, $fullPath )
    {
        $this->appendLogEntry( "current site: $currentSite", 'CS:get' );
        $this->setCurrentSite( $currentSite );

        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !$target )
        {
            if ( !in_array( $virtualFolder, $this->virtualFileList() ) )
            {
                return EZ_WEBDAV_FAILED_NOT_FOUND;
            }

            // We have reached the end of the path
            if ( $virtualFolder == basename( VIRTUAL_INFO_FILE_NAME ) )
            {
                $result["file"] = VIRTUAL_INFO_FILE_NAME;

                return $result;
            }

            // The rest in the virtual folder does not have any data
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !$this->userHasVirtualAccess( $currentSite, $virtualFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$virtualFolder' in site '$currentSite'", 'CS:get' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !in_array( $virtualFolder, $this->virtualFolderList() ) )
        {
            $this->appendLogEntry( "Unknown virtual folder: '$virtualFolder' in site '$currentSite'", 'CS:get' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( $virtualFolder == VIRTUAL_CONTENT_FOLDER_NAME or
             $virtualFolder == VIRTUAL_MEDIA_FOLDER_NAME )
        {
            return $this->getContentNodeData( $result, $currentSite, $virtualFolder, $target, $fullPath );
        }
        return EZ_WEBDAV_FAILED_NOT_FOUND;
    }

    /*!
     \private
     Handles data retrival on the content tree level.
    */
    function getContentNodeData( $result, $currentSite, $virtualFolder, $target, $fullPath )
    {
        $this->appendLogEntry( "attempting to fetch node, target is: $target", 'CS:get' );

        // Attempt to fetch the node the client wants to get.
        $nodePath = $this->internalNodePath( $virtualFolder, $target );
        $node = $this->fetchNodeByTranslation( $nodePath );

        // Proceed only if the node is valid:
        if ( $node == null )
        {
            $this->appendLogEntry( "No node for: $nodePath", 'CS:get' );
            return $result;
        }

        // Can we fetch the contents of the node
        if ( !$node->canRead() )
        {
            $this->appendLogEntry( "No access to get '$nodePath' in site '$currentSite'", 'CS:get' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $object = $node->attribute( 'object' );

        include_once( 'kernel/classes/ezcontentupload.php' );
        $upload = new eZContentUpload();
        $info = $upload->objectFileInfo( $object );
        if ( $info )
        {
            $result['file'] = $info['filepath'];
        }

        return $result;
    }

    /*!
     \note Not implemented yet
    */
    function head( $target )
    {
        return EZ_WEBDAV_FAILED_NOT_FOUND;
    }

    /*!
     \reimp
     Tries to create/update an object at location \a $target with the file \a $tempFile.
    */
    function put( $target, $tempFile )
    {
        $fullPath = $target;
        $target = $this->splitFirstPathElement( $target, $currentSite );

        if ( !$currentSite )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasSiteAccess( $currentSite ) )
        {
            $this->appendLogEntry( "No access to site '$currentSite'", 'CS:put' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->putVirtualFolderData( $currentSite, $target, $tempFile, $fullPath );
    }

    /*!
     \private
     Handles data storage on the content tree level.
     It will check if the target is below a content folder in which it calls putContentData().
    */
    function putVirtualFolderData( $currentSite, $target, $tempFile, $fullPath )
    {
        $this->appendLogEntry( "current site is: $currentSite", 'CS:put' );
        $this->setCurrentSite( $currentSite );

        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !$target )
        {
            // We have reached the end of the path
            // We do not allow 'put' operations for the virtual folder.
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !in_array( $virtualFolder, $this->virtualFolderList() ) )
        {
            $this->appendLogEntry( "Unknown virtual folder: '$virtualFolder' in site '$currentSite'", 'CS:put' );
            return EZ_WEBDAV_FAILED_CONFLICT;
        }

        if ( !$this->userHasVirtualAccess( $currentSite, $virtualFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$virtualFolder' in site '$currentSite'", 'CS:put' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( $virtualFolder == VIRTUAL_CONTENT_FOLDER_NAME or
             $virtualFolder == VIRTUAL_MEDIA_FOLDER_NAME )
        {
            return $this->putContentData( $currentSite, $virtualFolder, $target, $tempFile, $fullPath );
        }

        return EZ_WEBDAV_FAILED_FORBIDDEN;
    }

    /*!
     \private
     Handles data storage on the content tree level.
     It will try to find the parent node of the wanted placement and
     create a new object with data from \a $tempFile.
    */
    function putContentData( $currentSite, $virtualFolder, $target, $tempFile, $fullPath )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $target );

        $this->appendLogEntry( "Inside virtual content folder", 'CS:put' );

        $parentNode = $this->fetchParentNodeByTranslation( $nodePath );
        if ( $parentNode == null )
        {
            // The node does not exist, so we cannot put the file
            $this->appendLogEntry( "Cannot put file $nodePath, not parent found", 'CS:put' );
            return EZ_WEBDAV_FAILED_CONFLICT;
        }

        // Can we put content in the parent node
        if ( !$parentNode->canRead() )
        {
            $this->appendLogEntry( "No access to put '$nodePath' in site '$currentSite'", 'CS:put' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $parentNodeID = $parentNode->attribute( 'node_id' );

        // We need the MIME-Type to figure out which content-class we will use
        $mimeInfo = eZMimeType::findByURL( $nodePath );
        $mime = $mimeInfo['name'];

        $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
        $iniSettings = $webdavINI->variable( 'PutSettings', 'MIME' );
        $defaultObjectType = $webdavINI->variable( 'PutSettings', 'DefaultClass' );

        $existingNode = $this->fetchNodeByTranslation( $nodePath );
        include_once( 'kernel/classes/ezcontentupload.php' );

        $upload = new eZContentUpload();
        if ( !$upload->handleLocalFile( $result, $tempFile, $parentNodeID, $existingNode ) )
        {
            foreach ( $result['errors'] as $error )
            {
                $this->appendLogEntry( "Error: " . $error['description'], 'CS: put' );
            }
            foreach ( $result['notices'] as $notice )
            {
                $this->appendLogEntry( "Notice: " . $notice['description'], 'CS: put' );
            }
            return EZ_WEBDAV_FAILED_UNSUPPORTED;
        }

        return EZ_WEBDAV_OK_CREATED;
    }

    /*!
      \reimp
      Tries to create a collection at \a $target. In our case this is a content-class
      of a given type (most likely a folder).
    */
    function mkcol( $target )
    {
        $fullPath = $target;
        $target = $this->splitFirstPathElement( $target, $currentSite );

        if ( !$currentSite )
        {
            // Site list cannot get new entries
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasSiteAccess( $currentSite ) )
        {
            $this->appendLogEntry( "No access to site '$currentSite'", 'CS:mkcol' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->mkcolVirtualFolder( $currentSite, $target, $fullPath );
    }

    /*!
     \private
     Handles collection creation on the virtual folder level.
     It will check if the target is below a content folder in which it calls mkcolContent().
    */
    function mkcolVirtualFolder( $currentSite, $target, $fullPath )
    {
        $this->setCurrentSite( $currentSite );

        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !in_array( $virtualFolder, $this->virtualList() ) )
        {
            $this->appendLogEntry( "Unknown virtual element: '$virtualFolder' in site '$currentSite'", 'CS:mkcol' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !$target )
        {
            // We have reached the end of the path
            // We do not allow 'mkcol' operations for the virtual folder.
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasVirtualAccess( $currentSite, $virtualFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$virtualFolder' in site '$currentSite'", 'CS:mkcol' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( $virtualFolder == VIRTUAL_CONTENT_FOLDER_NAME or
             $virtualFolder == VIRTUAL_MEDIA_FOLDER_NAME )
        {
            return $this->mkcolContent( $currentSite, $virtualFolder, $target, $fullPath );
        }

        return EZ_WEBDAV_FAILED_FORBIDDEN;
    }

    /*!
     \private
     Handles collection creation on the content tree level.
     It will try to find the parent node of the wanted placement and
     create a new collection (folder etc.) as a child.
    */
    function mkcolContent( $currentSite, $virtualFolder, $target, $fullPath )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $target );
        $node = $this->fetchNodeByTranslation( $nodePath );
        if ( $node )
        {
            return EZ_WEBDAV_FAILED_EXISTS;
        }

        $parentNode = $this->fetchParentNodeByTranslation( $nodePath );
        $this->appendLogEntry( "Target is: $target", 'CS:mkcol' );

        if ( !$parentNode )
        {
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        // Can we create a collection in the parent node
        if ( !$parentNode->canRead() )
        {
            $this->appendLogEntry( "No access to mkcol '$nodePath' in site '$currentSite'", 'CS:getCollectionContent' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->createFolder( $parentNode, $nodePath );
    }

    /*!
      \reimp
      Removes the object from the node tree and leaves it in the trash.
    */
    function delete( $target )
    {
        $fullPath = $target;
        $target = $this->splitFirstPathElement( $target, $currentSite );

        if ( !$currentSite )
        {
            // Cannot delete entries in site list
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasSiteAccess( $currentSite ) )
        {
            $this->appendLogEntry( "No access to site '$currentSite'", 'CS:delete' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->deleteVirtualFolder( $currentSite, $target, $fullPath );
    }

    /*!
     \private
     Handles deletion on the virtual folder level.
     It will check if the target is below a content folder in which it calls deleteContent().
    */
    function deleteVirtualFolder( $currentSite, $target, $fullPath )
    {
        $this->appendLogEntry( "Target is: $target", 'CS:delete' );
        $this->setCurrentSite( $currentSite );

        $target = $this->splitFirstPathElement( $target, $virtualFolder );

        if ( !in_array( $virtualFolder, $this->virtualList() ) )
        {
            $this->appendLogEntry( "Unknown virtual element: '$virtualFolder' in site '$currentSite'", 'CS:delete' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !$target )
        {
            // We have reached the end of the path
            // We do not allow 'delete' operations for the virtual folder.
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasVirtualAccess( $currentSite, $virtualFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$virtualFolder' in site '$currentSite'", 'CS:delete' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( $virtualFolder == VIRTUAL_CONTENT_FOLDER_NAME or
             $virtualFolder == VIRTUAL_MEDIA_FOLDER_NAME )
        {
            return $this->deleteContent( $currentSite, $virtualFolder, $target, $fullPath );
        }

        return EZ_WEBDAV_FAILED_FORBIDDEN;
    }

    /*!
     \private
     Handles deletion on the content tree level.
     It will try to find the node of the target \a $target
     and then try to remove it (ie. move to trash) if the user is allowed.
    */
    function deleteContent( $currentSite, $virtualFolder, $target, $fullPath )
    {
        $nodePath = $this->internalNodePath( $virtualFolder, $target );
        $node = $this->fetchNodeByTranslation( $nodePath );

        if ( $node == null )
        {
            $this->appendLogEntry( "Cannot delete node/object $nodePath, it does not exist", 'CS:delete' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        // Can we delete the node?
        if ( !$node->canRead() or
             !$node->canRemove() )
        {
            $this->appendLogEntry( "No access to delete '$nodePath' in site '$currentSite'", 'CS:delete' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $this->appendLogEntry( "Removing node: $nodePath", 'CS:delete' );
        $object =& $node->attribute( 'object' );
        $object->remove();
        return EZ_WEBDAV_OK;
    }

    /*!
      \reimp
      Moves the object \a $source to destination \a $destination.
    */
    function move( $source, $destination )
    {
        $fullSource = $source;
        $fullDestination = $destination;
        $source = $this->splitFirstPathElement( $source, $sourceSite );
        $destination = $this->splitFirstPathElement( $destination, $destinationSite );
        if ( $sourceSite != $destinationSite )
        {
            // We do not support moving from one site to another yet
            // TODO: Check if the sites are using the same db,
            //       if so allow the move as a simple object move
            //       If not we will have to do an object export from
            //       $sourceSite and import it in $destinationSite
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$sourceSite or
             !$destinationSite )
        {
            // Cannot move entries in site list
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasSiteAccess( $sourceSite ) )
        {
            $this->appendLogEntry( "No access to site '$sourceSite'", 'CS:move' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }
        if ( !$this->userHasSiteAccess( $destinationSite ) )
        {
            $this->appendLogEntry( "No access to site '$destinationSite'", 'CS:move' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        return $this->moveVirtualFolder( $sourceSite, $destinationSite,
                                         $source, $destination,
                                         $fullSource, $fullDestination );
    }

    /*!
     \private
     Handles moving on the virtual folder level.
     It will check if the target is below a content folder in which it calls moveContent().
    */
    function moveVirtualFolder( $sourceSite, $destinationSite,
                                $source, $destination,
                                $fullSource, $fullDestination )
    {
        $this->setCurrentSite( $sourceSite );

        $source = $this->splitFirstPathElement( $source, $sourceVFolder );
        $destination = $this->splitFirstPathElement( $destination, $destinationVFolder );

        if ( !in_array( $sourceVFolder, $this->virtualList() ) )
        {
            $this->appendLogEntry( "Unknown virtual element: '$sourceVFolder' in site '$sourceSite'", 'CS:move' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !in_array( $destinationVFolder, $this->virtualList() ) )
        {
            $this->appendLogEntry( "Unknown virtual element: '$destinationVFolder' in site '$destinationSite'", 'CS:move' );
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !$source or
             !$destination )
        {
            // We have reached the end of the path for source or destination
            // We do not allow 'move' operations for the virtual folder (from or to)
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$this->userHasVirtualAccess( $sourceSite, $sourceVFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$sourceVFolder' in site '$sourceSite'", 'CS:move' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }
        if ( !$this->userHasVirtualAccess( $destinationSite, $destinationVFolder ) )
        {
            $this->appendLogEntry( "No access to virtual folder '$destinationVFolder' in site '$destinationSite'", 'CS:move' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( ( $sourceVFolder == VIRTUAL_CONTENT_FOLDER_NAME or
               $sourceVFolder == VIRTUAL_MEDIA_FOLDER_NAME ) and
             ( $destinationVFolder == VIRTUAL_CONTENT_FOLDER_NAME or
               $destinationVFolder == VIRTUAL_MEDIA_FOLDER_NAME ) )
        {
            return $this->moveContent( $sourceSite, $destinationSite,
                                       $sourceVFolder, $destinationVFolder,
                                       $source, $destination,
                                       $fullSource, $fullDestination );
        }

        return EZ_WEBDAV_FAILED_FORBIDDEN;
    }

    /*!
     \private
     Handles moving on the content tree level.
     It will try to find the node of the target \a $source
     and then try to move it to \a $destination.
    */
    function moveContent( $sourceSite, $destinationSite,
                          $sourceVFolder, $destinationVFolder,
                          $source, $destination,
                          $fullSource, $fullDestination )
    {
        $nodePath = $this->internalNodePath( $sourceVFolder, $source );
        $destinationNodePath = $this->internalNodePath( $destinationVFolder, $destination );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $source = $this->fileBasename( $source );

        $sourceNode = $this->fetchNodeByTranslation( $nodePath );

        if ( !$sourceNode )
        {
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        if ( !$sourceNode->canMove() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $object = $sourceNode->attribute( 'object' );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $destination = $this->fileBasename( $destination );

        $destinationNode = $this->fetchNodeByTranslation( $destinationNodePath );
        $this->appendLogEntry( "Destination: $destinationNodePath", 'CS:move' );

        if ( $destinationNode )
        {
            return EZ_WEBDAV_FAILED_EXISTS;
        }

        $destinationNode = $this->fetchParentNodeByTranslation( $destinationNodePath );

        if ( !$destinationNode )
        {
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        // Can we move the node from $sourceNode to $destinationNode
        if ( !$sourceNode->canMove() )
        {
            $this->appendLogEntry( "No access to move '$sourceSite':'$nodePath' to '$destinationSite':'$destinationNodePath'", 'CS:move' );
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        if ( !$destinationNode->canMove() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $sourceNode->move( $destinationNode->attribute( 'node_id' ) );

        $newNode = eZContentObjectTreeNode::fetchNode( $object->attribute( 'id' ), $destinationNode->attribute( 'node_id' ) );
        if ( $newNode )
        {
            $newNode->updateSubTreePath();
            if ( $newNode->attribute( 'main_node_id' ) == $newNode->attribute( 'node_id' ) )
            {
                $oldParentObject =& $destinationNode->attribute( 'object' );
                // If the main node is moved we need to check if the section ID must change
                // If the section ID is shared with its old parent we must update with the
                //  id taken from the new parent, if not the node is the starting point of the section.
                if ( $object->attribute( 'section_id' ) == $oldParentObject->attribute( 'section_id' ) )
                {
                    $newParentNode =& $newNode->fetchParent();
                    $newParentObject =& $newParentNode->object();
                    eZContentObjectTreeNode::assignSectionToSubTree( $newNode->attribute( 'main_node_id' ),
                                                                     $newParentObject->attribute( 'section_id' ),
                                                                     $oldParentObject->attribute( 'section_id' ) );
                }
            }
        }

        /*

        // Todo: add lookup of the name setting for the current object
                    $contentObjectID = $object->attribute( 'id' );
                    $contentObjectAttributes =& $object->contentObjectAttributes();
                    $contentObjectAttributes[0]->setAttribute( 'data_text', basename( $destination ) );
                    $contentObjectAttributes[0]->store();

                    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID, 'version' => 1 ) );
                    $object->store();
        */

        return EZ_WEBDAV_OK_CREATED;
    }

    /*!
     @}
    */

    /*!
      Sets/changes the current site(access) to a \a $site.
    */
    function setCurrentSite( $site )
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

    /*!
      Checks if the current user has access rights to site \a $site.
      \return \c true if the user proper access.
    */
    function userHasSiteAccess( $site )
    {
        $result = $this->User->hasAccessTo( 'user', 'login' );
        $accessWord = $result['accessWord'];

        if ( $accessWord == 'limited' )
        {
            $hasAccess = false;
            $policyChecked = false;
            foreach ( array_keys( $result['policies'] ) as $key )
            {
                $policy =& $result['policies'][$key];
                if ( isset( $policy['SiteAccess'] ) )
                {
                    $policyChecked = true;
                    if ( in_array( crc32( $site ), $policy['SiteAccess'] ) )
                    {
                        $hasAccess = true;
                        break;
                    }
                }
                if ( $hasAccess )
                    break;
            }
            if ( !$policyChecked )
                $hasAccess = true;
        }
        else if ( $accessWord == 'yes' )
        {
            $hasAccess = true;
        }
        else if ( $accessWord == 'no' )
        {
            $hasAccess = false;
        }
        return $hasAccess;
    }

    /*!
      Checks if the current user has access rights to virtual element \a virtual
      on site \a $site.
      \return \c true if the user proper access.
    */
    function userHasVirtualAccess( $site, $virtual )
    {
        $this->appendLogEntry( "Can access '$site' and '$virtual'", 'CS:userHasVirtualAccess' );
        return true;
    }

    /*!
      Detects a possible/valid site-name in start of a path.
      \return The name of the site that was detected or \c false if not site could be detected
    */
    function currentSiteFromPath( $path )
    {
        $this->appendLogEntry( "start path: $path", 'CS:currentSitePath' );

        $indexDir = eZSys::indexDir();

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^" . preg_quote( $indexDir ) . "(.+)$#", $path, $matches ) )
        {
            $path = $matches[1];
        }

        $this->appendLogEntry( "indexdir: $path", 'CS:currentSitePath' );

        // Get the list of available sites.
        $sites = $this->availableSites();

        foreach ( $sites as $site )
        {
            // Check if given path starts with this site-name, if so: return it.
            if ( preg_match( "#^/" . preg_quote( $site ) . "(.*)$#", $path, $matches ) )
            {
                $this->appendLogEntry( "site $site: $path", 'CS:currentSitePath' );
                return $site ;
            }
        }

        $this->appendLogEntry( "no valid site was found..", 'CS:currentSitePath' );
        return false ;
    }

    /*!
      \reimp
      Removes the www-dir and indexfile from the URL.
    */
    function processURL( $url )
    {
        $this->appendLogEntry( "start url: $url", 'CS:processURL' );
        $indexDir = eZSys::indexDir();
        $len = strlen( $indexDir );

        if ( $indexDir == substr( $url, 0, $len ) )
        {
            $url = substr( $url, $len );
        }

        // Remove the starting / if there is one
        // the rest of the operation code expects this to not be present
        if ( strlen( $url ) > 0 and $url[0] == '/' )
            $url = substr( $url, 1 );

        $this->appendLogEntry( "indexdir url: $url", 'CS:processURL' );
        return $url;
    }

    /*!
     \reimp
    */
    function headers()
    {
		header( "WebDAV-Powered-By: eZ publish" );
    }

    /*!
      Takes the first path element from \a $path and removes it from
      the path, the extracted part will be placed in \a $name.
      \return A string containing the rest of the path,
              the path will not contain a starting slash.
      \param $path A string defining a path of elements delimited by a slash,
                   if the path starts with a slash it will be removed.
      \param[out] $element The name of the first path element without any slashes.

      \code
      $path = '/path/to/item/';
      $newPath = eZWebDAVContentServer::splitFirstPathElement( $path, $root );
      print( $root ); // prints 'path', $newPath is now 'to/item/'
      $newPath = eZWebDAVContentServer::splitFirstPathElement( $newPath, $second );
      print( $second ); // prints 'to', $newPath is now 'item/'
      $newPath = eZWebDAVContentServer::splitFirstPathElement( $newPath, $third );
      print( $third ); // prints 'item', $newPath is now ''
      \endcode
    */
    function splitFirstPathElement( $path, &$element )
    {
        if ( $path[0] == '/' )
            $path = substr( $path, 1 );
        $pos = strpos( $path, '/' );
        if ( $pos === false )
        {
            $element = $path;
            $path = '';
        }
        else
        {
            $element = substr( $path, 0, $pos );
            $path = substr( $path, $pos + 1 );
        }
        return $path;
    }

    /*!
      Takes the last path element from \a $path and removes it from
      the path, the extracted part will be placed in \a $name.
      \return A string containing the rest of the path,
              the path will not contain the ending slash.
      \param $path A string defining a path of elements delimited by a slash,
                   if the path ends with a slash it will be removed.
      \param[out] $element The name of the first path element without any slashes.

      \code
      $path = '/path/to/item/';
      $newPath = eZWebDAVContentServer::splitLastPathElement( $path, $root );
      print( $root ); // prints 'item', $newPath is now '/path/to'
      $newPath = eZWebDAVContentServer::splitLastPathElement( $newPath, $second );
      print( $second ); // prints 'to', $newPath is now '/path'
      $newPath = eZWebDAVContentServer::splitLastPathElement( $newPath, $third );
      print( $third ); // prints 'path', $newPath is now ''
      \endcode
    */
    function splitLastPathElement( $path, &$element )
    {
        $len = strlen( $path );
        if ( $len > 0 and $path[$len - 1] == '/' )
            $path = substr( $path, 0, $len - 1 );
        $pos = strrpos( $path, '/' );
        if ( $pos === false )
        {
            $element = $path;
            $path = '';
        }
        else
        {
            $element = substr( $path, $pos + 1 );
            $path = substr( $path, 0, $pos );
        }
        return $path;
    }

    /*!
     \private
     \return A path that corresponds to the internal path of nodes.
    */
    function internalNodePath( $virtualFolder, $collection )
    {
        // All root nodes needs to prepend their name to get the correct path
        // except for the content root which uses the path directly.
        if ( $virtualFolder == VIRTUAL_MEDIA_FOLDER_NAME )
        {
            $nodePath = 'media';
            if ( strlen( $collection ) > 0 )
                $nodePath .= '/' . $collection;
        }
        else
        {
            $nodePath = $collection;
        }
        return $nodePath;
    }

    /*!
      Attempts to fetch a possible/existing node by translating
      the inputted string/path to a node-number.
    */
    function fetchNodeByTranslation( $nodePathString )
    {
        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $nodePathString = $this->fileBasename( $nodePathString );

        // Strip away last slash
        if ( strlen( $nodePathString ) > 0 and
             $nodePathString[strlen( $nodePathString ) - 1] == '/' )
        {
            $nodePathString = substr( $nodePathString, 0, strlen( $nodePathString ) - 1 );
        }

        if ( strlen( $nodePathString ) > 0 )
        {
            $nodePathString = eZURLAlias::convertPathToAlias( $nodePathString );
        }

        // Attempt to translate the URL to something like "/content/view/full/84".
        $translateResult =& eZURLAlias::translate( $nodePathString );

        if ( !$translateResult )
        {
            $this->appendLogEntry( "Node translation failed: $nodePathString", 'CS:fetchNodeByTranslation' );
        }

        // Get the ID of the node (which is the last part of the translated path).
        if ( preg_match( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
        {
            $nodeID = $matches[1];
            $this->appendLogEntry( "NodeID: $nodeID", 'CS:fetchNodeByTranslation' );
        }
        else
        {
            $this->appendLogEntry( "No nodeID", 'CS:fetchNodeByTranslation' );
            return false;
        }

        // Attempt to fetch the node.
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Return the node.
        return $node;
    }

    /*!
      \return The string \a $name without the final suffix (.jpg, .gif etc.)
    */
    function fileBasename( $name )
    {
        $pos = strrpos( $name, '.' );
        if ( $pos !== false )
        {
            $name = substr( $name, 0, $pos );
        }
        return $name;
    }

    /*!
      Attempts to fetch a possible node by translating
      the inputted string/path to a node-number. The last
      section of the path is removed before the actual
      translation: hence, the PARENT node is returned.
    */
    function fetchParentNodeByTranslation( $nodePathString )
    {
        // Strip extensions. E.g. .jpg
        $nodePathString = $this->fileBasename( $nodePathString );

        // Strip away last slash
        if ( strlen( $nodePathString ) > 0 and
             $nodePathString[strlen( $nodePathString ) - 1] == '/' )
        {
            $nodePathString = substr( $nodePathString, 0, strlen( $nodePathString ) - 1 );
        }

        $nodePathString = $this->splitLastPathElement( $nodePathString, $element );

        if ( strlen( $nodePathString ) == 0 )
            $nodePathString = '/';

        $nodePathString = eZURLAlias::convertPathToAlias( $nodePathString );

        // Attempt to translate the URL to something like "/content/view/full/84".
        $translateResult =& eZURLAlias::translate( $nodePathString );

        // Get the ID of the node (which is the last part of the translated path).
        if ( preg_match( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
        {
            $nodeID = $matches[1];
            $this->appendLogEntry( "NodeID: $nodeID", 'CS:fetchParentNodeByTranslation' );
        }
        else
        {
            $this->appendLogEntry( "Root node", 'CS:fetchParentNodeByTranslation' );
            $nodeID = 2;
        }

        // Attempt to fetch the node.
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Return the node.
        return $node;
    }

    /*!
     \return An array containing the names of all folders in the virtual root.
    */
    function virtualFolderList()
    {
        return array( VIRTUAL_CONTENT_FOLDER_NAME, VIRTUAL_MEDIA_FOLDER_NAME );
    }

    /*!
     \return An array containing the names of all folders in the virtual root.
    */
    function virtualFolderInfoList()
    {
        return array( array( 'name' => VIRTUAL_CONTENT_FOLDER_NAME ),
                      array( 'name' => VIRTUAL_MEDIA_FOLDER_NAME ) );
    }

    /*!
     \return An array containing the names of all files in the virtual root.
    */
    function virtualFileList()
    {
        return array( basename( VIRTUAL_INFO_FILE_NAME ) );
    }

    /*!
     \return An array containing the names of all files in the virtual root.
    */
    function virtualFileInfoList()
    {
        return array( array( 'name' => basename( VIRTUAL_INFO_FILE_NAME ),
                             'filepath' => VIRTUAL_INFO_FILE_NAME ) );
    }

    /*!
     \return An array containing the names of all elements in the virtual root.
    */
    function virtualList()
    {
        return array_merge( eZWebDAVContentServer::virtualFolderList(),
                            eZWebDAVContentServer::virtualFileList() );
    }

    /*!
     \return An array containing the names of all elements in the virtual root.
    */
    function virtualInfoList()
    {
        return array_merge( eZWebDAVContentServer::virtualFolderInfoList(),
                            eZWebDAVContentServer::virtualFileInfoList() );
    }

    /*!
     Functions related to creation collections
     @{
    */

    /*!
      Builds and returns the content of the virtual start fodler
      for a site. The virtual startfolder is an intermediate step
      between the site-list and actual content. This directory
      contains the "content" folder which leads to the site's
      actual content.
    */
    function fetchVirtualSiteContent( $site, $depth, $properties )
    {
        $this->appendLogEntry( "Script URL.." . $_SERVER["SCRIPT_URL"], 'CS:fetchVirtualSiteContent' );
        // Location of the info file.
        $infoFile = $_SERVER['DOCUMENT_ROOT'] . '/' . VIRTUAL_INFO_FILE_NAME;

        // Always add the current collection
        $contentEntry = array();
        $contentEntry["name"]     = $_SERVER["SCRIPT_URL"];
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = 'httpd/unix-directory';
        $contentEntry["ctime"]    = filectime( 'settings/siteaccess/' . $site );
        $contentEntry["mtime"]    = filemtime( 'settings/siteaccess/' . $site );
        $contentEntry["href"]     = $_SERVER["SCRIPT_URL"];
        $entries[] = $contentEntry;

        $defctime = $contentEntry['ctime'];
        $defmtime = $contentEntry['mtime'];

        if ( $depth > 0 )
        {
            $scriptURL = $_SERVER["SCRIPT_URL"];
            if ( $scriptURL{strlen($scriptURL) - 1} != "/" )
                $scriptURL .= "/";

            // Set up attributes for the virtual content folder:
            foreach ( $this->virtualInfoList() as $info )
            {
                $name = $info['name'];
                $filepath = false;
                if ( isset( $info['filepath'] ) )
                    $filepath = $info['filepath'];
                $size = 0;
                if ( $filepath === false or file_exists( $filepath ) )
                {
                    $mimeType = 'httpd/unix-directory';
                    if ( $filepath !== false )
                    {
                        $mimeInfo = eZMimeType::findByFileContents( $filepath );
                        $mimeType = $mimeInfo['name'];
                        $ctime = filectime( $filepath );
                        $mtime = filemtime( $filepath );
                        $size  = filesize( $filepath );
                    }
                    else
                    {
                        $ctime = $defctime;
                        $mtime = $defmtime;
                    }

                    $entry             = array();
                    $entry["name"]     = $name;
                    $entry["size"]     = $size;
                    $entry["mimetype"] = $mimeType;
                    $entry["ctime"]    = $ctime;
                    $entry["mtime"]    = $mtime;
                    $entry["href"]     = $scriptURL . $name;
                    $entries[]         = $entry;
                }
            }
        }

        return $entries;
    }

    /*!
      Builds a content-list of available sites and returns it.
    */
    function fetchSiteListContent( $depth, $properties )
    {
        // At the end: we'll return an array of entry-arrays.
        $entries = array();

        // An entry consists of several attributes (name, size, etc).
        $contentEntry = array();
        $entries = array();

        // Set up attributes for the virtual site-list folder:
        $contentEntry["name"]     = '/';
        $contentEntry["href"]     = $_SERVER['SCRIPT_URI'];
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = 'httpd/unix-directory';
        $contentEntry["ctime"]    = filectime( 'var' );
        $contentEntry["mtime"]    = filemtime( 'var' );

        $entries[] = $contentEntry;

        if ( $depth > 0 )
        {
            // Get list of available sites.
            $sites = $this->availableSites();

            // For all available sites:
            foreach ( $sites as $site )
            {
                // Set up attributes for the virtual site-list folder:
                $contentEntry["name"]     = $_SERVER['SCRIPT_URI'] . $site;
                $contentEntry["size"]     = 0;
                $contentEntry["mimetype"] = 'httpd/unix-directory';
                $contentEntry["ctime"]    = filectime( 'settings/siteaccess/' . $site );
                $contentEntry["mtime"]    = filemtime( 'settings/siteaccess/' . $site );

                if ( $_SERVER["SCRIPT_URL"] == '/' )
                {
                    $contentEntry["href"] = $contentEntry["name"];
                }
                else
                {
                    $contentEntry["href"] = $_SERVER["SCRIPT_URL"] . $contentEntry["name"];
                }

                $entries[] = $contentEntry;
            }
        }

        return $entries;
    }

    /*!
      Gets and returns the content of an actual node.
      List of other nodes belonging to the target node
      (one level below it) will be returned.
    */
    function fetchContentList( &$node, $target, $depth, $properties )
    {
        // We'll return an array of entries (which is an array of attributes).
        $entries = array();

        if ( $depth == 1 )
        {
            // Get all the children of the target node.
            $subTree =& $node->subTree( array ( 'Depth' => 1 ) );

            // Build the entries array by going through all the
            // nodes in the subtree and getting their attributes:
            foreach ( $subTree as $someNode )
            {
                $entries[] = $this->fetchNodeInfo( $someNode );
            }
        }

        // Always include the information about the current level node
        $thisNodeInfo = array();
        $thisNodeInfo = $this->fetchNodeInfo( $node );
        $thisNodeInfo["href"] = $_SERVER['SCRIPT_URI'];
        $entries[] = $thisNodeInfo;

        // Return the content of the target.
        return $entries;
    }

    /*!
      Gathers information about a given node (specified as parameter).
    */
    function fetchNodeInfo( &$node )
    {
        // When finished, we'll return an array of attributes/properties.
        $entry = array();

        // Grab settings from the ini file:
        $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
        $iniSettings = $webdavINI->variable( 'DisplaySettings', 'FileAttribute' );

        $classIdentifier = $node->attribute( 'class_identifier' );

        $object =& $node->attribute( 'object' );

        // By default, everything is displayed as a folder:
        // Trim the name of the node, it is in some cases whitespace in eZ publish
        $entry["name"] = trim( $node->attribute( 'name' ) );
        $entry["size"] = 0;
        $entry["mimetype"] = 'httpd/unix-directory';
        $entry["ctime"] = $object->attribute( 'published' );
        $entry["mtime"] = $object->attribute( 'modified' );

        include_once( 'kernel/classes/ezcontentupload.php' );
        $upload = new eZContentUpload();
        $info = $upload->objectFileInfo( $object );
        if ( $info )
        {
            $filePath = $info['filepath'];
            $entry["mimetype"] = false;
            $entry["size"] = false;
            if ( isset( $info['filesize'] ) )
                $entry['size'] = $info['filesize'];
            if ( isset( $info['mime_type'] ) )
                $entry['mimetype'] = $info['mime_type'];

            // Fill in information from the actual file if they are missing.
            if ( !$entry['size'] and file_exists( $filePath ) )
            {
                $entry["size"] = filesize( $filePath );
            }
            if ( !$entry['mimetype']  )
            {
                $mimeInfo = eZMimeType::findByURL( $filePath );
                $entry["mimetype"] = $mimeInfo['name'];
                $suffix = $mimeInfo['suffix'];
                if ( strlen( $suffix ) > 0 )
                    $entry["name"] .= '.' . $suffix;
            }
            else
            {
                $mimeInfo = eZMimeType::findByName( $entry['mimetype'] );
                $suffix = $mimeInfo['suffix'];
                if ( strlen( $suffix ) > 0 )
                    $entry["name"] .= '.' . $suffix;
            }

            if ( file_exists( $filePath ) )
            {
                $entry["ctime"] = filectime( $filePath );
                $entry["mtime"] = filemtime( $filePath );
            }
        }
        else
        {
            // Here we only show items as folders if they have
            // is_container set to true, otherwise it's an unknown binary file
            $class =& $object->contentClass();
            if ( !$class->attribute( 'is_container' ) )
            {
                $entry['mimetype'] = 'application/octet-stream';
            }
        }

        $scriptURL = $_SERVER["SCRIPT_URL"];
        if ( strlen( $scriptURL ) > 0 and $scriptURL[ strlen( $scriptURL ) - 1 ] != "/" )
            $scriptURL .= "/";

        // Set the href attribute (note that it doesn't just equal the name).
        if ( !isset( $entry['href'] ) )
            $entry["href"] = $scriptURL . $entry['name'];

        // Return array of attributes/properties (name, size, mime, times, etc.).
        return $entry;
    }

    /*!
     @}
    */

    /*!
      Creates a new folder under the given target node.
    */
    function createFolder( $node, $target )
    {
        // Attempt to get the current user ID.
        $userID = $this->User->id();

        // Set the parent node ID.
        $parentNodeID = $node->attribute( 'node_id' );

        // Grab settings from the ini file:
        $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
        $folderClassID = $webdavINI->variable( 'FolderSettings', 'FolderClass' );

        // Fetch the folder class.
        $class =& eZContentClass::fetch( $folderClassID );

        // Check if the user has access to create a folder here
        if ( $node->checkAccess( 'create', $folderClassID, $node->attribute( 'contentclass_id' ) ) != '1' )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

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

    /*!
      Gets and returns a list of the available sites (from site.ini).
    */
    function availableSites()
    {
        // The site list is an array of strings.
        $siteList = array();

        // Grab the sitelist from the ini file.
        $webdavINI =& eZINI::instance();
        $siteList = $webdavINI->variable( 'SiteSettings', 'SiteList' );

        // Return the site list.
        return $siteList ;
    }
}
?>
