<?php
//
//
// Created on: <10-Dec-2002 16:02:26 wy>
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

/*! \file removeobject.php
*/
//include_once( "kernel/classes/ezcontentobject.php" );
//include_once( "lib/ezutils/classes/ezhttppersistence.php" );
//include_once( "lib/ezdb/classes/ezdb.php" );
$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$objectID = (int) $http->sessionVariable( "DiscardObjectID" );
$version = (int) $http->sessionVariable( "DiscardObjectVersion" );
$editLanguage = $http->sessionVariable( "DiscardObjectLanguage" );

$isConfirmed = false;
if ( $http->hasPostVariable( "ConfirmButton" ) )
    $isConfirmed = true;

if ( $http->hasSessionVariable( "DiscardConfirm" ) )
{
    $discardConfirm = $http->sessionVariable( "DiscardConfirm" );
    if ( !$discardConfirm )
        $isConfirmed = true;
}

if ( $isConfirmed )
{
    $object = eZContentObject::fetch( $objectID );
    if ( $object === null )
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

    $versionObject = $object->version( $version );
    if ( is_object( $versionObject ) and $versionObject->attribute('status') != eZContentObjectVersion::STATUS_PUBLISHED )
    {
        if ( !$object->attribute( 'can_edit' ) )
        {
            // Check if it is a first created version of an object.
            // If so, then edit is allowed if we have an access to the 'create' function.
            if ( $object->attribute( 'current_version' ) == 1 && !$object->attribute( 'status' ) )
            {
                $mainNode = eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), 1 );
                $parentObj = $mainNode[0]->attribute( 'parent_contentobject' );
                $allowEdit = $parentObj->checkAccess( 'create', $object->attribute( 'contentclass_id' ), $parentObj->attribute( 'contentclass_id' ) );
            }
            else
                $allowEdit = false;

            if ( !$allowEdit )
                return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $object->accessList( 'edit' ) ) );
        }

        $db = eZDB::instance();
        $db->begin();

        $contentObjectAttributes = $versionObject->contentObjectAttributes( $editLanguage );
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
            $contentObjectAttribute->removeThis( $objectAttributeID, $version );
        }
        $versionCount= $object->getVersionCount();
        if ( $versionCount == 1 )
        {
            $nodeID = $versionObject->attribute( 'main_parent_node_id' );
            $object->purge();
        }
        else
        {
            $nodeID = $object->attribute( 'main_node_id' );
            $versionObject->remove();
        }

        $db->query( "DELETE FROM ezcontentobject_link
                     WHERE from_contentobject_id=$objectID AND from_contentobject_version=$version" );
        $db->query( "DELETE FROM eznode_assignment
                     WHERE contentobject_id=$objectID AND contentobject_version=$version" );

        $db->commit();
    }
    $hasRedirected = false;
    if ( $http->hasSessionVariable( 'RedirectIfDiscarded' ) )
    {
        $Module->redirectTo( $http->sessionVariable( 'RedirectIfDiscarded' ) );
        $http->removeSessionVariable( 'RedirectIfDiscarded' );
        $http->removeSessionVariable( 'ParentObject' );
        $http->removeSessionVariable( 'NewObjectID' );
        $hasRedirected = true;
    }
    if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $objectID )
    {
        $parentArray = $http->sessionVariable( 'ParentObject' );
        $parentURL = $Module->redirectionURI( 'content', 'edit', $parentArray );
        $http->removeSessionVariable( 'ParentObject' );
        $http->removeSessionVariable( 'NewObjectID' );
        $Module->redirectTo( $parentURL );
        $hasRedirected = true;
    }

    if ( $hasRedirected == false )
    {
        if ( isset( $nodeID ) && $nodeID )
            return $Module->redirectTo( '/content/view/full/' . $nodeID .'/' );

        //include_once( 'kernel/classes/ezredirectmanager.php' );
        return eZRedirectManager::redirectTo( $Module, '/', true, array( 'content/edit' ) );
    }
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/content/edit/' . $objectID . '/' . $version . '/' );
}

$Module->setTitle( "Remove Editing Version" );

require_once( "kernel/common/template.php" );
$tpl = templateInit();
$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "object_id", $objectID );
$tpl->setVariable( "object_version", $version );
$tpl->setVariable( "object_language", $editLanguage );
$Result = array();
$Result['content'] = $tpl->fetch( "design:content/removeeditversion.tpl" );
$Result['path'] = array( array( 'url' => '/content/removeeditversion/',
                                'text' => ezi18n( 'kernel/content', 'Remove editing version' ) ) );
?>
