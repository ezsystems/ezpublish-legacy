<?php
//
// Definition of eZWorkflowEventType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

//!! eZKernel
//! The class eZWorkflowEventType does
/*!

*/

require_once( "kernel/common/i18n.php" );

class eZWorkflowEventType extends eZWorkflowType
{
    function eZWorkflowEventType( $typeString, $name )
    {
        $this->eZWorkflowType( "event", $typeString, ezi18n( 'kernel/workflow/event', "Event" ), $name );
    }

    static function registerEventType( $typeString, $class_name )
    {
        eZWorkflowType::registerType( "event", $typeString, $class_name );
    }
}

?>
