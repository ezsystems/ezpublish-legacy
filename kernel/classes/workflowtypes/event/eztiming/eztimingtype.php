<?php
//
// Definition of eZTimingType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZTimingType eztimingtype.php
  \brief Event type for user approvals

*/

include_once( "kernel/classes/ezworkflowtype.php" );

define( "EZ_WORKFLOW_TYPE_TIMING_ID", "eztiming" );

define( "EZ_WORKFLOW_TYPE_TIMING_MINUTE_FIELD", "data_int1" );
define( "EZ_WORKFLOW_TYPE_TIMING_HOUR_FIELD", "data_int2" );
define( "EZ_WORKFLOW_TYPE_TIMING_DAY_FIELD", "data_int3" );

class eZTimingType extends eZWorkflowEventType
{
    function eZTimingType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_TIMING_ID, ezi18n( 'kernel/workflow/event', "Timing" ) );
        $this->Validator = null;
    }

    function execute( &$process, &$event )
    {
        include_once( "lib/ezlocale/classes/ezdatetime.php" );
        $date = new eZDateTime();
        $minute_adj = $event->attribute( EZ_WORKFLOW_TYPE_TIMING_MINUTE_FIELD );
        $hour_adj = $event->attribute( EZ_WORKFLOW_TYPE_TIMING_HOUR_FIELD );
        $day_adj = $event->attribute( EZ_WORKFLOW_TYPE_TIMING_DAY_FIELD );
        $date->adjustDateTime( $hour_adj, $minute_adj, 0,
                               0, $day_adj, 0 );
        $this->setInformation( "Event delayed until " . $date->toString( true ) );
        $this->setActivationDate( $date->timeStamp() );
        return EZ_WORKFLOW_TYPE_STATUS_DEFERRED_TO_CRON;
    }

    function &validator()
    {
        include_once( "lib/ezutils/classes/ezintegervalidator.php" );
        if ( !is_subclass_of( $this->Validator, "ezinputvalidator" ) )
            $this->Validator = new eZIntegerValidator();
        return $this->Validator;
    }

    function validateHTTPInput( &$http, $base, &$event )
    {
        $validator =& $this->validator();
        $result_state = EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        $day_var = $base . "_event_eztiming_day_" . $event->attribute( "id" );
        $validator->setRange( 0, false );
        if ( $http->hasPostVariable( $day_var ) )
        {
            $state = $validator->validate( $http->postVariable( $day_var ) );
            if ( $state == EZ_INPUT_VALIDATOR_STATE_INVALID )
                return $state;
            else if ( $state == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $result_state = $state;
        }
        $validator->setRange( 0, false );
        $hour_var = $base . "_event_eztiming_hour_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $hour_var ) )
        {
            $state = $validator->validate( $http->postVariable( $hour_var ) );
            if ( $state == EZ_INPUT_VALIDATOR_STATE_INVALID )
                return $state;
            else if ( $state == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $result_state = $state;
        }
        $minute_var = $base . "_event_eztiming_minute_" . $event->attribute( "id" );
        $validator->setRange( 0, false );
        if ( $http->hasPostVariable( $minute_var ) )
        {
            $state = $validator->validate( $http->postVariable( $minute_var ) );
            if ( $state == EZ_INPUT_VALIDATOR_STATE_INVALID )
                return $state;
            else if ( $state == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $result_state = $state;
        }
        return $result_state;
    }

    function fixupHTTPInput( &$http, $base, &$event )
    {
        $validator =& $this->validator();
        $validator->setRange( 0, false );
        $day_var = $base . "_event_eztiming_day_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $day_var ) )
        {
            $day_val = $validator->fixup( $http->postVariable( $day_var ) );
            $http->setPostVariable( $day_var, $day_val );
        }
        $hour_var = $base . "_event_eztiming_hour_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $hour_var ) )
        {
            $hour_val = $validator->fixup( $http->postVariable( $hour_var ) );
            $http->setPostVariable( $hour_var, $hour_val );
        }
        $minute_var = $base . "_event_eztiming_minute_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $minute_var ) )
        {
            $minute_val = $validator->fixup( $http->postVariable( $minute_var ) );
            $http->setPostVariable( $minute_var, $minute_val );
        }
    }

    function initializeEvent( &$event )
    {
        $event->setAttribute( EZ_WORKFLOW_TYPE_TIMING_MINUTE_FIELD, 30 );
        $event->setAttribute( EZ_WORKFLOW_TYPE_TIMING_HOUR_FIELD, 0 );
        $event->setAttribute( EZ_WORKFLOW_TYPE_TIMING_DAY_FIELD, 0 );
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $day_val = $event->attribute( EZ_WORKFLOW_TYPE_TIMING_DAY_FIELD );
        $hour_val = $event->attribute( EZ_WORKFLOW_TYPE_TIMING_HOUR_FIELD );
        $minute_val = $event->attribute( EZ_WORKFLOW_TYPE_TIMING_MINUTE_FIELD );
        $day_var = $base . "_event_eztiming_day_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $day_var ) )
            $day_val = $http->postVariable( $day_var );
        $hour_var = $base . "_event_eztiming_hour_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $hour_var ) )
            $hour_val = $http->postVariable( $hour_var );
        $minute_var = $base . "_event_eztiming_minute_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $minute_var ) )
            $minute_val = $http->postVariable( $minute_var );
        $minutes = $minute_val + ($hour_val*60) + ($day_val*24*60);
        $minute_val = $minutes % 60;
        $minutes = (int)($minutes/60);
        $hour_val = $minutes % 24;
        $minutes = (int)($minutes/24);
        $day_val = $minutes;
        $event->setAttribute( EZ_WORKFLOW_TYPE_TIMING_DAY_FIELD, $day_val );
        $event->setAttribute( EZ_WORKFLOW_TYPE_TIMING_HOUR_FIELD, $hour_val );
        $event->setAttribute( EZ_WORKFLOW_TYPE_TIMING_MINUTE_FIELD, $minute_val );
    }

    var $Validator;
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_TIMING_ID, "eztimingtype" );

?>
