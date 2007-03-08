<?php
//
//
// Created on: <10-Dec-2002 16:02:26 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "lib/ezdb/classes/ezdb.php" );
$Module =& $Params["Module"];
$http =& eZHTTPTool::instance();
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
    $object =& eZContentObject::fetch( $objectID );
    if ( $object === null )
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    if ( !$object->attribute( 'can_edit' ) )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    $db =& eZDB::instance();
    $db->begin();
    $db->query( "DELETE FROM ezcontentobject_link
                 WHERE from_contentobject_id=$objectID AND from_contentobject_version=$version" );
    $db->query( "DELETE FROM eznode_assignment
                 WHERE contentobject_id=$objectID AND contentobject_version=$version" );

    $versionObject =& $object->version( $version );
    $contentObjectAttributes =& $versionObject->contentObjectAttributes( $editLanguage );
    foreach ( $contentObjectAttributes as $contentObjectAttribute )
    {
        $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $contentObjectAttribute->remove( $objectAttributeID, $version );
    }
    $versionCount= $object->getVersionCount();
    $nodeID = $object->attribute( 'main_node_id' );
    if ( $versionCount == 1 )
    {
        $object->purge();
    }
    else
    {
        $versionObject->remove();
    }
    $db->commit();

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

        include_once( 'kernel/classes/ezredirectmanager.php' );
        return eZRedirectManager::redirectTo( $Module, '/', true, array( 'content/edit' ) );
    }
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/content/edit/' . $objectID . '/' . $version . '/' );
}

$Module->setTitle( "Remove Editing Version" );

include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "object_id", $objectID );
$tpl->setVariable( "object_version", $version );
$tpl->setVariable( "object_language", $editLanguage );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/removeeditversion.tpl" );
$Result['path'] = array( array( 'url' => '/content/removeeditversion/',
                                'text' => ezi18n( 'kernel/content', 'Remove editing version' ) ) );
?>
