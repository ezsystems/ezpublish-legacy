<?php
//
// Definition of eZWaitUntilDateType class
//
// Created on: <09-Jan-2003 15:01:18 sp>
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

/*! \file ezwaituntildatetype.php
*/

/*!
  \class eZWaitUntilDateType ezwaituntildatetype.php
  \brief The class eZWaitUntilDateType does

*/
include_once( 'kernel/classes/workflowtypes/event/ezwaituntildate/ezwaituntildate.php' );
define( "EZ_WORKFLOW_TYPE_WAIT_UNTIL_DATE_ID", "ezwaituntildate" );

class eZWaitUntilDateType  extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZWaitUntilDateType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_WAIT_UNTIL_DATE_ID, ezi18n( 'kernel/workflow/event', "Wait until date" ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'before',
                                                                               'after' ) ) ) );
    }

    function execute( &$process, &$event )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $object =& eZContentObject::fetch( $parameters['object_id'] );
        $version =& $object->version( $parameters['version'] );
        $objectAttributes = $version->attribute( 'contentobject_attributes' );
        $waitUntilDateObject =& $this->workflowEventContent( $event );
        $waitUntilDateEntryList = $waitUntilDateObject->attribute( 'classattribute_id_list' );
        $modifyPublishDate = $event->attribute( 'data_int1' );
        eZDebug::writeDebug( 'executing publish on time event' );
//        eZDebug::writeDebug( $waitUntilDateEntryList, 'executing publish on time event' );
//        eZDebug::writeDebug( $objectAttributes, 'publish on time event' );

        foreach ( array_keys( $objectAttributes ) as $key )
        {
            $objectAttribute =& $objectAttributes[$key];
            $contentClassAttributeID = $objectAttribute->attribute( 'contentclassattribute_id' );
//            eZDebug::writeDebug( $waitUntilDateEntryList, "checking if $contentClassAttributeID in array:" );
            if ( in_array( $objectAttribute->attribute( 'contentclassattribute_id' ), $waitUntilDateEntryList ) )
            {
                include_once( "lib/ezlocale/classes/ezdatetime.php" );
                $dateTime =& $objectAttribute->attribute( 'content' );
                if ( get_class( $dateTime ) == 'ezdatetime' or
                     get_class( $dateTime ) == 'eztime' or
                     get_class( $dateTime ) == 'ezdate' )
                {
                    if ( eZDateTime::currentTimeStamp() < $dateTime->timeStamp() )
                    {
                        $this->setInformation( "Event delayed until " . $dateTime->toString( true ) );
                        $this->setActivationDate( $dateTime->timeStamp() );
//                        eZDebug::writeDebug( $dateTime->toString(), 'executing publish on time event' );
                        return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON;
                    }
                    else if ( $dateTime->isValid() and $modifyPublishDate )
                    {
                        $object->setAttribute( 'published', $dateTime->timeStamp() );
                        $object->store();
                    }
                    else
                    {
                        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
//                        return EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE;
                    }
                }
                else
                {
                    return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
//                   return EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE;
                }
            }
        }
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
//        return EZ_WORKFLOW_TYPE_STATUS_WORKFLOW_DONE;
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr , array( 'contentclass_list', 'contentclassattribute_list', 'has_class_attributes' ) )
            or eZWorkflowEventType::hasAttribute( $attr );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'contentclass_list' :
            {
                $classList =& eZPersistentObject::fetchObjectList( eZContentClass::definition(), array( 'id', 'name' ), array( 'version' => 0 ),null,null,false );
                return $classList;

            }break;
            case 'contentclassattribute_list' :
            {
//                $postvarname = 'WorkflowEvent' . '_event_ezwaituntildate_' .'class_' . $workflowEvent->attribute( 'id' ); and $http->hasPostVariable( $postvarname )
                if ( isset ( $GLOBALS['eZWaitUntilDateSelectedClass'] ) )
                {
                    $classID = $GLOBALS['eZWaitUntilDateSelectedClass'];
                    $attributeList =& eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                                           array( 'id', 'name', 'data_type_string' ),
                                                                           array( 'contentclass_id'=> $classID,
                                                                                  'version' => 0 ),null,null,false );

                    eZDebug::writeDebug( $classID, 'class id in load attribute list' );
                }
                else
                    $attributeList = array();
                return $attributeList;
            }break;
            case 'has_class_attributes' :
            {
                if ( isset ( $GLOBALS['eZWaitUntilDateSelectedClass'] ) )
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }break;
            default:
                return eZWorkflowEventType::attribute( $attr );
        }
    }

    function customWorkflowEventHTTPAction( &$http, $action, &$workflowEvent )
    {
        $id = $workflowEvent->attribute( "id" );
        switch ( $action )
        {
            case "new_classelement" :
            {
                $waitUntilDate =& $workflowEvent->content( );

                $classIDList = $http->postVariable( 'WorkflowEvent' . '_event_ezwaituntildate_' . 'class_' . $workflowEvent->attribute( 'id' )  );

                $classAttributeIDList = $http->postVariable( 'WorkflowEvent' . '_event_ezwaituntildate_' . 'classattribute_' . $workflowEvent->attribute( 'id' )  );


                $waitUntilDate->addEntry(  $classAttributeIDList[0], $classIDList[0] );
                $workflowEvent->setContent( $waitUntilDate );
            }break;
            case "remove_selected" :
            {
                $version = $workflowEvent->attribute( "version" );
                $postvarname = "WorkflowEvent" . "_data_waituntildate_remove_" . $workflowEvent->attribute( "id" );
                $arrayRemove = $http->postVariable( $postvarname );
                eZDebug::writeDebug( $arrayRemove, 'remove params 0' );

                foreach( $arrayRemove as $entryID )
                {
                    eZDebug::writeDebug( "$id - $entryID - $version ", 'remove params' );
                    eZWaitUntilDate::removeEntry( $id, $entryID, $version );
                }
            }break;
            case "load_class_attribute_list" :
            {
                $postvarname = 'WorkflowEvent' . '_event_ezwaituntildate_' .'class_' . $workflowEvent->attribute( 'id' );
                if ( $http->hasPostVariable( $postvarname ) )
                {
                    $classIDList = $http->postVariable( 'WorkflowEvent' . '_event_ezwaituntildate_' .'class_' . $workflowEvent->attribute( 'id' ) );
                    eZDebug::writeDebug($classIDList, "classIDLIst" );
//                    $http->setSessionVariable( 'eZWaitUntilDateSelectedClass',  $classIDList[0] );
                    $GLOBALS['eZWaitUntilDateSelectedClass'] = $classIDList[0];
                }
                else
                {
                    eZDebug::writeDebug( "no class selected" );
                }
            }break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZEnumType" );
            }break;
        }

    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $modifyDateVariable = $base . "_data_waituntildate_modifydate_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $modifyDateVariable ) )
        {
            $modifyDateValue = (int)$http->postVariable( $modifyDateVariable );
            $event->setAttribute( 'data_int1', $modifyDateValue );
        }
    }

    function &workflowEventContent( &$event )
    {
        $id = $event->attribute( "id" );
        $version = $event->attribute( "version" );
        $waitUntilDate =& new eZWaitUntilDate( $id, $version );
        return $waitUntilDate;
    }

    function storeEventData( &$event, $version )
    {
        $waitUntilDate =& $event->content();
        $waitUntilDate->setVersion( $version );

    }

}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_WAIT_UNTIL_DATE_ID, "ezwaituntildatetype" );


?>
