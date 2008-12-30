<?php
//
// Created on: <06-Oct-2006 16:03:00 rl>
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

$FunctionList = array();

$FunctionList['workflow_statuses'] = array( 'name' => 'workflow_statuses',
                                            'operation_types' => array( 'read' ),
                                            'call_method' => array( 'class' => 'eZWorkflowFunctionCollection',
                                                                    'method' => 'fetchWorkflowStatuses' ),
                                            'parameter_type' => 'standard',
                                            'parameters' => array( ) );

$FunctionList['workflow_type_statuses'] = array( 'name' => 'workflow_type_statuses',
                                                 'operation_types' => array( 'read' ),
                                                 'call_method' => array( 'class' => 'eZWorkflowFunctionCollection',
                                                                         'method' => 'fetchWorkflowTypeStatuses' ),
                                                 'parameter_type' => 'standard',
                                                 'parameters' => array( ) );

?>
