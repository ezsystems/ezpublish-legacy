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
    //   print( "<br>parameters in registerSerchObject<br>" );
//    var_dump( $parameters );
    // Register the object in the search engine.
    eZSearch::removeObject( $object );
    eZSearch::addObject( $object );
}
$Module->addHook( 'post_publish', 'registerSearchObject', 1, false );

function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion )
{

    if ( $module->isCurrentAction( 'Preview' ) )
    {
        $module->redirectToView( 'versionview', array( $object->attribute('id'), $EditVersion ) );
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

    if ( $module->isCurrentAction( 'Publish' ) )
    {
        $nodeAssignmentList =& $version->attribute( 'node_assignments' );
//            exit();


        $count = 0;
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
        
            $nodeAssignment =& $nodeAssignmentList[$key];
            $status = eZTrigger::runTrigger( 'content',
                                             'publish',
                                             'b',
                                             array( 'object'  => $object,
                                                    'version' => $version->attribute( 'version' ),
                                                    'parent_node_id' => $nodeAssignment->attribute( 'parent_node' )
                                                    ),
                                             $module
                                             );

            if ( $status == EZ_TRIGGER_NO_CONNECTED_WORKFLOWS || $status == EZ_TRIGGER_WORKFLOW_DONE )
            {

                
//                print( "<br> we are going to publish in check action" );
//                flush();

                
                $object->setAttribute( 'current_version', $EditVersion );
                $object->setAttribute( 'modified', mktime() );
                $object->setAttribute( 'published', mktime() );

                $object->store();


                $nodeID = $nodeAssignment->attribute( 'parent_node' );

                $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
/*
                var_dump( $existingNode );
                print("<br>");
                var_dump( $nodeAssignment );
                print("<br>");
                print( $version->attribute( 'main_parent_node_id' ) . "\n bbbb" );
                print("<br>");
                exit();
*/

                $existingNode =& eZContentObjectTreeNode::findNode( $nodeID, $object->attribute( 'id' ), true );


                if ( $existingNode  == null )
                {

                    $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                    $existingNode =&  $parentNode->addChild( $object->attribute( 'id' ), 0, true );

                }
  
 
                $existingNode->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
                $existingNode->setAttribute( 'contentobject_is_published', 1 );
                
                if ( $version->attribute( 'main_parent_node_id' ) == $existingNode->attribute( 'parent_node_id' ) )
                {
                    print( $version->attribute( 'main_parent_node_id' ) . "\n inside if" );
                    $object->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
                }
                $object->store();
                $existingNode->store();

//                if ( $status )
//                    return $status;
                $count++;

            }
        }
        if( !$count )
        {
            $module->redirectToView( 'sitemap', array(2) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
        else
        {
            $status = $module->runHooks( 'post_publish', array( &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion ) );
//            if ( $status )
//                return $status;
//         eZDebug::writeNotice( $object, 'object' );
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
                        $body .= "\nhttp://nextgen.wy.dvh1.ez.no/content/view/full/";
                        $body .=  $object->attribute( "main_node_id" );
                        $body .= "\n\n\neZ System AS";
                        $message =& eZMessage::create( $sendMethod, $sendWeekday, $sendTime, $destinationAddress, $title, $body );
                        $message->store();

                        //include_once( "lib/ezutils/classes/ezmail.php" );
                        /* if( $sendMethod == "email" )
                    {
                        $email = new eZMail();
                        $email->setReceiver( "wy@ez.no" );
                        $email->setSender( "admin@ez.no" );
                        $email->setFromName( "Administrator" );
                        $email->setSubject( $title );
                        $email->setBody( $body );
                        $email->send();
                    }*/
                    }
                }

            }
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
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
