<?php
//
//
// Created on: <10-Dec-2002 16:02:26 wy>
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

/*! \file
*/
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
    if ( is_object( $versionObject ) and
         in_array( $versionObject->attribute( 'status' ), array( eZContentObjectVersion::STATUS_DRAFT, eZContentObjectVersion::STATUS_INTERNAL_DRAFT ) ) )
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

        $versionCount= $object->getVersionCount();
        $nodeID = $versionCount == 1 ? $versionObject->attribute( 'main_parent_node_id' ) : $object->attribute( 'main_node_id' );
        $versionObject->removeThis();
    }
    $hasRedirected = false;
    if ( $http->hasSessionVariable( 'RedirectIfDiscarded' ) )
    {
        $Module->redirectTo( $http->sessionVariable( 'RedirectIfDiscarded' ) );
        $http->removeSessionVariable( 'RedirectIfDiscarded' );
        $hasRedirected = true;
    }
    if ( $http->hasSessionVariable( 'ParentObject' ) && $http->sessionVariable( 'NewObjectID' ) == $objectID )
    {
        $parentArray = $http->sessionVariable( 'ParentObject' );
        $parentURL = $Module->redirectionURI( 'content', 'edit', $parentArray );
        $Module->redirectTo( $parentURL );
        $hasRedirected = true;
    }

    $http->removeSessionVariable( 'RedirectURIAfterPublish' );
    $http->removeSessionVariable( 'ParentObject' );
    $http->removeSessionVariable( 'NewObjectID' );

    if ( $hasRedirected )
    {
        return;
    }
    else if ( isset( $nodeID ) && $nodeID )
    {
        return $Module->redirectTo( '/content/view/full/' . $nodeID . '/' );
    }
    else
    {
        return eZRedirectManager::redirectTo( $Module, '/', true, array( 'content/edit' ) );
    }
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/content/edit/' . $objectID . '/' . $version . '/' );
}

$Module->setTitle( "Remove Editing Version" );


$tpl = eZTemplate::factory();
$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "object_id", $objectID );
$tpl->setVariable( "object_version", $version );
$tpl->setVariable( "object_language", $editLanguage );
$Result = array();
$Result['content'] = $tpl->fetch( "design:content/removeeditversion.tpl" );
$Result['path'] = array( array( 'url' => '/content/removeeditversion/',
                                'text' => ezpI18n::tr( 'kernel/content', 'Remove editing version' ) ) );
?>
