<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
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

include_once( 'kernel/classes/eztrigger.php' );
$Module =& $Params["Module"];
include_once( 'kernel/content/node_edit.php' );
initializeNodeEdit( $Module );
include_once( 'kernel/content/relation_edit.php' );
initializeRelationEdit( $Module );
$obj =& eZContentObject::fetch( $ObjectID );
if ( !$obj )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$obj->attribute( 'can_edit' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( !function_exists ( 'checkForExistingVersion'  ) )
{
    function checkForExistingVersion( &$module, $objectID, &$editVersion, &$editLanguage )
    {
        $requireNewVersion = false;
        $object =& eZContentObject::fetch( $objectID );
        if ( $object === null )
            return;

        $user =& eZUser::currentUser();
        $version = null;
        if ( is_numeric( $editVersion ) )
        {
            $version =& $object->version( $editVersion );
            if ( $version === null )
            {
                $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }
        else
        {
            $userID = $user->id();
            $version = eZContentObjectVersion::fetchUserDraft( $objectID, $userID );
        }

        if ( $version !== null )
        {
            $currentVersion = $object->currentVersion();
            /*
            print( "have draft" );
            // Check if the published version is newer than the draft
            if ( $version->attribute( 'modified' ) < $currentVersion->attribute( 'modified' ) )
            {
                print( "Draft is older than currentversion" );
            }
            else
            {
                print( "Draft is newer than currentversion" );
            }
            print( $version->attribute( 'modified' ) . "<br>" );
            print( $currentVersion->attribute( 'modified' ) . "<br>" );
            */
            if ( $version->attribute( 'status' ) != EZ_VERSION_STATUS_DRAFT or
                 $version->attribute( 'creator_id' ) != $user->id() )
            {
                $module->redirectToView( 'versions', array( $objectID, $version->attribute( "version" ), $editLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
            if ( $version->attribute( 'version' ) != $editVersion )
            {
                $module->redirectToView( "edit", array( $objectID, $version->attribute( "version" ), $editLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }
        else
            $requireNewVersion = true;
        if ( $requireNewVersion )
        {
            // Fetch and create new version
            if ( !$object->attribute( 'can_edit' ) )
            {
                $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
            $version =& $object->createNewVersion();

            $module->redirectToView( "edit", array( $objectID, $version->attribute( "version" ), $editLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
    }
}
$Module->addHook( 'pre_fetch', 'checkForExistingVersion' );

if ( !function_exists ( 'registerSearchObject'  ) )
{
    function registerSearchObject( &$module, $parameters )
    {
        eZDebug::createAccumulatorGroup( 'search_total', 'Search Total' );

        include_once( "kernel/classes/ezsearch.php" );
        $object =& $parameters[1];
        // Register the object in the search engine.
        eZDebug::accumulatorStart( 'remove_object', 'search_total', 'remove object' );
        eZSearch::removeObject( $object );
        eZDebug::accumulatorStop( 'remove_object', 'search_total', 'remove object' );
        eZDebug::accumulatorStart( 'add_object', 'search_total', 'add object' );
        eZSearch::addObject( $object );
        eZDebug::accumulatorStop( 'add_object', 'search_total', 'add object' );
    }
}
$Module->addHook( 'post_publish', 'registerSearchObject', 1, false );

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, $EditLanguage )
    {
        if ( $module->isCurrentAction( 'Preview' ) )
        {
            $module->redirectToView( 'versionview', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Translate' ) )
        {
            $module->redirectToView( 'translate', array( $object->attribute('id'), $EditVersion ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'VersionEdit' ) )
        {
            $module->redirectToView( 'versions', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'EditLanguage' ) )
        {
            if ( $module->hasActionParameter( 'SelectedLanguage' ) )
            {
                $EditLanguage = $module->actionParameter( 'SelectedLanguage' );
                if ( $EditLanguage == eZContentObject::defaultLanguage() )
                    $EditLanguage = false;
                $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }

        if ( $module->isCurrentAction( 'Discard' ) )
        {
            /*  $http =& eZHttpTool::instance();
            $module->redirectTo( '/content/view/full/2/' );

            $objectID = $object->attribute( 'id' );
            $versionCount= $object->getVersionCount();
            $db =& eZDB::instance();
            $db->query( "DELETE FROM ezcontentobject_link
		                 WHERE from_contentobject_id=$objectID AND from_contentobject_version=$EditVersion" );
            $db->query( "DELETE FROM eznode_assignment
		                 WHERE contentobject_id=$objectID AND contentobject_version=$EditVersion" );
            $version->remove();
            foreach ( $contentObjectAttributes as $contentObjectAttribute )
            {
                $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
                $version = $contentObjectAttribute->attribute( 'version' );
                if ( $version == $EditVersion )
                {
                    $contentObjectAttribute->remove( $objectAttributeID, $version );
                }
            }
            if ( $versionCount == 1 )
            {
                $object->remove();
            }
            if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $object->attribute( 'id' ) )
            {
                $parentArray = $http->sessionVariable( 'ParentObject' );
                $parentURL = $module->redirectionURI( 'content', 'edit', $parentArray );
                $http->removeSessionVariable( 'ParentObject' );
                $http->removeSessionVariable( 'NewObjectID' );
                $module->redirectTo( $parentURL );
            }
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;*/

            $http =& eZHTTPTool::instance();
            $objectID = $object->attribute( 'id' );
            $http->setSessionVariable( 'DiscardObjectID', $objectID );
            $http->setSessionVariable( 'DiscardObjectVersion', $EditVersion );
            $http->setSessionVariable( 'DiscardObjectLanguage', $EditLanguage );
            $module->redirectTo( $module->functionURI( 'removeeditversion' ) . '/' );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $user =& eZUser::currentUser();
            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            eZDebug::accumulatorStart( 'publish', '', 'publish' );
            $oldObjectName = $object->name();
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $version->attribute( 'version' ) ) );
            eZDebug::accumulatorStop( 'publish' );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );

            $newObjectName = $object->name();

            $http =& eZHttpTool::instance();

            $node = $object->mainNode();
            $hasRedirected = false;
            if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $object->attribute( 'id' ) )
            {
                $parentArray = $http->sessionVariable( 'ParentObject' );
                $parentURL = $module->redirectionURI( 'content', 'edit', $parentArray );
                $parentObject = eZContentObject::fetch( $parentArray[0] );
                $parentObject->addContentObjectRelation( $object->attribute( 'id' ), $parentArray[1] );
                $http->removeSessionVariable( 'ParentObject' );
                $http->removeSessionVariable( 'NewObjectID' );
                $module->redirectTo( $parentURL );
                $hasRedirected = true;
            }
            if ( !$hasRedirected )
            {
                if ( $node !== null )
                {
                    $parentNode = $node->attribute( 'parent_node_id' );
                    if ( $parentNode == 1 )
                        $parentNode = 2;
                    $module->redirectToView( 'view', array( 'full', $parentNode ) );
                }
                else
                {
                    $module->redirectToView( 'view', array( 'sitemap', 2 ) );
                }
            }
            $ini =& eZINI::instance();
            $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

            eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );
            if ( $viewCacheEnabled )
            {

                include_once( 'kernel/classes/ezcontentcache.php' );
                $nodeList = array();

                $parentNodes =& $object->parentNodes( $EditVersion );
                foreach ( array_keys( $parentNodes ) as $parentNodeKey )
                {
                    $parentNode =& $parentNodes[$parentNodeKey];
                    $nodeList[] = $parentNode->attribute( 'node_id' );
                }

                $assignedNodes =& $object->assignedNodes();
                foreach ( array_keys( $assignedNodes ) as $assignedNodeKey )
                {
                    $assignedNode =& $assignedNodes[$assignedNodeKey];
                    $nodeList[] = $assignedNode->attribute( 'node_id' );

                    if ( $oldObjectName != $newObjectName )
                    {
                        $children =& eZContentObjectTreeNode::subTree( false, $assignedNode->attribute( 'node_id' ) );
                        foreach ( array_keys( $children ) as $childKey )
                        {
                            $child =& $children[$childKey];
                            $nodeList[] = $child->attribute( 'node_id' );
                        }
                    }
                }
                $relatedObjects =& $object->contentObjectListRelatingThis();
                foreach ( array_keys( $relatedObjects ) as $relatedObjectKey )
                {
                    $relatedObject =& $relatedObjects[$relatedObjectKey];
                    $assignedNodes =& $relatedObject->assignedNodes();
                    foreach ( array_keys( $assignedNodes ) as $assignedNodeKey )
                    {
                        $assignedNode =& $assignedNodes[$assignedNodeKey];
                        $nodeList[] = $assignedNode->attribute( 'node_id' );
                    }
                }
                eZDebug::writeDebug( count( $nodeList), "count in nodeList " );
                eZDebug::accumulatorStart( 'node_cleanup', '', 'Node cleanup' );
                if ( eZContentCache::cleanup( $nodeList ) )
                {
//                     eZDebug::writeDebug( 'cache cleaned up', 'content' );
                }
                eZDebug::accumulatorStop( 'node_cleanup' );

            }
            eZDebug::accumulatorStop( 'check_cache' );
        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );
$includeResult = include( 'kernel/content/attribute_edit.php' );

if ( $includeResult != 1 )
    return $includeResult;

?>
