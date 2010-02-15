<?php
//
// Created on: <22-Apr-2002 15:41:30 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$http = eZHTTPTool::instance();

$ObjectID = $Params['ObjectID'];
$EditVersion = $Params['EditVersion'];

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

if ( $http->hasPostVariable( 'BackButton' )  )
{
    $userRedirectURI = '';
    if ( $http->hasPostVariable( 'RedirectURI' ) )
    {
        $redurectURI = $http->postVariable( 'RedirectURI' );
        $http->removeSessionVariable( 'LastAccessesVersionURI' );
        return $Module->redirectTo( $redurectURI );
    }
    if ( $http->hasSessionVariable( "LastAccessesURI" ) )
        $userRedirectURI = $http->sessionVariable( "LastAccessesURI" );
    return $Module->redirectTo( $userRedirectURI );
}

$object = eZContentObject::fetch( $ObjectID );

$editWarning = false;

$canEdit = false;
$canRemove = false;

if ( $object === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_read' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

if ( $object->attribute( 'can_edit' ) )
    $canEdit = true;

$canRemove = true;

//content/diff functionality
//Set default values
$previousVersion = 1;
$newestVersion = 1;

//By default, set preselect the previous and most recent version for diffing
if ( count( $object->versions() ) > 1 )
{
    $versionArray = $object->versions( false );
    $selectableVersions = array();
    foreach( $versionArray as $versionItem )
    {
        //Only return version numbers of archived or published items
        if ( in_array( $versionItem['status'], array( 0, 1, 3 ) ) )
        {
            $selectableVersions[] = $versionItem['version'];
        }
    }
    $newestVersion = array_pop( $selectableVersions );
    $previousVersion = array_pop( $selectableVersions );
}

$tpl->setVariable( 'selectOldVersion', $previousVersion );
$tpl->setVariable( 'selectNewVersion', $newestVersion );
$tpl->setVariable( 'module', $Module );

$diff = array();

if ( $http->hasPostVariable('DiffButton') && $http->hasPostVariable( 'FromVersion' ) && $http->hasPostVariable( 'ToVersion' ) )
{
    if ( !$object->attribute( 'can_diff' ) )
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

    $lang = false;
    if ( $http->hasPostVariable( 'Language' ) )
    {
        $lang = $http->postVariable( 'Language' );
    }
    $oldVersion = $http->postVariable( 'FromVersion' );
    $newVersion = $http->postVariable( 'ToVersion' );

    if ( is_numeric( $oldVersion ) && is_numeric( $newVersion ) )
    {
        $oldObject = $object->version( $oldVersion );
        $newObject = $object->version( $newVersion );

        if ( $lang )
        {
            $oldAttributes = $object->fetchDataMap( $oldVersion, $lang );
            //Fallback, if desired language not available in version
            if ( !$oldAttributes )
            {
                $oldObjectLang = $oldObject->attribute( 'initial_language' );
                $oldAttributes = $object->fetchDataMap( $oldVersion, $oldObjectLang->attribute( 'locale' ) );
            }
            $newAttributes = $object->fetchDataMap( $newVersion, $lang );
            //Fallback, if desired language not available in version
            if ( !$newAttributes )
            {
                $newObjectLang = $newObject->attribute( 'initial_language' );
                $newAttributes = $object->fetchDataMap( $newVersion, $newObjectLang->attribute( 'locale' ) );
            }

        }
        else
        {
            $oldAttributes = $oldObject->dataMap();
            $newAttributes = $newObject->dataMap();
        }

        //Extra options to open up for future extensions of the system.
        $extraOptions = false;
        if ( $http->hasPostVariable( 'ExtraOptions' ) )
        {
            $extraOptions = $http->postVariable( 'ExtraOptions' );
        }

        //Invoke diff method in the datatype
        foreach ( $oldAttributes as $attribute )
        {
            $newAttr = $newAttributes[$attribute->attribute( 'contentclass_attribute_identifier' )];
            $contentClassAttr = $newAttr->attribute( 'contentclass_attribute' );
            $diff[$contentClassAttr->attribute( 'id' )] = $contentClassAttr->diff( $attribute, $newAttr, $extraOptions );
        }

        $tpl->setVariable( 'oldVersion', $oldVersion );
        $tpl->setVariable( 'oldVersionObject', $object->version( $oldVersion ) );

        $tpl->setVariable( 'newVersion', $newVersion );
        $tpl->setVariable( 'newVersionObject', $object->version( $newVersion ) );
        $tpl->setVariable( 'diff', $diff );
    }
}
//content/diff end

//content/versions
if ( $http->hasSessionVariable( 'ExcessVersionHistoryLimit' ) )
{
    $excessLimit = $http->sessionVariable( 'ExcessVersionHistoryLimit' );
    if ( $excessLimit )
        $editWarning = 3;
    $http->removeSessionVariable( 'ExcessVersionHistoryLimit' );
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( !$canEdit )
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $db = eZDB::instance();
        $db->begin();

        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        $versionArray = array();
        foreach ( $deleteIDArray as $deleteID )
        {
            $version = eZContentObjectVersion::fetch( $deleteID );
            $versionArray[] = $version->attribute( 'version' );
            if ( $version != null )
            {
                if ( $version->attribute( 'can_remove' ) )
                {
                    $version->removeThis();
                }
            }
        }
        $db->commit();
    }
}

$user = eZUser::currentUser();

if ( $Module->isCurrentAction( 'Edit' )  )
{
    if ( !$canEdit )
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

    $versionID = false;

    if ( is_array( $Module->actionParameter( 'VersionKeyArray' ) ) )
    {
        $versionID = array_keys( $Module->actionParameter( 'VersionKeyArray' ) );
        $versionID = $versionID[0];
    }
    else if ( $Module->hasActionParameter( 'VersionID' ) )
        $versionID = $Module->actionParameter( 'VersionID' );

    $version = $object->version( $versionID );
    if ( !$version )
        $versionID = false;

    if ( $versionID !== false and
         !in_array( $version->attribute( 'status' ), array( eZContentObjectVersion::STATUS_DRAFT, eZContentObjectVersion::STATUS_INTERNAL_DRAFT ) ) )
    {
        $editWarning = 1;
        $EditVersion = $versionID;
    }
    else if ( $versionID !== false and
              $version->attribute( 'creator_id' ) != $user->attribute( 'contentobject_id' ) )
    {
        $editWarning = 2;
        $EditVersion = $versionID;
    }
    else
    {
        return $Module->redirectToView( 'edit', array( $ObjectID, $versionID, $version->initialLanguageCode() ) );
    }
}

if ( $Module->isCurrentAction( 'CopyVersion' )  )
{
    if ( !$canEdit )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    if ( is_array( $Module->actionParameter( 'VersionKeyArray' ) ) )
    {
        $versionID = array_keys( $Module->actionParameter( 'VersionKeyArray' ) );
        $versionID = $versionID[0];
    }
    else
    {
        $versionID = $Module->actionParameter( 'VersionID' );
    }

    $version = $object->version( $versionID );
    if ( !$version )
        $versionID = false;

    // if we cannot fetch version with given versionID or if fetched version is
    // an internal-draft then just skip copying and redirect back to the history view
    if ( !$versionID or $version->attribute( 'status' ) == eZContentObjectVersion::STATUS_INTERNAL_DRAFT )
    {
        $currentVersion = $object->attribute( 'current_version' );
        $Module->redirectToView( 'history', array( $ObjectID, $currentVersion ) );
        return eZModule::HOOK_STATUS_CANCEL_RUN;
    }

    $languages = $Module->actionParameter( 'LanguageArray' );
    if ( $languages && array_key_exists( $versionID, $languages ) )
    {
        $language = $languages[$versionID];
    }
    else
    {
        $language = $version->initialLanguageCode();
    }

    if ( !$object->checkAccess( 'edit', false, false, false, $language ) )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    // Check the version limit for the object's class
    $versionlimit = eZContentClass::versionHistoryLimit( $object->attribute( 'contentclass_id' ) );
    $versionCount = $object->getVersionCount();
    if ( $versionCount < $versionlimit )
    {
        $db = eZDB::instance();
        $db->begin();
        $newVersionID = $object->copyRevertTo( $versionID, $language );
        $db->commit();

        if ( !$http->hasPostVariable( 'DoNotEditAfterCopy' ) )
        {
            return $Module->redirectToView( 'edit', array( $ObjectID, $newVersionID, $language ) );
        }
    }
    else
    {
        $params = array( 'conditions'=> array( 'status' => 3 ) );
        $versions = $object->versions( true, $params );
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
            $newVersionID = $object->copyRevertTo( $versionID, $language );
            $db->commit();

            if ( !$http->hasPostVariable( 'DoNotEditAfterCopy' ) )
            {
                return $Module->redirectToView( 'edit', array( $ObjectID, $newVersionID, $language ) );
            }
        }
        else
        {
            $http->setSessionVariable( 'ExcessVersionHistoryLimit', true );
            $currentVersion = $object->attribute( 'current_version' );
            $Module->redirectToView( 'history', array( $ObjectID, $currentVersion ) );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }
    }
}

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                      array( 'remote_id', $object->attribute( 'remote_id' ) ),
                      array( 'class', $object->attribute( 'contentclass_id' ) ), // Class ID
                      array( 'class_identifier', $object->attribute( 'class_identifier' ) ), // Class identifier
                      array( 'section_id', $object->attribute( 'section_id' ) ) // Section ID
                      ) ); // Section ID, 0 so far

eZSection::setGlobalID( $object->attribute( 'section_id' ) );
$versionArray =( isset( $versionArray ) and is_array( $versionArray ) ) ? array_unique( $versionArray ) : array();
$LastAccessesVersionURI = $http->hasSessionVariable( 'LastAccessesVersionURI' ) ? $http->sessionVariable( 'LastAccessesVersionURI' ) : null;
$explodedURI = $LastAccessesVersionURI ? explode ( '/', $LastAccessesVersionURI ) : null;
if ( $LastAccessesVersionURI and is_array( $versionArray ) and !in_array( $explodedURI[3], $versionArray ) )
  $tpl->setVariable( 'redirect_uri', $http->sessionVariable( 'LastAccessesVersionURI' ) );

//Fetch newer drafts and count of newer drafts.
$newerDraftVersionList = eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                              null,
                                                              array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                     'status' => eZContentObjectVersion::STATUS_DRAFT,
                                                                     'version' => array( '>', $object->attribute( 'current_version' ) ) ),
                                                              array( 'modified' => 'asc',
                                                                     'initial_language_id' => 'desc' ),
                                                              null, true );
$newerDraftVersionListCount = is_array( $newerDraftVersionList ) ? count( $newerDraftVersionList ) : 0;

$versions = $object->versions();

$tpl->setVariable( 'newerDraftVersionList', $newerDraftVersionList );
$tpl->setVariable( 'newerDraftVersionListCount', $newerDraftVersionListCount );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'versions', $versions );
$tpl->setVariable( 'edit_warning', $editWarning );
$tpl->setVariable( 'can_edit', $canEdit );
//$tpl->setVariable( 'can_remove', $canRemove );
$tpl->setVariable( 'user_id', $user->attribute( 'contentobject_id' ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/history.tpl' );
$Result['path'] = array( array( 'text' => eZi18n::translate( 'kernel/content', 'History' ),
                                'url' => false ) );
$section = eZSection::fetch( $object->attribute( 'section_id' ) );
if ( $section )
{
    $Result['navigation_part'] = $section->attribute( 'navigation_part_identifier' );
    $Result['section_id'] = $section->attribute( 'id' );
}

?>