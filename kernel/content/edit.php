<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$Module =& $Params["Module"];

include( 'kernel/content/node_edit.php' );
initializeNodeEdit( $Module );
include( 'kernel/content/relation_edit.php' );
initializeRelationEdit( $Module );

function checkForExistingVersion( &$module, $objectID, $editVersion )
{
    if ( !is_numeric( $editVersion ) )
    {
        // Fetch and create new version
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->createNewVersion();
        $module->redirectToView( "edit", array( $objectID, $version->attribute( "version" ) ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
}
$Module->addHook( 'pre_fetch', 'checkForExistingVersion' );

function registerSearchObject( &$module, $parameters )
{
    include_once( "kernel/classes/ezsearch.php" );
    $object =& $parameters[1];
    // Register the object in the search engine.
    eZSearch::removeObject( $object );
    eZSearch::addObject( $object );
}
$Module->addHook( 'post_publish', 'registerSearchObject', false );

function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion )
{
    if ( $module->isCurrentAction( 'Preview' ) )
    {
        $module->redirectToView( 'versionview', array( $ObjectID, $EditVersion ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }

    if ( $module->isCurrentAction( 'Translate' ) )
    {
        $module->redirectToView( 'translate', array( $ObjectID, $EditVersion ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }

    if ( $module->isCurrentAction( 'VersionEdit' ) )
    {
        $module->redirectToView( 'versions', array( $ObjectID ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }

    if ( $module->isCurrentAction( 'Publish' ) )
    {
        $object->setAttribute( 'current_version', $EditVersion );
        $object->store();

        $status = $module->runHooks( 'post_publish', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) );
        if ( $status )
            return $status;

//         eZDebug::writeNotice( $object, 'object' );
        $module->redirectToView( 'view', array( 'full', $object->attribute( 'main_node_id' ) ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
}

$Module->addHook( 'action_check', 'checkContentActions' );

include( 'kernel/content/attribute_edit.php' );


/********** Custom Action Code Start ***************/
// $customAction = false;
// $customActionAttributeID = null;
// // Check for custom actions
// if ( $http->hasPostVariable( "CustomActionButton" ) )
// {
//     $customActionArray = $http->postVariable( "CustomActionButton" );
//     $customActionString = key( $customActionArray );

//     $customActionAttributeID = preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray );

//     $customActionAttributeID = $matchArray[1];
//     $customAction = $matchArray[2];
// }
/********** Custom Action Code End ***************/
/********** Custom Action Code Start ***************/
//         if ( $customActionAttributeID == $contentObjectAttribute->attribute( "id" ) )
//         {
//             $contentObjectAttribute->customHTTPAction( $http, $customAction );
//         }
/********** Custom Action Code End ***************/

?>
