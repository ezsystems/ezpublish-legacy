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
        $options = array( 'methods' => array( 'OPTIONS', 'PROPFIND', 'HEAD', 'GET', 'PUT', 'MKCOL', 'MOVE' ) );

        return $options;
    }

    /*!
      \reimp
      Produces the collection content. Builds either the virtual start folder
      with the virtual content folder in it (and additional files). OR: if
      we're browsing within the content folder: it gets the content of the
      target/given folder.
    */
    function getCollectionContent( $collection, $depth )
    {
        $currentSite = $this->currentSiteFromPath( $collection );
        $collection = $this->removeIndexAndSiteName( $collection, $currentSite );

        // ** Check level 1 (site)
        if ( !$currentSite )
        {
            // Display the root which contains a list of sites
            $this->appendLogEntry( "We're browsing list of sites..", 'ContentServer::getCollectionContent' );
            $entries = $this->fetchSiteListContent( $depth );
            return $entries;
        }

        // Switch to the site being browsed.
        $this->setSiteAccess( $currentSite );

        if ( !$this->userHasAccess() )
        {
            $this->appendLogEntry( "Entries: none ", 'ContentServer::getCollectionContent' );
            return false;
        }

        // ** Check level 2 (virtual folder)
        if ( !preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $collection ) and
             !preg_match( "#^/" . VIRTUAL_MEDIA_FOLDER_NAME . "(.*)$#", $collection ) )
        {
            // We are inside a site so we display the virtual folder for the site
            $this->appendLogEntry( "We're browsing the virtual start folder..", 'ContentServer::getCollectionContent' );
            $entries = $this->fetchVirtualSiteContent( $depth );
            return $entries;
        }

        // ** Check level 3 (inside a content tree)
        $this->appendLogEntry( "We're browsing actual content, collection is: $collection", 'ContentServer::getCollectionContent' );
        $entries = $this->fetchContentList( $collection, $depth );
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

        $currentSite = $this->currentSiteFromPath( $target );
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        // ** Check level 1 (site)
        if ( !$currentSite )
        {
            return $result;
        }

        $this->appendLogEntry( "current site: $currentSite", 'ContentServer::get' );
        // Switch site to the site being browsed.
        $this->setSiteAccess( $currentSite );

        if ( !$this->userHasAccess() )
        {
            return false;
        }

        // ** Check level 2 (elements in virtual folder)
        if ( $target == '/' . basename( VIRTUAL_INFO_FILE_NAME ) )
        {
            $result["file"] = VIRTUAL_INFO_FILE_NAME;

            return $result;
        }
        else if ( !preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $target ) )
        {
            // We are not inside the content tree so we cannot fetch anything.
            return false;
        }
        else if ( !preg_match( "#^/" . VIRTUAL_MEDIA_FOLDER_NAME . "(.*)$#", $target ) )
        {
            // We are not inside the media tree so we cannot fetch anything.
            return false;
        }

        // ** Check level 3 (inside a content tree)
        $this->appendLogEntry( "attempting to fetch node, target is: $target", 'ContentServer::get' );

        // Attempt to fetch the node the client wants to get.
        $node = $this->fetchNodeByTranslation( $target );

        // Proceed only if the node is valid:
        if ( $node == null )
        {
            return $result;
        }

        $object = $node->attribute( 'object' );
        $classID = $object->attribute( 'contentclass_id' );

        // Get the content class ID string of the object (image, folder, file, etc.).
        $class =& $object->attribute( 'content_class' );
        $classIdentifier =& $class->attribute( 'identifier' );

        $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
        $iniSettings = $webdavINI->variable( 'GetSettings', 'FileAttribute' );

        // Attempt to determine the attribute that should be used for display:
        $attributeID = $iniSettings[$classIdentifier];

        // Only proceed to the special cases if the
        // attribute is actually defined in the ini file:
        if ( $attributeID )
        {
            $dataMap = $object->dataMap();

            $attribute = $dataMap[$attributeID];
            $attributeContent = $attribute->content();
            $attributeDataTypeString = $attribute->attribute( 'data_type_string' );

            $attributeClass = get_class( $attributeContent );

            switch ( $attributeDataTypeString )
            {
                case 'ezimage':
                {
                    $originalAlias = $attributeContent->attribute( 'original' );
                    $filePath = $originalAlias['url'];
                } break;


                case 'ezbinaryfile':
                {
                    $filePath = $attributeContent->attribute( 'filepath' );
                } break;
            }
        }

        // Make sure file points to the real file found in the attribute
        $result["file"] = $filePath;
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
        $this->appendLogEntry( "target is: $target", 'ContentServer::put' );

        if ( !$this->userHasAccess() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $currentSite = $this->currentSiteFromPath( $target );
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        $existingNode = $this->fetchNodeByTranslation( $target );

        // ** Check level 1 (site)
        if ( !$currentSite )
            return EZ_WEBDAV_FAILED_NOT_FOUND;

        $this->appendLogEntry( "current site is: $currentSite", 'ContentServer::put' );

        // Switch to the site being browsed.
        $this->setSiteAccess( $currentSite );

        // ** Check level 2 (virtual folder)
        if ( !preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $target ) )
        {
            // Virtual directories cannot get new entries
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // ** Check level 3 (inside a content tree)
        $this->appendLogEntry( "inside virtual content folder, ok", 'ContentServer::put' );

        $parentNode = $this->fetchParentNodeByTranslation( $target );
        if ( $parentNode == null )
        {
            // The node does not exist, so we cannot put the file
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $parentNodeID = $parentNode->attribute( 'node_id' );

        // We need the MIME-Type to figure out which content-class we will use
        $mimeInfo = eZMimeType::findByURL( strtolower( basename( $target ) ) );
        $mime = $mimeInfo['name'];

        $webdavINI =& eZINI::instance( WEBDAV_INI_FILE );
        $iniSettings = $webdavINI->variable( 'PutSettings', 'MIME' );
        $defaultObjectType = $webdavINI->variable( 'PutSettings', 'DefaultClass' );

        $attributeID = false;

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
            } break;

            default:
            {
                return $this->putFile( $target, $tempFile, $parentNodeID, $existingNode );
            } break;
        }
        return EZ_WEBDAV_OK;
    }

    /*!
      \reimp
      Tries to create a collection at \a $target. In our case this is a content-class
      of a given type (most likely a folder).
    */
    function mkcol( $target )
    {
        if ( !$this->userHasAccess() )
        {
            return false;
        }

        $currentSite = $this->currentSiteFromPath( $target );
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        // ** Check level 1 (site)
        if ( !$currentSite )
        {
            // Site list cannot get new entries
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // Switch to the site being browsed.
        $this->setSiteAccess( $currentSite );

        // ** Check level 2 (virtual folder)
        if ( !preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $target ) and
             !preg_match( "#^/" . VIRTUAL_MEDIA_FOLDER_NAME . "(.*)$#", $target ) )
        {
            // Virtual directories cannot get new entries
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // ** Check level 3 (inside a content tree)

        // Check if collection already exists
        $node = $this->fetchNodeByTranslation( $target );
        if ( $node )
            return EZ_WEBDAV_FAILED_EXISTS;

        $parentNode = $this->fetchParentNodeByTranslation( $target );

        $this->appendLogEntry( "Target is: $target", 'ContentServer::mkcol' );

        if ( !$parentNode )
        {
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        return $this->createFolder( $parentNode, $target );
    }

    /*!
      \reimp
      Removes the object from the node tree and leaves it in the trash.
    */
    function delete( $target )
    {
        $currentSite = $this->currentSiteFromPath( $target );
        $target = $this->removeIndexAndSiteName( $target, $currentSite );

        $this->appendLogEntry( "Target is: $target", 'ContentServer::delete' );

        // ** Check level 1 (site)
        if ( !$currentSite )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // ** Check level 2 (virtual folder)
        $this->setSiteAccess( $currentSite );

        // ** Check level 3 (inside a content tree)
        $node = $this->fetchNodeByTranslation( $target );

        if ( $node == null )
            return EZ_WEBDAV_FAILED_NOT_FOUND;

        if ( !$node->canRemove() )
            return EZ_WEBDAV_FAILED_FORBIDDEN;

        $this->appendLogEntry( "Removing node: $target", 'ContentServer::delete' );
        $node->remove();
        return EZ_WEBDAV_OK;
    }

    /*!
      \reimp
      Moves the object \a $source to destination \a $destination.
    */
    function move( $source, $destination )
    {
        if ( !$this->userHasAccess() )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        $currentSite = $this->currentSiteFromPath( $source );
        $source = $this->removeIndexAndSiteName( $source, $currentSite );
        $destination = $this->removeIndexAndSiteName( $destination, $currentSite );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $source = preg_replace( "/\.\w*$/", "", $source );
        $source = preg_replace( "#\/$#", "", $source );

        // ** Check level 1 (site)
        if ( !$currentSite )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // Switch to the site being browsed.
        $this->setSiteAccess( $currentSite );

        // ** Check level 2 (virtual folder)
        if ( !preg_match( "#^/" . VIRTUAL_CONTENT_FOLDER_NAME . "(.*)$#", $source ) )
        {
            return EZ_WEBDAV_FAILED_FORBIDDEN;
        }

        // ** Check level 3 (inside a content tree)
        $sourceNode = $this->fetchNodeByTranslation( $source );

        if ( $sourceNode == null )
        {
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        $object = $sourceNode->attribute( 'object' );

        // Get rid of possible extensions, remove .jpeg .txt .html etc..
        $destination = preg_replace( "/\.\w*$/", "", $destination );
        $destination = preg_replace( "#\/$#", "", $destination );

        $destinationNode = $this->fetchNodeByTranslation( $destination );

        $this->appendLogEntry( "Destination: $destination", 'ContentServer::move' );

        if ( !$destinationNode )
        {
            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }

        $this->appendLogEntry( "Source: $source $sourceNode   Destination: " . $destination . " :: " .  dirname( $destination ) . " " .  $destinationNode , 'ContentServer::move');

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

    /*!
     @}
    */

    /*!
      Sets/changes the current site(access) to a \a $site.
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
      The function returns \c true if the user has admin rights,
      and \c false if not.
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
      Detects a possible/valid site-name in start of a path.
      \return The name of the site that was detected or \c false if not site could be detected
    */
    function currentSiteFromPath( $path )
    {
        $this->appendLogEntry( "start path: $path", 'ContentServer::currentSitePath' );

        $indexDir = eZSys::indexDir();

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^$indexDir(.+)$#", $path, $matches ) )
        {
            $path = $matches[1];
        }

        $this->appendLogEntry( "indexdir: $path", 'ContentServer::currentSitePath' );

        // Get the list of available sites.
        $sites = $this->availableSites();

        foreach ( $sites as $site )
        {
            // Check if given path starts with this site-name, if so: return it.
            if ( preg_match( "#^/$site(.*)$#", $path, $matches ) )
            {
                $this->appendLogEntry( "site $site: $path", 'ContentServer::currentSitePath' );
                return $site ;
            }
        }

        $this->appendLogEntry( "no valid site was found..", 'ContentServer::currentSitePath' );
        return false ;
    }

    /*!
      Removes the index file /if in NVH mode/ and the site name from
      the URL \a $targetURI.
      \return The new URL without index and site
    */
    function removeIndexAndSiteName( $targetURI, $currentSite )
    {
        $this->appendLogEntry( "start url: $targetURI", 'ContentServer::removeIndexAndSiteName' );

        // Just in case none of the strings we want to remove exist.
        $newURI = false;
        $indexDir = eZSys::indexDir();

        // Remove indexDir if used in non-virtualhost mode.
        if ( preg_match( "#^$indexDir(.+)$#", $targetURI, $matches ) )
        {
            $newURI = $matches[1];
        }
        $this->appendLogEntry( "indexdir: $newURI", 'ContentServer::removeIndexAndSiteName' );

        // Get rid of the site name:
        if ( preg_match( "#^/$currentSite(.+)$#", $newURI, $matches ) )
        {
            $newURI = $matches[1];
        }
        $this->appendLogEntry( "site: $newURI", 'ContentServer::removeIndexAndSiteName' );

        return $newURI;
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
      Creates a new instance of a file object, sets the attributes.
      Stores the object in the database. The file which was uploaded
      is copied to its final location.
    */
    function storeFile( $fileFileName, $fileOriginalFileName, &$contentObjectAttribute )
    {
        // Get the file/base-name part of the filepath.
        $filename = basename( $fileFileName );

        // Attempt to determine the mime type of the file to be saved.
        $mimeInfo = eZMimeType::findByURL( strtolower( $fileOriginalFileName ) );
        $mime = $mimeInfo['name'];

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
