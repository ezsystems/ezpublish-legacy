<?php
//
// Definition of eZMultiplexerType class
//
// Created on: <01-Ноя-2002 15:34:23 sp>
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
                if ( trim( $event->attribute( 'data_text1' ) ) == '' )
                    $sections = array( -1 );
                else
                    $sections = explode( ',', $event->attribute( 'data_text1' ) );
                return $sections;
            }
            break;

            case 'selected_classes':
            {
                if ( trim( $event->attribute( 'data_text3' ) ) == '' )
                    $users = array( -1 );
                else
                    $users = explode( ',', $event->attribute( 'data_text3' ) );
                return $users;
            }
            break;

            case 'selected_usergroups':
            {
                $groups = explode( ',', $event->attribute( 'data_text2' ) );
                return $groups;
            }

            case 'selected_workflow':
            {
                return $event->attribute( 'data_int1' );
            }
        }
        return null;
    }

    function typeFunctionalAttributes()
    {
        return array( 'selected_sections',
                      'selected_usergroups',
                      'selected_classes',
                      'selected_workflow' );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'sections':
            {
                include_once( 'kernel/classes/ezsection.php' );
                $sections =& eZSection::fetchList( false );
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
                $groups =& eZPersistentObject::fetchObjectList( eZContentObject::definition(), array( 'id', 'name' ),
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
                $classes =& eZContentClass::fetchList();
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
                $workflows =& eZWorkflow::fetchList();
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

    function hasAttribute( $attr )
    {
        return in_array( $attr, array( 'sections',
                                       'contentclass_list',
                                       'workflow_list',
                                       'usergroups' ) ) || eZWorkflowEventType::hasAttribute( $attr );
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
            $user =& eZUser::fetch( $userID );
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

                if ( isSet( $processParameters['node_id'] ) )
                {
                    $childParameters = array_merge( $processParameters,
                                                    array( 'workflow_id' => $workflowToRun,
                                                           'user_id' => $userID
                                                           ) );
                }
                else
                {
                    $childParameters = array_merge( $processParameters,
                                                    array( 'workflow_id' => $workflowToRun,
                                                           'user_id' => $userID
                                                           ) );
                }

                $childProcessKey = eZWorkflowProcess::createKey( $childParameters );

                $childProcessArray =& eZWorkflowProcess::fetchListByKey( $childProcessKey );
                $childProcess =& $childProcessArray[0];
                if ( $childProcess == null )
                {
                    $childProcess =& eZWorkflowProcess::create( $childProcessKey, $childParameters );
                    $childProcess->store();
                }

                $workflow =& eZWorkflow::fetch( $childProcess->attribute( "workflow_id" ) );
                $workflowEvent = null;

                if ( $childProcess->attribute( "event_id" ) != 0 )
                    $workflowEvent =& eZWorkflowEvent::fetch( $childProcess->attribute( "event_id" ) );

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
        $usersArray = $http->postVariable( $usersVar );
        $usersString = implode( ',', $usersArray );
        $event->setAttribute( "data_text2", $usersString );

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
