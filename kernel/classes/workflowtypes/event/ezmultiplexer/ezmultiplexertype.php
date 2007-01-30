<?php
//
// Definition of eZMultiplexerType class
//
// Created on: <01-Ноя-2002 15:34:23 sp>
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

/*! \file ezmultiplexertype.php
*/

/*!
  \class eZMultiplexerType ezmultiplexertype.php
  \brief The class eZMultiplexerType does

*/
define( "EZ_WORKFLOW_TYPE_MULTIPLEXER_ID", "ezmultiplexer" );

class eZMultiplexerType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZMultiplexerType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_MULTIPLEXER_ID, ezi18n( 'kernel/workflow/event', 'Multiplexer' ) );
    }

    function &attributeDecoder( &$event, $attr )
    {
        switch ( $attr )
        {
            case 'selected_sections':
            {
                $attributeValue = trim( $event->attribute( 'data_text1' ) );
                $returnValue = empty( $attributeValue ) ? array( -1 ) : explode( ',', $attributeValue );
            }break;

            case 'selected_classes':
            {
                $attributeValue = trim( $event->attribute( 'data_text3' ) );
                $returnValue = empty( $attributeValue ) ? array( -1 ) : explode( ',', $attributeValue );
            }break;

            case 'selected_usergroups':
            {
                $attributeValue = trim( $event->attribute('data_text2') );
                $returnValue = empty( $attributeValue ) ? array() : explode( ',', $attributeValue );
            }break;

            case 'selected_workflow':
            {
                $returnValue = $event->attribute( 'data_int1' );
            }break;

            default:
                $returnValue = null;
        }
        return $returnValue;
    }

    function typeFunctionalAttributes()
    {
        return array( 'selected_sections',
                      'selected_usergroups',
                      'selected_classes',
                      'selected_workflow' );
    }

    function attributes()
    {
        return array_merge( array( 'sections',
                                   'contentclass_list',
                                   'workflow_list',
                                   'usergroups' ),
                            eZWorkflowEventType::attributes() );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'sections':
            {
                include_once( 'kernel/classes/ezsection.php' );
                $sections = eZSection::fetchList( false );
                foreach ( array_keys( $sections ) as $key )
                {
                    $section =& $sections[$key];
                    $section['Name'] = $section['name'];
                    $section['value'] = $section['id'];
                }
                return $sections;
            }
            break;

            case 'usergroups':
            {
                $groups = eZPersistentObject::fetchObjectList( eZContentObject::definition(), array( 'id', 'name' ),
                                                                array( 'contentclass_id' => 3 ), null, null, false );
                foreach ( array_keys( $groups ) as $key )
                {
                    $group =& $groups[$key];
                    $group['Name'] = $group['name'];
                    $group['value'] = $group['id'];
                }
                return $groups;
            }
            break;

            case 'contentclass_list':
            {
                $classes = eZContentClass::fetchList( EZ_CLASS_VERSION_STATUS_DEFINED, true, false, array( 'name' => 'asc' ) );
                $classList = array();
                for ( $i = 0; $i < count( $classes ); $i++ )
                {
                    $classList[$i]['Name'] = $classes[$i]->attribute( 'name' );
                    $classList[$i]['value'] = $classes[$i]->attribute( 'id' );
                }
                return $classList;
            }
            break;

            case 'workflow_list':
            {
                $workflows = eZWorkflow::fetchList();
                $workflowList = array();
                for ( $i = 0; $i < count( $workflows ); $i++ )
                {
                    $workflowList[$i]['Name'] = $workflows[$i]->attribute( 'name' );
                    $workflowList[$i]['value'] = $workflows[$i]->attribute( 'id' );
                }
                return $workflowList;
            }
            break;
        }
        return eZWorkflowEventType::attribute( $attr );
    }

    function execute( &$process, &$event )
    {
        $processParameters = $process->attribute( 'parameter_list' );
        $storeProcessParameters = false;
        $classID = false;
        $objectID = false;
        $sectionID = false;

        if ( isset( $processParameters['object_id'] ) )
        {
            $objectID = $processParameters['object_id'];
            $object =& eZContentObject::fetch( $objectID );
            if ( $object )
            {
                $sectionID = $object->attribute( 'section_id' );
                $class =& $object->attribute( 'content_class' );
                if ( $class )
                {
                    $classID = $class->attribute( 'id' );
                }
            }
        }

        $userArray = explode( ',', $event->attribute( 'data_text2' ) );
        $classArray = explode( ',', $event->attribute( 'data_text3' ) );

        if ( !isset( $processParameters['user_id'] ) )
        {
            $user =& eZUser::currentUser();
            $userID = $user->id();
            $processParameters['user_id'] = $userID;
            $storeProcessParameters = true;
        }
        else
        {
            $userID = $processParameters['user_id'];
            $user = eZUser::fetch( $userID );
            if ( get_class( $user ) != 'ezuser' )
            {
                $user =& eZUser::currentUser();
                $userID = $user->id();
                $processParameters['user_id'] = $userID;
                $storeProcessParameters = true;
            }
        }
        $userGroups = $user->attribute( 'groups' );
        $inExcludeGroups = count( array_intersect( $userGroups, $userArray ) ) != 0;

        if ( $storeProcessParameters )
        {
            $process->setParameters( $processParameters );
            $process->store();
        }

        if ( ( !$inExcludeGroups ) &&
             ( in_array( -1, $classArray ) ||
               in_array( $classID, $classArray ) ) )
        {
            $sectionArray = explode( ',', $event->attribute( 'data_text1' ) );

            if ( in_array( $sectionID, $sectionArray ) ||
                 in_array( -1, $sectionArray ) )
            {
                $workflowToRun = $event->attribute( 'data_int1' );

                $childParameters = array_merge( $processParameters,
                                                array( 'workflow_id' => $workflowToRun,
                                                       'user_id' => $userID,
                                                       'parent_process_id' => $process->attribute( 'id' )
                                                       ) );

                $childProcessKey = eZWorkflowProcess::createKey( $childParameters );

                $childProcessArray = eZWorkflowProcess::fetchListByKey( $childProcessKey );
                $childProcess =& $childProcessArray[0];
                if ( $childProcess == null )
                {
                    $childProcess = eZWorkflowProcess::create( $childProcessKey, $childParameters );
                    $childProcess->store();
                }

                $workflow = eZWorkflow::fetch( $childProcess->attribute( "workflow_id" ) );
                $workflowEvent = null;

                if ( $childProcess->attribute( "event_id" ) != 0 )
                    $workflowEvent = eZWorkflowEvent::fetch( $childProcess->attribute( "event_id" ) );

                $childStatus = $childProcess->run( $workflow, $workflowEvent, $eventLog );
                $childProcess->store();

//                 eZDebug::writeNotice( $childProcess, "childProcess" );
//                 eZDebug::writeNotice( $childStatus, "childStatus" );

                if ( $childStatus ==  EZ_WORKFLOW_STATUS_FETCH_TEMPLATE )
                {
                    $process->Template =& $childProcess->Template;
                    return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
                }
                else if ( $childStatus ==  EZ_WORKFLOW_STATUS_REDIRECT )
                {
                    $process->RedirectUrl =& $childProcess->RedirectUrl;
                    return EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT;
                }
                else if ( $childStatus ==  EZ_WORKFLOW_STATUS_DONE  )
                {
                    $childProcess->remove();
                    return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
                }
                else if ( $childStatus == EZ_WORKFLOW_STATUS_CANCELLED || $childStatus == EZ_WORKFLOW_STATUS_FAILED )
                {
                    $childProcess->remove();
                    return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
                }
                return $childProcess->attribute( 'last_event_status' );

            }
        }
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
    }

    function initializeEvent( &$event )
    {
    }


    function fetchHTTPInput( &$http, $base, &$event )
    {
        $sectionsVar = $base . "_event_ezmultiplexer_section_ids_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $sectionsVar ) )
        {
            $sectionsArray = $http->postVariable( $sectionsVar );
            if ( in_array( '-1', $sectionsArray ) )
            {
                $sectionsArray = array( -1 );
            }
            $sectionsString = implode( ',', $sectionsArray );
            $event->setAttribute( "data_text1", $sectionsString );
        }

        $usersVar = $base . "_event_ezmultiplexer_not_run_ids_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $usersVar ) )
        {
            $usersArray = $http->postVariable( $usersVar );
            if ( in_array( '-1', $usersArray ) )
            {
                $usersArray = array( -1 );
            }
            $usersString = implode( ',', $usersArray );
            $event->setAttribute( "data_text2", $usersString );
        }

        $classesVar = $base . "_event_ezmultiplexer_class_ids_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $classesVar ) )
        {
            $classesArray = $http->postVariable( $classesVar );
            if ( in_array( '-1', $classesArray ) )
            {
                $classesArray = array( -1 );
            }
            $classesString = implode( ',', $classesArray );
            $event->setAttribute( "data_text3", $classesString );
        }

        $workflowVar = $base . "_event_ezmultiplexer_workflow_id_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $workflowVar ) )
        {
            $workflowID = $http->postVariable( $workflowVar );
            $event->setAttribute( "data_int1", $workflowID );
        }
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_MULTIPLEXER_ID, 'ezmultiplexertype' );

?>
