<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
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

include_once( 'kernel/classes/eztrigger.php' );
include_once( "lib/ezutils/classes/ezini.php" );
$Module =& $Params["Module"];
include_once( 'kernel/content/node_edit.php' );
initializeNodeEdit( $Module );
include_once( 'kernel/content/relation_edit.php' );
initializeRelationEdit( $Module );
$obj =& eZContentObject::fetch( $ObjectID );

if ( !$obj )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

//if ( !$obj->attribute( 'can_edit' ) )
//    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
if ( !$obj->canEdit() )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $obj->accessList( 'edit' ) ) );

$classID = $obj->attribute( 'contentclass_id' );
$class =& eZContentClass::fetch( $classID );
$http =& eZHTTPTool::instance();


if( $http->hasPostVariable( 'CancelDraftButton' ) )
{
   $mainNode =& eZNodeAssignment::fetchForObject( $obj->attribute( 'id' ), $obj->attribute( 'current_version' ), true );

   if( (count( $mainNode )) == 1 )
   {
        $node = $mainNode[0]->attribute( 'node' );
        return $Module->redirectToView( 'view', array( 'full', $node->attribute( 'node_id' ) ) );
   }
   else
   {
        $contentINI =& eZINI::instance( 'content.ini' );
        $rootNodeID = $contentINI->variable( 'NodeSettings', 'RootNode' );
        return $Module->redirectToView( 'view', array( 'full', $rootNodeID ) );
   }

}


if ( $http->hasPostVariable( 'EditButton' ) )
{
    if ( $http->hasPostVariable( 'SelectedVersion' ) )
    {
        $selectedVersion = $http->postVariable( 'SelectedVersion' );
        if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
        {
            $EditLanguage = $http->postVariable( 'ContentObjectLanguageCode' );
            if ( $EditLanguage == eZContentObject::defaultLanguage() )
            {
                $EditLanguage = false;
            }
        }
        return $Module->redirectToView( "edit", array( $ObjectID, $selectedVersion, $EditLanguage ) );
    }
}
else if ( $http->hasPostVariable( 'NewDraftButton' ) )
{
    $contentINI =& eZINI::instance( 'content.ini' );
    $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );
    if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
    {
        $EditLanguage = $http->postVariable( 'ContentObjectLanguageCode' );
        if ( $EditLanguage == eZContentObject::defaultLanguage() )
        {
            $EditLanguage = false;
        }
    }

    $limitList =& $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' );
    foreach ( array_keys ( $limitList ) as $key )
    {
        if ( $classID == $key )
            $versionlimit =& $limitList[$key];
    }
    if ( $versionlimit < 2 )
        $versionlimit = 2;
    $versionCount = $obj->getVersionCount();
    if ( $versionCount < $versionlimit )
    {
        $version =& $obj->createNewVersion();

        if ( !$http->hasPostVariable( 'DoNotEditAfterNewDraft' ) )
        {
            return $Module->redirectToView( 'edit', array( $ObjectID, $version->attribute( 'version' ), $EditLanguage ) );
        }
        else
        {
            return $Module->redirectToView( 'edit', array( $ObjectID ) );
        }
    }
    else
    {
        // Remove oldest archived version first
        if ( $contentINI->variable( 'VersionManagement', 'DeleteDrafts' ) == 'enabled' )
        {
            $params = array( 'conditions' => array( 'status' => array( array( 0, 3 ) ) ) );
        }
        else
        {
            $params = array( 'conditions'=> array( 'status' => 3 ) );
        }
        $versions =& $obj->versions( true, $params );
        if ( count( $versions ) > 0 )
        {
            $modified = $versions[0]->attribute( 'modified' );
            $removeVersion =& $versions[0];
            foreach ( array_keys( $versions ) as $versionKey )
            {
                $version =& $versions[$versionKey];
                $currentModified = $version->attribute( 'modified' );
                if ( $currentModified < $modified )
                {
                    $modified = $currentModified;
                    $removeVersion = $version;
                }
            }
            $removeVersion->remove();
            $version =& $obj->createNewVersion();

            if( !$http->hasPostVariable( 'DoNotEditAfterNewDraft' ) )
            {
                return $Module->redirectToView( 'edit', array( $ObjectID, $version->attribute( 'version' ), $EditLanguage ) );
            }
            else
            {
                return $Module->redirectToView( 'edit', array( $ObjectID ) );
            }

            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
        else
        {
            $http->setSessionVariable( 'ExcessVersionHistoryLimit', true );
            $currentVersion = $obj->attribute( 'current_version' );
            $Module->redirectToView( 'versions', array( $ObjectID, $currentVersion, $EditLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
    }
}

$ini =& eZINI::instance();
if ( is_numeric( $EditVersion ) )
{
    $version =& $obj->version( $EditVersion );
    if ( $version === null )
    {
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}
else if ( $ini->variable( 'ContentSettings', 'EditDirtyObjectAction' ) == 'usecurrent' )
{
    $version =& $obj->currentVersion( true );
}
else
{
    $draftVersions =& $obj->versions( true, array( 'conditions' => array( 'status' => EZ_VERSION_STATUS_DRAFT ) ) );
    if ( count( $draftVersions ) > 0 )
    {
        $mostRecentDraft =& $draftVersions[0];
        foreach( $draftVersions as $currentDraft )
        {
            if( $currentDraft->attribute( 'modified' ) > $mostRecentDraft->attribute( 'modified' ) )
            {
                $mostRecentDraft =& $currentDraft;
            }
        }

        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $obj->attribute( 'id' ) ),
                              array( 'class', $class->attribute( 'id' ) ),
                              array( 'class_identifier', $class->attribute( 'identifier' ) ),
                              array( 'class_group', $class->attribute( 'match_ingroup_id_list' ) ) ) );

        $tpl->setVariable( 'edit_language', $EditLanguage );
        $tpl->setVariable( 'object', $obj );
        $tpl->setVariable( 'class', $class );
        $tpl->setVariable( 'draft_versions', $draftVersions );
        $tpl->setVariable( 'most_recent_draft', $mostRecentDraft );

        $Result = array();
        $Result['content'] =& $tpl->fetch( 'design:content/edit_draft.tpl' );
        return $Result;
    }
}

if ( !function_exists( 'checkForExistingVersion'  ) )
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

            $contentINI =& eZINI::instance( 'content.ini' );
            $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );

            $limitList =& $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' );

            $classID = $object->attribute( 'contentclass_id' );
            foreach ( array_keys ( $limitList ) as $key )
            {
                if ( $classID == $key )
                    $versionlimit =& $limitList[$key];
            }
            if ( $versionlimit < 2 )
                $versionlimit = 2;

            $versionCount = $object->getVersionCount();
            if ( $versionCount < $versionlimit )
            {
                $version =& $object->createNewVersion();
                $module->redirectToView( "edit", array( $objectID, $version->attribute( "version" ), $editLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
            else
            {
                // Remove oldest archived version first
                if ( $contentINI->variable( 'VersionManagement', 'DeleteDrafts' ) == 'enabled' )
                {
                    $params = array( 'conditions' => array( 'status' => array( array( 0, 3 ) ) ) );
                }
                else
                {
                    $params = array( 'conditions'=> array( 'status' => 3 ) );
                }
                $versions =& $object->versions( true, $params );
                if ( count( $versions ) > 0 )
                {
                    $modified = $versions[0]->attribute( 'modified' );
                    $removeVersion =& $versions[0];
                    foreach ( array_keys( $versions ) as $versionKey )
                    {
                        $version =& $versions[$versionKey];
                        $currentModified = $version->attribute( 'modified' );
                        if ( $currentModified < $modified )
                        {
                            $modified = $currentModified;
                            $removeVersion = $version;
                        }
                    }
                    $removeVersion->remove();
                    $version =& $object->createNewVersion();
                    $module->redirectToView( "edit", array( $objectID, $version->attribute( "version" ), $editLanguage ) );
                    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
                }
                else
                {
                    $http =& eZHTTPTool::instance();
                    $http->setSessionVariable( 'ExcessVersionHistoryLimit', true );
                    $currentVersion = $object->attribute( 'current_version' );
                    $module->redirectToView( 'versions', array( $objectID, $currentVersion, $editLanguage ) );
                    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
                }
            }
        }
    }
}
$Module->addHook( 'pre_fetch', 'checkForExistingVersion' );

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage )
    {
        if ( $module->isCurrentAction( 'Preview' ) )
        {
            $module->redirectToView( 'versionview', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Translate' ) )
        {
            $module->redirectToView( 'translate', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
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
//                 if ( $EditLanguage == eZContentObject::defaultLanguage() )
//                     $EditLanguage = false;
                if ( strlen( $EditLanguage ) == 0 )
                    $EditLanguage = eZContentObject::defaultLanguage();
                // We reset the from language to disable the translation look
                $FromLanguage = false;
                $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage, $FromLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }

        if ( $module->isCurrentAction( 'TranslateLanguage' ) )
        {
            if ( $module->hasActionParameter( 'SelectedLanguage' ) )
            {
                $FromLanguage = $EditLanguage;
                if ( strlen( $FromLanguage ) == 0 )
                    $FromLanguage = eZContentObject::defaultLanguage();
                $EditLanguage = $module->actionParameter( 'SelectedLanguage' );
//                 if ( $FromLanguage == $EditLanguage )
//                     $FromLanguage = false;
                $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage, $FromLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }

        if ( $module->isCurrentAction( 'Discard' ) )
        {
            $http =& eZHTTPTool::instance();
            $objectID = $object->attribute( 'id' );
            $discardConfirm = true;
            if ( $http->hasPostVariable( 'DiscardConfirm' ) )
                $discardConfirm = $http->postVariable( 'DiscardConfirm' );
            if ( $http->hasPostVariable( 'RedirectIfDiscarded' ) )
                $http->setSessionVariable( 'RedirectIfDiscarded', $http->postVariable( 'RedirectIfDiscarded' ) );
            $http->setSessionVariable( 'DiscardObjectID', $objectID );
            $http->setSessionVariable( 'DiscardObjectVersion', $EditVersion );
            $http->setSessionVariable( 'DiscardObjectLanguage', $EditLanguage );
            $http->setSessionVariable( 'DiscardConfirm', $discardConfirm );
            $module->redirectTo( $module->functionURI( 'removeeditversion' ) . '/' );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        // helper function which computes the redirect after
        // publishing and final store of a draft.
        function computeRedirect( &$module, &$object, &$version )
        {
            $http =& eZHTTPTool::instance();

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
            if ( $http->hasSessionVariable( 'RedirectURIAfterPublish' ) && !$hasRedirected )
            {
                $uri =& $http->sessionVariable( 'RedirectURIAfterPublish' );
                $http->removeSessionVariable( 'RedirectURIAfterPublish' );
                $module->redirectTo( $uri );
                $hasRedirected = true;
            }
            if ( $http->hasPostVariable( 'RedirectURIAfterPublish' )  && !$hasRedirected )
            {
                $uri =& $http->postVariable( 'RedirectURIAfterPublish' );
                $module->redirectTo( $uri );
                $hasRedirected = true;
            }
            if ( !$hasRedirected )
            {
                if ( $http->hasPostVariable( 'RedirectURI' ) )
                {
                    $uri = $http->postVariable( 'RedirectURI' );
                    $module->redirectTo( $uri );
                }
                else if ( $node !== null )
                {
                    $parentNode = $node->attribute( 'parent_node_id' );
                    if ( $parentNode == 1 )
                    {
                        $parentNode = $node->attribute( 'node_id' );
                    }
                    $module->redirectToView( 'view', array( 'full', $parentNode ) );
                }
                else
                {
                    $module->redirectToView( 'view', array( 'full', $version->attribute( 'main_parent_node_id' ) ) );
                }
            }

        }

        if( $module->isCurrentAction( 'StoreExit' ) )
        {
            computeRedirect( $module, $object, $version );
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

            // set chosen hidden/invisible attributes for object nodes
            $http          =& eZHTTPTool::instance();
            $assignedNodes =& $object->assignedNodes( true );
            foreach ( $assignedNodes as $node )
            {
                $nodeID               =& $node->attribute( 'node_id' );
                $parentNodeID         =& $node->attribute( 'parent_node_id' );
                $updateNodeVisibility =  false;
                $postVarName          = "FutureNodeHiddenState_$parentNodeID";

                if ( !$http->hasPostVariable( $postVarName ) )
                    $updateNodeVisibility = true;
                else
                {
                    $futureNodeHiddenState =& $http->postVariable( $postVarName );
                    if ( $futureNodeHiddenState == 'hidden' )
                        eZContentObjectTreeNode::hideSubTree( $node );
                    else if ( $futureNodeHiddenState == 'visible' )
                        eZContentObjectTreeNode::unhideSubTree( $node );
                    else if ( $futureNodeHiddenState == 'unchanged' )
                        $updateNodeVisibility = true;
                    else
                        eZDebug::writeWarning( "Unknown value for the future node hidden state: '$futureNodeHiddenState'" );
                }

                if ( $updateNodeVisibility )
                {
                    // this might be redundant
                    $parentNode =& eZContentObjectTreeNode::fetch( $parentNodeID );
                    eZContentObjectTreeNode::updateNodeVisibility( $node, $parentNode, /* $recursive = */ false );
                    unset( $node, $parentNode );
                }
            }
            unset( $assignedNodes );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );

            $newObjectName = $object->name();

            $http =& eZHttpTool::instance();

            computeRedirect( $module, $object, $version );

            // Generate the view cache
            $ini =& eZINI::instance();
            include_once( 'kernel/classes/eznodeviewfunctions.php' );
            eZDebug::accumulatorStart( 'generate_cache', '', 'Generating view cache' );
            if ( $ini->variable( 'ContentSettings', 'PreViewCache' ) == 'enabled' )
            {
                $preCacheSiteaccessArray = $ini->variable( 'ContentSettings', 'PreCacheSiteaccessArray' );

                $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];

                foreach ( $preCacheSiteaccessArray as $changeToSiteAccess )
                {
                    $GLOBALS['eZCurrentAccess']['name'] = $changeToSiteAccess;

                    if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
                    {
                        eZSys::clearAccessPath();
                        eZSys::addAccessPath( $changeToSiteAccess );
                    }

                    include_once( 'kernel/common/template.php' );
                    $tpl =& templateInit();
                    $res =& eZTemplateDesignResource::instance();

                    $res->setDesignSetting( $changeToSiteAccess, 'site' );
                    $res->setOverrideAccess( $changeToSiteAccess );

                    $language = false; // Needs to be specified if you want to generate the cache for a specific language
                    $viewMode = 'full';

                    // Cache the current node
                    $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $user, $node->attribute( 'node_id' ), 0, false, $language, $viewMode );
                    $tmpRes = eZNodeviewfunctions::generateNodeView( $tpl, $node, $node->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true );

                    // Cache the parent nodes
                    foreach ( $parentNodes as $parentNode )
                    {
                        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $user, $parentNode->attribute( 'node_id' ), 0, false, $language, $viewMode );
                        $tmpRes = eZNodeviewfunctions::generateNodeView( $tpl, $parentNode, $parentNode->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true );
                    }
                }

                $GLOBALS['eZCurrentAccess']['name'] = $currentSiteAccess;
                $res->setDesignSetting( $currentSiteAccess, 'site' );
                $res->setOverrideAccess( false );
                if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
                {
                    eZSys::clearAccessPath();
                    eZSys::addAccessPath( $currentSiteAccess );
                }
            }

            eZDebug::accumulatorStop( 'generate_cache' );

        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );
$includeResult = include( 'kernel/content/attribute_edit.php' );

if ( $includeResult != 1 )
    return $includeResult;

?>
