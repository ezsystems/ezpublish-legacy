<?php
//
// Created on: <03-May-2002 15:17:01 bf>
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

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

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
            $Module->redirectToView( 'view', array( 'full', $object->attribute( 'main_parent_node_id' ) ) );
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
    if ( $contentObject->attribute( 'main_node_id' ) != null )
        $virtualNodeID = $contentObject->attribute( 'main_node_id' );
    else
        $virtualNodeID = null;

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
            $parentNodeID = $nodeAssignment->attribute( 'parent_node' );
            $query="SELECT node_id
                    FROM ezcontentobject_tree
                    WHERE contentobject_id=$ObjectID
                    AND parent_node_id=$parentNodeID";

            $db =& eZDB::instance();
            $nodeListArray =& $db->arrayQuery( $query );
            $virtualNodeID = $nodeListArray[0]['node_id'];
            break;
        }
    }
}
$parentNodeID = false;
$mainAssignment = false;
foreach ( array_keys( $nodeAssignments ) as $key )
{
    if ( $nodeAssignments[$key]->attribute( 'is_main' ) == 1 )
    {
        $mainAssignment =& $nodeAssignments[$key];
        $parentNodeID = $mainAssignment->attribute( 'parent_node' );
        break;
    }
}

$contentINI =& eZINI::instance( 'content.ini' );
if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesigns' ) )
{
    $sitedesignList = $contentINI->variableArray( 'VersionView', 'AvailableSiteDesigns' );
}
else
{
    $sitedesignList = $contentINI->variable( 'VersionView', 'AvailableSiteDesignList' );
}

if ( $contentINI->hasVariable( 'VersionView', 'DefaultPreviewDesign' ) )
{
    $defaultPreviewDesign = $contentINI->variable( 'VersionView', 'DefaultPreviewDesign' );
    $sitedesign = $defaultPreviewDesign;
}
if ( count( $sitedesignList ) == 1 )
{
    $sitedesign = $sitedesignList[0];
    $hasCustomSitedesign = true;
}

$allowChangeButtons = $contentINI->variable( 'VersionView', 'AllowChangeButtons' ) == 'enabled';
$allowVersionsButton = $contentINI->variable( 'VersionView', 'AllowVersionsButton' ) == 'enabled';

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

if ( $sitedesign )
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
if ( $assignment )
{
    $parentNodeObject =& $assignment->attribute( 'parent_node_obj' );
    $designKeys[] = array( 'parent_node', $assignment->attribute( 'parent_node' ) );
    if ( get_class( $parentNodeObject ) == 'ezcontentobjecttreenode' )
        $designKeys[] = array( 'depth', $parentNodeObject->attribute( 'depth' ) + 1 );
}

$navigationPartIdentifier = false;
if ( $sectionID !== false )
{
    $designKeys[] = array( 'section', $sectionID ); // Section ID
    include_once( 'kernel/classes/ezsection.php' );
    eZSection::setGlobalID( $sectionID );

    $section =& eZSection::fetch( $sectionID );
    if ( $section )
        $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
}
$designKeys[] = array( 'navigation_part_identifier', $navigationPartIdentifier );

$res->setKeys( $designKeys );

$contentObject->setAttribute( 'current_version', $EditVersion );

// Set variables to be compatible with normal design templates
$contentObject->CurrentLanguage = $LanguageCode;
$contentObject->setAttribute( 'current_version', $EditVersion );

$class =& eZContentClass::fetch( $contentObject->attribute( 'contentclass_id' ) );
$objectName = $class->contentObjectName( $contentObject );
$contentObject->setCachedName( $objectName );
$contentObject->ContentObjectAttributeArray;
if ( $assignment )
    $assignment->setName( $objectName );

$node = new eZContentObjectTreeNode();
$node->setAttribute( 'contentobject_version', $EditVersion );
$node->setAttribute( 'contentobject_id', $ObjectID );
$node->setAttribute( 'parent_node_id', $parentNodeID );
$node->setAttribute( 'main_node_id', $virtualNodeID );
$node->setAttribute( 'node_id', $virtualNodeID );
$node->setAttribute( 'path', array() );
$node->setName( $objectName );

$node->setContentObject( $contentObject );

$path = array();
$titlePath = array();

$hasPath = false;
if ( $mainAssignment )
{
    $parentNode =& $mainAssignment->attribute( 'parent_node_obj' );
    if ( $parentNode )
    {
        $parents =& $parentNode->attribute( 'path' );

        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' )
                             );
        }
        $path[] = array( 'text' => $parentNode->attribute( 'name' ),
                         'url' => '/content/view/full/' . $parentNode->attribute( 'node_id' ),
                         'url_alias' => $parentNode->attribute( 'url_alias' ),
                         'node_id' => $parentNode->attribute( 'node_id' ) );
        $objectPathElement = array( 'text' => $contentObject->attribute( 'name' ),
                                    'url' => false,
                                    'url_alias' => false );
        $existingNode = $contentObject->attribute( 'main_node' );
        if ( $existingNode )
        {
            $objectPathElement['url'] = '/content/view/full/' . $existingNode->attribute( 'node_id' );
            $objectPathElement['url_alias'] = $existingNode->attribute( 'url_alias' );
            $objectPathElement['node_id'] = $existingNode->attribute( 'node_id' );
        }
        $path[] = $objectPathElement;
        $hasPath = true;
    }
}
if ( !$hasPath )
{
    $existingNode = $contentObject->attribute( 'main_node' );
    if ( $existingNode )
    {
        $parents =& $existingNode->attribute( 'path' );

        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' )
                             );
        }
        $path[] = array( 'text' => $existingNode->attribute( 'name' ),
                         'url' => '/content/view/full/' . $existingNode->attribute( 'node_id' ),
                         'url_alias' => $existingNode->attribute( 'url_alias' ),
                         'node_id' => $existingNode->attribute( 'node_id' ) );
        $hasPath = true;
    }
}
if ( !$hasPath )
{
    $path[] = array( 'text' => $contentObject->attribute( 'name' ),
                     'url' => false );
}

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
$tpl->setVariable( 'allow_change_buttons', $allowChangeButtons );
$tpl->setVariable( 'allow_versions_button', $allowVersionsButton );

$tpl->setVariable( 'related_contentobject_array', $relatedObjectArray );
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/view/versionview.tpl' );
$Result['path'] = array( array( 'text' => $contentObject->attribute( 'name' ),
                                'url' => false ) );
$Result['path'] = $path;

?>
