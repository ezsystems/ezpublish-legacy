<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

//include_once( 'kernel/classes/eztrigger.php' );
//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "lib/ezutils/classes/ezini.php" );
$Module = $Params['Module'];
require 'kernel/content/node_edit.php';
initializeNodeEdit( $Module );
require 'kernel/content/relation_edit.php';
initializeRelationEdit( $Module );
require 'kernel/content/section_edit.php';
initializeSectionEdit( $Module );
$obj = eZContentObject::fetch( $ObjectID );

if ( !$obj )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

// If the object has status Archived (trash) we redirect to content/restore
// which can handle this status properly.
if ( $obj->attribute( 'status' ) == eZContentObject::STATUS_ARCHIVED )
{
    return $Module->redirectToView( 'restore', array( $ObjectID ) );
}

// Check if we should switch access mode (http/https) for this object.
//include_once( 'kernel/classes/ezsslzone.php' );
eZSSLZone::checkObject( 'content', 'edit', $obj );

// This controls if the final access check is done.
// Some code will turn it off since they do the checking themselves.
$isAccessChecked = false;
$classID = $obj->attribute( 'contentclass_id' );
$class = eZContentClass::fetch( $classID );
$http = eZHTTPTool::instance();

// Action for the edit_draft.tpl/edit_languages.tpl page.
// CancelDraftButton is set for the Cancel button.
// Note: This code is safe to place before permission checking.
if( $http->hasPostVariable( 'CancelDraftButton' ) )
{
    $nodes = $obj->assignedNodes();
    $chosenNode = null;
    foreach ( $nodes as $node )
    {
        if ( $node->attribute( 'is_main' ) )
        {
            $chosenNode = $node;
        }
        else if ( $chosenNode === null )
        {
            $chosenNode = $node;
        }
    }
    if ( $chosenNode )
    {
        return $Module->redirectToView( 'view', array( 'full', $chosenNode->attribute( 'node_id' ) ) );
    }
    else
    {
        $contentINI = eZINI::instance( 'content.ini' );
        $rootNodeID = $contentINI->variable( 'NodeSettings', 'RootNode' );
        return $Module->redirectToView( 'view', array( 'full', $rootNodeID ) );
    }
}

// Remember redirection URI in session for later use.
// Note: This code is safe to place before permission checking.
if ( $http->hasPostVariable( 'RedirectURIAfterPublish' ) )
{
    $http->setSessionVariable( 'RedirectURIAfterPublish', $http->postVariable( 'RedirectURIAfterPublish' ) );
}

// Action for edit_draft.tpl page,
// EditButton is the button for editing the selected version.
// Note: This code is safe to place before permission checking.
if ( $http->hasPostVariable( 'EditButton' ) )
{
    if ( $http->hasPostVariable( 'SelectedVersion' ) )
    {
        $selectedVersion = $http->postVariable( 'SelectedVersion' );
        // Kept for backwards compatability, EditLanguage may also be set in URL
        if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
        {
            $EditLanguage = $http->postVariable( 'ContentObjectLanguageCode' );
        }

        return $Module->redirectToView( "edit", array( $ObjectID, $selectedVersion, $EditLanguage ) );
    }
}

//Check if there is corresponding locale for the supplied language code.
if ( $EditLanguage != null )
{
   $localeObject = eZLocale::instance( $EditLanguage );
       if ( !$localeObject || !$localeObject->isValid() )
       {
           eZDebug::writeError( "No such locale $EditLanguage!", 'Can not find language.' );
           return $Module->handleError( eZError::KERNEL_LANGUAGE_NOT_FOUND, 'kernel',
                     array( 'AccessList' => $obj->accessList( 'edit' ) ) );
       }
}

// Action for edit_draft.tpl page,
// This will create a new draft of the object which the user can edit.
if ( $http->hasPostVariable( 'NewDraftButton' ) )
{
    // Check permission for object in specified language
    if ( !$obj->canEdit( false, false, false, $EditLanguage ) )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                     array( 'AccessList' => $obj->accessList( 'edit' ) ) );
    }
    $isAccessChecked = true;

    $contentINI = eZINI::instance( 'content.ini' );
    $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );
    // Kept for backwards compatability
    if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
    {
        $EditLanguage = $http->postVariable( 'ContentObjectLanguageCode' );
    }

    $limitList = $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' );
    foreach ( array_keys ( $limitList ) as $key )
    {
        if ( $classID == $key )
            $versionlimit = $limitList[$key];
    }
    if ( $versionlimit < 2 )
        $versionlimit = 2;
    $versionCount = $obj->getVersionCount();
    if ( $versionCount < $versionlimit )
    {
        $version = $obj->createNewVersionIn( $EditLanguage, $FromLanguage, false, true, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        if ( !$http->hasPostVariable( 'DoNotEditAfterNewDraft' ) )
        {
            return $Module->redirectToView( 'edit', array( $ObjectID, $version->attribute( 'version' ), $EditLanguage ) );
        }
        else
        {
            return $Module->redirectToView( 'edit', array( $ObjectID, 'f', $EditLanguage ) );
        }
    }
    else
    {
        $params = array( 'conditions'=> array( 'status' => 3 ) );
        $versions = $obj->versions( true, $params );
        if ( count( $versions ) > 0 )
        {
            $modified = $versions[0]->attribute( 'modified' );
            $removeVersion = $versions[0];
            foreach ( $versions as $version )
            {
                $currentModified = $version->attribute( 'modified' );
                if ( $currentModified < $modified )
                {
                    $modified = $currentModified;
                    $removeVersion = $version;
                }
            }

            $db = eZDB::instance();
            $db->begin();
            $removeVersion->removeThis();
            $version = $obj->createNewVersionIn( $EditLanguage, false, false, true, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
            $db->commit();

            if( !$http->hasPostVariable( 'DoNotEditAfterNewDraft' ) )
            {
                return $Module->redirectToView( 'edit', array( $ObjectID, $version->attribute( 'version' ), $EditLanguage ) );
            }
            else
            {
                return $Module->redirectToView( 'edit', array( $ObjectID, 'f', $EditLanguage ) );
            }

            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }
        else
        {
            $http->setSessionVariable( 'ExcessVersionHistoryLimit', true );
            $currentVersion = $obj->attribute( 'current_version' );
            $Module->redirectToView( 'history', array( $ObjectID, $currentVersion, $EditLanguage ) );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }
    }
}

// Action for the edit_language.tpl page.
// LanguageSelection is used to choose a language to edit the object in.
if ( $http->hasPostVariable( 'LanguageSelection' ) )
{
    $selectedEditLanguage = $http->postVariable( 'EditLanguage' );
    $selectedFromLanguage = $http->postVariable( 'FromLanguage' );
    if ( in_array( $selectedEditLanguage, $obj->availableLanguages() ) )
    {
        $selectedFromLanguage = false;
    }

    $user = eZUser::currentUser();
    $parameters = array( 'conditions' =>
                         array( 'status' => array( array( eZContentObjectVersion::STATUS_DRAFT,
                                                          eZContentObjectVersion::STATUS_INTERNAL_DRAFT ) ),
                                'creator_id' => $user->attribute( 'contentobject_id' ) ) );
    $chosenVersion = null;
    foreach ( $obj->versions( true, $parameters ) as $possibleVersion )
    {
        if ( $possibleVersion->initialLanguageCode() == $selectedEditLanguage )
        {
            if ( !$chosenVersion ||
                 $chosenVersion->attribute( 'modified' ) < $possibleVersion->attribute( 'modified' ) )
            {
                $chosenVersion = $possibleVersion;
            }
        }
    }
    // We already found a draft by the current user,
    // immediately redirect to edit page for that version.
    if ( $chosenVersion )
    {
        // Check permission for object edit in specified language
        if ( !$obj->canEdit( false, false, false, $selectedEditLanguage ) )
        {
            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                         array( 'AccessList' => $obj->accessList( 'edit' ) ) );
        }
        $isAccessChecked = true;
        return $Module->redirectToView( 'edit', array( $ObjectID, 'f', $selectedEditLanguage, $selectedFromLanguage ) );
    }
    // Check permission for object creation in specified language
    if ( !$obj->canEdit( false, false, false, $selectedEditLanguage ) )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                     array( 'AccessList' => $obj->accessList( 'edit' ) ) );
    }
    $isAccessChecked = true;

    $version = $obj->createNewVersionIn( $selectedEditLanguage, $selectedFromLanguage, false, true, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );

    return $Module->redirectToView( 'edit', array( $ObjectID, $version->attribute( 'version' ), $selectedEditLanguage, $selectedFromLanguage ) );
}

// If we have a version number we check if it exists.
if ( is_numeric( $EditVersion ) )
{
    $version = $obj->version( $EditVersion );
    if ( !$version )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}

// No language was specified in the URL, we need to figure out
// the language to use.
if ( $EditLanguage == false )
{
    // Check permission for object
    if ( !$obj->canEdit() )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                     array( 'AccessList' => $obj->accessList( 'edit' ) ) );
    }
    $isAccessChecked = true;
//  $isSelfEdit = !$obj->checkAccess( 'edit' );

    // We check the $version variable which might be set above
    if ( isset( $version ) && $version )
    {
        // We have a version so we then know the language directly.
        $translationList = $version->translationList( false, false );
        if ( $translationList )
        {
            $EditLanguage = $translationList[0];
            // Check permission for version in specified language.
            if ( !$version->checkAccess( 'edit', false, false, false, $EditLanguage ) )
            {
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
            }
        }
        else
        {
            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        }
        $obj->cleanupInternalDrafts();
    }
    else
    {
        // No version so we investigate further.
        $obj->cleanupInternalDrafts();

        // Check number of languages
        //include_once( 'kernel/classes/ezcontentlanguage.php' );
        $languages = eZContentLanguage::fetchList();
        // If there is only one language we choose it for the user and goes to version choice screen.
        if ( count( $languages ) == 1 )
        {
            $firstLanguage = array_shift( $languages );
            return $Module->redirectToView( 'edit', array( $ObjectID, 'f', $firstLanguage->attribute( 'locale' ) ) );
        }

        $canCreateLanguageList = $obj->attribute( 'can_create_languages' );
        $canEditLanguageList = $obj->attribute( 'can_edit_languages' );
        if ( count( $canCreateLanguageList ) == 0 && count( $canEditLanguageList ) == 1 )
        {
            $firstLanguage = array_shift( $canEditLanguageList );
            return $Module->redirectToView( 'edit', array( $ObjectID, 'f', $firstLanguage->attribute( 'locale' ) ) );
        }

        // No version found, ask the user.
        require_once( 'kernel/common/template.php' );

        $tpl = templateInit();

        $res = eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $obj->attribute( 'id' ) ) ) );

        $tpl->setVariable( 'object', $obj );
        $tpl->setVariable( 'show_existing_languages', ( $EditVersion == 'a' )? false: true );

        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:content/edit_languages.tpl' );
        $Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Content' ),
                                 'url' => false ),
                          array( 'text' => ezi18n( 'kernel/content', 'Edit' ),
                                 'url' => false ) );

        return $Result;
    }
}

$ini = eZINI::instance();

// There version is not set but we do have a language.
// This means we need to create a new draft for the user, or reuse
// an existing one.
if ( !is_numeric( $EditVersion ) )
{
    if ( $ini->variable( 'ContentSettings', 'EditDirtyObjectAction' ) == 'usecurrent' )
    {
        // Check permission for object in specified language
        if ( !$obj->canEdit( false, false, false, $EditLanguage ) )
        {
            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                         array( 'AccessList' => $obj->accessList( 'edit' ) ) );
        }
        $isAccessChecked = true;

        $obj->cleanupInternalDrafts();
        $version = $obj->createNewVersionIn( $EditLanguage, false, false, true, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        return $Module->redirectToView( "edit", array( $ObjectID, $version->attribute( "version" ), $EditLanguage ) );
    }
    else
    {
        $obj->cleanupInternalDrafts();
        $draftVersions = $obj->versions( true, array( 'conditions' => array( 'status' => array( array( eZContentObjectVersion::STATUS_DRAFT, eZContentObjectVersion::STATUS_INTERNAL_DRAFT ) ),
                                                                              'language_code' => $EditLanguage ) ) );
        if ( count( $draftVersions ) > 1 )
        {
            // No permission checking required since it will ask the user what to do.

            // There are already drafts for the specified language so we need to ask the user what to do.
            $mostRecentDraft = $draftVersions[0];
            foreach( $draftVersions as $currentDraft )
            {
                if( $currentDraft->attribute( 'modified' ) > $mostRecentDraft->attribute( 'modified' ) )
                {
                    $mostRecentDraft = $currentDraft;
                }
            }

            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

            $res = eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $obj->attribute( 'id' ) ),
                                array( 'class', $class->attribute( 'id' ) ),
                                array( 'class_identifier', $class->attribute( 'identifier' ) ),
                                array( 'class_group', $class->attribute( 'match_ingroup_id_list' ) ) ) );

            $tpl->setVariable( 'edit_language', $EditLanguage );
            $tpl->setVariable( 'from_language', $FromLanguage );
            $tpl->setVariable( 'object', $obj );
            $tpl->setVariable( 'class', $class );
            $tpl->setVariable( 'draft_versions', $draftVersions );
            $tpl->setVariable( 'most_recent_draft', $mostRecentDraft );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:content/edit_draft.tpl' );
            return $Result;
        }
        elseif ( count( $draftVersions ) == 1 )
        {
            // Check permission for version in specified language
            if ( !$obj->canEdit( false, false, false, $EditLanguage ) )
            {
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                             array( 'AccessList' => $obj->accessList( 'edit' ) ) );
            }
            // There are already drafts for the specified language so we need to ask the user what to do.
            $mostRecentDraft = $draftVersions[0];
            foreach( $draftVersions as $currentDraft )
            {
                if( $currentDraft->attribute( 'modified' ) > $mostRecentDraft->attribute( 'modified' ) )
                {
                    $mostRecentDraft = $currentDraft;
                }
            }
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

                $res = eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'object', $obj->attribute( 'id' ) ),
                                array( 'class', $class->attribute( 'id' ) ),
                                array( 'class_identifier', $class->attribute( 'identifier' ) ),
                                array( 'class_group', $class->attribute( 'match_ingroup_id_list' ) ) ) );

            $tpl->setVariable( 'edit_language', $EditLanguage );
            $tpl->setVariable( 'from_language', $FromLanguage );
            $tpl->setVariable( 'object', $obj );
            $tpl->setVariable( 'class', $class );
            $tpl->setVariable( 'draft_versions', $draftVersions );
            $tpl->setVariable( 'most_recent_draft', $mostRecentDraft );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:content/edit_draft.tpl' );
            return $Result;
        }
        else
        {
            // Check permission for object in specified language
            if ( !$obj->canEdit( false, false, false, $EditLanguage ) )
            {
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                             array( 'AccessList' => $obj->accessList( 'edit' ) ) );
            }
            $isAccessChecked = true;

            $version = $obj->createNewVersionIn( $EditLanguage, false, false, true, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
            return $Module->redirectToView( "edit", array( $ObjectID, $version->attribute( "version" ), $EditLanguage ) );
        }
    }
}
elseif ( is_numeric( $EditVersion ) )
{
    // Fetch version
    $version = eZContentObjectVersion::fetchVersion( $EditVersion, $obj->attribute( 'id' ) );
    if ( !is_object( $version ) )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
    $user = eZUser::currentUser();
    // Check if $user can edit the current version.
    // We should not allow to edit content without creating a new version.
    if ( ( $version->attribute( 'status' ) != eZContentObjectVersion::STATUS_INTERNAL_DRAFT and
           $version->attribute( 'status' ) != eZContentObjectVersion::STATUS_DRAFT and
           $version->attribute( 'status' ) != eZContentObjectVersion::STATUS_PENDING ) or
           $version->attribute( 'creator_id' ) != $user->id() )
    {
        return $Module->redirectToView( 'history', array( $ObjectID, $version->attribute( "version" ), $EditLanguage ) );
    }
}

// If $isAccessChecked is still false we need to check access ourselves.
if ( !$isAccessChecked )
{
    // Check permission for object and version in specified language.
    if ( !$obj->canEdit( false, false, false, $EditLanguage ) )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                     array( 'AccessList' => $obj->accessList( 'edit' ) ) );
    }
}

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( $module, $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage, &$Result )
    {
        if ( $module->isCurrentAction( 'Preview' ) )
        {
            $module->redirectToView( 'versionview', array( $object->attribute('id'), $EditVersion, $EditLanguage, $FromLanguage ) );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Translate' ) )
        {
            $module->redirectToView( 'translate', array( $object->attribute( 'id' ), $EditVersion, $EditLanguage, $FromLanguage ) );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'VersionEdit' ) )
        {
            if ( isset( $GLOBALS['eZRequestedURI'] ) and is_object( $GLOBALS['eZRequestedURI'] ) )
            {
                $uri = $GLOBALS['eZRequestedURI'];
                $uri = $uri->originalURIString();
                $http = eZHTTPTool::instance();
                $http->setSessionVariable( 'LastAccessesVersionURI', $uri );
            }
            $module->redirectToView( 'history', array( $object->attribute( 'id' ), $EditVersion, $EditLanguage ) );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'EditLanguage' ) )
        {
            if ( $module->hasActionParameter( 'SelectedLanguage' ) )
            {
                $EditLanguage = $module->actionParameter( 'SelectedLanguage' );
                // We reset the from language to disable the translation look
                $FromLanguage = false;
                $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage, $FromLanguage ) );
                return eZModule::HOOK_STATUS_CANCEL_RUN;
            }
        }

        if ( $module->isCurrentAction( 'TranslateLanguage' ) )
        {
            if ( $module->hasActionParameter( 'SelectedLanguage' ) )
            {
                $FromLanguage = $EditLanguage;
                $EditLanguage = $module->actionParameter( 'SelectedLanguage' );
                $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage, $FromLanguage ) );
                return eZModule::HOOK_STATUS_CANCEL_RUN;
            }
        }

        if ( $module->isCurrentAction( 'FromLanguage' ) )
        {
            $FromLanguage = $module->actionParameter( 'FromLanguage' );
            $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage, $FromLanguage ) );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Discard' ) )
        {
            $http = eZHTTPTool::instance();
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
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        // helper function which computes the redirect after
        // publishing and final store of a draft.
        function computeRedirect( $module, $object, $version, $EditLanguage = false )
        {
            $http = eZHTTPTool::instance();

            $node = $object->mainNode();

            $hasRedirected = false;
            if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $object->attribute( 'id' ) )
            {
                $parentArray = $http->sessionVariable( 'ParentObject' );
                $parentURL = $module->redirectionURI( 'content', 'edit', $parentArray );
                $parentObject = eZContentObject::fetch( $parentArray[0] );
                $db = eZDB::instance();
                $db->begin();
                $parentObject->addContentObjectRelation( $object->attribute( 'id' ), $parentArray[1] );
                $db->commit();
                $http->removeSessionVariable( 'ParentObject' );
                $http->removeSessionVariable( 'NewObjectID' );
                $module->redirectTo( $parentURL );
                $hasRedirected = true;
            }
            if ( $http->hasSessionVariable( 'RedirectURIAfterPublish' ) && !$hasRedirected )
            {
                $uri = $http->sessionVariable( 'RedirectURIAfterPublish' );
                $http->removeSessionVariable( 'RedirectURIAfterPublish' );
                $module->redirectTo( $uri );
                $hasRedirected = true;
            }
            if ( $http->hasPostVariable( 'RedirectURIAfterPublish' )  && !$hasRedirected )
            {
                $uri = $http->postVariable( 'RedirectURIAfterPublish' );
                $module->redirectTo( $uri );
                $hasRedirected = true;
            }
            if ( $http->hasPostVariable( "BackToEdit") && $http->postVariable( "BackToEdit") )
            {
                $uri = $module->redirectionURI( 'content', 'edit', array( $object->attribute( 'id'), 'f', $EditLanguage ) );
                $module->redirectTo( $uri );
                eZDebug::writeDebug( $uri, "uri  " .  $object->attribute( 'id')  );
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
            computeRedirect( $module, $object, $version, $EditLanguage );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            // Checking the source and destination language from the url,
            // if they are the same no confirmation is needed.
            if ( $EditLanguage != $FromLanguage )
            {
                $conflictingVersions = $version->hasConflicts( $EditLanguage );
                if ( $conflictingVersions )
                {
                    require_once( 'kernel/common/template.php' );
                    $tpl = templateInit();

                    $res = eZTemplateDesignResource::instance();
                    $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                                        array( 'class', $class->attribute( 'id' ) ),
                                        array( 'class_identifier', $class->attribute( 'identifier' ) ),
                                        array( 'class_group', $class->attribute( 'match_ingroup_id_list' ) ) ) );

                    $tpl->setVariable( 'edit_language', $EditLanguage );
                    $tpl->setVariable( 'current_version', $version->attribute( 'version' ) );
                    $tpl->setVariable( 'object', $object );
                    $tpl->setVariable( 'draft_versions', $conflictingVersions );

                    $Result = array();
                    $Result['content'] = $tpl->fetch( 'design:content/edit_conflict.tpl' );
                    return eZModule::HOOK_STATUS_CANCEL_RUN;
                }
            }

            //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            eZDebug::accumulatorStart( 'publish', '', 'publish' );
            $oldObjectName = $object->name();
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $version->attribute( 'version' ) ) );
            eZDebug::accumulatorStop( 'publish' );

            if ( ( array_key_exists( 'status', $operationResult ) && $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE ) )
            {
                switch( $operationResult['status'] )
                {
                    case eZModuleOperationInfo::STATUS_HALTED:
                    {
                        if ( isset( $operationResult['redirect_url'] ) )
                        {
                            $module->redirectTo( $operationResult['redirect_url'] );
                            return;
                        }
                        else if ( isset( $operationResult['result'] ) )
                        {
                            $result = $operationResult['result'];
                            $resultContent = false;
                            if ( is_array( $result ) )
                            {
                                if ( isset( $result['content'] ) )
                                {
                                    $resultContent = $result['content'];
                                }
                                if ( isset( $result['path'] ) )
                                {
                                    $Result['path'] = $result['path'];
                                }
                            }
                            else
                            {
                                $resultContent = $result;
                            }
                            // Temporary fix to make approval workflow work with edit.
                            if ( strpos( $resultContent, 'Deffered to cron' ) === 0 )
                            {
                                $Result = null;
                            }
                            else
                            {
                                $Result['content'] = $resultContent;
                            }
                        }
                    }break;
                    case eZModuleOperationInfo::STATUS_CANCELLED:
                    {
                        $Result = array();
                        $Result['content'] = "Content publish cancelled<br/>";
                    }
                }

                /* If we already have a correct module result
                 * we don't need to continue module execution.
                 */
                if ( is_array( $Result ) )
                    return eZModule::HOOK_STATUS_CANCEL_RUN;
            }

            // update content object attributes array by refetching them from database
            $object = eZContentObject::fetch( $object->attribute( 'id' ) );
            $contentObjectAttributes = $object->attribute( 'contentobject_attributes' );

            // set chosen hidden/invisible attributes for object nodes
            $http          = eZHTTPTool::instance();
            $assignedNodes = $object->assignedNodes( true );
            foreach ( $assignedNodes as $node )
            {
                $nodeID               = $node->attribute( 'node_id' );
                $parentNodeID         = $node->attribute( 'parent_node_id' );
                $updateNodeVisibility =  false;
                $postVarName          = "FutureNodeHiddenState_$parentNodeID";

                if ( !$http->hasPostVariable( $postVarName ) )
                    $updateNodeVisibility = true;
                else
                {
                    $futureNodeHiddenState = $http->postVariable( $postVarName );
                    $db = eZDB::instance();
                    $db->begin();
                    if ( $futureNodeHiddenState == 'hidden' )
                        eZContentObjectTreeNode::hideSubTree( $node );
                    else if ( $futureNodeHiddenState == 'visible' )
                        eZContentObjectTreeNode::unhideSubTree( $node );
                    else if ( $futureNodeHiddenState == 'unchanged' )
                        $updateNodeVisibility = true;
                    else
                        eZDebug::writeWarning( "Unknown value for the future node hidden state: '$futureNodeHiddenState'" );
                    $db->commit();
                }

                if ( $updateNodeVisibility )
                {
                    // this might be redundant
                    $db = eZDB::instance();
                    $db->begin();
                    $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
                    eZContentObjectTreeNode::updateNodeVisibility( $node, $parentNode, /* $recursive = */ false );
                    $db->commit();
                    unset( $node, $parentNode );
                }
            }
            unset( $assignedNodes );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );

            $newObjectName = $object->name();

            $http = eZHTTPTool::instance();

            computeRedirect( $module, $object, $version, $EditLanguage );
            // we have set redirection URI for module so we don't need to continue module execution
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );

$includeResult = include( 'kernel/content/attribute_edit.php' );

if ( $includeResult != 1 )
    return $includeResult;

?>
