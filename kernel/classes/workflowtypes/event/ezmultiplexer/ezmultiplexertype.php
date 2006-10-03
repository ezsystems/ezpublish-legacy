<?php
//
// Definition of eZMultiplexerType class
//
// Created on: <01-Ноя-2002 15:34:23 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

  WorkflowEvent storage fields : data_text1 - selected_sections
                                 data_text2 - selected_usergroups
                                 data_text3 - selected_classes
                                 data_int1  - selected_workflow
                                 data_int2  - language_list
                                 data_int3  - content object version option
*/
define( "EZ_WORKFLOW_TYPE_MULTIPLEXER_ID", "ezmultiplexer" );
define( 'EZ_MULTIPLEXER_VERSION_OPTION_FIRST_ONLY', 1 );
define( 'EZ_MULTIPLEXER_VERSION_OPTION_EXCEPT_FIRST', 2 );
define( 'EZ_MULTIPLEXER_VERSION_OPTION_ALL', EZ_MULTIPLEXER_VERSION_OPTION_FIRST_ONLY | EZ_MULTIPLEXER_VERSION_OPTION_EXCEPT_FIRST );

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

            case 'language_list':
            {
                if ( $event->attribute( 'data_int2' ) == 0 )
                {
                    $returnValue = array();
                    return $returnValue;
                }
                include_once( 'kernel/classes/ezcontentlanguage.php' );
                $languages = eZContentLanguage::languagesByMask( $event->attribute( 'data_int2' ) );
                $returnValue = array();
                foreach ( $languages as $language )
                {
                    $returnValue[$language->attribute( 'id' )] = $language->attribute( 'name' );
                }
                return $returnValue;
            } break;
            case 'version_option':
            {
                $retValue = EZ_MULTIPLEXER_VERSION_OPTION_ALL & $event->attribute( 'data_int3' );
                return $retValue;
            } break;
        }
        $retValue = null;
        return $retValue;
    }

    function typeFunctionalAttributes()
    {
        return array( 'selected_sections',
                      'selected_usergroups',
                      'selected_classes',
                      'selected_workflow',
                      'language_list',
                      'version_option' );
    }

    function attributes()
    {
        return array_merge( array( 'sections',
                                   'languages',
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

            case 'languages':
            {
                include_once( 'kernel/classes/ezcontentlanguage.php' );
                $languages = eZContentLanguage::fetchList();
                return $languages;
            }break;

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
        $languageID = 0;

        if ( isset( $processParameters['object_id'] ) )
        {
            $objectID = $processParameters['object_id'];
            $object =& eZContentObject::fetch( $objectID );
            if ( $object )
            {
                // Examine if the published version contains one of the languages we
                // match for.
                if ( isset( $processParameters['version'] ) )
                {
                    $versionID = $processParameters['version'];
                    $version =& $object->version( $versionID );

                    if ( is_object( $version ) )
                    {
                        $version_option = $event->attribute( 'version_option' );
                        if ( ( $version_option == EZ_MULTIPLEXER_VERSION_OPTION_FIRST_ONLY and $processParameters['version'] > 1 ) or
                             ( $version_option == EZ_MULTIPLEXER_VERSION_OPTION_EXCEPT_FIRST and $processParameters['version'] == 1 ) )
                        {
                            return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
                        }

                        // If the language ID is part of the mask the result is non-zero.
                        $languageID = (int)$version->attribute( 'initial_language_id' );
                    }
                }
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
        $languageMask = $event->attribute( 'data_int2' );

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

        // All languages match by default
        $hasLanguageMatch = true;
        if ( $languageMask != 0 )
        {
            // Match ID with mask.
            $hasLanguageMatch = (bool)( $languageMask & $languageID );
        }

        if ( $hasLanguageMatch &&
             ( !$inExcludeGroups ) &&
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

        $languageVar = $base . "_event_ezmultiplexer_languages_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $languageVar ) )
        {
            $languageArray = $http->postVariable( $languageVar );
            if ( in_array( '-1', $languageArray ) )
            {
                $languageArray = array();
            }
            $languageMask = 0;
            foreach ( $languageArray as $languageID )
            {
                $languageMask |= $languageID;
            }
            $event->setAttribute( "data_int2", $languageMask );
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

        $versionOptionVar = $base . "_event_ezmultiplexer_version_option_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $versionOptionVar ) )
        {
            $versionOptionArray = $http->postVariable( $versionOptionVar );
            $versionOption = 0;
            if ( is_array( $versionOptionArray ) )
            {
                foreach ( $versionOptionArray as $vv )
                {
                    $versionOption = $versionOption | $vv;
                }
            }
            $versionOption = $versionOption & EZ_MULTIPLEXER_VERSION_OPTION_ALL;
            $event->setAttribute( 'data_int3', $versionOption );
        }
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_MULTIPLEXER_ID, 'ezmultiplexertype' );

?>
