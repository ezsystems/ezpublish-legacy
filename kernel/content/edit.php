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
include_once( 'kernel/classes/eztrigger.php' );

$Module =& $Params["Module"];
include_once( 'kernel/content/node_edit.php' );
initializeNodeEdit( $Module );
include_once( 'kernel/content/relation_edit.php' );
initializeRelationEdit( $Module );

$obj =& eZContentObject::fetch( $ObjectID );
if ( !$obj )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !function_exists ( 'checkForExistingVersion'  ) )
{
    function checkForExistingVersion( &$module, $objectID, $editVersion, $editLanguage )
    {
        if ( !is_numeric( $editVersion ) )
        {
            // Fetch and create new version
            $object =& eZContentObject::fetch( $objectID );
            $user =& eZUser::currentUser();

            $userID = $user->id();

            $version = eZContentObjectVersion::fetchUserDraft( $objectID, $userID ); //$object->latestUserDraft();
            if ( $version == null )
            {
                $version =& $object->createNewVersion();
            }

            $module->redirectToView( "edit", array( $objectID, $version->attribute( "version" ), $editLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
    }
}
$Module->addHook( 'pre_fetch', 'checkForExistingVersion' );

if ( !function_exists ( 'registerSearchObject'  ) )
{
    function registerSearchObject( &$module, $parameters )
    {
        include_once( "kernel/classes/ezsearch.php" );
        $object =& $parameters[1];
        // Register the object in the search engine.
        eZSearch::removeObject( $object );
        eZSearch::addObject( $object );
    }
}
$Module->addHook( 'post_publish', 'registerSearchObject', 1, false );

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, $EditLanguage )
    {
        if ( $module->isCurrentAction( 'Preview' ) )
        {
            $module->redirectToView( 'versionview', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Translate' ) )
        {
            $module->redirectToView( 'translate', array( $object->attribute('id'), $EditVersion ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'VersionEdit' ) )
        {
            $module->redirectToView( 'versions', array( $object->attribute('id') ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'EditLanguage' ) )
        {
            if ( $module->hasActionParameter( 'SelectedLanguage' ) )
            {
                $EditLanguage = $module->actionParameter( 'SelectedLanguage' );
                if ( $EditLanguage == eZContentObject::defaultLanguage() )
                    $EditLanguage = false;
                $module->redirectToView( 'edit', array( $object->attribute('id'), $EditVersion, $EditLanguage ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }

        if ( $module->isCurrentAction( 'Cancel' ) )
        {
            $module->redirectTo( '/content/view/full/2/' );

            $objectID = $object->attribute( 'id' );
            $versionCount= $object->getVersionCount();
            $db =& eZDB::instance();
            $db->query( "DELETE FROM ezcontentobject_link
		                 WHERE from_contentobject_id=$objectID AND from_contentobject_version=$EditVersion" );
            $db->query( "DELETE FROM eznode_assignment
		                 WHERE contentobject_id=$objectID AND contentobject_version=$EditVersion" );
            $version->remove();
            foreach ( $contentObjectAttributes as $contentObjectAttribute )
            {
                $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
                $version = $contentObjectAttribute->attribute( 'version' );
                if ( $version == $EditVersion )
                {
                    $contentObjectAttribute->remove( $objectAttributeID, $version );
                }
            }
            if ( $versionCount == 1 )
            {
                $object->remove();
            }
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $http =& eZHttpTool::instance();
            $nodeAssignmentList =& $version->attribute( 'node_assignments' );

            $count = 0;
            foreach ( array_keys( $nodeAssignmentList ) as $key )
            {

                $nodeAssignment =& $nodeAssignmentList[$key];
                $existingNode =& eZContentObjectTreeNode::findNode( $nodeAssignment->attribute( 'parent_node' ) , $object->attribute( 'id' ), true );
                $runTrigger = true;
                $status['Status'] = EZ_TRIGGER_NO_CONNECTED_WORKFLOWS;
                if ( get_class( $existingNode ) == 'ezcontentobjecttreenode' )
                {
                    if ( $existingNode->attribute( 'contentobject_version' ) == $version->attribute( 'version' ) )
                    {
                        $runTrigger = false;
                    }
                }

                if ( $runTrigger )
                {
                    $version->setAttribute( 'status', EZ_VERSION_STATUS_PENDING );
                    $version->store();
                    $status = eZTrigger::runTrigger( 'pre_publish',
                                                     'content',
                                                     'publish',
                                                     array( 'object'  => $object,
                                                            'version' => $version->attribute( 'version' ),
                                                            'parent_node_id' => $nodeAssignment->attribute( 'parent_node' )
                                                            )
                                                     );
                }
                if ( $status['Status'] == EZ_TRIGGER_NO_CONNECTED_WORKFLOWS || $status['Status'] == EZ_TRIGGER_WORKFLOW_DONE || !$runTrigger )
                {
                    $oldVersion =& $object->attribute( 'current' );
                    $oldVersion->setAttribute( 'status', EZ_VERSION_STATUS_ARCHIVED );
                    $oldVersion->store();
                    $object->setAttribute( 'current_version', $EditVersion );
                    $object->setAttribute( 'modified', mktime() );
                    $object->setAttribute( 'published', mktime() );
                    $object->store();

                    $class =& eZContentClass::fetch( $object->attribute( 'contentclass_id' ) );
                    $objectName = $class->contentObjectName( $object );
                    $object->setAttribute( 'name', $objectName );
                    $object->store();


                    $fromNodeID = $nodeAssignment->attribute( 'from_node_id' );
                    $originalObjectID = $nodeAssignment->attribute( 'contentobject_id' );

                    $nodeID = $nodeAssignment->attribute( 'parent_node' );
                    $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                    $parentNodeId = $parentNode->attribute( 'node_id' );
                    if ( $existingNode  == null )
                    {
                        if ( $fromNodeID == 0 )
                        {
                            $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                            $existingNode =&  $parentNode->addChild( $object->attribute( 'id' ), 0, true );
                        }else
                        {
                            $originalNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $fromNodeID );
                            $originalNode->move( $parentNodeId );
                            $existingNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $parentNodeId );
                        }
                    }

                    $existingNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
                    $existingNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );
                    $existingNode->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
                    $existingNode->setAttribute( 'contentobject_is_published', 1 );
                    if ( $version->attribute( 'main_parent_node_id' ) == $existingNode->attribute( 'parent_node_id' ) )
                    {
                        $object->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
                    }
                    $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
                    $version->store();

                    $object->store();
                    $existingNode->store();
                    $count++;
                }
            }

            if( !$count )
            {
                $module->redirectToView( 'view', array( 'sitemap',2 ) );
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
            else
            {

                $assignedExistingNodes =& $object->attribute( 'assigned_nodes' );
                $curentVersionNodeAssignments =& $version->attribute( 'assigned_nodes' );
                $versionParentIDList = array();
                foreach ( array_keys( $curentVersionNodeAssignments ) as $key )
                {
                    $nodeAssignment =& $curentVersionNodeAssignments[$key];
                    $versionParentIDList[] = $nodeAssignment->attribute( 'parent_node' );
                }
                foreach ( array_keys( $assignedExistingNodes )  as $key )
                {
                    $node =& $assignedExistingNodes[$key];
                    if ( $node->attribute( 'contentobject_version' ) < $version->attribute( 'version' ) &&
                         !in_array( $node->attribute( 'parent_node_id' ), $versionParentIDList ) )
                    {
                        $node->remove();
                    }
                }

                $status = $module->runHooks( 'post_publish', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) );

                /*  clean up nodes for old versions      */

//            if ( $status )
//                return $status;
//         eZDebug::writeDebug( $object, 'object' );

                $module->redirectToView( 'view', array( 'full', $object->attribute( 'main_node_id' ) ) );

                include_once( "kernel/notification/eznotificationrule.php" );
                include_once( "kernel/notification/eznotificationruletype.php" );
                include_once( "kernel/notification/eznotificationuserlink.php" );
                include_once( "kernel/notification/ezmessage.php" );
                $allrules =& eZNotificationRule::fetchList( null );
                foreach ( $allrules as $rule )
                {
                    $ruleClass = $rule->attribute("rule_type");
                    $ruleID = $rule->attribute( "id" );
                    if ( $ruleClass->match( &$object, &$rule ) )
                    {
                        $users =& eZNotificationUserLink::fetchUserList( $ruleID );
                        foreach ( $users as $user )
                        {
                            $sendMethod = $user->attribute( "send_method" );
                            $sendWeekday = $user->attribute( "send_weekday" );
                            $sendTime = $user->attribute( "send_time" );
                            $destinationAddress = $user->attribute( "destination_address" );
                            $title = "New publishing notification";
                            $body = $object->attribute( "name" );
                            $domain = getenv( 'HTTP_HOST' );
                            $body .= "\nhttp://" .  $domain . "/content/view/full/";
                            $body .=  $object->attribute( "main_node_id" );
                            $body .= "\n\n\nAdministrator";
                            $message =& eZMessage::create( $sendMethod, $sendWeekday, $sendTime, $destinationAddress, $title, $body );
                            $message->store();
                        }
                    }
                }
                return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
            }
        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );
$includeResult = include( 'kernel/content/attribute_edit.php' );
if ( $includeResult != 1 )
    return $includeResult;


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
