<?php
//
// Created on: <03-May-2002 15:17:01 bf>
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


$http = eZHTTPTool::instance();
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


$tpl = eZTemplate::factory();
// Will be sent from the content/edit page and should be kept
// incase the user decides to continue editing.
$FromLanguage = $Params['FromLanguage'];

if ( $http->hasSessionVariable( 'LastAccessesVersionURI' ) )
{
    $tpl->setVariable( 'redirect_uri', $http->sessionVariable( 'LastAccessesVersionURI' ) );
}

$ini = eZINI::instance();

$contentObject = eZContentObject::fetch( $ObjectID );
if ( $contentObject === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$versionObject = $contentObject->version( $EditVersion );
if ( !$versionObject )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$versionObject->attribute( 'can_read' ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

if ( !$LanguageCode )
{
    $LanguageCode = $versionObject->initialLanguageCode();
}

$user = eZUser::currentUser();

$isCreator = ( $versionObject->attribute( 'creator_id' ) == $user->id() );

if ( $Module->isCurrentAction( 'Versions' ) )
{
    return $Module->redirectToView( 'history', array( $ObjectID, $EditVersion, $LanguageCode, $FromLanguage ) );
}

$sectionID = false;
$placementID = false;
$assignment = false;

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
    $LanguageCode = $http->postVariable( 'ContentObjectLanguageCode' );
if ( $http->hasPostVariable( 'ContentObjectPlacementID' ) )
    $placementID = $http->postVariable( 'ContentObjectPlacementID' );

$nodeAssignments = $versionObject->attribute( 'node_assignments' );
$virtualNodeID = null;
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
    foreach ( $nodeAssignments as $nodeAssignment )
    {
        if ( $nodeAssignment->attribute( 'is_main' ) )
        {
            $placementID = $nodeAssignment->attribute( 'id' );
            $parentNodeID = $nodeAssignment->attribute( 'parent_node' );
            $ObjectID = (int) $ObjectID;
            $query="SELECT node_id
                    FROM ezcontentobject_tree
                    WHERE contentobject_id=$ObjectID
                    AND parent_node_id=$parentNodeID";

            $db = eZDB::instance();
            $nodeListArray = $db->arrayQuery( $query );
            $virtualNodeID = $nodeListArray[0]['node_id'];
            break;
        }
    }
}
$parentNodeID = false;
$mainAssignment = false;
foreach ( $nodeAssignments as $nodeAssignment )
{
    if ( $nodeAssignment->attribute( 'is_main' ) == 1 )
    {
        $mainAssignment = $nodeAssignment;
        $parentNodeID = $mainAssignment->attribute( 'parent_node' );
        break;
    }
}

$contentINI = eZINI::instance( 'content.ini' );

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
}

$assignment = null;
if ( is_numeric( $placementID ) )
    $assignment = eZNodeAssignment::fetchByID( $placementID );
if ( $assignment !== null )
{
    $node = $assignment->getParentNode();
    if ( $node !== null )
    {
        $nodeObject = $node->attribute( "object" );
        $sectionID = $nodeObject->attribute( "section_id" );
    }
}
else
{
    $assignment = false;
}

$ini = eZINI::instance();

if ( $assignment )
{
    $parentNodeObject = $assignment->attribute( 'parent_node_obj' );
}

$navigationPartIdentifier = false;
if ( $sectionID !== false )
{
    $designKeys[] = array( 'section', $sectionID ); // Section ID
    eZSection::setGlobalID( $sectionID );

    $section = eZSection::fetch( $sectionID );
    if ( $section )
        $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
}
$designKeys[] = array( 'navigation_part_identifier', $navigationPartIdentifier );

if ( !$Module->isCurrentAction( 'Publish' ) )
    $contentObject->setAttribute( 'current_version', $EditVersion );

$class = eZContentClass::fetch( $contentObject->attribute( 'contentclass_id' ) );
$objectName = $class->contentObjectName( $contentObject );
$contentObject->setCachedName( $objectName );
if ( $assignment )
    $assignment->setName( $objectName );


$path = array();
$pathString = '';
$pathIdentificationString = '';
$requestedURIString = '';
$depth = 2;
if ( isset( $parentNodeObject ) && is_object( $parentNodeObject ) )
{
    $pathString = $parentNodeObject->attribute( 'path_string' ) . $virtualNodeID . '/';
    $pathIdentificationString = $parentNodeObject->attribute( 'path_identification_string' ); //TODO add current node ident string.
    $depth = $parentNodeObject->attribute( 'depth' ) + 1;
    $requestedURIString = $parentNodeObject->attribute( 'url_alias' );
}

if ( isset( $node ) && is_object( $node ) )
{
    $requestedURIString = $node->attribute( 'url_alias' );
}

$node = new eZContentObjectTreeNode();
$node->setAttribute( 'contentobject_version', $EditVersion );
$node->setAttribute( 'path_identification_string', $pathIdentificationString );
$node->setAttribute( 'contentobject_id', $ObjectID );
$node->setAttribute( 'parent_node_id', $parentNodeID );
$node->setAttribute( 'main_node_id', $virtualNodeID );
$node->setAttribute( 'path_string', $pathString );
$node->setAttribute( 'depth', $depth );
$node->setAttribute( 'node_id', $virtualNodeID );
$node->setAttribute( 'sort_field', $class->attribute( 'sort_field' ) );
$node->setAttribute( 'sort_order', $class->attribute( 'sort_order' ) );
$node->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)time() ) );
$node->setName( $objectName );

$node->setContentObject( $contentObject );

if ( $Params['SiteAccess'] )
{
    $siteAccess = $Params['SiteAccess'];
}
else
{
    include( 'kernel/content/versionviewframe.php' );
    return;
}

$contentINI = eZINI::instance( 'content.ini' );
if ( !$siteAccess )
{
    if ( $contentINI->hasVariable( 'VersionView', 'DefaultPreviewDesign' ) )
    {
        $siteAccess = $contentINI->variable( 'VersionView', 'DefaultPreviewDesign' );
    }
    else
    {
        $siteAccess = eZTemplateDesignResource::designSetting( 'site' );
    }
}

$access = $GLOBALS['eZCurrentAccess'];
$access['name'] = $siteAccess;

if ( $access['type'] == EZ_ACCESS_TYPE_URI )
{
    eZSys::clearAccessPath();
}
changeAccess( $access );

// Load the siteaccess extensions
eZExtension::activateExtensions( 'access' );

// Change content object default language
$GLOBALS['eZContentObjectDefaultLanguage'] = $LanguageCode;
eZContentObject::clearCache();

eZContentLanguage::expireCache();

$Module->setTitle( 'View ' . $class->attribute( 'name' ) . ' - ' . $contentObject->attribute( 'name' ) );

$res = eZTemplateDesignResource::instance();
$res->setDesignSetting( $ini->variable( 'DesignSettings', 'SiteDesign' ), 'site' );
$res->setOverrideAccess( $siteAccess );

$designKeys = array( array( 'object', $contentObject->attribute( 'id' ) ), // Object ID
                     array( 'node', $virtualNodeID ), // Node id
                     array( 'remote_id', $contentObject->attribute( 'remote_id' ) ),
                     array( 'class', $class->attribute( 'id' ) ), // Class ID
                     array( 'class_identifier', $class->attribute( 'identifier' ) ), // Class identifier
                     array( 'viewmode', 'full' ) );  // View mode

if ( $assignment )
{
    $designKeys[] = array( 'parent_node', $assignment->attribute( 'parent_node' ) );
    if ( $parentNodeObject instanceof eZContentObjectTreeNode )
        $designKeys[] = array( 'depth', $parentNodeObject->attribute( 'depth' ) + 1 );
}


$res->setKeys( $designKeys );

unset( $contentObject );
$contentObject = $node->attribute( 'object' ); // do not remove &

$Result = eZNodeviewfunctions::generateNodeViewData( $tpl, $node, $contentObject, $LanguageCode, 'full', 0, $viewParameters );

$Result['requested_uri_string'] = $requestedURIString;
$Result['ui_context'] = 'view';

?>
