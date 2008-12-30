<?php
//
// Definition of eZWaitUntilDateType class
//
// Created on: <09-Jan-2003 15:01:18 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file
*/

/*!
  \class eZWaitUntilDateType ezwaituntildatetype.php
  \brief The class eZWaitUntilDateType does

*/
class eZWaitUntilDateType  extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = "ezwaituntildate";

    /*!
     Constructor
    */
    function eZWaitUntilDateType()
    {
        $this->eZWorkflowEventType( eZWaitUntilDateType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "Wait until date" ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'before',
                                                                               'after' ) ) ) );
    }

    function execute( $process, $event )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $object = eZContentObject::fetch( $parameters['object_id'] );

        if ( !$object )
        {
            eZDebugSetting::writeError( 'kernel-workflow-waituntildate','The object with ID '.$parameters['object_id'].' does not exist.', 'eZApproveType::execute() object is unavailable' );
            return eZWorkflowType::STATUS_WORKFLOW_CANCELLED;
        }

        $version = $object->version( $parameters['version'] );
        $objectAttributes = $version->attribute( 'contentobject_attributes' );
        $waitUntilDateObject = $this->workflowEventContent( $event );
        $waitUntilDateEntryList = $waitUntilDateObject->attribute( 'classattribute_id_list' );
        $modifyPublishDate = $event->attribute( 'data_int1' );

        foreach ( array_keys( $objectAttributes ) as $key )
        {
            $objectAttribute = $objectAttributes[$key];
            $contentClassAttributeID = $objectAttribute->attribute( 'contentclassattribute_id' );
            if ( in_array( $objectAttribute->attribute( 'contentclassattribute_id' ), $waitUntilDateEntryList ) )
            {
                $dateTime = $objectAttribute->attribute( 'content' );
                if ( $dateTime instanceof eZDateTime or
                     $dateTime instanceof eZTime or
                     $dateTime instanceof eZDate )
                {
                    if ( time() < $dateTime->timeStamp() )
                    {
                        $this->setInformation( "Event delayed until " . $dateTime->toString( true ) );
                        $this->setActivationDate( $dateTime->timeStamp() );
                        return eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT;
                    }
                    else if ( $dateTime->isValid() and $modifyPublishDate )
                    {
                        $object->setAttribute( 'published', $dateTime->timeStamp() );
                        $object->store();
                    }
                    else
                    {
                        return eZWorkflowType::STATUS_ACCEPTED;
//                        return eZWorkflowType::STATUS_WORKFLOW_DONE;
                    }
                }
                else
                {
                    return eZWorkflowType::STATUS_ACCEPTED;
//                   return eZWorkflowType::STATUS_WORKFLOW_DONE;
                }
            }
        }
        return eZWorkflowType::STATUS_ACCEPTED;
//        return eZWorkflowType::STATUS_WORKFLOW_DONE;
    }

    function attributes()
    {
        return array_merge( array( 'contentclass_list',
                                   'contentclassattribute_list',
                                   'has_class_attributes' ),
                            eZWorkflowEventType::attributes() );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case 'contentclass_list' :
            {
                return eZContentClass::fetchList( eZContentClass::VERSION_STATUS_DEFINED, true );
            }break;
            case 'contentclassattribute_list' :
            {
//                $postvarname = 'WorkflowEvent' . '_event_ezwaituntildate_' .'class_' . $workflowEvent->attribute( 'id' ); and $http->hasPostVariable( $postvarname )
                if ( isset ( $GLOBALS['eZWaitUntilDateSelectedClass'] ) )
                {
                    $classID = $GLOBALS['eZWaitUntilDateSelectedClass'];
                }
                else
                {
                    // if nothing was preselected, we will use the first one:
                    // POSSIBLE ENHANCEMENT: in the common case, the contentclass_list fetch will be called twice
                    $classList = eZWaitUntilDateType::attribute( 'contentclass_list' );
                    if ( isset( $classList[0] ) )
                        $classID = $classList[0]->attribute( 'id' );
                    else
                        $classID = false;
                }
                if ( $classID )
                {
                   return eZContentClassAttribute::fetchListByClassID( $classID );
                }
                return array();
            }break;
            case 'has_class_attributes' :
            {
                // for the backward compatibility:
                return 1;
            }break;
            default:
                return eZWorkflowEventType::attribute( $attr );
        }
    }

    function customWorkflowEventHTTPAction( $http, $action, $workflowEvent )
    {
        $id = $workflowEvent->attribute( "id" );
        switch ( $action )
        {
            case "new_classelement" :
            {
                $waitUntilDate = $workflowEvent->content( );

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
                $waitUntilDate = $workflowEvent->content( );

                foreach( $arrayRemove as $entryID )
                {
                    $waitUntilDate->removeEntry( $id, $entryID, $version );
                }
            }break;
            case "load_class_attribute_list" :
            {
                $postvarname = 'WorkflowEvent' . '_event_ezwaituntildate_' .'class_' . $workflowEvent->attribute( 'id' );
                if ( $http->hasPostVariable( $postvarname ) )
                {
                    $classIDList = $http->postVariable( 'WorkflowEvent' . '_event_ezwaituntildate_' .'class_' . $workflowEvent->attribute( 'id' ) );
                    $GLOBALS['eZWaitUntilDateSelectedClass'] = $classIDList[0];
                }
                else
                {
                    eZDebug::writeError( "no class selected" );
                }
            }break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZEnumType" );
            }break;
        }

    }

    function fetchHTTPInput( $http, $base, $event )
    {
        $modifyDateVariable = $base . "_data_waituntildate_modifydate_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $modifyDateVariable ) )
        {
            $modifyDateValue = (int)$http->postVariable( $modifyDateVariable );
            $event->setAttribute( 'data_int1', $modifyDateValue );
        }
    }

    function workflowEventContent( $event )
    {
        $id = $event->attribute( "id" );
        $version = $event->attribute( "version" );
        return new eZWaitUntilDate( $id, $version );
    }

    function storeEventData( $event, $version )
    {
        $event->content()->setVersion( $version );
    }

    function storeDefinedEventData( $event )
    {
        $id = $event->attribute( 'id' );
        $version = 1;
        $waitUntilDateVersion1 = new eZWaitUntilDate( $id, $version );
        $waitUntilDateVersion1->setVersion( 0 ); //strange name but we are creating version 0 here
        eZWaitUntilDate::removeWaitUntilDateEntries( $id, 1 );
    }

}

eZWorkflowEventType::registerEventType( eZWaitUntilDateType::WORKFLOW_TYPE_STRING, "eZWaitUntilDateType" );


?>
