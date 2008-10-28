<?php
//
// Created on: <21-Nov-2004 21:58:43 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file versionviewframe.php
*/

/* Module action checks */
if ( $Module->isCurrentAction( 'Edit' ) and
     $versionObject->attribute( 'status' ) == eZContentObjectVersion::STATUS_DRAFT and
     $contentObject->attribute( 'can_edit' ) and
     $isCreator )
{
    return $Module->redirectToView( 'edit', array( $ObjectID, $EditVersion, $LanguageCode ) );
}

// If we have an archived version editing we cannot edit the version directly.
// Instead we redirect to the edit page without a version, this will create
// a new version for us and start the edit operation
if ( $Module->isCurrentAction( 'Edit' ) and
     $contentObject->attribute( 'status' ) == eZContentObject::STATUS_ARCHIVED and
     $contentObject->attribute( 'can_edit' ) )
{
    return $Module->redirectToView( 'edit', array( $ObjectID, false, $LanguageCode, $FromLanguage ) );
}

if ( $Module->isCurrentAction( 'Publish' ) and
     $versionObject->attribute( 'status' ) == eZContentObjectVersion::STATUS_DRAFT and
     $contentObject->attribute( 'can_edit' ) and
     $isCreator )
{
    $conflictingVersions = $versionObject->hasConflicts( $LanguageCode );
    if ( $conflictingVersions )
    {
        require_once( 'kernel/common/template.php' );
        $tpl = templateInit();

        $res = eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $contentObject->attribute( 'id' ) ),
                            array( 'class', $class->attribute( 'id' ) ),
                            array( 'class_identifier', $class->attribute( 'identifier' ) ),
                            array( 'class_group', $class->attribute( 'match_ingroup_id_list' ) ) ) );

        $tpl->setVariable( 'edit_language', $LanguageCode );
        $tpl->setVariable( 'current_version', $versionObject->attribute( 'version' ) );
        $tpl->setVariable( 'object', $contentObject );
        $tpl->setVariable( 'draft_versions', $conflictingVersions );

        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:content/edit_conflict.tpl' );
        $section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
        if ( $section )
        {
            $Result['navigation_part'] = $section->attribute( 'navigation_part_identifier' );
            $Result['section_id'] = $section->attribute( 'id' );
        }
        $Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Version preview' ),
                                        'url' => false ) );
        return $Result;
    }

    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $ObjectID,
                                                                                 'version' => $EditVersion ) );
    $object = eZContentObject::fetch( $ObjectID );
    $http = eZHTTPTool::instance();
    if ( $object->attribute( 'main_node_id' ) != null )
    {
        if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $object->attribute( 'id' ) )
        {
            $parentArray = $http->sessionVariable( 'ParentObject' );
            $parentURL = $Module->redirectionURI( 'content', 'edit', $parentArray );
            $parentObject = eZContentObject::fetch( $parentArray[0] );
            $parentObject->addContentObjectRelation( $object->attribute( 'id' ), $parentArray[1] );
            $http->removeSessionVariable( 'ParentObject' );
            $http->removeSessionVariable( 'NewObjectID' );
            $Module->redirectTo( $parentURL );
        }
        else
        {
            $Module->redirectToView( 'view', array( 'full', $object->attribute( 'main_parent_node_id' ) ) );
        }
    }
    else
    {
        $Module->redirectToView( 'view', array( 'sitemap', 2 ) );
    }

    return;
}

$contentObject->setAttribute( 'current_version', $EditVersion );

$ini = eZINI::instance();

$siteaccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
if ( $Module->hasActionParameter( 'SiteAccess' ) )
{
    $siteaccess = $Module->actionParameter( 'SiteAccess' );
}

if ( $LanguageCode )
{
    $oldLanguageCode = $node->currentLanguage();
    $oldObjectLanguageCode = $contentObject->currentLanguage();
    $node->setCurrentLanguage( $LanguageCode );
    $contentObject->setCurrentLanguage( $LanguageCode );
}
$tpl->setVariable( 'site_access_list', $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' ) );
$tpl->setVariable( 'node', $node );
$tpl->setVariable( 'object', $contentObject );
$tpl->setVariable( 'version', $versionObject );
$tpl->setVariable( 'language', $LanguageCode );
$tpl->setVariable( 'object_languagecode', $LanguageCode );
$tpl->setVariable( 'siteaccess', $siteaccess );
$tpl->setVariable( 'is_creator', $isCreator );
$tpl->setVariable( 'from_language', $FromLanguage );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/view/versionview.tpl' );
$Result['node_id'] = $node->attribute( 'node_id' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Version preview' ),
                                'url' => false ) );
$section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
if ( $section )
{
    $Result['navigation_part'] = $section->attribute( 'navigation_part_identifier' );
    $Result['section_id'] = $section->attribute( 'id' );
}
if ( $LanguageCode )
{
    $node->setCurrentLanguage( $oldLanguageCode );
    $contentObject->setCurrentLanguage( $oldObjectLanguageCode );
}

return $Result;


?>
