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
      \reimp
      Restricts the allowed methods to only the subset that this server supports.
    */
    function options( $target )
    {
        // Only a few WebDAV operations are allowed.
        $options = array( 'methods' => array( 'OPTIONS', 'PROPFIND', 'HEAD', 'GET', 'PUT', 'MKCOL', 'MOVE' ) );

        // Return the allowed options.
        return $options;
    }

    /*! Produces the collection content. Builds either the virtual start folder
        with the virtual content folder in it (and additional files). OR: if
        we're browsing within the content folder: it gets the content of the
        target/given folder.
     */
    function getCollectionContent( $collection, $depth )
    {
        // Get the name of the site that is being browsed.
        $currentSite = $this->currentSiteFromPath( $collection );

        // Get rid of the index-file /NVH mode/ and the site name.
        $collection = $this->removeIndexAndSiteName( $collection, $currentSite );

        // Proceed only if the current site is valid.
        if ( $currentSite )
        {
            // Switch to the site being browsed.
            $this->setSiteAccess( $currentSite );

            // Bail if the current user doesn't have the required privileges:
            if ( !$this->userHasAccess() )
            {
                $this->appendLogEntry( "Entries: none ", 'ContentServer::getCollectionContent' );
                return false;
            }

            // If the path starts with "/content":
            if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $collection ) or
                 preg_match( "#^/" . VIRTUAL_MEDIA_FOLDER_NAME . "(.*)$#", $collection ) )
            {
                $this->appendLogEntry( "We're browsing actual content, collection is: $collection", 'ContentServer::getCollectionContent' );
                $entries = $this->fetchContentList( $collection, $depth );
            }
            // We aren't browsing content just yet, show the virtual start folder:
            else
            {
                $this->appendLogEntry( "We're browsing the virtual start folder..", 'ContentServer::getCollectionContent' );
                $entries = $this->fetchVirtualSiteContent( $depth );
            }
        }
        // Else: we're browsing the list of sites.
        else
        {
            $this->appendLogEntry( "We're browsing list of sites..", 'ContentServer::getCollectionContent' );
            // Get the list of sites.
            $entries = $this->fetchSiteListContent( $depth );
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
        $currentSite = $this->currentSiteFromPath( $target );

        // Get rid of the index-file /NVH mode/ and the site name.
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            $this->appendLogEntry( "current site er $currentSite", 'ContentServer::get' );
            // Switch site to the site being browsed.
            $this->setSiteAccess( $currentSite );

            // Bail if the current user doesn't have the required privileges:
            if ( !$this->userHasAccess() )
            {
                return false;
            }

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $target ) )
            {
                $this->appendLogEntry( "attempting to fetch node, target is: $target", 'ContentServer::get' );

                // Attempt to fetch the node the client wants to get.
                $node = $this->fetchNodeByTranslation( $target );

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
        $this->appendLogEntry( "target is: $target", 'ContentServer::put' );

        // Bail if the current user doesn't have the required privileges:
        if ( !$this->userHasAccess() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // Get the name of the site that is being browsed.
        $currentSite = $this->currentSiteFromPath( $target );

        // Get rid of the index-file /NVH mode/ and the site name.
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        // Check if node already exists
        $existingNode = $this->fetchNodeByTranslation( $target );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            $this->appendLogEntry( "current site is: $currentSite", 'ContentServer::put' );

            // Switch to the site being browsed.
            $this->setSiteAccess( $currentSite );

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $target ) )
            {
                $this->appendLogEntry( "inside virtual content folder, ok", 'ContentServer::put' );

                // Attempt to get the parent node of the target.
                $parentNode = $this->fetchParentNodeByTranslation( $target );

                // Proceed only if the parentNode is OK:
                if ( $parentNode != null )
                {
                    // Get the node ID of the node.
                    $parentNodeID = $parentNode->attribute( 'node_id' );

                    // Attempt to determine the mime type of the file that has been uploaded.
                    $mimeObj = new eZMimeType();
                    $mime = $mimeObj->mimeTypeFor( false, strtolower( basename( $target ) ) );

                    // Grab settings from the ini file:
                    $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
                    $iniSettings = $webdavINI->variable( 'PutSettings', 'MIME' );
                    $defaultObjectType = $webdavINI->variable( 'PutSettings', 'DefaultClass' );

                    $attributeID = false;
                    if ( $mime )
                    {
                        // Extract elements from the mime array.
                        list( $type, $extension ) = split ("/", $mime );
                    }
                    else
                    {
                        $mime = "application/octet-stream";
                    }

                    // Attempt to determine the attribute that should be used for display:
                    $objectType = false;
                    if ( isset( $iniSettings[$mime] ) )
                        $objectType = $iniSettings[$mime];

                    if ( !$objectType )
                    {
                        $objectType = $defaultObjectType;
                    }

                    switch ( $objectType )
                    {
                        case 'image':
                        {
                            return $this->putImage( $target, $tempFile, $parentNodeID, $existingNode );
                        }
                        break;

                        default:
                        {
                            $this->appendLogEntry( "TRYING TO PUT FILE", 'ContentServer::put' );

                            return $this->putFile( $target, $tempFile, $parentNodeID, $existingNode );
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
//        if ( !$this->userHasAccess() )
//        {
//            return false;
//        }

        // Get the name of the site that is being browsed.
        $currentSite = $this->currentSiteFromPath( $target );

        // Get rid of the index-file /NVH mode/ and the site name.
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        // Proceed only if the current site is valid:
        if ( $currentSite != "" )
        {
            // Switch to the site being browsed.
            $this->setSiteAccess( $currentSite );

            // If the path starts with "/content":
            if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $target ) or
                 preg_match( "#^/" . VIRTUAL_MEDIA_FOLDER_NAME . "(.*)$#", $target ) )
            {
                // Check if collection already exists
                $node = $this->fetchNodeByTranslation( $target );
                if ( $node )
                    return EZ_WEBDAV_FAILED_EXISTS;

                // Attempt to get the parent node.
                $parentNode = $this->fetchParentNodeByTranslation( $target );

                $this->appendLogEntry( "Target is: $target", 'ContentServer::mkcol' );

                // If no node: use the root node.
                if ( !$parentNode )
                {
                    return EZ_WEBDAV_FAILED_NOT_FOUND;
                }

                // Attempt to create the folder.
                return $this->createFolder( $parentNode, $target );
            }
            // Else: somebody is trying to make a folder in a virtual dir: no deal!
            else
            {
                return EZ_WEBDAV_FAILED_FORBIDDEN;
            }
        }
    }

    /*!
      Removes the object from the node tree and leaves it in the trash.
    */
    function delete( $target )
    {
        $currentSite = $this->currentSiteFromPath( $target );
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        $this->appendLogEntry( "Target is: $target", 'ContentServer::delete' );

        if ( $currentSite )
        {
            $this->setSiteAccess( $currentSite );

            $node = $this->fetchNodeByTranslation( $target );

            if ( $node != null )
            {
                // Check if the user has permissions to remove the object
                if ( !$node->canRemove() )
                    return EZ_WEBDAV_FAILED_FORBIDDEN;

                $this->appendLogEntry( "Removing node: $target", 'ContentServer::delete' );
                $node->remove();
            }
            else
            {
                return EZ_WEBDAV_FAILED_NOT_FOUND;
            }
        }
        return EZ_WEBDAV_OK;
    }

    /*!
     */
    function move( $source, $destination )
    {
        // Bail if the current user doesn't have the required privileges:
        if ( !$this->userHasAccess() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // Get the name of the site that is being browsed.
        $currentSite = $this->currentSiteFromPath( $source );

        // Get rid of the index-file /NVH mode/ and the site name.
        $source = $this->removeIndexAndSiteName( $source, $currentSite );
        $destination = $this->removeIndexAndSiteName( $destination, $currentSite );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $source = preg_replace( "/\.\w*$/", "", $source );
        $source = preg_replace( "#\/$#", "", $source );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            // Switch to the site being browsed.
            $this->setSiteAccess( $currentSite );

            // If the path starts with "/content":
            if ( preg_match( "#^/".VIRTUAL_CONTENT_FOLDER_NAME."(.*)$#", $source ) )
            {
                // Attempt to get the node.
                $sourceNode = $this->fetchNodeByTranslation ( $source );

                // Proceed only if we were able to find the node:
                if ( $sourceNode != null )
                {
                    // Get the object.
                    $object = $sourceNode->attribute( 'object' );

                    // Get rid of possible extensions, remove .jpeg .txt .html etc..
                    $destination = preg_replace( "/\.\w*$/", "", $destination );
                    $destination = preg_replace( "#\/$#", "", $destination );

                    $destinationNode = $this->fetchNodeByTranslation( $destination );

                    $this->appendLogEntry( "Destination: $destination", 'ContentServer::move' );

                    if ( $destinationNode )
                    {
                        $this->appendLogEntry( "Source: $source $sourceNode   Destination: " . $destination . " :: " .  dirname( $destination ) . " " .  $destinationNode , 'ContentServer::move');

                        // Move the node
                        $sourceNode->move( $destinationNode->attribute( 'node_id' ) );
                        $newNode = eZContentObjectTreeNode::fetchNode( $object->attribute( 'id' ), $destinationNode->attribute( 'node_id' ) );
                        if ( $newNode )
                        {
                            $newNode->updateSubTreePath();
                            if ( $newNode->attribute( 'main_node_id' ) == $newNode->attribute( 'node_id' ) )
                            {
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
                }
            }
            // Else: somebody is trying to move stuff in a virtual dir: no deal!
            else
            {
                return EZ_WEBDAV_FAILED_FORBIDDEN;
            }
        }
    }

    /*!
      Detects a possible/valid site-name in start of a path.
      Returns the name of the site that was detected.
    */
    function currentSiteFromPath( $path )
    {
        $this->appendLogEntry( "path is: $path", 'ContentServer::currentSitePath' );

        $indexDir = eZSys::indexDir();

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^$indexDir(.+)$#", $path, $matches ) )
        {
            $path = $matches[1];
        }

        $this->appendLogEntry( "path is: $path", 'ContentServer::currentSitePath' );

        // Get the list of available sites.
        $sites = $this->availableSites();

        // For each site:
        foreach ( $sites as $site )
        {
            // Check if given path starts with this site-name, if so: return it.
            if ( preg_match( "#^/$site(.*)$#", $path, $matches ) )
            {
                return $site ;
            }
        }

        $this->appendLogEntry( "no valid site was found..", 'ContentServer::currentSitePath' );
        // No valid site was found!
        return false ;
    }

    /*!
      Removes the index file /if in NVH mode/ and
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

    /*!
       Attempts to fetch a possible/existing node by translating
       the inputted string/path to a node-number.
    */
    function fetchNodeByTranslation( $nodePathString )
    {
        $this->appendLogEntry( "nodepathstring: $nodePathString", 'ContentServer::fetchNodeByTranslation' );
        $indexDir = eZSys::indexDir();

        $this->appendLogEntry( "indexDir: $indexDir", 'ContentServer::fetchNodeByTranslation' );

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^$indexDir/(.+)$#", $nodePathString, $matches ) )
        {
            $nodePathString = $matches[1];
        }

        $this->appendLogEntry( "nodepathstring0: $nodePathString", 'ContentServer::fetchNodeByTranslation' );
        // If exists: remove the content folder from the path.

        if ( preg_match( "#^" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
        {
            $this->appendLogEntry( "REMOVING CONTENT FOLDER: nodepathstring1: $nodePathString", 'ContentServer::fetchNodeByTranslation' );
            $nodePathString = $matches[1];
        }

        if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
        {
            $this->appendLogEntry( "REMOVING CONTENT FOLDER: nodepathstring2: $nodePathString", 'ContentServer::fetchNodeByTranslation' );
            $nodePathString = $matches[1];
        }

        $this->appendLogEntry( "nodepathstring1: $nodePathString", 'ContentServer::fetchNodeByTranslation' );
        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );

        // Remove the first slash if it exists.
        if ( isset( $nodePathString[1] ) and $nodePathString[1] == '/' )
        {
            $nodePathString = substr( $nodePathString, 1 );
        }

        //
        $nodePathString = eZURLAlias::convertPathToAlias( $nodePathString );

        //
        $this->appendLogEntry( "nodepathstring2: $nodePathString", 'ContentServer::fetchNodeByTranslation' );

        // Attempt to translate the URL to something like "/content/view/full/84".
        $translateResult =& eZURLAlias::translate( $nodePathString );

        $this->appendLogEntry( "nodepathstring3: $nodePathString", 'ContentServer::fetchNodeByTranslation' );

        if ( !$translateResult )
        {
            $this->appendLogEntry( "Node translation failed: $nodePathString", 'ContentServer::fetchNodeByTranslation' );
        }

        // Get the ID of the node (which is the last part of the translated path).
        if ( preg_match( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
        {
            $nodeID = $matches[1];
            $this->appendLogEntry( "nodeID: $nodeID", 'ContentServer::fetchNodeByTranslation' );
        }
        else
        {
            $this->appendLogEntry( "no nodeID", 'ContentServer::fetchNodeByTranslation' );
            return false;
        }

        // Attempt to fetch the node.
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Return the node.
        return $node;
    }

    /*!
       Attempts to fetch a possible node by translating
       the inputted string/path to a node-number. The last
       section of the path is removed before the actual
       translation: hence, the PARENT node is returned.
    */
    function fetchParentNodeByTranslation( $nodePathString )
    {
        $this->appendLogEntry( "nodePathString1: $nodePathString", 'ContentServer::fetchParentNodeByTranslation' );
        $indexDir = eZSys::indexDir();

        $this->appendLogEntry( "indexDir: $indexDir", 'ContentServer::fetchParentNodeByTranslation' );

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^$indexDir/(.+)$#", $nodePathString, $matches ) )
        {
            $nodePathString = $matches[1];
        }

        if ( preg_match( "#^" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
        {
            $this->appendLogEntry( "REMOVING CONTENT FOLDER: nodepathstring1: $nodePathString", 'ContentServer::fetchParentNodeByTranslation' );
            $nodePathString = $matches[1];
        }

        if ( preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "/(.*)$#", $nodePathString, $matches ) )
        {
            $this->appendLogEntry( "REMOVING CONTENT FOLDER: nodepathstring2: $nodePathString", 'ContentServer::fetchParentNodeByTranslation' );
            $nodePathString = $matches[1];
        }

        // Strip extensions. E.g. .jpg
        $nodePathString = preg_replace( "/\.\w*$/", "", $nodePathString );
        $nodePathString = preg_replace( "#\/$#", "", $nodePathString );

        $this->appendLogEntry( "nodePathString2: $nodePathString", 'ContentServer::fetchParentNodeByTranslation' );

        // Remove the first slash if it exists.
        if ( isset( $nodePathString[1] ) and $nodePathString[1] == '/' )
        {
            $nodePathString = substr( $nodePathString, 1 );
        }

        $this->appendLogEntry( "nodePathString3: $nodePathString", 'ContentServer::fetchParentNodeByTranslation' );

        // Get rid of the last part; strip away last slash and anything behind it.
        $cut = strrpos( $nodePathString, '/' );
        $nodePathString = substr( $nodePathString, 0, $cut );

        $this->appendLogEntry( "nodePathString4: $nodePathString", 'ContentServer::fetchParentNodeByTranslation' );
        //
        $nodePathString = eZURLAlias::convertPathToAlias( $nodePathString );

        // Attempt to translate the URL to something like "/content/view/full/84".
        $translateResult =& eZURLAlias::translate( $nodePathString );

        // Get the ID of the node (which is the last part of the translated path).
        if ( preg_match( "#^content/view/full/([0-9]+)$#", $nodePathString, $matches ) )
        {
            $nodeID = $matches[1];
            $this->appendLogEntry( "nodeID: $nodeID", 'ContentServer::fetchParentNodeByTranslation' );
        }
        else
        {
            $this->appendLogEntry( "Root node", 'ContentServer::fetchParentNodeByTranslation' );
            $nodeID = 2;
        }

        // Attempt to fetch the node.
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Return the node.
        return $node;
    }

    /*!
      Builds and returns the content of the virtual start fodler
      for a site. The virtual startfolder is an intermediate step
      between the site-list and actual content. This directory
      contains the "content" folder which leads to the site's
      actual content.
    */
    function fetchVirtualSiteContent( $depth )
    {
        $this->appendLogEntry( "Script URL.." . $_SERVER["SCRIPT_URL"], 'ContentServer::fetchVirtualSiteContent' );
        $this->appendLogEntry( "href=" . $contentEntry["href"], 'ContentServer::fetchVirtualSiteContent' );
        // Location of the info file.
        $infoFile = $_SERVER['DOCUMENT_ROOT'] . '/' . VIRTUAL_INFO_FILE_NAME;

        // Always add the current collection
        $contentEntry = array ();
        $contentEntry["name"]     = $_SERVER["SCRIPT_URL"];
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = 'httpd/unix-directory';
        $contentEntry["ctime"]    = filectime( 'var' );
        $contentEntry["mtime"]    = filemtime( 'var' );
        $contentEntry["href"]     = $_SERVER["SCRIPT_URL"];
        $startFolderEntries[] = $contentEntry;

        if ( $depth > 0 )
        {
            $scriptURL = $_SERVER["SCRIPT_URL"];
            if ( $scriptURL{strlen($scriptURL)} != "/" )
                $scriptURL .= "/";

            // Set up attributes for the virtual content folder:
            $contentEntry = array ();
            $contentEntry["name"]     = VIRTUAL_CONTENT_FOLDER_NAME;
            $contentEntry["size"]     = 0;
            $contentEntry["mimetype"] = 'httpd/unix-directory';
            $contentEntry["ctime"]    = filectime( 'var' );
            $contentEntry["mtime"]    = filemtime( 'var' );
            $contentEntry["href"]     = $scriptURL . $contentEntry["name"];

            // Set up attributes for the virtual media folder:
            $mediaEntry = array ();
            $mediaEntry["name"]     = VIRTUAL_MEDIA_FOLDER_NAME;
            $mediaEntry["size"]     = 0;
            $mediaEntry["mimetype"] = 'httpd/unix-directory';
            $mediaEntry["ctime"]    = filectime( 'var' );
            $mediaEntry["mtime"]    = filemtime( 'var' );
            $mediaEntry["href"]     = $scriptURL . $mediaEntry["name"];

            // If the info file actually exists: set up attributes and
            // include it in the entries-to-be served array:
            if ( file_exists ( $infoFile ) )
            {
                $infoEntry = array();
                $infoEntry["name"]     = basename( $infoFile );
                $infoEntry["size"]     = filesize( $infoFile );
                $infoEntry["mimetype"] = 'text/plain';
                $infoEntry["ctime"]    = filectime( $infoFile );
                $infoEntry["mtime"]    = filemtime( $infoFile );
                $infoEntry["href"]     = $scriptURL . $infoEntry["name"];

                // Include the info-file's attributes.
                $startFolderEntries[] = $contentEntry;
                $startFolderEntries[] = $mediaEntry;
                $startFolderEntries[] = $infoEntry;
            }
            // Else: info file can't be located, skip it...
            else
            {
                $startFolderEntries[] = $contentEntry;
                $startFolderEntries[] = $mediaEntry;
            }
        }

        return $startFolderEntries;
    }

    /*!
      Builds a content-list of available sites and returns it.
    */
    function fetchSiteListContent( $depth )
    {
        // At the end: we'll return an array of entry-arrays.
        $siteListFolderEntries = array();

        // An entry consists of several attributes (name, size, etc).
        $contentEntry = array();
        $siteListFolderEntries = array();

        // Set up attributes for the virtual site-list folder:
        $contentEntry["name"]     = '/';
        $contentEntry["href"]     = $_SERVER['SCRIPT_URI'];
        $contentEntry["size"]     = 0;
        $contentEntry["mimetype"] = 'httpd/unix-directory';
        $contentEntry["ctime"]    = filectime( 'var' );
        $contentEntry["mtime"]    = filemtime( 'var' );

        $siteListFolderEntries[] = $contentEntry;

        if ( $depth > 0 )
        {
            // Get list of available sites.
            $sites = $this->availableSites();

            // For all available sites:
            foreach ( $sites as $site )
            {
                // Set up attributes for the virtual site-list folder:
                $contentEntry["name"]     = $_SERVER['SCRIPT_URI'].$site;
                $contentEntry["size"]     = 0;
                $contentEntry["mimetype"] = 'httpd/unix-directory';
                $contentEntry["ctime"]    = filectime( 'var' );
                $contentEntry["mtime"]    = filemtime( 'var' );

                if ( $_SERVER["SCRIPT_URL"] == '/' )
                {
                    $contentEntry["href"] = $contentEntry["name"];
                }
                else
                {
                    $contentEntry["href"] = $_SERVER["SCRIPT_URL"] . $contentEntry["name"];
                }

                $siteListFolderEntries[] = $contentEntry;
            }
        }

        return $siteListFolderEntries;
    }

    /*!
      Creates a new folder under the given target node.
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

    /*!
      Gets and returns the content of an actual node.
      List of other nodes belonging to the target node
      (one level below it) will be returned.
    */
    function fetchContentList( $target, $depth )
    {
        $this->appendLogEntry( "target is: $target", 'ContentServer::fetchContentList' );
        // Attempt to fetch the desired node.
        $node = $this->fetchNodeByTranslation( $target );

        // If unable to fetch the node: get the content/root folder instead.
        if ( !$node )
        {
            $this->appendLogEntry( "unknown node: $target, using root node", 'ContentServer::fetchContentList' );
            $node =& eZContentObjectTreeNode::fetch( 2 );
        }

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
        // Trim the name of the node, it is in some casese whitespace in eZ publish
        $entry["name"] = trim( $node->attribute( 'name' ) );
        $entry["size"] = 0;
        $entry["mimetype"] = 'httpd/unix-directory';
        $entry["ctime"] = $object->attribute( 'published' );
        $entry["mtime"] = $object->attribute( 'modified' );

        // Attempt to determine the attribute that should be used for display:
        $attributeID = $iniSettings[$classIdentifier];

        // Only proceed to the special cases if the
        // attribute is actually defined in the ini file:
        if ( $attributeID )
        {
            $this->appendLogEntry( "inside if attributeID", 'ContentServer::fetchNodeInfo' );

            // Get the object's datamap.
            $dataMap =& $object->dataMap();

            $attribute =& $dataMap[$attributeID];

            // Check if the attribute settings are valid
            if ( $attribute )
            {
                $attributeDataTypeIdentifier = $attribute->attribute( 'data_type_string' );

                switch ( $attributeDataTypeIdentifier )
                {
                    // If the file being uploaded is an image:
                    case 'ezimage':
                    {
                        $attributeContent =& $attribute->attribute( 'content' );
                        $originalAlias =& $attributeContent->attribute( 'original' );
                        $mime = $originalAlias['mime_type'];
                        $originalName = $originalAlias['original_filename'];
                        $imageFile = $originalAlias['url'];
                        $suffix = $originalAlias['suffix'];

                        $entry["size"] = filesize( $imageFile );
                        $entry["mimetype"] = $mime;
                        if ( strlen( $suffix ) > 0 )
                            $entry["name"] .= '.' . $suffix;
                        $entry["href"] = '/' . $imageFile;
                    }break;


                    // If the file being uploaded is a regular file:
                    case 'ezbinaryfile':
                    {
                        $attributeContent =& $attribute->attribute( 'content' );
                        $mime = $attributeContent->attribute( 'mime_type' );
                        $originalName = $attributeContent->attribute( 'original_filename' );
                        $fileLocation = $attributeContent->attribute( 'filepath' );
                        $pathInfo = pathinfo( $originalName );
                        $extension = $pathInfo["extension"];

                        $entry["size"] = $attributeContent->attribute( 'filesize' );
                        $entry["mimetype"] = $mime;
                        if ( strlen( $extension ) > 0 )
                            $entry["name"] .= '.' . $extension;
                        $entry["ctime"] = filectime( $fileLocation );
                        $entry["mtime"] = filemtime( $fileLocation );
                    }break;

                    default:
                    {
                        $this->appendLogEntry( "datatype = " . $attributeDataTypeIdentifier, 'ContentServer::fetchNodeInfo' );
                    } break;
                }
            }
        }

        $scriptURL = $_SERVER["SCRIPT_URL"];
        if ( $scriptURL{strlen($scriptURL)} != "/" )
            $scriptURL .= "/";

        // Set the href attribute (note that it doesn't just equal the name).
        if ( !isset( $entry['href'] ) )
            $entry["href"] = $scriptURL . $entry['name'];

        $this->appendLogEntry( "Name: '" . $entry['name']  . "' - '" . $node->attribute( 'name' ) . "'", 'ContentServer::fetchNodeInfo' );
        $this->appendLogEntry( "Href: " . $entry['href'], 'ContentServer::fetchNodeInfo' );
        // Return array of attributes/properties (name, size, mime, times, etc.).
        return $entry;
    }

    /*!
      Sets/changes the current site(access) to a given site.
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

    /*!
      Checks if the current user has administrator privileges.
      This is done by checking the roles assigned to the user.
      We're looking for the star "*").
      The function returns TRUE if the user has admin rights,
      and FALSE if not.
    */
    function userHasAccess()
    {
        $user = eZUser::currentUser();
        $userObject =& $user->attribute( 'contentobject' );
        $this->appendLogEntry( "username:" . $userObject->attribute( 'name' ), 'ContentServer::userHasAccess' );

        return true;
        // Todo: implement webdav permissions
//    $status = $user->hasAccessTo( '*', '*' );
//
//    return $status['accessWord'] == 'yes';
    }

    /*!
      Creates a new instance of a file object, sets the attributes.
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
    function putImage( $target, $tempFile, $parentNodeID, $existingNode )
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

        if ( !is_object( $existingNode ) )
        {
            $objectVersion = 1;

            // Attempt to fetch the parent node.
            $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
            if ( !$parentNode )
            {
                $sectionID = 1;
            }
            else
            {
                $sectionID = $parentNode->ContentObject->SectionID;
            }

            // Create object by user id.
            $contentObject =& $class->instantiate( $userID, $sectionID );

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

            $imageCreatedTime = mktime();
            $imageModifiedTime = mktime();

            $version =& $contentObject->version( 1 );
            $version->setAttribute( 'modified', $imageModifiedTime );
            $version->setAttribute( 'created', $imageCreatedTime );
            $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
            $version->store();

            $objectID = $contentObject->attribute( 'id' );
            $dataMap = $version->dataMap();

            $storeInDBName = preg_replace( "/\.\w*$/", "", $imageFileName );
            $storeInDBName = preg_replace( "#\/$#", "", $storeInDBName );

            $dataMap['name']->setAttribute( 'data_text', $storeInDBName );
            $dataMap['name']->store();

            $imageHandler =& $dataMap['image']->content();
            $imageIsStored = $imageHandler->initializeFromFile( $tempFile, false, $imageOriginalFileName );
            $imageHandler->store();
        }
        else
        {
            $object = $existingNode->attribute( 'object' );
            $newVersion = $object->createNewVersion();

            $objectVersion = $newVersion->attribute( 'version' );
            $objectID = $newVersion->attribute( 'contentobject_id' );

            $dataMap = $newVersion->dataMap();

            $imageHandler = $dataMap['image']->content();
            $imageIsStored = $imageHandler->initializeFromFile( $tempFile, false, $imageOriginalFileName );
            $imageHandler->store();
        }

        $this->appendLogEntry( "result: " . ( $imageStored ? 'true' : 'false' ), 'ContentServer::putImage' );

        if ( $imageIsStored )
        {
            //
            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID, 'version' => $objectVersion ) );

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
    function putFile( $target, $tempFile, $parentNodeID, $existingNode )
    {
        // Attempt to get the current user ID.
        $user = eZUser::currentUser();
        $userID = $user->id();

        $this->appendLogEntry( "User is: $userID", 'ContentServer::putFile' );

        // Fetch the file class.
        $class =& eZContentClass::fetch( 12 );

        // Attempt to fetch the parent node.
        $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( !$parentNode )
        {
            $sectionID = 1;
        }
        else
        {
            $sectionID = $parentNode->ContentObject->SectionID;
        }

        $this->appendLogEntry( "parent node $parentNodeID $existingNode", 'ContentServer::putFile' );
        if ( !is_object( $existingNode ) )
        {
            $objectVersion = 1;

            $this->appendLogEntry( "existing node $existingNode", 'ContentServer::putFile' );

            // Create object by user id.
            $object =& $class->instantiate( $userID, $sectionID );

            $nodeAssignment =& eZNodeAssignment::create( array(
                                                             'contentobject_id' => $object->attribute( 'id' ),
                                                             'contentobject_version' => $object->attribute( 'current_version' ),
                                                             'parent_node' => $parentNodeID,
                                                             'sort_field' => 2,
                                                             'sort_order' => 0,
                                                             'is_main' => 1
                                                             )
                                                         );
            $nodeAssignment->store();

            $version =& $object->version( 1 );
            $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
            $version->store();

            $objectID = $object->attribute( 'id' );
            $dataMap =& $version->dataMap();

            $storeInDBName = basename( $target );
            $storeInDBName = preg_replace( "/\.\w*$/", "", $storeInDBName );
            $storeInDBName = preg_replace( "#\/$#", "", $storeInDBName );

            $dataMap['name']->setAttribute( 'data_text', $storeInDBName );
            $dataMap['name']->store();

            // Attempt to store the file object in the DB and copy the file.
            $fileIsStored = $this->storeFile( $tempFile, basename( $target ), $dataMap['file'] );
            $dataMap['file']->store();
        }
        else
        {
            $object = $existingNode->attribute( 'object' );
            $newVersion = $object->createNewVersion();

            $objectVersion = $newVersion->attribute( 'version' );
            $objectID = $newVersion->attribute( 'contentobject_id' );

            $dataMap = $newVersion->dataMap();

            $fileIsStored = $this->storeFile( $tempFile, basename( $target ), $dataMap['file'] );
            $dataMap['file']->store();
        }

        if ( $fileIsStored )
        {
            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID, 'version' => $objectVersion ) );

            return EZ_WEBDAV_OK_CREATED;
        }
        else
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }
    }
}
?>
