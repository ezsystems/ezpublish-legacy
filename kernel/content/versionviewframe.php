<?php
//
// Created on: <21-Nov-2004 21:58:43 hovik>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file versionviewframe.php
*/

/* Module action checks */
if ( $Module->isCurrentAction( 'Edit' ) and
     $versionObject->attribute( 'status' ) == EZ_VERSION_STATUS_DRAFT and
     $contentObject->attribute( 'can_edit' ) and
     $isCreator )
{
    return $Module->redirectToView( 'edit', array( $ObjectID, $EditVersion, $LanguageCode ) );
}

// If we have an archived version editing we cannot edit the version directly.
// Instead we redirect to the edit page without a version, this will create
// a new version for us and start the edit operation
if ( $Module->isCurrentAction( 'Edit' ) and
     $contentObject->attribute( 'status' ) == EZ_CONTENT_OBJECT_STATUS_ARCHIVED and
     $contentObject->attribute( 'can_edit' ) )
{
    return $Module->redirectToView( 'edit', array( $ObjectID, false, $LanguageCode, $FromLanguage ) );
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
}

$ini =& eZINI::instance();

$siteaccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
if ( $Module->hasActionParameter( 'SiteAccess' ) )
{
    $siteaccess = $Module->actionParameter( 'SiteAccess' );
}

$allowVersionsButton = $contentINI->variable( 'VersionView', 'AllowVersionsButton' ) == 'enabled';
$tpl->setVariable( 'allow_versions_button', $allowVersionsButton );
$tpl->setVariable( 'site_access_list', $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) );
$tpl->setVariable( 'node', $node );
$tpl->setVariable( 'object', $contentObject );
$tpl->setVariable( 'version', $versionObject );
$tpl->setVariable( 'language', $LanguageCode );
$tpl->setVariable( 'object_languagecode', $LanguageCode );
$tpl->setVariable( 'siteaccess', $siteaccess );
$tpl->setVariable( 'is_creator', $isCreator );
$tpl->setVariable( 'from_language', $FromLanguage );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/view/versionview.tpl' );
$Result['node_id'] =& $node->attribute( 'node_id' );

return $Result;


?>
