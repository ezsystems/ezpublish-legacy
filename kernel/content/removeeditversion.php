<?php
//
//
// Created on: <10-Dec-2002 16:02:26 wy>
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

/*! \file removeobject.php
*/
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "lib/ezdb/classes/ezdb.php" );
$Module =& $Params["Module"];
$http =& eZHTTPTool::instance();
$objectID = $http->sessionVariable( "DiscardObjectID" );
$version = $http->sessionVariable( "DiscardObjectVersion" );
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
        if ( $nodeID != null )
            $Module->redirectTo( '/content/view/full/' . $nodeID .'/' );
        else
            if ( $http->hasSessionVariable( "LastAccessesURI" ) )
            {
                $Module->redirectTo( $http->sessionVariable( "LastAccessesURI" ) );
            }
            else
            {
                $Module->redirectTo( '/' );
            }
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
