<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
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

/*! \file ezcontentoperationcollection.php
*/

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

class eZContentOperationCollection
{
    /*!
     Constructor
    */
    function eZContentOperationCollection()
    {
    }

    function readNode( $nodeID )
    {

    }

    function readObject( $nodeID, $languageCode )
    {
        $node =& eZContentObjectTreeNode::fetch( $nodeID );

        if ( $node === null )
//            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
            return false;


        $object = $node->attribute( 'object' );

        if ( $object === null )
//            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        {
            return false;
        }

        if ( !$object->attribute( 'can_read' ) )
        {
//            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
            return false;
        }

        if ( $languageCode != '' )
        {
            $object->setCurrentLanguage( $languageCode );
        }
        return array( 'status' => true, 'object' => $object, 'node' => $node );
    }

    function loopNodes( $nodeID )
    {
        return array( 'parameters' => array( array( 'parent_node_id' => 3 ),
                                             array( 'parent_node_id' => 5 ),
                                             array( 'parent_node_id' => 12 ) ) );
    }

    function loopNodeAssignment( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );
        $nodeAssignmentList =& $version->attribute( 'node_assignments' );
//        var_dump( $nodeAssignmentList );

        $parameters = array();
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
//            $nodeAssignment =& $nodeAssignmentList[$key];

            $parameters[] = array( 'parent_node_id' => $nodeAssignmentList[$key]->attribute( 'parent_node' ) );
        }

        return array( 'parameters' => $parameters );
    }

    function setVersionStatus( $objectID, $versionNum, $status )
    {
        $object =& eZContentObject::fetch( $objectID );
        if ( !$versionNum )
        {
            $versionNum = $object->attribute( 'current_version' );
        }
        $version =& $object->version( $versionNum );
        switch ( $status )
        {
            case 1:
            {
                $statusName = 'pending';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_PENDING );
            } break;
            case 2:
            {
                $statusName = 'archived';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_ARCHIVED );
            } break;
            case 3:
            {
                $statusName = 'published';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
            } break;
            default:
                $statusName = 'none';
        }
        $version->store();
    }

    function publishNode( $parentNodeID, $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );
        $nodeAssignment =& eZNodeAssignment::fetch( $objectID, $versionNum, $parentNodeID );

        $object->setAttribute( 'current_version', $versionNum );
        $object->setAttribute( 'modified', mktime() );
        $object->setAttribute( 'published', mktime() );
        $object->store();

        $class =& eZContentClass::fetch( $object->attribute( 'contentclass_id' ) );
        $objectName = $class->contentObjectName( $object );

        $object->setName( $objectName, $versionNum );
//        $object->setAttribute( 'name', $objectName );
        $object->store();

        $fromNodeID = $nodeAssignment->attribute( 'from_node_id' );
        $originalObjectID = $nodeAssignment->attribute( 'contentobject_id' );

        $nodeID = $nodeAssignment->attribute( 'parent_node' );
        $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
        $parentNodeID = $parentNode->attribute( 'node_id' );
        $existingNode =& eZContentObjectTreeNode::findNode( $nodeAssignment->attribute( 'parent_node' ) , $object->attribute( 'id' ), true );
        if ( $existingNode  == null )
        {
            if ( $fromNodeID == 0 )
            {
                $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                $existingNode =&  $parentNode->addChild( $object->attribute( 'id' ), 0, true );
            }else
            {
                $originalNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $fromNodeID );
                $originalNode->move( $parentNodeID );
                $existingNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $parentNodeID );
            }
        }


        $existingNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
        $existingNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );
        $existingNode->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
        $existingNode->setAttribute( 'contentobject_is_published', 1 );
        $existingNode->setName( $objectName );

        $existingNode->updateSubTreePath();


        if ( $nodeAssignment->attribute( 'is_main' ) )
        {
            $existingNode->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
            $existingNodes =& eZContentObjectTreeNode::fetchByContentObjectID( $objectID, true );
            foreach( array_keys( $existingNodes ) as $key )
            {
                $node =& $existingNodes[$key];
                $node->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
                $node->store();
            }
        }
/*
        if ( $version->attribute( 'main_parent_node_id' ) == $existingNode->attribute( 'parent_node_id' ) )
        {
            $object->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
        }
*/
        $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
        $version->store();

        $object->store();
        $existingNode->store();

    }


    function removeOldNodes( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );

        $assignedExistingNodes =& $object->attribute( 'assigned_nodes' );

        $curentVersionNodeAssignments = $version->attribute( 'node_assignments' );
        //    var_dump( $curentVersionNodeAssignments );
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
    }

    function registerSearchObject( $objectID, $versionNum )
    {
        include_once( "kernel/classes/ezsearch.php" );
        $object =& eZContentObject::fetch( $objectID );
        // Register the object in the search engine.
        eZSearch::removeObject( $object );
        eZSearch::addObject( $object );

    }

    function checkNotifications( $objectID, $versionNum )
    {
        include_once( "kernel/notification/eznotificationrule.php" );
        include_once( "kernel/notification/eznotificationruletype.php" );
        include_once( "kernel/notification/eznotificationuserlink.php" );
        include_once( "kernel/notification/ezmessage.php" );
        $object =& eZContentObject::fetch( $objectID );
        $allrules =& eZNotificationRule::fetchList( null );
        foreach ( $allrules as $rule )
        {
            $ruleID = $rule->attribute( "id" );
            $ruleClass = $rule->attribute("rule_type");

            if ( is_object( $ruleClass ) && $ruleClass->match( &$object, &$rule ) )
            {
                $users =& eZNotificationUserLink::fetchUserList( $ruleID );
                foreach ( $users as $user )
                {
                    $userID = $user->attribute( "user_id" );
                    $sendMethod = $user->attribute( "send_method" );
                    $sendWeekday = $user->attribute( "send_weekday" );
                    $sendTime = $user->attribute( "send_time" );
                    $destinationAddress = $user->attribute( "destination_address" );

                    // get user domain
                    $ini = eZINI::instance( "site.ini" );
                    $domain = $ini->variable( "SiteSettings", "SiteURL" );

// BEGIN HiO specific code
                    if ( get_class( $ruleClass ) == "ezhiorule" )
                    {
                        $userObject = eZUser::fetch( $userID );
                        ob_start();
                        print_r( $userObject );
                        $userString = ob_get_contents();
                        ob_end_clean();
                        $userHash = md5( "$ruleID\n$userID\n$userString" );

                        switch ( $sendWeekday )
                        {
                            case 1:
                                $weekday = "Mandag";
                            break;
                            case 2:
                                $weekday = "Tirsdag";
                            break;
                            case 3:
                                $weekday = "Onsdag";
                            break;
                            case 4:
                                $weekday = "Torsdag";
                            break;
                            case 5:
                                $weekday = "Fredag";
                            break;
                            case 6:
                                $weekday = "Lørdag";
                            break;
                            case 7:
                                $weekday = "Søndag";
                            break;
                            default:
                                $weekday = "Med en gang";
                            break;
                        }

                        if ( $sendTime == -1 )
                            $time = "Hver time";
                        else
                            $time = $sendTime . ":00";

                        $charset = $ini->variable( "CharacterSettings", "Charset" );
                        $codec =& eZTextCodec::instance( "ISO-8859-1", $charset );

                        $title = "Oppdatering på " . $domain;
                        $codec->convertString( $title );

                        $body1 = "Oppdatering på " . $domain;
                        $body1 .= "\n\nDenne siden er oppdatert:\n";
                        $codec->convertString( $body1 );
                        $body1 .= $object->attribute( "name" );
                        $body1 .= "\nhttp://" . $domain . "/content/view/full/";
                        $body1 .= $object->attribute( "main_node_id" );

                        $body2 = "\n\nDette er en automatisk generert melding. Den ble sendt til deg";
                        $body2 .= "\nfordi du har startet et abonnement med følgende regler:";
                        $body2 .= "\nSøkeord: ";
                        $codec->convertString( $body2 );
                        $body2 .= $rule->attribute( "keyword" );

                        $body3 = "\nUkedag: " . $weekday;
                        $body3 .= "\nTime: " . $time;
                        $body3 .= "\n\nHvis du vil avslutte abonnementet, følg denne lenken:\n";
                        $body3 .= "http://" . $domain . "/notification/remove/" . $ruleID;
                        $body3 .= "/" . $userID . "/" . $userHash;
                        $body3 .= "\n\n\nAdministrator";
                        $codec->convertString( $body3 );

                        $body = $body1 . $body2 . $body3;
                    }
                    else
                    {
// END HiO specific code
                        $title = "New publishing notification";
                        $body = $object->attribute( "name" );
                        $body .= "\nhttp://" .  $domain . "/content/view/full/";
                        $body .=  $object->attribute( "main_node_id" );
                        $body .= "\n\n\nAdministrator";
// BEGIN HiO specific code
                    }
// END HiO specific code

                    $message =& eZMessage::create( $sendMethod, $sendWeekday, $sendTime,
                                                   $destinationAddress, $title, $body );
                    $message->store();
                    $msgid = $message->attribute( "id" );
                    $mymsg = eZMessage::fetch( $msgid );
                }
            }
        }
    }

}

?>
