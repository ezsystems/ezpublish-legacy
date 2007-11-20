<?php
//
// Definition of eZApproveType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*!
  \class eZApproveType ezapprovetype.php
  \brief Event type for user approvals

  WorkflowEvent storage fields : data_text1 - selected_sections
                                 data_text2 - selected_usergroups
                                 data_text3 - approve_users
                                 data_text4 - approve_groups
                                 data_int2  - language_list
                                 data_int3  - content object version option
*/

//include_once( "kernel/classes/ezworkflowtype.php" );
//include_once( 'kernel/classes/collaborationhandlers/ezapprove/ezapprovecollaborationhandler.php' );

class eZApproveType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = "ezapprove";

    const COLLABORATION_NOT_CREATED = 0;
    const COLLABORATION_CREATED = 1;

    const VERSION_OPTION_FIRST_ONLY = 1;
    const VERSION_OPTION_EXCEPT_FIRST = 2;
    const VERSION_OPTION_ALL = 3;

    function eZApproveType()
    {
        $this->eZWorkflowEventType( eZApproveType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "Approve" ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'before' ) ) ) );
    }

    function attributeDecoder( $event, $attr )
    {
        switch ( $attr )
        {
            case 'selected_sections':
            {
                $attributeValue = trim( $event->attribute( 'data_text1' ) );
                $returnValue = empty( $attributeValue ) ? array( -1 ) : explode( ',', $attributeValue );
            }break;

            case 'approve_users':
            {
                $attributeValue = trim( $event->attribute( 'data_text3' ) );
                $returnValue = empty( $attributeValue ) ? array() : explode( ',', $attributeValue );
            }break;

            case 'approve_groups':
            {
                $attributeValue = trim( $event->attribute( 'data_text4' ) );
                $returnValue = empty( $attributeValue ) ? array() : explode( ',', $attributeValue );
            }break;

            case 'selected_usergroups':
            {
                $attributeValue = trim( $event->attribute( 'data_text2' ) );
                $returnValue = empty( $attributeValue ) ? array() : explode( ',', $attributeValue );
            }break;

            case 'language_list':
            {
                $returnValue = array();
                $attributeValue = $event->attribute( 'data_int2' );
                if ( $attributeValue != 0 )
                {
                    //include_once( 'kernel/classes/ezcontentlanguage.php' );
                    $languages = eZContentLanguage::languagesByMask( $attributeValue );
                    foreach ( $languages as $language )
                    {
                        $returnValue[$language->attribute( 'id' )] = $language->attribute( 'name' );
                    }
                }
            }break;

            case 'version_option':
            {
                $returnValue = eZApproveType::VERSION_OPTION_ALL & $event->attribute( 'data_int3' );
            }break;

            default:
                $returnValue = null;
        }
        return $returnValue;
    }

    function typeFunctionalAttributes( )
    {
        return array( 'selected_sections',
                      'approve_users',
                      'approve_groups',
                      'selected_usergroups',
                      'language_list',
                      'version_option' );
    }

    function attributes()
    {
        return array_merge( array( 'sections',
                                   'languages',
                                   'users',
                                   'usergroups' ),
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
            case 'sections':
            {
                //include_once( 'kernel/classes/ezsection.php' );
                $sections = eZSection::fetchList( false );
                foreach ( $sections as $key => $section )
                {
                    $sections[$key]['Name'] = $section['name'];
                    $sections[$key]['value'] = $section['id'];
                }
                return $sections;
            }break;
            case 'languages':
            {
                //include_once( 'kernel/classes/ezcontentlanguage.php' );
                return eZContentLanguage::fetchList();
            }break;
        }
        return eZWorkflowEventType::attribute( $attr );
    }

    function execute( $process, $event )
    {
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $process, 'eZApproveType::execute' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'eZApproveType::execute' );
        $parameters = $process->attribute( 'parameter_list' );
        $versionID =& $parameters['version'];
        $object = eZContentObject::fetch( $parameters['object_id'] );

        if ( !$object )
        {
            eZDebugSetting::writeError( 'kernel-workflow-approve', $parameters['object_id'], 'eZApproveType::execute' );
            return eZWorkflowType::STATUS_WORKFLOW_CANCELLED;
        }

        // version option checking
        $version_option = $event->attribute( 'version_option' );
        if ( ( $version_option == eZApproveType::VERSION_OPTION_FIRST_ONLY and $parameters['version'] > 1 ) or
             ( $version_option == eZApproveType::VERSION_OPTION_EXCEPT_FIRST and $parameters['version'] == 1 ) )
        {
            return eZWorkflowType::STATUS_ACCEPTED;
        }

        /*
          If we run event first time ( when we click publish in admin ) we do not have user_id set in workflow process,
          so we take current user and store it in workflow process, so next time when we run event from cronjob we fetch
          user_id from there.
         */
        if ( $process->attribute( 'user_id' ) == 0 )
        {
            $user = eZUser::currentUser();
            $process->setAttribute( 'user_id', $user->id() );
        }
        else
        {
            $user = eZUser::instance( $process->attribute( 'user_id' ) );
        }

        $userGroups = array_merge( $user->attribute( 'groups' ), array( $user->attribute( 'contentobject_id' ) ) );
        $workflowSections = explode( ',', $event->attribute( 'data_text1' ) );
        $workflowGroups = explode( ',', $event->attribute( 'data_text2' ) );
        $editors = explode( ',', $event->attribute( 'data_text3' ) ); //$event->attribute( 'data_int1' );
        $approveGroups = explode( ',', $event->attribute( 'data_text4' ) );
        $languageMask = $event->attribute( 'data_int2' );

        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $user, 'eZApproveType::execute::user' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $userGroups, 'eZApproveType::execute::userGroups' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $editors, 'eZApproveType::execute::editor' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowSections, 'eZApproveType::execute::workflowSections' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowGroups, 'eZApproveType::execute::workflowGroups' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $languageMask, 'eZApproveType::execute::languageMask' );
        eZDebugSetting::writeDebug( 'kernel-workflow-approve', $object->attribute( 'section_id'), 'eZApproveType::execute::section_id' );

        $section = $object->attribute( 'section_id' );
        $correctSection = false;

        if ( !in_array( $section, $workflowSections ) && !in_array( -1, $workflowSections ) )
        {
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            if ( $assignedNodes )
            {
                foreach( $assignedNodes as $assignedNode )
                {
                    $parent = $assignedNode->attribute( 'parent' );
                    $parentObject = $parent->object();
                    $section = $parentObject->attribute( 'section_id');

                    if ( in_array( $section, $workflowSections ) )
                    {
                        $correctSection = true;
                        break;
                    }
                }
            }
        }
        else
            $correctSection = true;

        $inExcludeGroups = count( array_intersect( $userGroups, $workflowGroups ) ) != 0;

        $userIsEditor = ( in_array( $user->id(), $editors ) ||
                          count( array_intersect( $userGroups, $approveGroups ) ) != 0 );

        // All languages match by default
        $hasLanguageMatch = true;
        if ( $languageMask != 0 )
        {
            // Examine if the published version contains one of the languages we
            // match for.
            $version = $object->version( $versionID );
            // If the language ID is part of the mask the result is non-zero.
            $languageID = (int)$version->attribute( 'initial_language_id' );
            $hasLanguageMatch = (bool)( $languageMask & $languageID );
        }

        if ( $hasLanguageMatch and
             !$userIsEditor and
             !$inExcludeGroups and
             $correctSection )
        {

            /* Get user IDs from approve user groups */
            $ini = eZINI::instance();
            $userClassIDArray = array( $ini->variable( 'UserSettings', 'UserClassID' ) );
            $approveUserIDArray = array();
            foreach( $approveGroups as $approveUserGroupID )
            {
                if (  $approveUserGroupID != false )
                {
                    $approveUserGroup = eZContentObject::fetch( $approveUserGroupID );
                    if ( isset( $approveUserGroup ) )
                        foreach( $approveUserGroup->attribute( 'assigned_nodes' ) as $assignedNode )
                        {
                            $userNodeArray =& $assignedNode->subTree( array( 'ClassFilterType' => 'include',
                                                                             'ClassFilterArray' => $userClassIDArray,
                                                                             'Limitation' => array() ) );
                            foreach( $userNodeArray as $userNode )
                            {
                                $approveUserIDArray[] = $userNode->attribute( 'contentobject_id' );
                            }
                        }
                }
            }
            $approveUserIDArray = array_merge( $approveUserIDArray, $editors );
            $approveUserIDArray = array_unique( $approveUserIDArray );

            $collaborationID = false;
            $db = eZDb::instance();
            $taskResult = $db->arrayQuery( 'select workflow_process_id, collaboration_id from ezapprove_items where workflow_process_id = ' . $process->attribute( 'id' )  );
            if ( count( $taskResult ) > 0 )
                $collaborationID = $taskResult[0]['collaboration_id'];

            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $collaborationID, 'approve collaborationID' );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $process->attribute( 'event_state'), 'approve $process->attribute( \'event_state\')' );
            if ( $collaborationID === false )
            {
                $this->createApproveCollaboration( $process, $event, $user->id(), $object->attribute( 'id' ), $versionID, $approveUserIDArray );
                $this->setInformation( "We are going to create approval" );
                $process->setAttribute( 'event_state', eZApproveType::COLLABORATION_CREATED );
                $process->store();
                eZDebugSetting::writeDebug( 'kernel-workflow-approve', $this, 'approve execute' );
                return eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT;
            }
            else if ( $process->attribute( 'event_state') == eZApproveType::COLLABORATION_NOT_CREATED )
            {
                eZApproveCollaborationHandler::activateApproval( $collaborationID );
                $process->setAttribute( 'event_state', eZApproveType::COLLABORATION_CREATED );
                $process->store();
                eZDebugSetting::writeDebug( 'kernel-workflow-approve', $this, 'approve re-execute' );
                return eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT;
            }
            else //eZApproveType::COLLABORATION_CREATED
            {
                $this->setInformation( "we are checking approval now" );
                eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'check approval' );
                return $this->checkApproveCollaboration(  $process, $event );
            }
        }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowSections , "we are not going to create approval " . $object->attribute( 'section_id') );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $userGroups, "we are not going to create approval" );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $workflowGroups,  "we are not going to create approval" );
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $user->id(), "we are not going to create approval "  );
            return eZWorkflowType::STATUS_ACCEPTED;
        }
    }

    function initializeEvent( $event )
    {
    }

    function validateUserIDList( $userIDList, &$reason )
    {
        $returnState = eZInputValidator::STATE_ACCEPTED;
        foreach ( $userIDList as $userID )
        {
            if ( !is_numeric( $userID ) or
                 !eZUser::isUserObject( eZContentObject::fetch( $userID ) ) )
            {
                $returnState = eZInputValidator::STATE_INVALID;
                $reason[ 'list' ][] = $userID;
            }
        }
        $reason[ 'text' ] = "Some of passed user IDs are not valid, must be IDs of existing users only.";
        return $returnState;
    }

    function validateGroupIDList( $userGroupIDList, &$reason )
    {
        $returnState = eZInputValidator::STATE_ACCEPTED;
        $groupClassNames = eZUser::fetchUserGroupClassNames();
        if ( count( $groupClassNames ) > 0 )
        {
            foreach( $userGroupIDList as $userGroupID )
            {
                if ( !is_numeric( $userGroupID ) or
                     !is_object( $userGroup = eZContentObject::fetch( $userGroupID ) ) or
                     !in_array( $userGroup->attribute( 'class_identifier' ), $groupClassNames ) )
                {
                    $returnState = eZInputValidator::STATE_INVALID;
                    $reason[ 'list' ][] = $userGroupID;
                }
            }
            $reason[ 'text' ] = "Some of passed user-group IDs are not valid, must be IDs of existing user groups only.";
        }
        else
        {
            $returnState = eZInputValidator::STATE_INVALID;
            $reason[ 'text' ] = "There is no one user-group classes among the user accounts, please choose standalone users.";
        }
        return $returnState;
    }

    function validateHTTPInput( $http, $base, $workflowEvent, &$validation )
    {
        $returnState = eZInputValidator::STATE_ACCEPTED;
        $reason = array();

        if ( !$http->hasSessionVariable( 'BrowseParameters' ) )
        {
            // check approve-users
            $approversIDs = array_unique( $this->attributeDecoder( $workflowEvent, 'approve_users' ) );
            if ( is_array( $approversIDs ) and
                 count( $approversIDs ) > 0 )
            {
                $returnState = eZApproveType::validateUserIDList( $approversIDs, $reason );
            }
            else
                $returnState = false;

            if ( $returnState != eZInputValidator::STATE_INVALID )
            {
                // check approve-groups
                $userGroupIDList = array_unique( $this->attributeDecoder( $workflowEvent, 'approve_groups' ) );
                if ( is_array( $userGroupIDList ) and
                     count( $userGroupIDList ) > 0 )
                {
                    $returnState = eZApproveType::validateGroupIDList( $userGroupIDList, $reason );
                }
                else if ( $returnState === false )
                {
                    // if no one user or user-group was passed as approvers
                    $returnState = eZInputValidator::STATE_INVALID;
                    $reason[ 'text' ] = "There must be passed at least one valid user or user group who approves content for the event.";
                }

                // check excluded-users
                /*
                if ( $returnState != eZInputValidator::STATE_INVALID )
                {
                    // TODO:
                    // ....
                }
                */

                // check excluded-groups
                if ( $returnState != eZInputValidator::STATE_INVALID )
                {
                    $userGroupIDList = array_unique( $this->attributeDecoder( $workflowEvent, 'selected_usergroups' ) );
                    if ( is_array( $userGroupIDList ) and
                         count( $userGroupIDList ) > 0 )
                    {
                        $returnState = eZApproveType::validateGroupIDList( $userGroupIDList, $reason );
                    }
                }
            }
        }
        else
        {
            $browseParameters = $http->sessionVariable( 'BrowseParameters' );
            if ( isset( $browseParameters['custom_action_data'] ) )
            {
                $customData = $browseParameters['custom_action_data'];
                if ( isset( $customData['event_id'] ) and
                     $customData['event_id'] == $workflowEvent->attribute( 'id' ) )
                {
                    if ( !$http->hasPostVariable( 'BrowseCancelButton' ) and
                         $http->hasPostVariable( 'SelectedObjectIDArray' ) )
                    {
                        $objectIDArray = $http->postVariable( 'SelectedObjectIDArray' );
                        if ( is_array( $objectIDArray ) and
                             count( $objectIDArray ) > 0 )
                        {
                            switch( $customData['browse_action'] )
                            {
                            case "AddApproveUsers":
                                {
                                    $returnState = eZApproveType::validateUserIDList( $objectIDArray, $reason );
                                } break;
                            case 'AddApproveGroups':
                            case 'AddExcludeUser':
                                {
                                    $returnState = eZApproveType::validateGroupIDList( $objectIDArray, $reason );
                                } break;
                            case 'AddExcludedGroups':
                                {
                                    // TODO:
                                    // .....
                                } break;
                            }
                        }
                    }
                }
            }
        }

        if ( $returnState == eZInputValidator::STATE_INVALID )
        {
            $validation[ 'processed' ] = true;
            $validation[ 'events' ][] = array( 'id' => $workflowEvent->attribute( 'id' ),
                                               'placement' => $workflowEvent->attribute( 'placement' ),
                                               'workflow_type' => &$this,
                                               'reason' => $reason );
        }
        return $returnState;
    }


    function fetchHTTPInput( $http, $base, $event )
    {
        $sectionsVar = $base . "_event_ezapprove_section_" . $event->attribute( "id" );
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

        $languageVar = $base . "_event_ezapprove_languages_" . $event->attribute( "id" );
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

        $versionOptionVar = $base . "_event_ezapprove_version_option_" . $event->attribute( "id" );
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
            $versionOption = $versionOption & eZApproveType::VERSION_OPTION_ALL;
            $event->setAttribute( 'data_int3', $versionOption );
        }

        if ( $http->hasSessionVariable( 'BrowseParameters' ) )
        {
            $browseParameters = $http->sessionVariable( 'BrowseParameters' );
            if ( isset( $browseParameters['custom_action_data'] ) )
            {
                $customData = $browseParameters['custom_action_data'];
                if ( isset( $customData['event_id'] ) &&
                     $customData['event_id'] == $event->attribute( 'id' ) )
                {
                    if ( !$http->hasPostVariable( 'BrowseCancelButton' ) and
                         $http->hasPostVariable( 'SelectedObjectIDArray' ) )
                    {
                        $objectIDArray = $http->postVariable( 'SelectedObjectIDArray' );
                        if ( is_array( $objectIDArray ) and
                             count( $objectIDArray ) > 0 )
                        {

                            switch( $customData['browse_action'] )
                            {
                            case 'AddApproveUsers':
                                {
                                    foreach( $objectIDArray as $key => $userID )
                                    {
                                        if ( !eZUser::isUserObject( eZContentObject::fetch( $userID ) ) )
                                        {
                                            unset( $objectIDArray[$key] );
                                        }
                                    }
                                    $event->setAttribute( 'data_text3', implode( ',',
                                                                                 array_unique( array_merge( $this->attributeDecoder( $event, 'approve_users' ),
                                                                                                            $objectIDArray ) ) ) );
                                } break;

                            case 'AddApproveGroups':
                                {
                                    $event->setAttribute( 'data_text4', implode( ',',
                                                                                 array_unique( array_merge( $this->attributeDecoder( $event, 'approve_groups' ),
                                                                                                            $objectIDArray ) ) ) );
                                } break;

                            case 'AddExcludeUser':
                                {
                                    $event->setAttribute( 'data_text2', implode( ',',
                                                                                 array_unique( array_merge( $this->attributeDecoder( $event, 'selected_usergroups' ),
                                                                                                            $objectIDArray ) ) ) );
                                } break;

                            case 'AddExcludedGroups':
                                {
                                    // TODO:
                                    // .....
                                } break;
                            }
                        }
                        $http->removeSessionVariable( 'BrowseParameters' );
                    }
                }
            }
        }
    }

    function createApproveCollaboration( $process, $event, $userID, $contentobjectID, $contentobjectVersion, $editors )
    {
        if ( $editors === null )
            return false;
        $authorID = $userID;
        $collaborationItem = eZApproveCollaborationHandler::createApproval( $contentobjectID, $contentobjectVersion,
                                                                            $authorID, $editors );
        $db = eZDb::instance();
        $db->query( 'INSERT INTO ezapprove_items( workflow_process_id, collaboration_id )
                       VALUES(' . $process->attribute( 'id' ) . ',' . $collaborationItem->attribute( 'id' ) . ' ) ' );
    }

    /*
     \reimp
    */
    function customWorkflowEventHTTPAction( $http, $action, $workflowEvent )
    {
        $eventID = $workflowEvent->attribute( "id" );
        $module =& $GLOBALS['eZRequestedModule'];
        //$siteIni = eZINI::instance();
        //include_once( 'kernel/classes/ezcontentclass.php' );

        switch ( $action )
        {
            case 'AddApproveUsers' :
            {
                $userClassNames = eZUser::fetchUserClassNames();
                if ( count( $userClassNames ) > 0 )
                {
                    //include_once( 'kernel/classes/ezcontentbrowse.php' );
                    eZContentBrowse::browse( array( 'action_name' => 'SelectMultipleUsers',
                                                    'from_page' => '/workflow/edit/' . $workflowEvent->attribute( 'workflow_id' ),
                                                    'custom_action_data' => array( 'event_id' => $eventID,
                                                                                   'browse_action' => $action ),
                                                    'class_array' => $userClassNames ),
                                             $module );
                }
            } break;

            case 'RemoveApproveUsers' :
            {
                if ( $http->hasPostVariable( 'DeleteApproveUserIDArray_' . $eventID ) )
                {
                    $workflowEvent->setAttribute( 'data_text3', implode( ',', array_diff( $this->attributeDecoder( $workflowEvent, 'approve_users' ),
                                                                                          $http->postVariable( 'DeleteApproveUserIDArray_' . $eventID ) ) ) );
                }
            } break;

            case 'AddApproveGroups' :
            case 'AddExcludeUser' :
            {
                $groupClassNames = eZUser::fetchUserGroupClassNames();
                if ( count( $groupClassNames ) > 0 )
                {
                    //include_once( 'kernel/classes/ezcontentbrowse.php' );
                    eZContentBrowse::browse( array( 'action_name' => 'SelectMultipleUsers',
                                                    'from_page' => '/workflow/edit/' . $workflowEvent->attribute( 'workflow_id' ),
                                                    'custom_action_data' => array( 'event_id' => $eventID,
                                                                                   'browse_action' => $action ),
                                                    'class_array' => $groupClassNames ),
                                             $module );
                }
            } break;

            case 'RemoveApproveGroups' :
            {
                if ( $http->hasPostVariable( 'DeleteApproveGroupIDArray_' . $eventID ) )
                {
                    $workflowEvent->setAttribute( 'data_text4', implode( ',', array_diff( $this->attributeDecoder( $workflowEvent, 'approve_groups' ),
                                                                                          $http->postVariable( 'DeleteApproveGroupIDArray_' . $eventID ) ) ) );
                }
            } break;

            case 'RemoveExcludeUser' :
            {
                if ( $http->hasPostVariable( 'DeleteExcludeUserIDArray_' . $eventID ) )
                {
                    $workflowEvent->setAttribute( 'data_text2', implode( ',', array_diff( $this->attributeDecoder( $workflowEvent, 'selected_usergroups' ),
                                                                                          $http->postVariable( 'DeleteExcludeUserIDArray_' . $eventID ) ) ) );
                }
            } break;

            case 'AddExcludedGroups' :
            {
                // TODO:
                // .....
            } break;

            case 'RemoveExcludedGroups' :
            {
                // TODO:
                // .....
            } break;
        }
    }

    /*
     \reimp
    */
    function cleanupAfterRemoving( $attr = array() )
    {
        foreach ( array_keys( $attr ) as $attrKey )
        {
          switch ( $attrKey )
          {
              case 'DeleteContentObject':
              {
                     $contentObjectID = (int)$attr[ $attrKey ];
                     $db = eZDb::instance();
                     // Cleanup "User who approves content"
                     $db->query( 'UPDATE ezworkflow_event
                                  SET    data_int1 = \'0\'
                                  WHERE  data_int1 = \'' . $contentObjectID . '\''  );
                     // Cleanup "Excluded user groups"
                     $excludedGroupsID = $db->arrayQuery( 'SELECT data_text2, id
                                                           FROM   ezworkflow_event
                                                           WHERE  data_text2 like \'%' . $contentObjectID . '%\'' );
                     if ( count( $excludedGroupsID ) > 0 )
                     {
                         foreach ( $excludedGroupsID as $groupID )
                         {
                             // $IDArray will contain IDs of "Excluded user groups"
                             $IDArray = split( ',', $groupID[ 'data_text2' ] );
                             // $newIDArray will contain  array without $contentObjectID
                             $newIDArray = array_filter( $IDArray, create_function( '$v', 'return ( $v != ' . $contentObjectID .' );' ) );
                             $newValues = implode( ',', $newIDArray );
                             $db->query( 'UPDATE ezworkflow_event
                                          SET    data_text2 = \''. $newValues .'\'
                                          WHERE  id = ' . $groupID[ 'id' ] );
                         }
                     }
              } break;
          }
        }
    }

    function checkApproveCollaboration( $process, $event )
    {
        $db = eZDb::instance();
        $taskResult = $db->arrayQuery( 'select workflow_process_id, collaboration_id from ezapprove_items where workflow_process_id = ' . $process->attribute( 'id' )  );
        $collaborationID = $taskResult[0]['collaboration_id'];
        $collaborationItem = eZCollaborationItem::fetch( $collaborationID );
        $contentObjectVersion = eZApproveCollaborationHandler::contentObjectVersion( $collaborationItem );
        $approvalStatus = eZApproveCollaborationHandler::checkApproval( $collaborationID );
        if ( $approvalStatus == eZApproveCollaborationHandler::STATUS_WAITING )
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval still waiting' );
            return eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT;
        }
        else if ( $approvalStatus == eZApproveCollaborationHandler::STATUS_ACCEPTED )
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval was accepted' );
            $status = eZWorkflowType::STATUS_ACCEPTED;
        }
        else if ( $approvalStatus == eZApproveCollaborationHandler::STATUS_DENIED or
                  $approvalStatus == eZApproveCollaborationHandler::STATUS_DEFERRED )
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, 'approval was denied' );
            $contentObjectVersion->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
            $status = eZWorkflowType::STATUS_WORKFLOW_CANCELLED;
        }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-workflow-approve', $event, "approval unknown status '$approvalStatus'" );
            $contentObjectVersion->setAttribute( 'status', eZContentObjectVersion::STATUS_REJECTED );
            $status = eZWorkflowType::STATUS_WORKFLOW_CANCELLED;
        }
        $contentObjectVersion->sync();
        if ( $approvalStatus != eZApproveCollaborationHandler::STATUS_DEFERRED )
            $db->query( 'DELETE FROM ezapprove_items WHERE workflow_process_id = ' . $process->attribute( 'id' )  );
        return $status;
    }
}

eZWorkflowEventType::registerEventType( eZApproveType::WORKFLOW_TYPE_STRING, "eZApproveType" );

?>
