<?php
//
// Created on: <03-May-2002 15:17:01 bf>
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

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$tpl =& templateInit();
$ObjectID = $Params['ObjectID'];
$Module =& $Params['Module'];
$OriginalLanguageCode = $Params['LanguageCode'];
$LanguageCode = $Params['LanguageCode'];
$EditVersion = $Params['EditVersion'];

$ini =& eZINI::instance();
$hasCustomSitedesign = false;
$sitedesign = eZTemplateDesignResource::designSetting( 'site' );

$contentObject =& eZContentObject::fetch( $ObjectID );
if ( $contentObject === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$versionObject =& $contentObject->version( $EditVersion );
if ( $versionObject === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$user =& eZUser::currentUser();

// eZDebug::writeDebug( ($versionObject->attribute( 'can_versionread' ) ? 'true' : 'false'), 'can_versionread' );
if ( !$versionObject->attribute( 'can_versionread' ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

$isCreator = $versionObject->attribute( 'creator_id' ) == $user->id();

if ( $Module->isCurrentAction( 'Versions' ) )
{
    return $Module->redirectToView( 'versions', array( $ObjectID, $EditVersion, $LanguageCode ) );
}

if ( $Module->isCurrentAction( 'Edit' ) and
     $versionObject->attribute( 'status' ) == EZ_VERSION_STATUS_DRAFT and
     $contentObject->attribute( 'can_edit' ) and
     $isCreator )
{
    return $Module->redirectToView( 'edit', array( $ObjectID, $EditVersion, $LanguageCode ) );
}

if ( $Module->isCurrentAction( 'Publish' ) and
     $versionObject->attribute( 'status' ) == EZ_VERSION_STATUS_DRAFT and
     $contentObject->attribute( 'can_edit' ) and
     $isCreator )
{
    $user =& eZUser::currentUser();
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $ObjectID,
                                                                                 'version' => $EditVersion ) );
    $object = eZContentObject::fetch( $ObjectID );
    $http =& eZHttpTool::instance();
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
            $Module->redirectToView( 'view', array( 'full', $object->attribute( 'main_node_id' ) ) );
        }
    }
    else
    {
        $Module->redirectToView( 'view', array( 'sitemap', 2 ) );
    }

    return;
    $Module->setCurrentAction( 'Publish', 'edit' );
    return $Module->run( 'edit', array( $ObjectID, $EditVersion, $LanguageCode ) );
//     return $Module->redirectToView( 'edit', array( $ObjectID, $EditVersion, $LanguageCode ) );
}

// eZDebug::writeDebug( 'HiO specific code on versionview, generalize' );
// $ini =& eZINI::instance();
// $ini->setVariable( 'DesignSettings', 'SiteDesign', 'hio' );

$sectionID = false;
$placementID = false;
$assignment = false;

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
    $LanguageCode = $http->postVariable( 'ContentObjectLanguageCode' );
if ( $http->hasPostVariable( 'ContentObjectPlacementID' ) )
    $placementID = $http->postVariable( 'ContentObjectPlacementID' );

$nodeAssignments =& $versionObject->attribute( 'node_assignments' );
if ( is_array( $nodeAssignments ) and
     count( $nodeAssignments ) == 1 )
{
    $placementID = $nodeAssignments[0]->attribute( 'id' );
}
else if ( !$placementID && count( $nodeAssignments ) )
{
    foreach ( array_keys( $nodeAssignments ) as $key )
    {
        $nodeAssignment =& $nodeAssignments[$key];
        if ( $nodeAssignment->attribute( 'is_main' ) )
        {
           $placementID = $nodeAssignment->attribute( 'id' );
           break;
        }
    }
}

$contentINI =& eZINI::instance( 'content.ini' );
$sitedesignList = $contentINI->variableArray( 'VersionView', 'AvailableSiteDesigns' );
if ( count( $sitedesignList ) == 1 )
{
    $sitedesign = $sitedesignList[0];
    $hasCustomSitedesign = true;
}

if ( $Module->isCurrentAction( 'ChangeSettings' ) )
{
    if ( $Module->hasActionParameter( 'Language' ) )
    {
        $LanguageCode = $Module->actionParameter( 'Language' );
    }

    if ( $Module->hasActionParameter( 'PlacementID' ) )
    {
        $placementID = $Module->actionParameter( 'PlacementID' );
    }

    if ( $Module->hasActionParameter( 'Sitedesign' ) )
    {
        $sitedesign = $Module->actionParameter( 'Sitedesign' );
        $hasCustomSitedesign = true;
    }
}

$assignment =& eZNodeAssignment::fetchByID( $placementID );
if ( $assignment !== null )
{
    $node =& $assignment->getParentNode();
    if ( $node !== null )
    {
        $nodeObject =& $node->attribute( "object" );
        $sectionID = $nodeObject->attribute( "section_id" );
    }
}
else
{
    $assignment = false;
}

$versionAttributes = $versionObject->contentObjectAttributes( $LanguageCode );
if ( $versionAttributes === null or
     count( $versionAttributes ) == 0 )
{
    $versionAttributes = $versionObject->contentObjectAttributes();
    $LanguageCode = eZContentObject::defaultLanguage();
}

if ( $hasCustomSitedesign )
{
    eZTemplateDesignResource::setDesignSetting( $sitedesign, 'site' );
}

$relatedObjectArray =& $contentObject->relatedContentObjectArray( $EditVersion );

$classID = $contentObject->attribute( 'contentclass_id' );

$class =& eZContentClass::fetch( $classID );

$classes =& eZContentClass::fetchList( $version = 0, $asObject = true, $user_id = false,
                                       array( 'name' => 'name' ), $fields = null );

$Module->setTitle( 'View ' . $class->attribute( 'name' ) . ' - ' . $contentObject->attribute( 'name' ) );

$res =& eZTemplateDesignResource::instance();
$designKeys = array( array( 'object', $contentObject->attribute( 'id' ) ), // Object ID
                     array( 'class', $class->attribute( 'id' ) ), // Class ID
                     array( 'viewmode', 'full' ) );  // View mode
$sectionID = 4;
if ( $sectionID !== false )
{
    $designKeys[] = array( 'section', $sectionID ); // Section ID
    include_once( 'kernel/classes/ezsection.php' );
    eZSection::setGlobalID( $sectionID );
}
$res->setKeys( $designKeys );

// include_once( 'kernel/classes/ezsection.php' );
// eZSection::setGlobalID( $contentObject->attribute( 'section_id' ) );

$contentObject->setAttribute( 'current_version', $EditVersion );

// Set variables to be compatible with normal design templates
$class =& eZContentClass::fetch( $contentObject->attribute( 'contentclass_id' ) );
$objectName = $class->contentObjectName( $contentObject );
$contentObject->setCachedName( $objectName );

$node = new eZContentObjectTreeNode();
$node->setAttribute( 'contentobject_version', $EditVersion );
$node->setAttribute( 'parent_node_id', $placementID );
$node->setAttribute( 'main_node_id', $placementID );
$node->setName( $objectName );
$node->setContentObject( $contentObject );

$tpl->setVariable( 'node', $node );

$tpl->setVariable( 'object', $contentObject );
$tpl->setVariable( 'version', $versionObject );
$tpl->setVariable( 'version_attributes', $versionAttributes );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'object_version', $EditVersion );
$tpl->setVariable( 'object_languagecode', $LanguageCode );
$tpl->setVariable( 'language', $OriginalLanguageCode );
$tpl->setVariable( 'placement', $placementID );
$tpl->setVariable( 'assignment', $assignment );
$tpl->setVariable( 'sitedesign', $sitedesign );
$tpl->setVariable( 'is_creator', $isCreator );

$tpl->setVariable( 'related_contentobject_array', $relatedObjectArray );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/view/versionview.tpl' );
$Result['path'] = array( array( 'text' => $contentObject->attribute( 'name' ),
                                'url' => false ) );

?>
