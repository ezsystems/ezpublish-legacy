<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
                $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
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
                $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
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
        include_once( "kernel/classes/ezsearch.php" );
        $object =& $parameters[1];
        // Register the object in the search engine.
        eZSearch::removeObject( $object );
        eZSearch::addObject( $object );
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

        if ( $module->isCurrentAction( 'Cancel' ) )
        {
            $http =& eZHttpTool::instance();
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
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $http =& eZHttpTool::instance();
            $nodeAssignmentList =& $version->attribute( 'node_assignments' );

            $count = 0;
            $user =& eZUser::currentUser();
            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $version->attribute( 'version' ) ) );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );
            if ( $object->attribute( 'main_node_id' ) != null )
            {
                if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $object->attribute( 'id' ) )
                {
                    $parentArray = $http->sessionVariable( 'ParentObject' );
                    $parentURL = $module->redirectionURI( 'content', 'edit', $parentArray );
                    $parentObject = eZContentObject::fetch( $parentArray[0] );
                    $parentObject->addContentObjectRelation( $object->attribute( 'id' ), $parentArray[1] );
                    $http->removeSessionVariable( 'ParentObject' );
                    $http->removeSessionVariable( 'NewObjectID' );
                    $module->redirectTo( $parentURL );
                }
                else
                {
                    $module->redirectToView( 'view', array( 'full', $object->attribute( 'main_node_id' ) ) );
                }
            }
            else
            {
                $module->redirectToView( 'view', array( 'sitemap', 2 ) );
            }
        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );
$includeResult = include( 'kernel/content/attribute_edit.php' );

if ( $includeResult != 1 )
    return $includeResult;

?>
